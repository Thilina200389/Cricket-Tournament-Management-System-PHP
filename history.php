<?php 
session_start();
include 'api/db_connect.php'; 

//ADMIN CHECK
$is_admin = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Match Archive | CTMS PRO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@400;500;600;700&family=Heebo:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-dark: #0f172a;
            --bg-panel: #1e293b;
            --text-main: #f8fafc;
            --text-dim: #94a3b8;
            --accent-gold: #facc15;
            --accent-blue: #3b82f6;
            --accent-green: #22c55e;
            --accent-red: #ef4444;
            --border: #334155;
        }

        * { box-sizing: border-box; }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Heebo', sans-serif;
            margin: 0;
            padding-bottom: 50px;
        }

        /* NAVBAR */
        .history-navbar {
            background: rgba(30, 41, 59, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid var(--accent-gold);
            padding: 15px 40px;
            display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; z-index: 100;
        }
        .brand-logo { font-family: 'Teko', sans-serif; font-size: 2rem; font-weight: 700; color: white; letter-spacing: 1px; }
        .brand-logo span { color: var(--accent-gold); }

        .btn-back {
            background: transparent; border: 1px solid var(--border); color: var(--text-dim);
            padding: 8px 20px; border-radius: 6px; text-decoration: none; font-weight: 500;
            transition: 0.3s; display: flex; align-items: center; gap: 8px;
        }
        .btn-back:hover { border-color: var(--accent-blue); color: var(--accent-blue); background: rgba(59, 130, 246, 0.1); }

        /* CONTAINER */
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }

        /* TABLE CARD */
        .history-card {
            background: var(--bg-panel);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }

        .card-header {
            padding: 25px 30px;
            background: rgba(15, 23, 42, 0.5);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 15px;
        }
        .header-title { font-family: 'Teko', sans-serif; font-size: 1.8rem; color: white; margin: 0; text-transform: uppercase; }
        .count-badge { background: var(--accent-blue); color: white; padding: 2px 10px; border-radius: 12px; font-size: 0.9rem; font-weight: bold; }

        /* TABLE STYLES */
        table { width: 100%; border-collapse: collapse; }
        
        th {
            background: #0f172a; color: var(--text-dim);
            text-align: left; padding: 18px 25px;
            text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; font-weight: 600;
        }
        
        td {
            padding: 20px 25px;
            border-bottom: 1px solid var(--border);
            color: var(--text-main);
            vertical-align: middle;
        }
        
        tr:last-child td { border-bottom: none; }
        tr:hover { background: rgba(255,255,255,0.02); }

        /* DATA STYLING */
        .date-text { color: var(--text-dim); font-size: 0.9rem; display: block; margin-bottom: 3px; }
        .time-text { color: #64748b; font-size: 0.8rem; font-weight: bold; }
        
        .match-title { font-weight: 700; font-size: 1.1rem; color: white; display: block; margin-bottom: 5px; }
        
        .team-name { font-family: 'Teko', sans-serif; font-size: 1.4rem; color: #cbd5e1; }
        .vs-tag { color: var(--text-dim); font-size: 0.8rem; font-weight: bold; margin: 0 10px; background: #0f172a; padding: 4px 8px; border-radius: 4px; }
        
        .score-display { font-family: 'Teko', sans-serif; font-size: 1.5rem; color: var(--accent-gold); letter-spacing: 0.5px; }

        /* RESULT BADGES */
        .badge { display: inline-block; padding: 6px 15px; border-radius: 30px; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        
        .badge-win { background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); }
        .badge-draw { background: rgba(250, 204, 21, 0.1); color: #facc15; border: 1px solid rgba(250, 204, 21, 0.3); }
        .badge-abandon { background: rgba(148, 163, 184, 0.1); color: #94a3b8; border: 1px solid #334155; }

        /* EMPTY STATE */
        .empty-state { padding: 60px; text-align: center; color: var(--text-dim); }
        .empty-icon { font-size: 3rem; margin-bottom: 20px; opacity: 0.3; }

        @media (max-width: 768px) {
            .team-name { display: block; font-size: 1.2rem; }
            .vs-tag { display: block; width: fit-content; margin: 5px 0; }
        }
    </style>
</head>
<body>

    <nav class="history-navbar">
        <div class="brand-logo">CTMS <span>ARCHIVE</span></div>
        
        <?php if($is_admin): ?>
            <a href="admin.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back to Console</a>
        <?php else: ?>
            <a href="login.php" class="btn-back" style="border-color:var(--accent-green); color:var(--accent-green);">
                <i class="fa-solid fa-user-lock"></i> Admin Login
            </a>
        <?php endif; ?>
    </nav>

    <div class="container">
        
        <div class="history-card">
            <div class="card-header">
                <i class="fa-solid fa-clock-rotate-left" style="font-size:1.5rem; color:var(--accent-gold);"></i>
                <h2 class="header-title">Match History Log</h2>
                
                <?php 
                $count_res = $conn->query("SELECT COUNT(*) as c FROM match_history");
                $count = $count_res ? $count_res->fetch_assoc()['c'] : 0;
                ?>
                <span class="count-badge"><?php echo $count; ?> Records</span>

                <?php if($is_admin): ?>
                <form method="POST" action="api/admin_actions.php" onsubmit="return confirm('WARNING: This will delete ALL history. Cannot undo!');" style="margin-left:auto;">
                    <input type="hidden" name="action" value="clear_all_history">
                    <button style="background:rgba(239, 68, 68, 0.1); border:1px solid #7f1d1d; color:#ef4444; padding:5px 15px; border-radius:8px; cursor:pointer; font-weight:bold; font-size:0.8rem; transition:0.3s;">
                        CLEAR ALL
                    </button>
                </form>
                <?php endif; ?>
            </div>

            <table>
                <thead>
                    <tr>
                        <th width="20%">Date & Time</th>
                        <th width="25%">Match Details</th>
                        <th width="30%">Teams</th>
                        <th width="10%">Scores</th>
                        <th width="<?php echo $is_admin ? '10%' : '15%'; ?>">Result</th>
                        
                        <?php if($is_admin): ?>
                        <th width="5%" style="text-align:center;">Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = $conn->query("SELECT * FROM match_history ORDER BY id DESC");

                    if ($res && $res->num_rows > 0) {
                        while ($row = $res->fetch_assoc()) {
                            
                            // Badge Logic
                            $res_text = $row['result_desc'];
                            $badge_class = "badge-draw"; 
                            $icon = "fa-circle-check";

                            if (stripos($res_text, 'WON') !== false) {
                                $badge_class = "badge-win";
                                $icon = "fa-trophy";
                            } elseif (stripos($res_text, 'Abandoned') !== false || stripos($res_text, 'No Result') !== false) {
                                $badge_class = "badge-abandon";
                                $icon = "fa-ban";
                            }

                            $phpdate = strtotime($row['played_date']);
                            $date_str = date("M d, Y", $phpdate);
                            $time_str = date("h:i A", $phpdate);

                            echo "<tr>
                                <td>
                                    <span class='date-text'><i class='fa-regular fa-calendar'></i> $date_str</span>
                                    <span class='time-text'>$time_str</span>
                                </td>
                                <td>
                                    <span class='match-title'>{$row['match_name']}</span>
                                    <span style='font-size:0.8rem; color:#64748b;'>ID: #{$row['id']}</span>
                                </td>
                                <td>
                                    <span class='team-name'>{$row['team_a']}</span>
                                    <span class='vs-tag'>VS</span>
                                    <span class='team-name'>{$row['team_b']}</span>
                                </td>
                                <td>
                                    <span class='score-display'>{$row['scores']}</span>
                                </td>
                                <td>
                                    <span class='badge {$badge_class}'>
                                        <i class='fa-solid {$icon}'></i> {$res_text}
                                    </span>
                                </td>";

                                // DELETE BUTTON (ADMIN ONLY)
                                if($is_admin) {
                                    echo "<td>
                                        <form method='POST' action='api/admin_actions.php' onsubmit=\"return confirm('Delete this record permanently?');\">
                                            <input type='hidden' name='match_id' value='{$row['id']}'>
                                            <input type='hidden' name='action' value='delete_history'>
                                            <button style='background:transparent; border:none; color:#ef4444; cursor:pointer; font-size:1.1rem; transition:0.2s;' title='Delete Record'>
                                                <i class='fa-solid fa-trash'></i>
                                            </button>
                                        </form>
                                    </td>";
                                }

                            echo "</tr>";
                        }
                    } else {

                        $colspan = $is_admin ? 6 : 5;
                        echo "<tr>
                            <td colspan='$colspan'>
                                <div class='empty-state'>
                                    <i class='fa-solid fa-box-open empty-icon'></i>
                                    <h3>No Match Records Found</h3>
                                    <p>Completed matches will appear here automatically.</p>
                                </div>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

    <script>
// CATCH JSON FROM BACKEND FUNCTION
function submitForm(event, formElement) {
    event.preventDefault();

    const formData = new FormData(formElement);
    const btn = formElement.querySelector('button[type="submit"]');
    const originalText = btn ? btn.innerText : "Submit";
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
        if (data.status === 'success') {
            alert("Success: " + data.message);
            if(data.redirect) {
                window.location.href = data.redirect;
            } else {
                window.location.reload();
            }
        } else {
            alert("Error: " + data.message);
            if(btn) {
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }
    })
    .catch(error => {
        alert("System Error: Connection Failed!");
        if(btn) {
            btn.innerText = originalText;
            btn.disabled = false;
        }
    });
}
</script>

</body>
</html>