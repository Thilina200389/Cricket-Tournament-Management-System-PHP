<?php 
session_start();

// REDIRECT TO LOGIN PAGE IF NOT LOGGED IN
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: login.php");
    exit;
}

include 'api/db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CTMS PRO Admin Console</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@400;500;600;700&family=Heebo:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-dark: #020617;
            --bg-panel: #0f172a;
            --bg-input: #1e293b;
            --text-main: #f8fafc;
            --text-dim: #94a3b8;
            --accent-gold: #facc15;
            --accent-blue: #3b82f6;
            --accent-green: #22c55e;
            --accent-red: #ef4444;
            --border: #334155;
        }

        * { box-sizing: border-box; outline: none; }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Heebo', sans-serif;
            margin: 0;
            height: 100vh;
            overflow: hidden; 
        }

        /* --- NAVBAR --- */
        .admin-navbar {
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid var(--accent-gold);
            padding: 8px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
        }

        .brand-logo {
            font-family: 'Teko', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: white;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .nav-actions { display: flex; gap: 10px; }

        .btn-nav {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-dim);
            padding: 5px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.8rem;
            transition: 0.3s;
            display: flex; align-items: center; gap: 6px;
        }
        .btn-nav:hover {
            border-color: var(--accent-gold);
            color: var(--accent-gold);
        }

        /* DASHBOARD LAYOUT */
        .dashboard-container {
            display: grid;
            grid-template-columns: 1fr 1fr 1.2fr; 
            gap: 15px;
            padding: 15px;
            height: calc(100vh - 60px);
            max-width: 100%;
        }

        /* COMPACT CARDS */
        .control-card {
            background: var(--bg-panel);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .card-title {
            font-family: 'Teko', sans-serif;
            font-size: 1.4rem;
            color: white;
            margin-bottom: 12px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 8px;
            display: flex; align-items: center; gap: 8px;
            text-transform: uppercase;
        }
        .card-title i { color: var(--accent-blue); }

        /* FORMS */
        .form-group { margin-bottom: 12px; }
        
        label {
            display: block;
            color: var(--text-dim);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        input, select, textarea {
            width: 100%;
            background: var(--bg-input);
            border: 1px solid var(--border);
            color: white;
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: 0.2s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--accent-blue);
            background: var(--bg-dark);
        }

        /* BUTTONS */
        .btn-action {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .btn-blue { background: var(--accent-blue); color: white; }
        .btn-green { background: var(--accent-green); color: white; }
        .btn-red { background: var(--accent-red); color: white; font-size: 1.1rem; }
        .btn-action:hover { opacity: 0.9; transform: translateY(-1px); }

        /* MATCH CONTROL SPECIFIC */
        .match-card {
            border: 1px solid var(--accent-gold);
            background: linear-gradient(to bottom, #1e293b, #0f172a);
        }

        .vs-grid {
            display: grid;
            grid-template-columns: 1fr 40px 1fr;
            align-items: center;
            gap: 8px;
        }
        .vs-badge {
            background: var(--accent-gold);
            color: #000;
            font-weight: 900;
            text-align: center;
            width: 30px;
            height: 30px;
            line-height: 30px;
            border-radius: 50%;
            font-size: 0.7rem;
            margin-top: 15px;
        }

        .warning-box {
            background: rgba(239, 68, 68, 0.05);
            border: 1px dashed var(--accent-red);
            padding: 10px;
            margin: 10px 0;
            color: #fca5a5;
            font-size: 0.75rem;
            border-radius: 6px;
        }

        /* Success Alert */
        .success-toast {
            position: fixed; top: 70px; right: 20px;
            background: var(--accent-green); color: white;
            padding: 12px 20px; border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            z-index: 200; display: flex; align-items: center; gap: 8px; font-weight: bold; font-size: 0.85rem;
        }

    </style>
</head>
<body>

    <nav class="admin-navbar">
        <div class="brand-logo">CTMS PRO<span style="color:var(--accent-blue)"> ADMIN CONSOLE</span></div>
        <div class="nav-actions">
            <a href="index.php" class="btn-nav" style="border-color:var(--accent-blue); color:var(--accent-blue);">
                <i class="fa-solid fa-house"></i> Home
            </a>
            <a href="history.php" class="btn-nav"><i class="fa-solid fa-clock-rotate-left"></i> History</a>
            <a href="scoreboard.php" target="_blank" class="btn-nav" style="border-color:var(--accent-gold); color:var(--accent-gold);">
                <i class="fa-solid fa-tv"></i> Scoreboard
            </a>
        </div>
    </nav>

    <div class="dashboard-container">
        
        <div class="control-card">
            <div>
                <div class="card-title">01. Create Team</div>
                <form onsubmit="submitForm(event, this)">
                    <div class="form-group">
                        <label>Team Full Name</label>
                        <input type="text" name="team_name" placeholder="e.g. Dark Riders" required>
                    </div>
                    <div class="form-group">
                        <label>Short Code (2 Letters)</label>
                        <input type="text" name="short_code" placeholder="e.g. DR" maxlength="2" style="text-transform:uppercase" required>
                    </div>
                    <input type="hidden" name="action" value="add_team">
            </div>
            <button class="btn-action btn-blue">Save Team</button>
            </form>
        </div>

        <div class="control-card">
            <div>
                <div class="card-title">02. Register Players</div>
                <form onsubmit="submitForm(event, this)">
                    <div class="form-group">
                        <label>Select Team</label>
                        <select name="team_id" required>
                            <option value="">-- Choose Team --</option>
                            <?php
                            $res = $conn->query("SELECT * FROM teams");
                            while($row = $res->fetch_assoc()) echo "<option value='{$row['team_id']}'>{$row['team_name']}</option>";
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Player Names (One per line)</label>
                        <textarea style="resize: none;" name="player_names" rows="4" placeholder="Kumar Sangakkara&#10;Mahela Jayawardene" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Default Role</label>
                        <select name="role">
                            <option value="All-Rounder">All-Rounder</option>
                            <option value="Batsman">Batsman</option>
                            <option value="Bowler">Bowler</option>
                        </select>
                    </div>
                    <input type="hidden" name="action" value="bulk_add_players">
            </div>
            <button class="btn-action btn-green">Add Players</button>
            </form>
        </div>

        <div class="control-card match-card">
            <div>   
                <div class="card-title" style="color:var(--accent-gold); border-bottom-color:rgba(250, 204, 21, 0.2);">
                    <i class="fa-solid fa-trophy"></i> Live Match Control
                </div>

                <?php
                // CHECK IF MATCH IS LIVE
                $live_check = $conn->query("SELECT * FROM match_live WHERE match_id=1");
                $match_data = $live_check->fetch_assoc();
                
                // SHOW BUTTONS ONLY IF MATCH IS LIVE
                $is_live = ($match_data && $match_data['status'] == 'LIVE');
                ?>

        <?php if ($is_live): ?>
            <div style="text-align:center; padding: 10px; background:rgba(0,0,0,0.2); border-radius:8px; margin-bottom:15px;">
                <h3 style="margin:0; color:var(--text-main);">MATCH RUNNING...</h3>
                <p style="margin:5px 0; color:var(--accent-gold); font-size:0.9rem;">
                    Target: <?php echo $match_data['target']; ?> | Overs: <?php echo $match_data['overs']; ?>
                </p>
            </div>

            <?php 
            // SHOW DLS BUTTON ONLY IF INNINGS 2 AND NOT DLS
            if($match_data['innings_no'] == 2 && $match_data['is_dls'] == 0): 
            ?>
                <div style="margin-top:15px; border-top:1px dashed var(--border); padding-top:10px;">
                    <label style="color:var(--accent-blue)">Weather Control</label>
                <form onsubmit="submitForm(event, this)">
                    <input type="hidden" name="action" value="trigger_rain">
                    <button class="btn-action" style="background:var(--bg-panel); border:1px solid var(--accent-blue); color:var(--accent-blue);">
                        FORCE RAIN (DLS)
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <?php else: ?>
            
            <div id="setupPanel">
                <form onsubmit="submitForm(event, this)" data-target="_blank">
                    <div class="form-group">
                        <label>Match Title</label>
                        <input type="text" name="match_name" placeholder="Tournament Name" required style="color:var(--accent-gold);">
                    </div>

                    <div class="vs-grid">
                        <div class="form-group">
                            <label>Team A</label>
                            <select name="team_a" id="teamA" required onchange="updateBattingOptions()">
                                <option value="">Select...</option>
                                <?php 
                                $res = $conn->query("SELECT * FROM teams");
                                while($row = $res->fetch_assoc()) echo "<option value='{$row['team_id']}'>{$row['team_name']}</option>";
                                ?>
                            </select>
                        </div>
                        <div class="vs-badge">VS</div>
                        <div class="form-group">
                            <label>Team B</label>
                            <select name="team_b" id="teamB" required onchange="updateBattingOptions()">
                                <option value="">Select...</option>
                                <?php 
                                $res = $conn->query("SELECT * FROM teams");
                                while($row = $res->fetch_assoc()) echo "<option value='{$row['team_id']}'>{$row['team_name']}</option>";
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="color:var(--accent-gold)">Who Bats First?</label>
                        <select name="batting_first" id="battingSelect" required style="border-color:var(--accent-gold);">
                            <option value="team_a">Team A</option>
                            <option value="team_b">Team B</option>
                        </select>
                    </div>
                    <input type="hidden" name="action" value="create_match">
                    
                    <button class="btn-action btn-red">Start Live Match</button>
                </form>
            </div>

            <div id="runningPanel" style="display:none; text-align:center; padding: 30px 20px; background:rgba(0,0,0,0.2); border-radius:8px; margin-bottom:15px; border: 1px dashed var(--accent-green);">
                <i class="fa-solid fa-circle-check" style="font-size:2rem; color:var(--accent-green); margin-bottom:10px;"></i>
                <h3 style="margin:0; color:var(--accent-green);">MATCH STARTED!</h3>
                <p style="margin:10px 0; color:var(--text-dim); font-size:0.9rem;">
                    Refresh to see Admin Controls...
                </p>
                <a href="admin.php" style="display:inline-block; margin-top:10px; color:var(--accent-gold); text-decoration:none; font-size:0.8rem; border:1px solid var(--border); padding:8px 15px; border-radius:5px; transition:0.3s;">
                    <i class="fa-solid fa-rotate"></i> Refresh
                </a>
            </div>

            <script>
                function uiSwitchToRunning() {
                    setTimeout(function() {
                        document.getElementById('setupPanel').style.display = 'none';
                        document.getElementById('runningPanel').style.display = 'block';
                    }, 100);
                }
            </script>

        <?php endif; ?>
    </div>
</div>

    </div>

    <script>
        function updateBattingOptions() {
            var teamA = document.getElementById("teamA");
            var teamB = document.getElementById("teamB");
            var battingSelect = document.getElementById("battingSelect");

            var nameA = teamA.options[teamA.selectedIndex].text;
            var nameB = teamB.options[teamB.selectedIndex].text;

            if (nameA !== "Select...") battingSelect.options[0].text = "Bat First: " + nameA;
            if (nameB !== "Select...") battingSelect.options[1].text = "Bat First: " + nameB;
        }
    </script>

    <script>
// TOAST NOTIFICATION
function showToast(message, type = 'success') {
    // Remove existing toast if any
    const existing = document.querySelector('.success-toast');
    if(existing) existing.remove();

    // Create new toast
    const toast = document.createElement('div');
    toast.className = 'success-toast';

    if (type === 'error') {
        toast.style.background = '#ef4444';
    }

    const icon = type === 'success' ? '<i class="fa-solid fa-circle-check"></i>' : '<i class="fa-solid fa-circle-exclamation"></i>';
    toast.innerHTML = `${icon} ${message}`;
    
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.transition = "opacity 0.5s ease";
        toast.style.opacity = "0";
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}

//FORM SUBMIT MAIN FUNCTION
function submitForm(event, formElement) {
    event.preventDefault();

    const formData = new FormData(formElement);
    const btn = formElement.querySelector('button[type="submit"]');
    const originalText = btn ? btn.innerText : "Submit";
    const openInNewTab = formElement.getAttribute('data-target') === '_blank';

    if(btn) {
        btn.innerText = "Processing...";
        btn.disabled = true;
    }

    fetch("api/admin_actions.php", {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(btn) {
            btn.innerText = originalText;
            btn.disabled = false;
        }

        if (data.status === 'success') {
            showToast(data.message, 'success');
            formElement.reset(); 

            if(data.redirect && openInNewTab) {
                window.open(data.redirect, '_blank');
                
                if(typeof uiSwitchToRunning === 'function') {
                    uiSwitchToRunning(); 
                }
            }
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        if(btn) {
            btn.innerText = originalText;
            btn.disabled = false;
        }
        showToast("System Connection Error!", 'error');
    });
}
</script>

</body>
</html>