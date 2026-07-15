<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Tournamate - Smart Rugby Management System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <style>
        :root {
            --color-bg-primary: #0a1628;
            --color-bg-secondary: #121e33;
            --color-bg-tertiary: #1b2942;
            --color-bg-elevated: #243555;
            
            --color-rugby-green: #00a86b;
            --color-rugby-green-dark: #008556;
            --color-rugby-green-light: #00c17f;
            --color-electric-blue: #00d4ff;
            --color-electric-blue-light: #33ddff;
            
            --color-danger: #ef4444;
            --color-warning: #ffa726;
            --color-success: #00a86b;
            --color-info: #00d4ff;
            
            --color-text-primary: #f8fafc;
            --color-text-secondary: #cbd5e1;
            --color-text-tertiary: #94a3b8;
            --color-text-muted: #64748b;
            
            --color-border: #1e293b;
            --color-border-light: #334155;
            
            --shadow-glow-green: 0 0 25px rgba(0, 168, 107, 0.25);
            --shadow-glow-blue: 0 0 25px rgba(0, 212, 255, 0.25);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--color-bg-primary);
            color: var(--color-text-primary);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Ambient background glow elements */
        .ambient-glow-1 {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(0, 168, 107, 0.08) 0%, rgba(0,0,0,0) 70%);
            top: -200px;
            left: -200px;
            pointer-events: none;
            z-index: 0;
        }

        .ambient-glow-2 {
            position: absolute;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(0, 212, 255, 0.06) 0%, rgba(0,0,0,0) 70%);
            top: 200px;
            right: -250px;
            pointer-events: none;
            z-index: 0;
        }

        /* Container helper */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        /* ===== NAVBAR ===== */
        header {
            width: 100%;
            background: rgba(10, 22, 40, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--color-border);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            padding: 15px 0;
            transition: all 0.3s;
        }

        .nav-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.6rem;
            color: #fff;
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .logo span {
            color: var(--color-rugby-green);
        }

        .logo-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, rgba(0, 168, 107, 0.2), rgba(0, 212, 255, 0.2));
            border: 1px solid var(--color-rugby-green);
            border-radius: 8px;
            color: var(--color-rugby-green);
            font-size: 1.1rem;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-links a {
            color: var(--color-text-secondary);
            font-weight: 500;
            font-size: 0.95rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: var(--color-rugby-green);
        }

        /* CTAs in Nav */
        .nav-ctas {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
            border: none;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--color-border-light);
            color: var(--color-text-secondary);
        }

        .btn-outline:hover {
            border-color: #fff;
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--color-rugby-green) 0%, var(--color-rugby-green-dark) 100%);
            color: #fff;
            box-shadow: 0 4px 15px rgba(0, 168, 107, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-glow-green);
            background: linear-gradient(135deg, var(--color-rugby-green-light) 0%, var(--color-rugby-green) 100%);
        }

        /* Dropdown Menu */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: var(--color-bg-secondary);
            border: 1px solid var(--color-border-light);
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            padding: 8px 0;
            width: 210px;
            display: none;
            z-index: 1100;
            animation: slideDown 0.2s ease;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        /* Transparent bridge to prevent losing hover in the 10px gap */
        .dropdown-menu::before {
            content: '';
            position: absolute;
            top: -12px;
            left: 0;
            right: 0;
            height: 12px;
            background: transparent;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            color: var(--color-text-secondary);
            font-size: 0.9rem;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background: var(--color-bg-tertiary);
            color: #fff;
        }

        .dropdown-item i {
            width: 16px;
            text-align: center;
            font-size: 1rem;
        }

        .dropdown-divider {
            height: 1px;
            background: var(--color-border-light);
            margin: 6px 0;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== HERO SECTION ===== */
        .hero {
            padding: 160px 0 80px 0;
            position: relative;
            background-image: 
                radial-gradient(var(--color-border) 1px, transparent 1px),
                radial-gradient(var(--color-border) 1px, transparent 1px);
            background-size: 40px 40px;
            background-position: 0 0, 20px 20px;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .hero-content {
            max-width: 550px;
        }

        .badge-promo {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(0, 212, 255, 0.08);
            border: 1px solid rgba(0, 212, 255, 0.2);
            color: var(--color-electric-blue);
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -1px;
            margin-bottom: 20px;
            color: #fff;
        }

        .hero-title span {
            background: linear-gradient(135deg, var(--color-rugby-green) 0%, var(--color-electric-blue) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc {
            color: var(--color-text-secondary);
            font-size: 1.1rem;
            margin-bottom: 35px;
            line-height: 1.7;
        }

        .hero-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .hero-widget-area {
            position: relative;
        }

        /* ===== WEATHER SAFETY CARD ===== */
        .safety-card {
            background: linear-gradient(135deg, var(--color-bg-secondary) 0%, var(--color-bg-tertiary) 100%);
            border: 1px solid var(--color-border-light);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            position: relative;
            overflow: hidden;
        }

        .safety-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--color-rugby-green) 0%, var(--color-electric-blue) 100%);
        }

        .safety-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .safety-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .safety-update-time {
            font-size: 0.75rem;
            color: var(--color-text-muted);
        }

        .safety-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .safety-item {
            background: rgba(10, 22, 40, 0.4);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .safety-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        /* Color classes */
        .safety-icon.safe { background: rgba(0, 168, 107, 0.1); color: var(--color-success); border: 1px solid rgba(0, 168, 107, 0.2); }
        .safety-icon.caution { background: rgba(255, 167, 38, 0.1); color: var(--color-warning); border: 1px solid rgba(255, 167, 38, 0.2); }
        .safety-icon.warning { background: rgba(239, 68, 68, 0.1); color: var(--color-danger); border: 1px solid rgba(239, 68, 68, 0.2); }
        .safety-icon.danger { background: rgba(239, 68, 68, 0.2); color: var(--color-danger); border: 1px solid var(--color-danger); animation: pulseAlert 1.5s infinite; }

        @keyframes pulseAlert {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            50% { box-shadow: 0 0 12px 6px rgba(239, 68, 68, 0); }
        }

        .safety-data {
            flex: 1;
        }

        .safety-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--color-text-tertiary);
            font-weight: 600;
        }

        .safety-value {
            font-size: 1.35rem;
            font-weight: 700;
            color: #fff;
            font-family: 'Outfit', sans-serif;
            margin-top: 2px;
        }

        .safety-footer {
            background: rgba(0, 212, 255, 0.03);
            border: 1px solid rgba(0, 212, 255, 0.08);
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.8rem;
            color: var(--color-text-secondary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* ===== TOURNAMENTS SECTION ===== */
        .section-tournaments {
            padding: 80px 0;
            background: #081120;
            border-top: 1px solid var(--color-border);
            border-bottom: 1px solid var(--color-border);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 50px;
        }

        .section-title-area {
            max-width: 550px;
        }

        .section-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
            margin-bottom: 10px;
        }

        .section-subtitle {
            color: var(--color-text-tertiary);
            font-size: 1rem;
        }

        .tournaments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }

        .tournament-card {
            background: var(--color-bg-secondary);
            border: 1px solid var(--color-border-light);
            border-radius: 14px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        .tournament-card:hover {
            transform: translateY(-5px);
            border-color: var(--color-rugby-green);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .tournament-card-header {
            background: var(--color-bg-tertiary);
            padding: 20px;
            border-bottom: 1px solid var(--color-border);
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .tournament-status-badge {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .tournament-status-badge.ongoing { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }
        .tournament-status-badge.upcoming { background: rgba(0, 168, 107, 0.15); color: var(--color-rugby-green-light); border: 1px solid rgba(0, 168, 107, 0.3); }
        .tournament-status-badge.completed { background: rgba(100, 116, 139, 0.15); color: var(--color-text-tertiary); border: 1px solid rgba(100, 116, 139, 0.3); }

        .tournament-card-body {
            padding: 24px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .tournament-name {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .tournament-meta-list {
            margin-bottom: 24px;
        }

        .tournament-meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.875rem;
            color: var(--color-text-secondary);
            margin-bottom: 8px;
        }

        .tournament-meta-item i {
            color: var(--color-rugby-green);
            width: 16px;
            text-align: center;
        }

        .tournament-card-footer {
            padding: 0 24px 24px 24px;
            display: flex;
            gap: 10px;
        }

        /* ===== FEATURES SECTION ===== */
        .features {
            padding: 80px 0;
            background: var(--color-bg-primary);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 50px;
        }

        .feature-card {
            background: linear-gradient(135deg, rgba(18, 30, 51, 0.6) 0%, rgba(27, 41, 66, 0.6) 100%);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            padding: 30px;
            transition: all 0.3s;
        }

        .feature-card:hover {
            border-color: var(--color-electric-blue-light);
            background: linear-gradient(135deg, var(--color-bg-secondary) 0%, var(--color-bg-tertiary) 100%);
        }

        .feature-icon-wrapper {
            width: 50px;
            height: 50px;
            background: rgba(0, 212, 255, 0.08);
            border: 1px solid rgba(0, 212, 255, 0.15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            color: var(--color-electric-blue);
            margin-bottom: 20px;
        }

        .feature-card:hover .feature-icon-wrapper {
            background: var(--color-electric-blue);
            color: #0a1628;
            box-shadow: var(--shadow-glow-blue);
        }

        .feature-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 12px;
        }

        .feature-desc {
            color: var(--color-text-secondary);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* ===== FOOTER ===== */
        footer {
            background: #040912;
            border-top: 1px solid var(--color-border);
            padding: 50px 0 30px 0;
            color: var(--color-text-muted);
            font-size: 0.9rem;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-logo {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.3rem;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-logo span { color: var(--color-rugby-green); }

        .footer-links {
            display: flex;
            gap: 25px;
        }

        .footer-links a {
            color: var(--color-text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: #fff;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.03);
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
        }

        /* ===== RESPONSIVENESS ===== */
        @media (max-width: 991px) {
            .hero-grid {
                grid-template-columns: 1fr;
                gap: 50px;
            }
            .features-grid {
                grid-template-columns: 1fr;
            }
            .hero-title {
                font-size: 2.8rem;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <!-- NAVBAR -->
    <header>
        <div class="container nav-wrapper">
            <a href="/" class="logo">
                <div class="logo-icon"><i class="fas fa-football"></i></div>
                TOURNA<span>MATE</span>
            </a>
            
            <nav class="nav-links">
                <a href="#hero">Home</a>
                <a href="#safety">Safety</a>
                <a href="#tournaments">Tournaments</a>
                <a href="#features">Features</a>
            </nav>

            <div class="nav-ctas">
                @auth
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-desktop"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-outline">
                        <i class="fas fa-user-plus"></i> Register Team
                    </a>
                    
                    <div class="dropdown">
                        <button class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Login <i class="fas fa-chevron-down" style="font-size:0.75rem; margin-left: 2px;"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="{{ route('login.spectator') }}" class="dropdown-item">
                                <i class="fas fa-eye" style="color: var(--color-electric-blue);"></i> Spectator / Fan
                            </a>
                            <a href="{{ route('login.manager') }}" class="dropdown-item">
                                <i class="fas fa-tasks" style="color: var(--color-rugby-green);"></i> Team Manager
                            </a>
                            <a href="{{ route('login.referee') }}" class="dropdown-item">
                                <i class="fas fa-flag" style="color: var(--color-warning);"></i> Referee / Official
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('login.admin') }}" class="dropdown-item">
                                <i class="fas fa-user-shield" style="color: var(--color-danger);"></i> Admin
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="hero" id="hero">
        <div class="container hero-grid">
            <div class="hero-content">
                <div class="badge-promo">
                    <i class="fas fa-sparkles"></i> Rugby Management Redefined
                </div>
                <h1 class="hero-title">Smart & Safe <span>Rugby Management</span> System</h1>
                <p class="hero-desc">
                    Manage rugby tournaments, monitor live match actions, generate pool standings dynamically, and ensure player welfare with our integrated weather hazard alert system (WBGT & Lightning).
                </p>
                <div class="hero-actions">
                    @auth
                        <a href="{{ route('home') }}" class="btn btn-primary" style="padding: 14px 28px; font-size: 1rem;">
                            <i class="fas fa-desktop"></i> Open Your Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 14px 28px; font-size: 1rem;">
                            <i class="fas fa-trophy"></i> Register Tournament Team
                        </a>
                        <a href="{{ route('login.spectator') }}" class="btn btn-outline" style="padding: 14px 28px; font-size: 1rem;">
                            <i class="fas fa-play"></i> Watch & Check Schedule
                        </a>
                    @endauth
                </div>
            </div>

            <!-- WEATHER SAFETY WIDGET -->
            <div class="hero-widget-area" id="safety">
                @php
                    $wbgtValue = $safetyLog->wbgt ?? null;
                    $lightningValue = $safetyLog->lightning_risk ?? null;

                    // WBGT Status
                    $wbgtStatus = 'safe';
                    $wbgtLabel = 'Safe';
                    if ($wbgtValue !== null) {
                        if ($wbgtValue >= 32) {
                            $wbgtStatus = 'danger';
                            $wbgtLabel = 'Extreme Danger';
                        } elseif ($wbgtValue >= 28) {
                            $wbgtStatus = 'warning';
                            $wbgtLabel = 'High Caution';
                        } elseif ($wbgtValue >= 22) {
                            $wbgtStatus = 'caution';
                            $wbgtLabel = 'Moderate';
                        }
                    }

                    // Lightning Status
                    $lightningStatus = 'safe';
                    $lightningLabel = 'Safe';
                    if ($lightningValue !== null) {
                        if ($lightningValue <= 10) {
                            $lightningStatus = 'danger';
                            $lightningLabel = 'Lightning Hazard';
                        } elseif ($lightningValue <= 20) {
                            $lightningStatus = 'warning';
                            $lightningLabel = 'Close Caution';
                        }
                    }
                @endphp
                <div class="safety-card">
                    <div class="safety-header">
                        <h3 class="safety-title">
                            <i class="fas fa-shield-halved" style="color: var(--color-electric-blue);"></i>
                            Safety Weather Widget
                        </h3>
                        <div class="safety-update-time">
                            <i class="fas fa-clock"></i> 
                            {{ $safetyLog ? $safetyLog->created_at->diffForHumans() : 'No records' }}
                        </div>
                    </div>

                    <div class="safety-grid">
                        <!-- WBGT METRIC -->
                        <div class="safety-item">
                            <div class="safety-icon {{ $wbgtStatus }}">
                                <i class="fas fa-temperature-high"></i>
                            </div>
                            <div class="safety-data">
                                <div class="safety-label">WBGT Index</div>
                                <div class="safety-value">
                                    {{ $wbgtValue ? number_format($wbgtValue, 1) . '°C' : 'N/A' }}
                                </div>
                                <span style="font-size: 0.75rem; font-weight: 600; color: {{ $wbgtStatus === 'danger' ? 'var(--color-danger)' : ($wbgtStatus === 'warning' ? 'var(--color-warning)' : 'var(--color-success)') }}">
                                    ● {{ $wbgtLabel }}
                                </span>
                            </div>
                        </div>

                        <!-- LIGHTNING METRIC -->
                        <div class="safety-item">
                            <div class="safety-icon {{ $lightningStatus }}">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div class="safety-data">
                                <div class="safety-label">Lightning Risk</div>
                                <div class="safety-value">
                                    {{ $lightningValue ? number_format($lightningValue, 1) . ' km' : 'N/A' }}
                                </div>
                                <span style="font-size: 0.75rem; font-weight: 600; color: {{ $lightningStatus === 'danger' ? 'var(--color-danger)' : ($lightningStatus === 'warning' ? 'var(--color-warning)' : 'var(--color-success)') }}">
                                    ● {{ $lightningLabel }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="safety-footer">
                        <i class="fas fa-info-circle" style="color: var(--color-electric-blue);"></i>
                        Tournament weather status is updated automatically to prevent heatstroke and lightning strike hazards on the field.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TOURNAMENTS SECTION -->
    <section class="section-tournaments" id="tournaments">
        <div class="container">
            <div class="section-header">
                <div class="section-title-area">
                    <h2 class="section-title">Active Rugby Tournaments</h2>
                    <p class="section-subtitle">Watch live broadcasts or register your team to our ongoing or upcoming tournaments.</p>
                </div>
            </div>

            @if(isset($tournaments) && $tournaments->count() > 0)
                <div class="tournaments-grid">
                    @foreach($tournaments as $t)
                        <div class="tournament-card">
                            <div class="tournament-card-header">
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <i class="fas fa-trophy" style="color: var(--color-rugby-green);"></i>
                                    <span style="font-size: 0.75rem; font-weight: 700; color: var(--color-text-tertiary); text-transform:uppercase;">
                                        {{ $t->categories ?? 'Rugby 7s' }}
                                    </span>
                                </div>
                                <span class="tournament-status-badge {{ $t->status }}">
                                    {{ $t->status === 'ongoing' ? '🔴 Live' : 'Upcoming' }}
                                </span>
                            </div>
                            <div class="tournament-card-body">
                                <h3 class="tournament-name">{{ $t->name }}</h3>
                                <div class="tournament-meta-list">
                                    <div class="tournament-meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $t->venue_name ?? $t->venue ?? 'TBD' }}</span>
                                    </div>
                                    <div class="tournament-meta-item">
                                        <i class="fas fa-calendar-days"></i>
                                        <span>
                                            {{ $t->start_date ? $t->start_date->format('d M Y') : 'TBD' }}
                                            @if($t->end_date)
                                                - {{ $t->end_date->format('d M Y') }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="tournament-meta-item">
                                        <i class="fas fa-coins"></i>
                                        <span>Fee: RM {{ number_format($t->fee ?? 0, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="tournament-card-footer">
                                @if($t->status === 'ongoing')
                                    <a href="{{ route('shared.live-stream', ['tournament_id' => $t->id]) }}" class="btn btn-primary" style="flex:1;">
                                        <i class="fas fa-broadcast-tower"></i> Live Stream
                                    </a>
                                    <a href="{{ route('shared.brackets', ['tournament_id' => $t->id]) }}" class="btn btn-outline" style="flex:1;">
                                        <i class="fas fa-sitemap"></i> Bracket
                                    </a>
                                @else
                                    <a href="{{ route('register') }}" class="btn btn-primary" style="width: 100%;">
                                        <i class="fas fa-user-plus"></i> Register Now
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align:center; padding: 60px 20px; background: var(--color-bg-secondary); border-radius: 12px; border: 1px dashed var(--color-border-light);">
                    <i class="fas fa-folder-open fa-3x" style="color: var(--color-text-muted); margin-bottom: 20px;"></i>
                    <h3 style="font-weight:600; margin-bottom: 8px;">No Active Tournaments</h3>
                    <p style="color: var(--color-text-secondary);">There are currently no ongoing or upcoming tournaments.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- CORE FEATURES SECTION -->
    <section class="features" id="features">
        <div class="container">
            <div style="text-align: center; max-width: 600px; margin: 0 auto 50px auto;">
                <h2 class="section-title">All-in-One Rugby Solution</h2>
                <p class="section-subtitle">Engineered specifically for smooth, fair, and safety-first modern rugby tournament administration.</p>
            </div>

            <div class="features-grid">
                <!-- FEATURE 1 -->
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-broadcast-tower"></i>
                    </div>
                    <h3 class="feature-title">Live Stream Broadcasts</h3>
                    <p class="feature-desc">Direct integration of YouTube/Twitch streams from each field with real-time score overlays updated by official referees.</p>
                </div>

                <!-- FEATURE 2 -->
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-cloud-sun"></i>
                    </div>
                    <h3 class="feature-title">Weather Safety Center</h3>
                    <p class="feature-desc">Digital monitoring of Wet Bulb Globe Temperature (WBGT) index and lightning proximity tracker to protect player and official welfare.</p>
                </div>

                <!-- FEATURE 3 -->
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <h3 class="feature-title">Automated Brackets</h3>
                    <p class="feature-desc">Dynamic generation of pools, match fixtures, and knockout stage brackets with automated point and standing calculations.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <a href="/" class="footer-logo">
                    <div class="logo-icon" style="width:30px; height:30px; font-size: 0.9rem;"><i class="fas fa-football"></i></div>
                    TOURNA<span>MATE</span>
                </a>
                <div class="footer-links">
                    <a href="#hero">Home</a>
                    <a href="#safety">Safety</a>
                    <a href="#tournaments">Tournaments</a>
                    <a href="#features">Features</a>
                </div>
            </div>
            <div class="footer-bottom">
                <div>&copy; 2026 Tournamate. All Rights Reserved.</div>
                <div>Smart Rugby Tournament Management System</div>
            </div>
        </div>
    </footer>
</body>

</html>
