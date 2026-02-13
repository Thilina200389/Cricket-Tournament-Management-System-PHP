<?php
include 'db_connect.php';

// GET MATCH DATA
$m = $conn->query("SELECT * FROM match_live WHERE match_id=1")->fetch_assoc();

if ($m['innings_no'] == 1) {
    // SWITCH TO 2ND INNINGS
    
    // SAVE 1ST INNINGS SCORE
    $inn1_score = $m['total_runs'];
    $inn1_wkts = $m['wickets'];
    $inn1_overs = $m['overs'];
    $target = $inn1_score + 1;

    // SWAP TEAMS
    $new_batting_team = $m['bowling_team_id'];
    $new_bowling_team = $m['batting_team_id'];

    // SELECT NEW OPENERS (FROM NEW BATTING TEAM)
    $batters = $conn->query("SELECT player_id FROM players WHERE team_id = $new_batting_team LIMIT 2");
    $p1 = ($row = $batters->fetch_assoc()) ? $row['player_id'] : null;
    $p2 = ($row = $batters->fetch_assoc()) ? $row['player_id'] : null;

    // SELECT NEW BOWLER (FROM NEW BOWLING TEAM - OLD BATTING TEAM)
    $bowler = $conn->query("SELECT player_id FROM players WHERE team_id = $new_bowling_team LIMIT 1")->fetch_assoc()['player_id'];

    // UPDATE DATABASE
    // Reset Score, Wickets, Balls. Set Target. Change Innings to 2.
    $sql = "UPDATE match_live SET 
            innings_no = 2,
            target = $target,
            inn1_runs = $inn1_score,
            inn1_wickets = $inn1_wkts,
            total_runs = 0,
            wickets = 0,
            total_legal_balls = 0,
            extras = 0,
            batting_team_id = $new_batting_team,
            bowling_team_id = $new_bowling_team,
            striker_id = $p1,
            non_striker_id = $p2,
            current_bowler_id = $bowler
            WHERE match_id = 1";
    
    $conn->query($sql);

    // UPDATE PLAYER STATUSES
    $conn->query("UPDATE match_players SET status='Batting' WHERE player_id IN ($p1, $p2)");
    
    echo "Switched";
}
?>