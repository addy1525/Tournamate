<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tournamate — Select Your Portal</title>
    <link rel="stylesheet" href="{{ asset('css/tournamate-styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            background: #070d1a;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        /* ── Background ── */
        .bg-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
        }
        .bg-orb-1 {
            width: 700px; height: 700px;
            background: radial-gradient(circle, rgba(0,168,107,0.14) 0%, transparent 70%);
            top: -250px; left: -200px;
            animation: drift1 18s ease-in-out infinite alternate;
        }
        .bg-orb-2 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(0,212,255,0.1) 0%, transparent 70%);
            bottom: -150px; right: -150px;
            animation: drift2 22s ease-in-out infinite alternate;
        }
        .bg-orb-3 {
            width: 350px; height: 350px;
            background: radial-gradient(circle, rgba(124,58,237,0.08) 0%, transparent 70%);
            top: 50%; left: 50%;
            transform: translate(-50%,-50%);
            animation: drift3 14s ease-in-out infinite alternate;
        }
        @keyframes drift1 { to { transform: translate(60px,40px) scale(1.1); } }
        @keyframes drift2 { to { transform: translate(-50px,-30px) scale(1.15); } }
        @keyframes drift3 { to { transform: translate(-45%, -55%) scale(1.2); } }

        .bg-grid {
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.024) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.024) 1px, transparent 1px);
            background-size: 48px 48px;
            z-index: 0;
        }

        /* ── Content Wrapper ── */
        .wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 960px;
        }

        /* ── Brand Header ── */
        .brand-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        .brand-logo {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, #00A86B, #00d4ff);
            border-radius: 22px;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.2rem; color: #fff;
            box-shadow: 0 0 50px rgba(0,168,107,0.4);
            margin: 0 auto 1.5rem;
            animation: logoPulse 3s ease-in-out infinite;
        }
        @keyframes logoPulse {
            0%, 100% { box-shadow: 0 0 50px rgba(0,168,107,0.4); }
            50% { box-shadow: 0 0 70px rgba(0,168,107,0.6), 0 0 30px rgba(0,212,255,0.2); }
        }
        .brand-name {
            font-size: 3.25rem;
            font-weight: 900;
            color: #fff;
            letter-spacing: -2px;
            line-height: 1;
            margin-bottom: 0.6rem;
        }
        .brand-name span {
            background: linear-gradient(90deg, #00A86B, #00d4ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .brand-tagline {
            font-size: 1rem;
            color: rgba(255,255,255,0.38);
            font-weight: 400;
            letter-spacing: 0.5px;
        }

        /* ── Prompt ── */
        .portal-prompt {
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.25);
            text-align: center;
            margin-bottom: 1.25rem;
        }

        /* ── Portal Grid ── */
        .portal-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        @media (max-width: 780px) {
            .portal-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 420px) {
            .portal-grid { grid-template-columns: 1fr; }
        }

        .portal-card {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 2rem 1.25rem 1.75rem;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.34,1.56,0.64,1);
            overflow: hidden;
            cursor: pointer;
        }
        .portal-card::before {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: inherit;
        }
        .portal-card:hover {
            transform: translateY(-8px) scale(1.02);
            border-color: rgba(255,255,255,0.18);
        }
        .portal-card:hover::before { opacity: 1; }

        /* Per-role colors */
        .card-admin { }
        .card-admin::before { background: radial-gradient(ellipse at 50% 0%, rgba(0,168,107,0.12) 0%, transparent 70%); }
        .card-admin:hover { box-shadow: 0 20px 50px rgba(0,168,107,0.2), 0 0 0 1px rgba(0,168,107,0.3); border-color: rgba(0,168,107,0.4) !important; }

        .card-manager::before { background: radial-gradient(ellipse at 50% 0%, rgba(0,212,255,0.1) 0%, transparent 70%); }
        .card-manager:hover { box-shadow: 0 20px 50px rgba(0,212,255,0.15), 0 0 0 1px rgba(0,212,255,0.3); border-color: rgba(0,212,255,0.35) !important; }

        .card-referee::before { background: radial-gradient(ellipse at 50% 0%, rgba(251,191,36,0.1) 0%, transparent 70%); }
        .card-referee:hover { box-shadow: 0 20px 50px rgba(251,191,36,0.15), 0 0 0 1px rgba(251,191,36,0.3); border-color: rgba(251,191,36,0.35) !important; }

        .card-spectator::before { background: radial-gradient(ellipse at 50% 0%, rgba(167,139,250,0.1) 0%, transparent 70%); }
        .card-spectator:hover { box-shadow: 0 20px 50px rgba(167,139,250,0.15), 0 0 0 1px rgba(167,139,250,0.3); border-color: rgba(167,139,250,0.35) !important; }

        /* Icon */
        .portal-icon {
            width: 64px; height: 64px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1.1rem;
            position: relative;
            z-index: 1;
            transition: transform 0.3s ease;
        }
        .portal-card:hover .portal-icon {
            transform: scale(1.1);
        }
        .icon-admin    { background: linear-gradient(135deg, #00A86B, #00c17f); color: #fff; box-shadow: 0 8px 25px rgba(0,168,107,0.35); }
        .icon-manager  { background: linear-gradient(135deg, #0ea5e9, #38bdf8); color: #fff; box-shadow: 0 8px 25px rgba(14,165,233,0.35); }
        .icon-referee  { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: #1a1100; box-shadow: 0 8px 25px rgba(245,158,11,0.35); }
        .icon-spectator{ background: linear-gradient(135deg, #7c3aed, #a78bfa); color: #fff; box-shadow: 0 8px 25px rgba(124,58,237,0.35); }

        .portal-name {
            font-size: 1.1rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 0.35rem;
            position: relative; z-index: 1;
        }
        .portal-desc {
            font-size: 0.78rem;
            color: rgba(255,255,255,0.38);
            line-height: 1.5;
            margin-bottom: 1.15rem;
            position: relative; z-index: 1;
        }
        .portal-features {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            text-align: left;
            padding: 0.85rem;
            background: rgba(0,0,0,0.2);
            border-radius: 10px;
            position: relative; z-index: 1;
        }
        .portal-feat {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.73rem;
            color: rgba(255,255,255,0.45);
        }
        .portal-feat i { font-size: 0.65rem; }
        .feat-admin    { color: #4ade80; }
        .feat-manager  { color: #38bdf8; }
        .feat-referee  { color: #fbbf24; }
        .feat-spectator{ color: #a78bfa; }

        .portal-arrow {
            position: absolute;
            top: 1rem; right: 1rem;
            font-size: 0.75rem;
            color: rgba(255,255,255,0.2);
            transition: all 0.3s;
            z-index: 1;
        }
        .portal-card:hover .portal-arrow {
            color: rgba(255,255,255,0.6);
            transform: translate(2px,-2px);
        }

        /* ── Register Strip ── */
        .register-strip {
            text-align: center;
        }
        .register-strip p {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.3);
            margin-bottom: 0.75rem;
        }
        .register-strip-btns {
            display: flex;
            gap: 0.65rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .reg-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.6rem 1.25rem;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            background: rgba(255,255,255,0.04);
            color: rgba(255,255,255,0.55);
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.25s ease;
        }
        .reg-btn:hover {
            background: rgba(0,168,107,0.12);
            border-color: rgba(0,168,107,0.35);
            color: #4ade80;
        }

        /* ── Footer ── */
        .page-footer {
            text-align: center;
            margin-top: 2.5rem;
            font-size: 0.75rem;
            color: rgba(255,255,255,0.18);
        }
        .page-footer span { margin: 0 0.5rem; }
    </style>
</head>

<body>
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>
    <div class="bg-grid"></div>

    <div class="wrapper">
        <!-- Brand -->
        <div class="brand-header">
            <div class="brand-logo">
                <i class="fas fa-football"></i>
            </div>
            <h1 class="brand-name">Tourna<span>mate</span></h1>
            <p class="brand-tagline">Smart Rugby Management System</p>
        </div>

        <!-- Prompt -->
        <p class="portal-prompt">Choose your portal to continue</p>

        <!-- Portal Cards -->
        <div class="portal-grid">

            <!-- Admin -->
            <a href="{{ route('login.admin') }}" class="portal-card card-admin">
                <i class="fas fa-arrow-up-right portal-arrow"></i>
                <div class="portal-icon icon-admin">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="portal-name">Administrator</div>
                <div class="portal-desc">Full system control for tournament management</div>
                <div class="portal-features">
                    <div class="portal-feat"><i class="fas fa-check feat-admin"></i> Tournament Management</div>
                    <div class="portal-feat"><i class="fas fa-check feat-admin"></i> User Administration</div>
                    <div class="portal-feat"><i class="fas fa-check feat-admin"></i> Reports & Analytics</div>
                </div>
            </a>

            <!-- Manager -->
            <a href="{{ route('login.manager') }}" class="portal-card card-manager">
                <i class="fas fa-arrow-up-right portal-arrow"></i>
                <div class="portal-icon icon-manager">
                    <i class="fas fa-users"></i>
                </div>
                <div class="portal-name">Team Manager</div>
                <div class="portal-desc">Manage your squad & enter tournaments</div>
                <div class="portal-features">
                    <div class="portal-feat"><i class="fas fa-check feat-manager"></i> Player Registration</div>
                    <div class="portal-feat"><i class="fas fa-check feat-manager"></i> Team Management</div>
                    <div class="portal-feat"><i class="fas fa-check feat-manager"></i> Match Schedule</div>
                </div>
            </a>

            <!-- Referee -->
            <a href="{{ route('login.referee') }}" class="portal-card card-referee">
                <i class="fas fa-arrow-up-right portal-arrow"></i>
                <div class="portal-icon icon-referee">
                    <i class="fas fa-whistle"></i>
                </div>
                <div class="portal-name">Match Official</div>
                <div class="portal-desc">Record scores & manage live matches</div>
                <div class="portal-features">
                    <div class="portal-feat"><i class="fas fa-check feat-referee"></i> Scoring Console</div>
                    <div class="portal-feat"><i class="fas fa-check feat-referee"></i> Event Log</div>
                    <div class="portal-feat"><i class="fas fa-check feat-referee"></i> Match History</div>
                </div>
            </a>

            <!-- Spectator -->
            <a href="{{ route('login.spectator') }}" class="portal-card card-spectator">
                <i class="fas fa-arrow-up-right portal-arrow"></i>
                <div class="portal-icon icon-spectator">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="portal-name">Spectator</div>
                <div class="portal-desc">Follow tournaments & watch live matches</div>
                <div class="portal-features">
                    <div class="portal-feat"><i class="fas fa-check feat-spectator"></i> Live Streaming</div>
                    <div class="portal-feat"><i class="fas fa-check feat-spectator"></i> Standings & Brackets</div>
                    <div class="portal-feat"><i class="fas fa-check feat-spectator"></i> Full Schedule</div>
                </div>
            </a>
        </div>

        <!-- Register Strip -->
        <div class="register-strip">
            <p>Don't have an account? Register as</p>
            <div class="register-strip-btns">
                <a href="{{ route('register') }}?role=manager" class="reg-btn">
                    <i class="fas fa-users"></i> Team Manager
                </a>
                <a href="{{ route('register') }}?role=spectator" class="reg-btn">
                    <i class="fas fa-eye"></i> Spectator
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="page-footer">
            <span>© 2026 Tournamate</span>•
            <span>Smart Rugby Management System</span>
        </div>
    </div>
</body>
</html>