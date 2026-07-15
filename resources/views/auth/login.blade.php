<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — Tournamate</title>
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
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* ── Animated Background ── */
        .auth-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }
        .auth-bg::before {
            content: '';
            position: absolute;
            width: 700px; height: 700px;
            background: radial-gradient(circle, rgba(0,168,107,0.15) 0%, transparent 70%);
            top: -200px; left: -200px;
            animation: floatOrb 14s ease-in-out infinite alternate;
        }
        .auth-bg::after {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(0,212,255,0.1) 0%, transparent 70%);
            bottom: -100px; right: -100px;
            animation: floatOrb 18s ease-in-out infinite alternate-reverse;
        }
        @keyframes floatOrb {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(60px, 40px) scale(1.15); }
        }

        /* Grid dots */
        .auth-grid {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 48px 48px;
            z-index: 0;
        }

        /* ── Wrapper ── */
        .auth-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1100px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.25rem;
            gap: 4rem;
        }

        /* ── Left Brand Panel ── */
        .brand-panel {
            flex: 0 0 360px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .brand-logo {
            width: 72px; height: 72px;
            background: linear-gradient(135deg, #00A86B, #00d4ff);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; color: #fff;
            box-shadow: 0 0 40px rgba(0,168,107,0.45);
            margin-bottom: 1.75rem;
        }
        .brand-title {
            font-size: 3rem;
            font-weight: 900;
            color: #fff;
            line-height: 1.05;
            margin-bottom: 1rem;
            letter-spacing: -1.5px;
        }
        .brand-title span {
            background: linear-gradient(90deg, #00A86B, #00d4ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .brand-desc {
            color: rgba(255,255,255,0.45);
            font-size: 1rem;
            line-height: 1.65;
            margin-bottom: 2.5rem;
            max-width: 300px;
        }
        .brand-features {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }
        .brand-feat {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: rgba(255,255,255,0.6);
            font-size: 0.9rem;
            font-weight: 500;
        }
        .brand-feat-icon {
            width: 32px; height: 32px;
            background: rgba(0,168,107,0.12);
            border: 1px solid rgba(0,168,107,0.25);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #00A86B;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        /* ── Login Card ── */
        .login-card {
            flex: 0 0 420px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.09);
            border-radius: 24px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 2.5rem;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5);
        }

        /* ── Role Pills ── */
        .role-tabs {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 0.4rem;
            background: rgba(0,0,0,0.3);
            border-radius: 12px;
            padding: 0.35rem;
            margin-bottom: 2rem;
        }
        .role-tab {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
            padding: 0.6rem 0.25rem;
            border-radius: 9px;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: all 0.25s ease;
            color: rgba(255,255,255,0.4);
            font-family: 'Outfit', sans-serif;
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }
        .role-tab i { font-size: 1rem; }
        .role-tab.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
            box-shadow: 0 2px 12px rgba(0,0,0,0.2);
        }
        .role-tab.active.role-admin   { background: rgba(0,168,107,0.18); color: #4ade80; border: 1px solid rgba(0,168,107,0.3); }
        .role-tab.active.role-manager { background: rgba(0,212,255,0.12); color: #38bdf8; border: 1px solid rgba(0,212,255,0.25); }
        .role-tab.active.role-referee { background: rgba(251,191,36,0.12); color: #fbbf24; border: 1px solid rgba(251,191,36,0.25); }
        .role-tab.active.role-spectator { background: rgba(167,139,250,0.12); color: #a78bfa; border: 1px solid rgba(167,139,250,0.25); }

        /* Role accent bar */
        .role-accent {
            width: 100%; height: 3px;
            border-radius: 2px;
            margin-bottom: 1.75rem;
            transition: background 0.4s ease;
        }

        /* ── Form Elements ── */
        .form-card-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 0.3rem;
            letter-spacing: -0.5px;
            transition: all 0.3s ease;
        }
        .form-card-sub {
            font-size: 0.875rem;
            color: rgba(255,255,255,0.4);
            margin-bottom: 1.75rem;
            transition: all 0.3s ease;
        }
        .field-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: rgba(255,255,255,0.55);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 0.45rem;
        }
        .field-wrap {
            position: relative;
            margin-bottom: 1.15rem;
        }
        .field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.3);
            font-size: 0.9rem;
            pointer-events: none;
        }
        .field-input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.6rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            transition: all 0.25s ease;
            outline: none;
        }
        .field-input::placeholder { color: rgba(255,255,255,0.25); }
        .field-input:focus {
            border-color: rgba(0,168,107,0.6);
            background: rgba(0,168,107,0.05);
            box-shadow: 0 0 0 3px rgba(0,168,107,0.12);
        }
        .field-input.focused-manager:focus { border-color: rgba(0,212,255,0.6); background: rgba(0,212,255,0.05); box-shadow: 0 0 0 3px rgba(0,212,255,0.1); }
        .field-input.focused-referee:focus  { border-color: rgba(251,191,36,0.6); background: rgba(251,191,36,0.05); box-shadow: 0 0 0 3px rgba(251,191,36,0.1); }
        .field-input.focused-spectator:focus { border-color: rgba(167,139,250,0.6); background: rgba(167,139,250,0.05); box-shadow: 0 0 0 3px rgba(167,139,250,0.1); }

        .toggle-pw {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255,255,255,0.3);
            cursor: pointer;
            font-size: 0.9rem;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: rgba(255,255,255,0.7); }

        /* Remember row */
        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .remember-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: rgba(255,255,255,0.5);
            font-size: 0.85rem;
            cursor: pointer;
        }
        .remember-check input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #00A86B;
            cursor: pointer;
        }
        .forgot-link {
            font-size: 0.82rem;
            color: rgba(255,255,255,0.35);
            text-decoration: none;
            transition: color 0.2s;
        }
        .forgot-link:hover { color: rgba(255,255,255,0.7); }

        /* ── Submit Button ── */
        .btn-login {
            width: 100%;
            padding: 0.9rem;
            border: none;
            border-radius: 12px;
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            letter-spacing: 0.3px;
        }
        .btn-login:hover { transform: translateY(-2px); filter: brightness(1.1); }
        .btn-login:active { transform: translateY(0); }

        .btn-admin    { background: linear-gradient(135deg, #00A86B, #00c17f); color: #fff; box-shadow: 0 8px 25px rgba(0,168,107,0.35); }
        .btn-manager  { background: linear-gradient(135deg, #0ea5e9, #38bdf8); color: #fff; box-shadow: 0 8px 25px rgba(14,165,233,0.35); }
        .btn-referee  { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: #1a1100; box-shadow: 0 8px 25px rgba(245,158,11,0.35); }
        .btn-spectator { background: linear-gradient(135deg, #7c3aed, #a78bfa); color: #fff; box-shadow: 0 8px 25px rgba(124,58,237,0.35); }

        /* ── Footer ── */
        .login-footer {
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.07);
            text-align: center;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.35);
        }
        .login-footer a {
            color: #00A86B;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s;
        }
        .login-footer a:hover { color: #4ade80; }

        /* ── Alert ── */
        .auth-alert {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1rem;
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: 10px;
            color: #fca5a5;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }
        .auth-alert i { font-size: 1rem; flex-shrink: 0; }

        /* ── Responsive ── */
        @media (max-width: 860px) {
            .brand-panel { display: none; }
            .auth-wrapper { justify-content: center; }
            .login-card { flex: 0 0 100%; max-width: 440px; }
        }
    </style>
</head>

<body>
    <div class="auth-bg"></div>
    <div class="auth-grid"></div>

    <div class="auth-wrapper">
        <!-- ── Brand Panel ── -->
        <div class="brand-panel">
            <div class="brand-logo">
                <i class="fas fa-football"></i>
            </div>
            <h1 class="brand-title">Tourna<span>mate</span></h1>
            <p class="brand-desc">
                The smart, all-in-one rugby tournament platform — from team registration to live match results.
            </p>
            <div class="brand-features">
                <div class="brand-feat">
                    <div class="brand-feat-icon"><i class="fas fa-trophy"></i></div>
                    <span>Full Tournament Management</span>
                </div>
                <div class="brand-feat">
                    <div class="brand-feat-icon"><i class="fas fa-broadcast-tower"></i></div>
                    <span>Real-Time Scores & Live Stream</span>
                </div>
                <div class="brand-feat">
                    <div class="brand-feat-icon"><i class="fas fa-bolt"></i></div>
                    <span>Elo Power Ranking System</span>
                </div>
                <div class="brand-feat">
                    <div class="brand-feat-icon"><i class="fas fa-shield-alt"></i></div>
                    <span>Multi-Role Access Control</span>
                </div>
            </div>
        </div>

        <!-- ── Login Card ── -->
        <div class="login-card">

            <!-- Role Selector -->
            <div class="role-tabs" role="tablist">
                <button class="role-tab role-admin active" data-role="admin" onclick="switchRole('admin')" type="button">
                    <i class="fas fa-shield-alt"></i>
                    Admin
                </button>
                <button class="role-tab role-manager" data-role="manager" onclick="switchRole('manager')" type="button">
                    <i class="fas fa-users"></i>
                    Manager
                </button>
                <button class="role-tab role-referee" data-role="referee" onclick="switchRole('referee')" type="button">
                    <i class="fas fa-whistle"></i>
                    Referee
                </button>
                <button class="role-tab role-spectator" data-role="spectator" onclick="switchRole('spectator')" type="button">
                    <i class="fas fa-eye"></i>
                    Spectator
                </button>
            </div>

            <!-- Accent Bar -->
            <div class="role-accent" id="roleAccent" style="background: linear-gradient(90deg, #00A86B, #00d4ff);"></div>

            <!-- Heading -->
            <h2 class="form-card-title" id="cardTitle">Admin Portal</h2>
            <p class="form-card-sub" id="cardSub">Full access to the tournament management system</p>

            <!-- Error -->
            @if($errors->any())
                <div class="auth-alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input type="hidden" name="portal_role" id="portalRoleInput" value="admin">

                <div>
                    <label class="field-label">Email Address</label>
                    <div class="field-wrap">
                        <i class="fas fa-envelope field-icon"></i>
                        <input type="email" name="email" class="field-input" id="emailInput"
                            value="{{ old('email') }}"
                            placeholder="your.email@example.com"
                            required autofocus autocomplete="email">
                    </div>
                </div>

                <div>
                    <label class="field-label">Password</label>
                    <div class="field-wrap">
                        <i class="fas fa-lock field-icon"></i>
                        <input type="password" name="password" class="field-input" id="passwordInput"
                            placeholder="••••••••"
                            required autocomplete="current-password">
                        <button type="button" class="toggle-pw" onclick="togglePassword()" id="togglePwBtn">
                            <i class="fas fa-eye" id="togglePwIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="remember-row">
                    <label class="remember-check">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-login btn-admin" id="submitBtn">
                    <i class="fas fa-sign-in-alt" id="submitIcon"></i>
                    <span id="submitText">Sign in as Admin</span>
                </button>
            </form>

            <!-- Footer -->
            <div class="login-footer">
                Don't have an account?
                <a href="{{ route('register') }}">Register here</a>
            </div>
            <div style="text-align:center; margin-top:0.85rem;">
                <a href="{{ route('welcome') }}" style="display:inline-flex; align-items:center; gap:0.4rem; font-size:0.8rem; color:rgba(255,255,255,0.25); text-decoration:none; transition:color 0.2s;" onmouseover="this.style.color='rgba(255,255,255,0.6)'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">
                    <i class="fas fa-arrow-left" style="font-size:0.7rem;"></i> Back to Home
                </a>
            </div>
        </div>
    </div>

    <script>
        const roles = {
            admin: {
                title: 'Admin Portal',
                sub: 'Full access to the tournament management system',
                accent: 'linear-gradient(90deg, #00A86B, #00d4ff)',
                btnClass: 'btn-admin',
                btnText: 'Sign in as Admin',
                btnIcon: 'fa-shield-alt',
            },
            manager: {
                title: 'Team Manager Portal',
                sub: 'Manage your squad, registrations & match schedule',
                accent: 'linear-gradient(90deg, #0ea5e9, #38bdf8)',
                btnClass: 'btn-manager',
                btnText: 'Sign in as Manager',
                btnIcon: 'fa-users',
            },
            referee: {
                title: 'Referee Portal',
                sub: 'Record scores & manage live match execution',
                accent: 'linear-gradient(90deg, #f59e0b, #fbbf24)',
                btnClass: 'btn-referee',
                btnText: 'Sign in as Referee',
                btnIcon: 'fa-whistle',
            },
            spectator: {
                title: 'Spectator Portal',
                sub: 'Follow tournaments & watch matches live',
                accent: 'linear-gradient(90deg, #7c3aed, #a78bfa)',
                btnClass: 'btn-spectator',
                btnText: 'Sign in as Spectator',
                btnIcon: 'fa-eye',
            }
        };

        let currentRole = 'admin';

        function switchRole(role) {
            currentRole = role;
            const cfg = roles[role];

            // Update tabs
            document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
            document.querySelector(`.role-tab.role-${role}`).classList.add('active');

            // Update accent bar
            document.getElementById('roleAccent').style.background = cfg.accent;

            // Update heading
            document.getElementById('cardTitle').textContent = cfg.title;
            document.getElementById('cardSub').textContent = cfg.sub;

            // Update button
            const btn = document.getElementById('submitBtn');
            btn.className = `btn-login ${cfg.btnClass}`;
            document.getElementById('submitIcon').className = `fas ${cfg.btnIcon}`;
            document.getElementById('submitText').textContent = cfg.btnText;

            // Update hidden input
            document.getElementById('portalRoleInput').value = role;

            // Update input focus class
            const inputs = document.querySelectorAll('.field-input');
            inputs.forEach(inp => {
                inp.classList.remove('focused-manager','focused-referee','focused-spectator');
                if (role !== 'admin') inp.classList.add(`focused-${role}`);
            });
        }

        function togglePassword() {
            const inp = document.getElementById('passwordInput');
            const icon = document.getElementById('togglePwIcon');
            if (inp.type === 'password') {
                inp.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                inp.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        // Pre-select role from URL param if any
        const params = new URLSearchParams(window.location.search);
        const paramRole = params.get('role');
        if (paramRole && roles[paramRole]) switchRole(paramRole);
    </script>
</body>
</html>
