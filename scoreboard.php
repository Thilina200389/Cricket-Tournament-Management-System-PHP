<?php
include 'api/db_connect.php';

// Fetch match data
$match_query = $conn->query("SELECT * FROM match_live WHERE match_id=1");

if ($match_query && $match_query->num_rows > 0) {
    $match = $match_query->fetch_assoc();
    $t1 = $conn->query("SELECT team_id, team_name FROM teams WHERE team_id={$match['team_a_id']}")->fetch_assoc();
    $t2 = $conn->query("SELECT team_id, team_name FROM teams WHERE team_id={$match['team_b_id']}")->fetch_assoc();
    $team_a_id = $t1['team_id'];
    $team_b_id = $t2['team_id'];
    $team_a_name = $t1['team_name'];
    $team_b_name = $t2['team_name'];
    $current_status = $match['status']; 
} else {
    $match = [
        'match_name' => 'NO LIVE MATCH',
        'total_runs' => 0, 'wickets' => 0, 'total_legal_balls' => 0, 'extras' => 0, 'target' => 0, 'innings_no' => 1,
        'batting_team_id' => 0
    ];
    $team_a_name = "TEAM A";
    $team_b_name = "TEAM B";
    $team_a_id = 0; $team_b_id = 0;
    $t1 = ['team_name' => 'TEAM A'];
    $t2 = ['team_name' => 'TEAM B'];
    $current_status = "OFF";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <meta charset="UTF-8">
    <title>CTMS Pro Live Broadcast</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@500;700&family=Heebo:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-dark: #0f172a; --bg-card: #1e293b; --bg-header: #162032;
            --text-light: #f1f5f9; --text-dim: #94a3b8;
            --accent-gold: #facc15; --accent-red: #ef4444; --accent-green: #22c55e; --accent-blue: #3b82f6;
            --border: #334155;
        }
        * { box-sizing: border-box; }
        body { margin: 0; height: 100vh; overflow: hidden; background: var(--bg-dark); color: var(--text-light); font-family: 'Heebo', sans-serif; display: flex; flex-direction: column; }

        /* HEADER */
        .broadcast-header {
            background: var(--bg-header); border-bottom: 3px solid var(--accent-gold);
            padding: 10px 20px; display: flex; flex-direction: column; gap: 10px;
            flex-shrink: 0; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.5); z-index: 10;
        }

        .top-info-bar { 
            display: flex; justify-content: space-between; align-items: center; padding-bottom: 5px;
        }

        .match-info-container {
            display: flex; align-items: center; gap: 15px; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;
        }

        .live-badge {
            background: rgba(239, 68, 68, 0.15); color: var(--accent-red); padding: 4px 12px; border-radius: 20px;
            font-weight: 800; display: flex; align-items: center; gap: 8px; border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .pulse-dot {
            width: 8px; height: 8px; background: var(--accent-red); border-radius: 50%;
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); animation: pulse-red 2s infinite;
        }
        @keyframes pulse-red {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        .match-title-text { font-weight: 500; color: var(--text-light); }
        
        .admin-btn-header { 
            color: var(--text-dim); text-decoration: none; font-size: 0.75rem; font-weight: 700; letter-spacing: 1px;
            border: 1px solid var(--border); padding: 6px 15px; border-radius: 6px; transition: 0.3s; display: flex; align-items: center; gap: 8px; background: rgba(0,0,0,0.2);
        } 
        .admin-btn-header:hover { border-color: var(--accent-blue); color: var(--accent-blue); background: rgba(59, 130, 246, 0.1); }
        
        .scoreboard-flex { display: flex; align-items: center; justify-content: center; gap: 40px; }
        .team-wrapper { text-align: center; width: 300px; transition: 0.3s; opacity: 0.5; filter: grayscale(1); }
        .team-wrapper.active { opacity: 1; filter: grayscale(0); transform: scale(1.05); }
        .team-name-display { font-family: 'Teko', sans-serif; font-size: 2.5rem; line-height: 1; text-transform: uppercase; margin-bottom: 5px; }
        .team-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .badge-batting { background: var(--accent-gold); color: #000; }
        .badge-bowling { background: var(--bg-card); color: var(--text-dim); border: 1px solid var(--text-dim); }

        .main-score-box { text-align: center; }
        .big-score-text { font-family: 'Teko', sans-serif; font-size: 5rem; font-weight: 700; line-height: 0.9; color: #fff; text-shadow: 0 0 20px rgba(250, 204, 21, 0.2); }
        .wickets-text { font-size: 3rem; color: var(--accent-gold); vertical-align: top; }
        .match-stats-bar { display: flex; justify-content: center; gap: 25px; margin-top: -5px; font-size: 0.95rem; color: var(--text-dim); }
        .stat-hilite { color: var(--text-light); font-weight: bold; }

        .dashboard-grid { display: grid; grid-template-columns: 1.4fr 1fr 1fr; gap: 15px; padding: 15px; flex-grow: 1; overflow: hidden; }
        .glass-panel { background: var(--bg-card); border: 1px solid #334155; border-radius: 12px; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .panel-head { background: rgba(0,0,0,0.2); padding: 12px 15px; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-dim); display: flex; justify-content: space-between; }
        
        .table-container { flex-grow: 1; overflow-y: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; white-space: nowrap; }
        th { position: sticky; top: 0; background: var(--bg-dark); color: var(--text-dim); text-align: left; padding: 10px 15px; font-weight: 500; font-size: 0.8rem; }
        td { padding: 8px 15px; border-bottom: 1px solid #334155; }

        .striker-row { background: rgba(34, 197, 94, 0.1); border-left: 3px solid var(--accent-green); }
        .txt-striker { color: var(--accent-green); font-weight: bold; }
        .txt-out { color: var(--accent-red); font-weight: bold; opacity: 0.8; }
        .txt-yet { color: var(--text-dim); font-style: italic; }
        .score-emph { font-weight: bold; font-size: 1.1rem; }

        .comm-feed { flex-grow: 1; overflow-y: auto; padding: 0; }
        .comm-item { padding: 10px 15px; border-bottom: 1px solid #334155; display: flex; gap: 12px; align-items: center; font-size: 0.9rem; }
        .ball-stamp { width: 28px; height: 28px; border-radius: 50%; background: #334155; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; flex-shrink: 0;}
        
        .btn-live-action { width: 100%; padding: 18px; border: none; background: var(--accent-blue); color: white; font-weight: 700; font-size: 1.1rem; text-transform: uppercase; cursor: pointer; transition: 0.3s; }
        .btn-live-action:hover { background: #2563eb; }

        #summaryModal { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.95); z-index: 50; align-items: center; justify-content: center; }
        .summary-box { 
            background: var(--bg-card); border: 2px solid var(--accent-gold); padding: 40px; 
            border-radius: 16px; text-align: center; width: 600px; position: relative;
            box-shadow: 0 0 50px rgba(250, 204, 21, 0.2); 
        }
        .close-btn { position: absolute; top: 15px; right: 20px; font-size: 2rem; color: var(--text-dim); cursor: pointer; transition: 0.2s; }
        .close-btn:hover { color: #fff; }

        .summary-stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 30px; text-align: left; }
        .stat-card { background: var(--bg-dark); padding: 15px; border-radius: 10px; border: 1px solid #334155; }
        .stat-lbl { color: var(--text-dim); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; }
        .stat-val { color: #fff; font-size: 1.4rem; font-weight: bold; margin-top: 5px; font-family: 'Teko', sans-serif; letter-spacing: 1px; }
        .stat-sub { color: var(--accent-gold); font-weight: bold; font-size: 1rem; }

    </style>
</head>
<body>
    <div style="display:none;">
    <audio id="sndWicket" src="sounds/wicket.mp3" preload="auto"></audio>
    <audio id="sndFour" src="sounds/four.mp3" preload="auto"></audio>
    <audio id="sndSix" src="sounds/six.mp3" preload="auto"></audio>
    <audio id="sndWin" src="sounds/win.mp3" preload="auto"></audio>
</div>

<header class="broadcast-header">
    <div class="top-info-bar">
        <div class="match-info-container">
            <div class="live-badge"><span class="pulse-dot"></span> LIVE</div>
            <span style="color:var(--text-dim)">|</span>
            <span class="match-title-text"><?php echo $match['match_name']; ?></span>
        </div>
        <a href="admin.php" class="admin-btn-header">
            <i class="fa-solid fa-user-gear"></i> ADMIN PANEL
        </a>
    </div>

    <div class="scoreboard-flex">
        <div id="wrapA" class="team-wrapper">
            <div class="team-name-display"><?php echo $t1['team_name']; ?></div>
            <span id="badgeA" class="team-badge badge-bowling">BOWLING</span>
        </div>

        <div class="main-score-box">
            <div class="big-score-text">
                <span id="score">0</span><span class="wickets-text">/<span id="wickets">0</span></span>
            </div>
            <div class="match-stats-bar">
                <span>OVERS: <span id="overs" class="stat-hilite">0.0</span></span>
                <span>Run Rate: <span id="crr" class="stat-hilite">0.00</span></span>
                <span id="targetUI" style="display:none">TARGET: <span id="target" class="stat-hilite" style="color:var(--accent-gold)">0</span></span>
            </div>
        </div>

        <div id="wrapB" class="team-wrapper">
            <div class="team-name-display"><?php echo $t2['team_name']; ?></div>
            <span id="badgeB" class="team-badge badge-bowling">BOWLING</span>
        </div>
    </div>
</header>

<main class="dashboard-grid">
    <div class="glass-panel">
        <div class="panel-head"><span>Batting</span><span id="battingTeamTitle" style="color:var(--accent-gold)">Loading...</span></div>
        <div class="table-container">
            <table><thead><tr><th width="40%">Batter</th><th>Status</th><th>Runs</th><th>Balls</th><th>4s</th><th>6s</th><th>Strike Rate</th></tr></thead><tbody id="battingBody"></tbody></table>
        </div>
    </div>

    <div class="glass-panel">
        <div class="panel-head"><span>Bowling</span><span id="bowlingTeamTitle" style="color:var(--text-dim)">Loading...</span></div>
        <div class="table-container">
            <table><thead><tr><th width="40%">Bowler</th><th>Overs</th><th>Runs</th><th>Wickets</th><th>Economy Rate</th></tr></thead><tbody id="bowlingBody"></tbody></table>
        </div>
    </div>

    <div class="glass-panel">
        <button id="mainBtn" class="btn-live-action" onclick="handleStateAction()">▶ START MATCH LIVE</button>
        <div class="panel-head" style="background:var(--bg-dark)">Commentary</div>
        <div id="commFeed" class="comm-feed"><div style="padding:20px; text-align:center; color:var(--text-dim);">Ready...</div></div>
    </div>
</main>

<div id="summaryModal">
    <div class="summary-box">
        <span class="close-btn" onclick="document.getElementById('summaryModal').style.display='none'">&times;</span>
        <h3 style="color:var(--text-dim); margin:0;">MATCH SUMMARY</h3>
        <h1 id="resultText" style="font-family:'Teko'; font-size:3.5rem; color:var(--accent-gold); margin:10px 0;">RESULT PENDING</h1>
        <div class="summary-stats-grid">
            <div class="stat-card">
                <div class="stat-lbl">Top Scorer</div>
                <div id="bestBatName" class="stat-val">-</div>
                <div id="bestBatStats" class="stat-sub">0 Runs (0)</div>
            </div>
            <div class="stat-card">
                <div class="stat-lbl">Best Bowler</div>
                <div id="bestBowlName" class="stat-val">-</div>
                <div id="bestBowlStats" class="stat-sub">0/0</div>
            </div>
        </div>
        <button onclick="window.location.href='admin.php'" class="btn-live-action" style="background:var(--bg-dark); border:1px solid var(--text-dim); margin-top:30px;">BACK TO ADMIN</button>
    </div>
</div>

<div id="cheerOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:9999; align-items:center; justify-content:center; background:rgba(0,0,0,0.5); backdrop-filter:blur(2px);">
    <div style="text-align:center;">
        <h1 style="font-family:'Teko'; font-size:8rem; color:#facc15; margin:0; text-shadow:0 0 20px red; animation: popUp 0.5s infinite alternate;">SIX!</h1>
        
        <img src="images/cheer.gif" style="height:300px; border-radius:10px; border:2px solid #facc15;">
    </div>
</div>

<style>
@keyframes popUp {
    from { transform: scale(1); }
    to { transform: scale(1.1); }
}
</style>

<!--
<div id="wicketOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:9999; align-items:center; justify-content:center; background:rgba(220, 38, 38, 0.6); backdrop-filter:blur(4px);">
    <div style="text-align:center; animation: shake 0.5s;">
        <h1 style="font-family:'Teko'; font-size:10rem; color:#fff; margin:0; text-shadow:4px 4px 0px #000; letter-spacing:5px;">OUT!</h1>
        <h2 style="font-family:'Heebo'; font-size:2rem; color:#feca1d; margin:0; text-transform:uppercase; letter-spacing:2px; font-weight:bold;">WICKET DOWN</h2>
        
        <img src="images/wicket.gif" style="height:250px; margin-top:20px; border-radius:10px; border:4px solid #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
    </div>
</div>


<style>
/* විකට් එකක් ගියාම ගැස්සෙන Animation එක */
@keyframes shake {
    0% { transform: translate(1px, 1px) rotate(0deg); }
    10% { transform: translate(-1px, -2px) rotate(-1deg); }
    20% { transform: translate(-3px, 0px) rotate(1deg); }
    30% { transform: translate(3px, 2px) rotate(0deg); }
    40% { transform: translate(1px, -1px) rotate(1deg); }
    50% { transform: translate(-1px, 2px) rotate(-1deg); }
    60% { transform: translate(-3px, 1px) rotate(0deg); }
    70% { transform: translate(3px, 1px) rotate(-1deg); }
    80% { transform: translate(-1px, -1px) rotate(1deg); }
    90% { transform: translate(1px, 2px) rotate(0deg); }
    100% { transform: translate(1px, -2px) rotate(-1deg); }
}
</style>
-->

<script>
    const TEAM_A_ID = <?php echo $team_a_id; ?>; 
    const TEAM_B_ID = <?php echo $team_b_id; ?>;
    const TEAM_A_NAME = "<?php echo $t1['team_name']; ?>"; 
    const TEAM_B_NAME = "<?php echo $t2['team_name']; ?>";
    const DB_STATUS = "<?php echo $current_status; ?>";
    
    let isRunning = false; 
    let timer = null; 
    let currentState = "live";
    let thisOverBalls = [];

    window.onload = function() {
        if(DB_STATUS === 'LIVE') { handleStateAction(); } 
        else if (DB_STATUS === 'innings_break') {
            currentState = "break";
            let btn = document.getElementById('mainBtn');
            btn.innerText = "START 2ND INNINGS";
            btn.style.background = "#3b82f6";
            fetchData();
        }
    };

    function handleStateAction() {
        if(currentState === "break") {
            if(confirm("Start 2nd Innings now?")) fetch('api/switch_innings.php').then(()=>window.location.reload());
            return;
        }
        if(isRunning || currentState === "done") return;
        isRunning = true;
        let btn = document.getElementById('mainBtn');
        btn.innerText = "● LIVE IN PROGRESS...";
        btn.style.background = "#facc15"; btn.style.color = "black";
        timer = setInterval(fetchData, 1000);
    }

    function fetchData() {
        fetch('api/engine.php').then(res => res.json()).then(data => {
            
            if(data.status === 'innings_break') {
                clearInterval(timer); isRunning = false; currentState = "break";
                let btn = document.getElementById('mainBtn');
                btn.innerText = "START 2ND INNINGS"; btn.style.background = "#3b82f6"; btn.style.color = "white";
                alert(data.commentary); return;
            }
            if(data.status === 'completed') {
                clearInterval(timer); isRunning = false; currentState = "done";
                document.getElementById('mainBtn').innerText = "MATCH FINISHED"; 
                document.getElementById('mainBtn').style.background = "#1e293b"; 
                showSummary(data); return;
            }

            let m = data.match_data || data;

            // SOUND EFFECTS LOGIC
            if (data.wicket == 1) {
                // Wicket Sound
                document.getElementById('sndWicket').play().catch(e=>{});
            } 
            else if (data.runs == 4) {
                // Four Sound
                document.getElementById('sndFour').play().catch(e=>{});
            } 
            else if (data.runs == 6) {
                // Six Sound
                document.getElementById('sndSix').play().catch(e=>{});
            }


            // CHEERLEADER ANIMATION FOR 6
            if (data.runs == 6) {
                // SHOW GIF
                document.getElementById('cheerOverlay').style.display = 'flex';

                // HIDE GIF AFTER 4 SECONDS
                setTimeout(() => {
                    document.getElementById('cheerOverlay').style.display = 'none';
                }, 4000);
            }

            // HEADER
            let batID = parseInt(m.batting_team_id);
            let wrapA = document.getElementById('wrapA'); let wrapB = document.getElementById('wrapB');
            let badgeA = document.getElementById('badgeA'); let badgeB = document.getElementById('badgeB');

            wrapA.className = "team-wrapper"; wrapB.className = "team-wrapper";
            badgeA.className = "team-badge badge-bowling"; badgeA.innerText = "BOWLING"; badgeA.style.background="#1e293b"; badgeA.style.color="#94a3b8";
            badgeB.className = "team-badge badge-bowling"; badgeB.innerText = "BOWLING"; badgeB.style.background="#1e293b"; badgeB.style.color="#94a3b8";

            if(batID === TEAM_A_ID) {
                wrapA.classList.add('active'); 
                badgeA.innerText = "BATTING"; badgeA.style.background="#facc15"; badgeA.style.color="black";
                document.getElementById('battingTeamTitle').innerText = TEAM_A_NAME; document.getElementById('bowlingTeamTitle').innerText = TEAM_B_NAME;
            } else {
                wrapB.classList.add('active'); 
                badgeB.innerText = "BATTING"; badgeB.style.background="#facc15"; badgeB.style.color="black";
                document.getElementById('battingTeamTitle').innerText = TEAM_B_NAME; document.getElementById('bowlingTeamTitle').innerText = TEAM_A_NAME;
            }

            document.getElementById('score').innerText = m.total_runs;
            document.getElementById('wickets').innerText = m.wickets;
            let balls = parseInt(m.total_legal_balls);
            document.getElementById('overs').innerText = Math.floor(balls/6) + "." + (balls%6);
            document.getElementById('crr').innerText = (balls>0)?(m.total_runs/(balls/6)).toFixed(2):"0.00";
            
            if(m.innings_no == 2) {
            let runsNeed = m.target - m.total_runs;
            let ballsLeft = 60 - m.total_legal_balls;
            let rrr = (ballsLeft > 0) ? ((runsNeed / ballsLeft) * 6).toFixed(2) : "0.00";
            
            // DLS Display Logic
            let displayTarget = m.target;
            if (m.is_dls == 1) {
                displayTarget = m.target + " (DLS)";
            }

            document.getElementById('targetUI').style.display="inline"; 
            document.getElementById('targetUI').innerHTML = `TARGET: <span class="stat-hilite" style="color:var(--accent-gold)">${displayTarget}</span> <span style="color:#94a3b8; font-size:0.8rem; margin-left:10px;">(RRR: ${rrr})</span>`;
            }

            

            // BATTING TABLE
            if(data.batting_data) {
                let html = "";
                data.batting_data.forEach(p => {
                    let rowCls = ""; let nameHtml = p.name; let statusHtml = "";
                    if(p.status === 'Batting') {
                        if(p.player_id == data.current_striker_id) {
                            rowCls = "striker-row"; nameHtml = `<span class="txt-striker">➤ ${p.name}</span>`; statusHtml = `<span class="txt-striker">On Strike</span>`;
                        } else { statusHtml = `<span style="color:#f1f5f9">At Crease</span>`; }
                    } else if(p.status === 'Out') {
                        nameHtml = `<span class="txt-out">${p.name}</span>`;
                        let outDesc = p.how_out ? p.how_out : "OUT";
                        statusHtml = `<span class="txt-out" style="font-size: 0.8rem; text-transform: uppercase; font-weight:bold; color:#ef4444;">${outDesc}</span>`;
                    } else { statusHtml = `<span class="txt-yet">Yet to Bat</span>`; }
                    let sr = (p.balls_faced > 0) ? ((p.runs_scored/p.balls_faced)*100).toFixed(0) : 0;
                    html += `<tr class="${rowCls}"><td>${nameHtml}</td><td>${statusHtml}</td><td class="score-emph">${p.runs_scored}</td><td>${p.balls_faced}</td><td>${p.fours}</td><td>${p.sixes}</td><td style="color:#94a3b8">${sr}</td></tr>`;
                });
                
                let extras = m.extras; 
                let w = Math.floor(extras * 0.6); let nb = Math.floor(extras * 0.2); let lb = extras - w - nb;
                html += `<tr style="border-top: 2px solid #334155; font-weight:bold; color:#facc15;"><td colspan="2">EXTRAS</td><td colspan="5" style="text-align:left; padding-left:20px;">${extras} <span style="color:#94a3b8; font-size:0.8rem; font-weight:normal;">(W ${w}, NB ${nb}, LB ${lb})</span></td></tr>`;
                document.getElementById('battingBody').innerHTML = html;
            }

            // BOWLING TABLE
            if(data.bowling_data) {
                let html = ""; data.bowling_data.forEach(p => {
                    let econ = (p.overs_bowled > 0) ? (p.runs_conceded/p.overs_bowled).toFixed(1) : "-";
                    html += `<tr><td>${p.name}</td><td>${p.overs_bowled}</td><td>${p.runs_conceded}</td><td style="color:#facc15; font-weight:bold">${p.wickets_taken}</td><td>${econ}</td></tr>`;
                });
                document.getElementById('bowlingBody').innerHTML = html;
            }

            // COMMENTARY
            if(data.commentary) {
                let div = document.createElement('div'); div.className = "comm-item";
                let c = "#334155"; if(data.wicket) c="#ef4444"; else if(data.runs>=4) c="#22c55e";
                div.innerHTML = `<div class="ball-stamp" style="background:${c}">${data.wicket?'W':data.runs}</div><div>${data.commentary}</div>`;
                document.getElementById('commFeed').prepend(div);

                let bVal="0", bCol="#334155";
                if(data.wicket) { bVal="W"; bCol="#ef4444"; }
                else if(data.extra) { bVal="wd"; bCol="#f59e0b"; }
                else {
                    bVal = data.runs;
                    if(bVal==4) bCol="#3b82f6"; if(bVal==6) bCol="#22c55e";
                }
                thisOverBalls.push({val:bVal, col:bCol});
                if(thisOverBalls.length > 6) thisOverBalls.shift();

                let stripHtml = '<div style="display:flex; gap:10px; justify-content:center; margin-bottom:15px;">';
                thisOverBalls.forEach(b => {
                    stripHtml += `<div style="width:35px; height:35px; background:${b.col}; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:0.9rem; border:2px solid rgba(255,255,255,0.2); box-shadow:0 2px 5px rgba(0,0,0,0.3);">${b.val}</div>`;
                });
                stripHtml += '</div>';

                let commContainer = document.querySelector('.glass-panel:last-child .panel-head');
                if(!document.getElementById('thisOverStrip')) {
                    let strip = document.createElement('div');
                    strip.id = "thisOverStrip";
                    commContainer.insertAdjacentElement('afterend', strip);
                }
                document.getElementById('thisOverStrip').innerHTML = stripHtml;
            }
        });
    }

    function showSummary(data) {
        confetti({ particleCount: 150, spread: 70, origin: { y: 0.6 } });
        // WINNING SOUND
        try { document.getElementById('sndWin').play(); } catch(e){}

        // Confetti
        document.getElementById('summaryModal').style.display = 'flex';

        document.getElementById('summaryModal').style.display = 'flex';

        document.getElementById('resultText').innerText = data.result; 
        
        if (data.best_batter) {
            document.getElementById('bestBatName').innerText = data.best_batter.name;
            document.getElementById('bestBatStats').innerText = data.best_batter.runs_scored + " Runs (" + data.best_batter.balls_faced + ")";
        }
        if (data.best_bowler) {
            document.getElementById('bestBowlName').innerText = data.best_bowler.name;
            document.getElementById('bestBowlStats').innerText = data.best_bowler.wickets_taken + "/" + data.best_bowler.runs_conceded;
        }
    }

</script>
</body>
</html>