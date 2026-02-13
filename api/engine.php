<?php
include 'db_connect.php';
header("Cache-Control: no-store");
header('Content-Type: application/json');

// Fetch Current Match State
$match = $conn->query("SELECT * FROM match_live WHERE match_id=1")->fetch_assoc();
if (!$match) exit(json_encode(["status" => "error", "message" => "No match data"]));

// Match End Logic
$balls = $match['total_legal_balls'];
$wkts = $match['wickets'];
$runs = $match['total_runs'];
$target = $match['target'];
$innings = $match['innings_no'];
// GET DLS STATUS
$is_dls = isset($match['is_dls']) ? $match['is_dls'] : 0; 

// MATCH END CHECKING
$max_balls = 60;

if ($innings == 1 && ($balls >= $max_balls || $wkts >= 10)) {
    echo json_encode(["status" => "innings_break", "commentary" => "Innings Break! Target: " . ($runs + 1), "match_data" => $match]); exit;
}
if ($innings == 2) {
    if ($runs >= $target) { reportWin($conn, $match, "Batting Team"); exit; }
    if ($balls >= $max_balls || $wkts >= 10) { 
        // GO TO SUPER OVER IF TIE
        if ($runs == $target - 1) {
            reportWin($conn, $match, "Tie-SuperOver"); 
        } else {
            reportWin($conn, $match, "Bowling Team"); 
        }
        exit; 
    }
}

$striker = $match['striker_id'];
$bowler = $match['current_bowler_id'];
$s_name = $conn->query("SELECT name FROM players WHERE player_id=$striker")->fetch_assoc()['name'];

// Initialize Response
$resp = ["runs" => 0, "wicket" => 0, "extra" => 0, "commentary" => "", "status" => "live"];
$wicket_desc = ""; 

// PENALTY RUNS (1% Chance)
if (rand(1, 1000) <= 10) { 
    $resp['runs'] = 5;
    $resp['extra'] = 1;
    $resp['commentary'] = "PENALTY RUNS! 5 runs awarded to batting side (Fielding Violation).";
    
    // DB Update & Exit immediately for this turn
    $conn->query("UPDATE match_live SET total_runs = total_runs + 5, extras = extras + 5 WHERE match_id=1");
    // Send updated data back
    $resp['match_data'] = $conn->query("SELECT * FROM match_live WHERE match_id=1")->fetch_assoc();
    echo json_encode($resp);
    exit;
}

// RAIN / DLS (2% Chance in 2nd Innings)
if ($innings == 2 && $is_dls == 0 && rand(1, 100) <= 2) {
    $reduction = rand(5, 15);
    $new_target = max($runs + 5, $target - $reduction); // CREATE NEW TARGET
    
    $conn->query("UPDATE match_live SET target = $new_target, is_dls = 1 WHERE match_id=1");
    
    $resp['commentary'] = "RAIN INTERRUPTION! Play resumes. DLS Method applied. New Target: $new_target";
    $resp['match_data'] = $conn->query("SELECT * FROM match_live WHERE match_id=1")->fetch_assoc();
    echo json_encode($resp);
    exit;
}

// Delivery Success
$is_legal = (rand(1, 100) <= 99); 

if (!$is_legal) {
    // Delivery Failed (1%) - Illegal
    $resp['extra'] = 1; $resp['runs'] = 1; 
    $resp['commentary'] = "Wide/No Ball! Extra run.";
    $conn->query("UPDATE match_live SET extras = extras + 1 WHERE match_id=1");
} else {
    // Legal Delivery
    $conn->query("UPDATE match_players SET balls_faced = balls_faced + 1 WHERE player_id=$striker");
    $conn->query("UPDATE match_players SET overs_bowled = overs_bowled + 0.1 WHERE player_id=$bowler");

    // Batsman Reaction
    if (rand(1, 100) <= 80) {
        // PLAYED THE BALL
        $impact = rand(1, 100);
        
        if ($impact <= 80) {
            // HIT ON BAT (80%)
            $outcome = rand(1, 100);
            
            if ($outcome <= 80) { 
                // Scoring
                processScoring($resp, $s_name, $conn, $striker, $wicket_desc); 
            } elseif ($outcome <= 96) { 
                // Catch Attempt
                if (rand(1, 100) <= 65) {
                    $resp['wicket'] = 1; $resp['commentary'] = "OUT! Caught by fielder!";
                    $wicket_desc = "Caught"; 
                } else {
                    $resp['runs'] = 1; $resp['commentary'] = "Dropped catch! They take a single.";
                }
            } else { 
                // Hit Wicket
                $resp['wicket'] = 1; $resp['commentary'] = "OUT! Clean Bowled! (Played on)";
                $wicket_desc = "Bowled"; 
            }

        } elseif ($impact <= 95) {
            // HIT ON PAD (15%)
            $outcome = rand(1, 100);
            if ($outcome <= 60) {
                $resp['runs'] = 1; $resp['commentary'] = "Run off the pads.";
            } elseif ($outcome <= 95) {
                // LBW Attempt
                if (rand(1, 100) <= 58) {
                    $resp['wicket'] = 1; $resp['commentary'] = "OUT! LBW! Umpire raises the finger.";
                    $wicket_desc = "LBW"; 
                } else {
                    $resp['commentary'] = "Loud appeal for LBW! Not given.";
                }
            } else {
                // Hit Wicket
                $resp['wicket'] = 1; $resp['commentary'] = "OUT! Bowled off the pads!";
                $wicket_desc = "Bowled"; 
            }

        } elseif ($impact <= 98) {
            // HIT ON GLOVES (3%)
            $outcome = rand(1, 100);
            if ($outcome <= 50) {
                processScoring($resp, $s_name, $conn, $striker, $wicket_desc);
            } elseif ($outcome <= 95) {
                // Catch
                if (rand(1, 100) <= 65) {
                    $resp['wicket'] = 1; $resp['commentary'] = "OUT! Caught off the gloves!";
                    $wicket_desc = "Caught"; 
                } else {
                    $resp['runs'] = 1; $resp['commentary'] = "Gloves it but falls safe.";
                }
            } else {
                // Hit Wicket
                $resp['wicket'] = 1; $resp['commentary'] = "OUT! Play onto stumps from gloves.";
                $wicket_desc = "Bowled"; 
            }

        } else {
            // HIT ON BODY (2%)
            if (rand(1, 100) <= 90) {
                $resp['runs'] = 1; $resp['commentary'] = "Hit on the body, takes a run.";
            } else {
                $resp['wicket'] = 1; $resp['commentary'] = "OUT! Bowled! Deflected off the body.";
                $wicket_desc = "Bowled"; 
            }
        }

    } else {
        // LEAVE (20%)
        $caught_keeper = (rand(1, 100) <= 88);
        
        if ($caught_keeper) {
            // Stumping Attempt
            if (rand(1, 100) <= 40) {
                if (rand(1, 100) <= 35) {
                    $resp['wicket'] = 1; $resp['commentary'] = "OUT! Stumped! Keeper whips the bails off.";
                    $wicket_desc = "Stumped"; 
                } else {
                    $resp['commentary'] = "Stumping appeal referred... Not Out.";
                }
            } else {
                $resp['commentary'] = "$s_name leaves. Keeper collects.";
            }
        } else {
            // Keeper Missed -> Byes
            $resp['extra'] = 1; $resp['runs'] = 1; $resp['commentary'] = "Byes! Keeper misses it.";
            $conn->query("UPDATE match_live SET extras = extras + 1 WHERE match_id=1");
        }
    }

    // Process Wicket Update
    if ($resp['wicket'] == 1) {
        if($wicket_desc == "") $wicket_desc = "Run Out"; // Default fallback
        
        $conn->query("UPDATE match_players SET status='Out', how_out='$wicket_desc' WHERE player_id=$striker");
        $conn->query("UPDATE match_players SET wickets_taken = wickets_taken + 1 WHERE player_id=$bowler");
        
        $next = $conn->query("SELECT player_id FROM match_players WHERE match_id=1 AND team_id={$match['batting_team_id']} AND status='Yet to Bat' LIMIT 1")->fetch_assoc();
        if ($next) { 
            $new_pid = $next['player_id'];
            $conn->query("UPDATE match_players SET status='Batting' WHERE player_id=$new_pid");
            $conn->query("UPDATE match_live SET striker_id=$new_pid WHERE match_id=1");
        }
    }
}

// Database Update (Runs/Wickets)
$conn->query("UPDATE match_live SET total_runs = total_runs + {$resp['runs']}, wickets = wickets + {$resp['wicket']} WHERE match_id=1");
if($resp['runs'] > 0 && $resp['extra'] == 0) {
    $conn->query("UPDATE match_players SET runs_scored = runs_scored + {$resp['runs']} WHERE player_id=$striker");
    $conn->query("UPDATE match_players SET runs_conceded = runs_conceded + {$resp['runs']} WHERE player_id=$bowler");
}

// Rotate Strike
if (($resp['runs'] - $resp['extra']) % 2 != 0 && $resp['wicket'] == 0) {
    $conn->query("UPDATE match_live SET striker_id={$match['non_striker_id']}, non_striker_id=$striker WHERE match_id=1");
}

// Over End Logic
if ($is_legal) {
    $conn->query("UPDATE match_live SET total_legal_balls = total_legal_balls + 1 WHERE match_id=1");
    if (($balls + 1) % 6 == 0) {
        $resp['commentary'] .= " <b>End of Over!</b>";
        $conn->query("UPDATE match_players SET overs_bowled = CEILING(overs_bowled) WHERE player_id=$bowler");
        
        $curr_striker = $conn->query("SELECT striker_id FROM match_live WHERE match_id=1")->fetch_assoc()['striker_id'];
        $curr_non = $conn->query("SELECT non_striker_id FROM match_live WHERE match_id=1")->fetch_assoc()['non_striker_id'];
        $conn->query("UPDATE match_live SET striker_id=$curr_non, non_striker_id=$curr_striker WHERE match_id=1");

        $new_b = $conn->query("SELECT player_id FROM players WHERE team_id={$match['bowling_team_id']} AND player_id != $bowler ORDER BY RAND() LIMIT 1")->fetch_assoc();
        if ($new_b) $conn->query("UPDATE match_live SET current_bowler_id={$new_b['player_id']} WHERE match_id=1");
    }
}

// Response Construction
$resp['match_data'] = $conn->query("SELECT * FROM match_live WHERE match_id=1")->fetch_assoc();
$resp['current_striker_id'] = ($resp['wicket'] == 1 && isset($new_pid)) ? $new_pid : $striker;

$bat_squad = []; 
$q1 = $conn->query("SELECT p.name, mp.* FROM match_players mp JOIN players p ON mp.player_id=p.player_id WHERE mp.match_id=1 AND mp.team_id={$match['batting_team_id']} ORDER BY mp.player_id ASC"); 
while($r = $q1->fetch_assoc()) $bat_squad[] = $r;

$bowl_squad = []; 
$q2 = $conn->query("SELECT p.name, mp.runs_conceded, mp.wickets_taken, mp.overs_bowled FROM match_players mp JOIN players p ON mp.player_id=p.player_id WHERE mp.match_id=1 AND mp.team_id={$match['bowling_team_id']}"); 
while($r = $q2->fetch_assoc()) $bowl_squad[] = $r;

$resp['batting_data'] = $bat_squad; $resp['bowling_data'] = $bowl_squad;
echo json_encode($resp);

// FUNCTIONS

function processScoring(&$resp, $s_name, $conn, $striker, &$wicket_desc) {
    // Scoring Probabilities
    $r = rand(1, 100);
    $runs = 0;
    if ($r <= 50) $runs = 0;
    elseif ($r <= 70) $runs = 1;
    elseif ($r <= 80) $runs = 2;
    elseif ($r <= 85) $runs = 3;
    elseif ($r <= 97) { $runs = 4; $conn->query("UPDATE match_players SET fours=fours+1 WHERE player_id=$striker"); }
    elseif ($r <= 98) $runs = 5;
    else { $runs = 6; $conn->query("UPDATE match_players SET sixes=sixes+1 WHERE player_id=$striker"); }

    $resp['runs'] = $runs;
    
    // Check RUN OUT
    if (in_array($runs, [1, 2, 3, 5])) {
        if (rand(1, 1000) <= 17) {
            $resp['runs'] = $runs - 1;
            $resp['wicket'] = 1;
            $resp['commentary'] = "OUT! Run Out while going for run number $runs!";
            $wicket_desc = "Run Out"; // (2) Save Type
            return;
        }
    }

    if ($runs == 0) $resp['commentary'] = "$s_name defends.";
    elseif ($runs == 4) $resp['commentary'] = "FOUR! What a shot!";
    elseif ($runs == 6) $resp['commentary'] = "SIX! Out of the Stadium!";
    else $resp['commentary'] = "$s_name takes $runs runs.";
}

function reportWin($conn, $match, $winner_type) {
    // DECIDE WINNER
    if ($winner_type == "Tie-SuperOver") {
        $winner_team_id = (rand(0, 1) == 0) ? $match['team_a_id'] : $match['team_b_id'];
        $super_over_note = " (Won via Super Over)";
    } else {
        $winner_team_id = ($winner_type == "Batting Team") ? $match['batting_team_id'] : (($match['batting_team_id'] == $match['team_a_id']) ? $match['team_b_id'] : $match['team_a_id']);
        $super_over_note = "";
    }

    // DECIDE WINNER NAME
    $win_name = "MATCH TIED!";
    if($winner_team_id > 0) {
        $q = $conn->query("SELECT team_name FROM teams WHERE team_id=$winner_team_id")->fetch_assoc();
        $win_name = strtoupper($q['team_name']) . " WON!" . $super_over_note;
    }

    // UPDATE MATCH STATUS
    $conn->query("UPDATE match_live SET status='COMPLETED', result='$win_name' WHERE match_id=1");

    // SELECT BEST PLAYER
    $safe_player = ["name" => "N/A", "runs_scored" => 0, "balls_faced" => 0, "wickets_taken" => 0, "runs_conceded" => 0];

    $bestBat = $conn->query("SELECT p.name, mp.runs_scored, mp.balls_faced FROM match_players mp JOIN players p ON mp.player_id=p.player_id WHERE mp.match_id=1 ORDER BY mp.runs_scored DESC LIMIT 1")->fetch_assoc();
    $bestBowl = $conn->query("SELECT p.name, mp.wickets_taken, mp.runs_conceded FROM match_players mp JOIN players p ON mp.player_id=p.player_id WHERE mp.match_id=1 ORDER BY mp.wickets_taken DESC LIMIT 1")->fetch_assoc();

    // RETURN MATCH RESULT TO SCOREBOARD
    echo json_encode([
        "status" => "completed", 
        "result" => $win_name, 
        "best_batter" => $bestBat ? $bestBat : $safe_player, 
        "best_bowler" => $bestBowl ? $bestBowl : $safe_player
    ]);
}
?>