<?php

header('Content-Type: application/json');
ini_set('display_errors', 0); 
error_reporting(E_ALL);

include 'db_connect.php';
$response = ["status" => "error", "message" => "Unknown Action"];

try {
    if (!isset($_POST['action'])) {
        throw new Exception("No Action Sent!");
    }
    
    $action = $_POST['action'];

    // ADD TEAM
    if ($action == 'add_team') {
        if(empty($_POST['team_name']) || empty($_POST['short_code'])) throw new Exception("Team Name missing");
        
        $stmt = $conn->prepare("INSERT INTO teams (team_name, short_code) VALUES (?, ?)");
        $stmt->bind_param("ss", $_POST['team_name'], $_POST['short_code']);
        
        if(!$stmt->execute()) throw new Exception("Failed to add team");
        
        $response = ["status" => "success", "message" => "Team Added Successfully!", "redirect" => "admin.php"];
    }

    // ADD PLAYERS
    elseif ($action == 'bulk_add_players') {
        $team_id = (int)$_POST['team_id']; 
        $role = $_POST['role'];
        $names = explode("\n", $_POST['player_names']);
        
        $stmt = $conn->prepare("INSERT INTO players (name, team_id, role) VALUES (?, ?, ?)");
        $count = 0;
        
        foreach ($names as $nm) {
            $nm = trim($nm);
            if(!empty($nm)) { 
                $stmt->bind_param("sis", $nm, $team_id, $role); 
                $stmt->execute();
                $count++;
            }
        }
        $response = ["status" => "success", "message" => "$count Players Added Successfully!", "redirect" => "admin.php"];
    }

    // CREATE MATCH
    elseif ($action == 'create_match') {
        $team_a = (int)$_POST['team_a'];
        $team_b = (int)$_POST['team_b'];
        $match_name = $_POST['match_name'];
        $bat_first = $_POST['batting_first'];

        // Validation
        if ($team_a === $team_b) throw new Exception("You selected the SAME TEAM for both sides!");

        function getPlayerCount($conn, $tid) {
            $r = $conn->query("SELECT COUNT(*) as c FROM players WHERE team_id=$tid");
            return ($r) ? $r->fetch_assoc()['c'] : 0;
        }

        $count_a = getPlayerCount($conn, $team_a);
        $count_b = getPlayerCount($conn, $team_b);

        if ($count_a < 11 || $count_b < 11) {
            throw new Exception("Not enough players! Team A: $count_a, Team B: $count_b (Need 11)");
        }

        // Archive Old MatcH
        $check_q = $conn->query("SELECT * FROM match_live WHERE match_id=1");
        if ($check_q && $check_q->num_rows > 0) {
            $old = $check_q->fetch_assoc();
            
            // Get Team Names
            $t1n = "Unknown"; $t2n = "Unknown";
            $r1 = $conn->query("SELECT team_name FROM teams WHERE team_id={$old['team_a_id']}");
            if($r1 && $r1->num_rows>0) $t1n=$r1->fetch_assoc()['team_name'];
            $r2 = $conn->query("SELECT team_name FROM teams WHERE team_id={$old['team_b_id']}");
            if($r2 && $r2->num_rows>0) $t2n=$r2->fetch_assoc()['team_name'];

            // Result Calculation
            $res_txt = "Match Completed";
            if ($old['target'] > 0) {
                $batting_team_name = ($old['batting_team_id'] == $old['team_a_id']) ? $t1n : $t2n;
                $bowling_team_name = ($old['batting_team_id'] == $old['team_a_id']) ? $t2n : $t1n;

                if ($old['total_runs'] >= $old['target']) {
                    $wkt_left = 10 - $old['wickets'];
                    $res_txt = "$batting_team_name Won by $wkt_left Wickets";
                } elseif ($old['total_runs'] == ($old['target'] - 1)) {
                    $res_txt = "Match Tied";
                } else {
                    $runs_margin = ($old['target'] - 1) - $old['total_runs'];
                    $res_txt = "$bowling_team_name Won by $runs_margin Runs";
                }
                if ($old['is_dls'] == 1) $res_txt .= " (DLS)";
            } elseif (!empty($old['result'])) {
                $res_txt = $old['result'];
            }

            $score_txt = $old['total_runs']."/".$old['wickets'];
            $stmt_h = $conn->prepare("INSERT INTO match_history (match_name, team_a, team_b, scores, result_desc) VALUES (?, ?, ?, ?, ?)");
            $stmt_h->bind_param("sssss", $old['match_name'], $t1n, $t2n, $score_txt, $res_txt);
            $stmt_h->execute();
        }

        // Clear & Setup New Match
        $conn->query("SET FOREIGN_KEY_CHECKS = 0");
        $conn->query("TRUNCATE TABLE match_live");
        $conn->query("TRUNCATE TABLE match_players");
        $conn->query("SET FOREIGN_KEY_CHECKS = 1");

        $bat_id = ($bat_first == 'team_a') ? $team_a : $team_b;
        $bowl_id = ($bat_first == 'team_a') ? $team_b : $team_a;

        $batters = $conn->query("SELECT player_id FROM players WHERE team_id = $bat_id LIMIT 2");
        $p1 = $batters->fetch_assoc()['player_id'];
        $p2 = $batters->fetch_assoc()['player_id'];

        $bowlers = $conn->query("SELECT player_id FROM players WHERE team_id = $bowl_id LIMIT 1");
        $bowler = $bowlers->fetch_assoc()['player_id'];

        $stmt = $conn->prepare("INSERT INTO match_live (match_id, match_name, team_a_id, team_b_id, batting_team_id, bowling_team_id, striker_id, non_striker_id, current_bowler_id, status) VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, 'LIVE')");
        $stmt->bind_param("siiiiiii", $match_name, $team_a, $team_b, $bat_id, $bowl_id, $p1, $p2, $bowler);
        
        if (!$stmt->execute()) throw new Exception("DB Error: " . $stmt->error);

        $conn->query("INSERT INTO match_players (match_id, player_id, team_id) SELECT 1, player_id, team_id FROM players WHERE team_id IN ($team_a, $team_b)");
        $conn->query("UPDATE match_players SET status='Batting' WHERE player_id IN ($p1, $p2) AND match_id=1");

        $response = ["status" => "success", "message" => "Match Created Successfully!", "redirect" => "scoreboard.php"];
    }

    // TRIGGER RAIN
    elseif ($action == 'trigger_rain') {
        $q = $conn->query("SELECT * FROM match_live WHERE status='LIVE' LIMIT 1");
        if ($q->num_rows > 0) {
            $m = $q->fetch_assoc();
            if ($m['innings_no'] == 2) {
                if ($m['is_dls'] == 0) {
                    $new_target = floor($m['target'] * 0.8);
                    $conn->query("UPDATE match_live SET target = $new_target, is_dls = 1 WHERE match_id={$m['match_id']}");
                    $response = ["status" => "success", "message" => "DLS Applied. Target reduced to $new_target", "redirect" => "admin.php"];
                } else {
                    throw new Exception("DLS Already Active!");
                }
            } else {
                throw new Exception("Wait for 2nd Innings to apply Rain Rule.");
            }
        } else {
            throw new Exception("No Live Match Found.");
        }
    }

    // DELETE HISTORY
    elseif ($action == 'delete_history') {
        if (isset($_POST['match_id'])) {
            $id = intval($_POST['match_id']);
            $conn->query("DELETE FROM match_history WHERE id=$id");
            $response = ["status" => "success", "message" => "Record Deleted", "redirect" => "history.php"];
        }
    }
    
    // CLEAR ALL HISTORY
    elseif ($action == 'clear_all_history') {
        $conn->query("TRUNCATE TABLE match_history");
        $response = ["status" => "success", "message" => "All History Cleared", "redirect" => "history.php"];
    }

} catch (Exception $e) {
    $response = ["status" => "error", "message" => $e->getMessage()];
}

echo json_encode($response);
exit;
?>