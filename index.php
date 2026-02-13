<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CTMS PRO Broadcast Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@400;600;700&family=Heebo:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-dark: #020617;
            --accent-gold: #facc15;
            --accent-blue: #3b82f6;
            --accent-green: #22c55e; /* History Button Color */
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        body {
            margin: 0;
            height: 100vh;
            background-color: var(--bg-dark);
            /* Dynamic Background */
            background: radial-gradient(circle at top center, #1e3a8a 0%, #0f172a 40%, #020617 100%);
            background-size: 200% 200%;
            animation: bgPulse 10s ease infinite;
            color: var(--text-main);
            font-family: 'Heebo', sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        @keyframes bgPulse {
            0% { background-position: 50% 0%; }
            50% { background-position: 50% 20%; }
            100% { background-position: 50% 0%; }
        }

        /* --- STATUS BADGE --- */
        .system-badge {
            position: absolute;
            top: 30px;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--text-muted);
            backdrop-filter: blur(5px);
            display: flex; align-items: center; gap: 10px;
        }
        .status-dot { width: 8px; height: 8px; background: var(--accent-green); border-radius: 50%; box-shadow: 0 0 10px #22c55e; }

        /* --- HERO TITLE --- */
        .brand-section {
            text-align: center;
            margin-bottom: 60px;
            position: relative;
            z-index: 10;
        }

        .main-title {
            font-family: 'Teko', sans-serif;
            font-size: 6rem;
            line-height: 0.9;
            margin: 0;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 4px;
            /* Gold Gradient Text */
            background: linear-gradient(to bottom, #ffffff 40%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.5));
        }

        .sub-title {
            font-size: 1.1rem;
            color: var(--accent-gold);
            letter-spacing: 6px;
            text-transform: uppercase;
            margin-top: 10px;
            opacity: 0.9;
            text-shadow: 0 0 10px rgba(250, 204, 21, 0.3);
        }

        /* --- CARD GRID --- */
        .card-container {
            display: grid;
            /* Updated to fit 3 cards in one row */
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            z-index: 10;
            max-width: 1200px;
            padding: 0 20px;
        }

        .nav-card {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            /* Removed fixed width to allow flexibility */
            min-width: 280px;
            padding: 50px 30px;
            border-radius: 24px;
            text-align: center;
            text-decoration: none;
            color: white;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
        }

        /* Hover Glow */
        .nav-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle at center, rgba(255,255,255,0.05) 0%, transparent 70%);
            opacity: 0;
            transition: 0.4s;
        }

        .nav-card:hover {
            transform: translateY(-15px);
            background: rgba(30, 41, 59, 0.8);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6);
        }
        .nav-card:hover::after { opacity: 1; }

        /* Specific Colors */
        .card-admin:hover { border-bottom: 4px solid var(--accent-blue); }
        .card-score:hover { border-bottom: 4px solid var(--accent-gold); }
        .card-history:hover { border-bottom: 4px solid var(--accent-green); } /* Added History Color */

        .icon-circle {
            width: 80px; height: 80px;
            background: rgba(15, 23, 42, 0.5);
            border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            margin: 0 auto 25px auto;
            font-size: 2rem;
            transition: 0.4s;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .card-admin .icon-circle { color: var(--accent-blue); }
        .card-score .icon-circle { color: var(--accent-gold); }
        .card-history .icon-circle { color: var(--accent-green); } /* Added History Icon Color */

        .nav-card:hover .icon-circle {
            transform: scale(1.1) rotate(5deg);
            background: rgba(255, 255, 255, 0.05);
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }

        .card-headline {
            font-family: 'Teko', sans-serif;
            font-size: 2.2rem;
            font-weight: 500;
            text-transform: uppercase;
            display: block;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .card-subtext {
            color: var(--text-muted);
            font-size: 0.9rem;
            line-height: 1.6;
            display: block;
        }

        /* --- FOOTER --- */
        .footer {
            position: absolute; bottom: 20px;
            color: var(--text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            opacity: 0.6;
        }

        @media (max-width: 900px) {
            .card-container { grid-template-columns: 1fr; }
            .main-title { font-size: 4rem; }
        }
    </style>
</head>
<body>

    <div class="brand-section">
        <h1 class="main-title">CTMS Pro V3</h1>
        <div class="sub-title">Cricket Tournament Management System</div>
    </div>

    <div class="card-container">
        
        <a href="login.php" class="nav-card card-admin">
            <div class="icon-circle">
                <i class="fa-solid fa-sliders"></i>
            </div>
            <span class="card-headline">Admin Console</span>
            <span class="card-subtext">Manage teams, players, and initialize match configurations.</span>
        </a>

        <a href="scoreboard.php" class="nav-card card-score">
            <div class="icon-circle">
                <i class="fa-solid fa-satellite-dish"></i>
            </div>
            <span class="card-headline">Live Broadcast</span>
            <span class="card-subtext">Launch the public scoreboard display for streaming.</span>
        </a>

        <a href="history.php" class="nav-card card-history">
            <div class="icon-circle">
                <i class="fa-solid fa-clock"></i>
            </div>
            <span class="card-headline">History</span>
            <span class="card-subtext">View previous match records and manage history.</span>
        </a>

    </div>

    <div class="footer">CTMS Pro • Version 3.0 • Developed by Thilina Sandakelum</div>

</body>
</html>