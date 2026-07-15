<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Account — Tournamate</title>
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
            overflow-x: hidden;
            position: relative;
        }

        /* ── Background ── */
        .auth-bg {
            position: fixed; inset: 0; z-index: 0; overflow: hidden;
            pointer-events: none;
        }
        .auth-bg::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(0,168,107,0.13) 0%, transparent 70%);
            top: -180px; right: -180px;
            animation: floatOrb 16s ease-in-out infinite alternate;
        }
        .auth-bg::after {
            content: '';
            position: absolute;
            width: 450px; height: 450px;
            background: radial-gradient(circle, rgba(124,58,237,0.1) 0%, transparent 70%);
            bottom: -100px; left: -100px;
            animation: floatOrb 20s ease-in-out infinite alternate-reverse;
        }
        @keyframes floatOrb {
            from { transform: translate(0,0) scale(1); }
            to   { transform: translate(50px,30px) scale(1.12); }
        }
        .auth-grid {
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 48px 48px;
            z-index: 0;
        }

        /* ── Page Wrapper ── */
        .auth-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 560px;
            padding: 2.5rem 1.25rem;
        }

        /* ── Header ── */
        .reg-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .reg-logo-wrap {
            width: 68px; height: 68px;
            background: linear-gradient(135deg, #00A86B, #00d4ff);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.9rem; color: #fff;
            box-shadow: 0 0 35px rgba(0,168,107,0.4);
            margin: 0 auto 1.25rem;
            transition: all 0.4s ease;
        }
        .reg-title {
            font-size: 2rem;
            font-weight: 900;
            color: #fff;
            letter-spacing: -0.8px;
            margin-bottom: 0.3rem;
            transition: all 0.3s ease;
        }
        .reg-sub {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.4);
            transition: all 0.3s ease;
        }

        /* ── Card ── */
        .reg-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.09);
            border-radius: 24px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 2.25rem;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5);
        }

        /* ── Role Chooser Cards ── */
        .role-chooser {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1.75rem;
        }
        .role-choice {
            position: relative;
            cursor: pointer;
        }
        .role-choice input[type="radio"] {
            position: absolute; opacity: 0; pointer-events: none;
        }
        .role-choice-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            padding: 1.1rem 0.75rem;
            background: rgba(255,255,255,0.04);
            border: 2px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            transition: all 0.25s ease;
            cursor: pointer;
        }
        .role-choice-label:hover {
            background: rgba(255,255,255,0.07);
            border-color: rgba(255,255,255,0.15);
        }
        .role-choice-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }
        .role-choice-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: rgba(255,255,255,0.6);
            transition: color 0.25s;
        }
        .role-choice-desc {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.3);
            text-align: center;
            line-height: 1.4;
            transition: color 0.25s;
        }
        /* Selected state per role */
        .role-choice input:checked + .role-choice-label.label-manager {
            border-color: rgba(0,212,255,0.5);
            background: rgba(0,212,255,0.07);
        }
        .role-choice input:checked + .role-choice-label.label-manager .role-choice-icon {
            background: rgba(0,212,255,0.15);
            color: #38bdf8;
            box-shadow: 0 0 18px rgba(0,212,255,0.2);
        }
        .role-choice input:checked + .role-choice-label.label-manager .role-choice-name { color: #38bdf8; }
        .role-choice input:checked + .role-choice-label.label-manager .role-choice-desc { color: rgba(56,189,248,0.6); }

        .role-choice input:checked + .role-choice-label.label-spectator {
            border-color: rgba(167,139,250,0.5);
            background: rgba(167,139,250,0.07);
        }
        .role-choice input:checked + .role-choice-label.label-spectator .role-choice-icon {
            background: rgba(167,139,250,0.15);
            color: #a78bfa;
            box-shadow: 0 0 18px rgba(167,139,250,0.2);
        }
        .role-choice input:checked + .role-choice-label.label-spectator .role-choice-name { color: #a78bfa; }
        .role-choice input:checked + .role-choice-label.label-spectator .role-choice-desc { color: rgba(167,139,250,0.6); }

        /* ── Section Divider ── */
        .section-divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }
        .section-divider-line {
            flex: 1; height: 1px;
            background: rgba(255,255,255,0.08);
        }
        .section-divider-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: rgba(255,255,255,0.3);
            white-space: nowrap;
        }

        /* ── Field Group ── */
        .field-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }
        .field-group { margin-bottom: 1rem; }
        .field-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.4rem;
        }
        .field-wrap { position: relative; }
        .field-icon {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.28);
            font-size: 0.88rem;
            pointer-events: none;
        }
        .field-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 11px;
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            transition: all 0.25s ease;
            outline: none;
        }
        .field-input::placeholder { color: rgba(255,255,255,0.22); }
        .field-input:focus {
            border-color: rgba(0,168,107,0.55);
            background: rgba(0,168,107,0.05);
            box-shadow: 0 0 0 3px rgba(0,168,107,0.1);
        }
        .field-input.role-manager:focus { border-color: rgba(0,212,255,0.55); background: rgba(0,212,255,0.05); box-shadow: 0 0 0 3px rgba(0,212,255,0.1); }
        .field-input.role-spectator:focus { border-color: rgba(167,139,250,0.55); background: rgba(167,139,250,0.05); box-shadow: 0 0 0 3px rgba(167,139,250,0.1); }

        .toggle-pw {
            position: absolute;
            right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: rgba(255,255,255,0.28);
            cursor: pointer; font-size: 0.88rem;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: rgba(255,255,255,0.65); }

        /* ── Manager Extra Fields ── */
        .manager-fields {
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            transition: max-height 0.45s ease, opacity 0.35s ease;
        }
        .manager-fields.show {
            max-height: 600px;
            opacity: 1;
        }

        /* ── Invitation Badge ── */
        .inv-hint {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 0.85rem;
            background: rgba(251,191,36,0.08);
            border: 1px solid rgba(251,191,36,0.2);
            border-radius: 9px;
            font-size: 0.78rem;
            color: rgba(251,191,36,0.8);
            margin-bottom: 0.85rem;
        }

        /* ── Password Strength ── */
        .pw-strength {
            margin-top: 0.4rem;
            display: flex;
            gap: 4px;
        }
        .pw-bar {
            flex: 1; height: 3px;
            border-radius: 2px;
            background: rgba(255,255,255,0.1);
            transition: background 0.3s;
        }

        /* ── Submit Buttons ── */
        .btn-row {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }
        .btn-cancel {
            padding: 0.85rem;
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 12px;
            background: rgba(255,255,255,0.05);
            color: rgba(255,255,255,0.5);
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-cancel:hover { background: rgba(255,255,255,0.09); color: #fff; }
        .btn-register {
            padding: 0.85rem;
            border: none;
            border-radius: 12px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.25s ease;
            background: linear-gradient(135deg, #00A86B, #00d4ff);
            color: #fff;
            box-shadow: 0 8px 25px rgba(0,168,107,0.3);
        }
        .btn-register:hover { transform: translateY(-2px); filter: brightness(1.1); }
        .btn-register.manager { background: linear-gradient(135deg, #0ea5e9, #38bdf8); box-shadow: 0 8px 25px rgba(14,165,233,0.3); }
        .btn-register.spectator { background: linear-gradient(135deg, #7c3aed, #a78bfa); box-shadow: 0 8px 25px rgba(124,58,237,0.3); }

        /* ── Error ── */
        .auth-alert {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.85rem 1rem;
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: 10px;
            color: #fca5a5;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

        /* ── Footer ── */
        .reg-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.07);
            font-size: 0.85rem;
            color: rgba(255,255,255,0.35);
        }
        .reg-footer a {
            color: #00A86B;
            font-weight: 700;
            text-decoration: none;
        }
        .reg-footer a:hover { color: #4ade80; }
    </style>
</head>

<body>
    <div class="auth-bg"></div>
    <div class="auth-grid"></div>

    <div class="auth-wrapper">
        <!-- Header -->
        <div class="reg-header">
            <div class="reg-logo-wrap" id="regLogoWrap">
                <i class="fas fa-user-plus" id="regLogoIcon"></i>
            </div>
            <h1 class="reg-title" id="regTitle">Create New Account</h1>
            <p class="reg-sub" id="regSub">Join the Tournamate platform today</p>
        </div>

        <!-- Card -->
        <div class="reg-card">

            @if($errors->any())
                <div class="auth-alert">
                    <i class="fas fa-exclamation-circle" style="margin-top:2px;flex-shrink:0;"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Role Chooser -->
                <div style="margin-bottom:0.6rem;">
                    <label class="field-label">Register as</label>
                </div>
                <div class="role-chooser">
                    <!-- Manager -->
                    <div class="role-choice">
                        <input type="radio" name="role" id="roleManager" value="manager"
                            {{ old('role', request('role')) == 'manager' ? 'checked' : '' }}
                            onchange="onRoleChange('manager')">
                        <label for="roleManager" class="role-choice-label label-manager">
                            <div class="role-choice-icon" style="background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.35);">
                                <i class="fas fa-users"></i>
                            </div>
                            <span class="role-choice-name">Team Manager</span>
                            <span class="role-choice-desc">Manage teams & enter tournaments</span>
                        </label>
                    </div>
                    <!-- Spectator -->
                    <div class="role-choice">
                        <input type="radio" name="role" id="roleSpectator" value="spectator"
                            {{ old('role', request('role')) == 'spectator' ? 'checked' : '' }}
                            onchange="onRoleChange('spectator')">
                        <label for="roleSpectator" class="role-choice-label label-spectator">
                            <div class="role-choice-icon" style="background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.35);">
                                <i class="fas fa-eye"></i>
                            </div>
                            <span class="role-choice-name">Spectator</span>
                            <span class="role-choice-desc">Follow & watch live matches</span>
                        </label>
                    </div>
                </div>

                <!-- Basic Info -->
                <div class="section-divider">
                    <div class="section-divider-line"></div>
                    <span class="section-divider-label">Account Details</span>
                    <div class="section-divider-line"></div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="name">Full Name</label>
                    <div class="field-wrap">
                        <i class="fas fa-user field-icon"></i>
                        <input type="text" id="name" name="name"
                            class="field-input @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="Enter your full name"
                            required autocomplete="name" autofocus>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="email">Email Address</label>
                    <div class="field-wrap">
                        <i class="fas fa-envelope field-icon"></i>
                        <input type="email" id="email" name="email"
                            class="field-input @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="your.email@example.com"
                            required autocomplete="email">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="phone_number">Phone Number <span style="color:rgba(255,255,255,0.25);font-size:0.7rem;">(Optional)</span></label>
                    <div class="field-wrap">
                        <i class="fas fa-phone field-icon"></i>
                        <input type="text" id="phone_number" name="phone_number"
                            class="field-input @error('phone_number') is-invalid @enderror"
                            value="{{ old('phone_number') }}"
                            placeholder="+60 12-345 6789"
                            autocomplete="tel">
                    </div>
                </div>

                <div class="field-grid-2">
                    <div class="field-group">
                        <label class="field-label" for="password">Password</label>
                        <div class="field-wrap">
                            <i class="fas fa-lock field-icon"></i>
                            <input type="password" id="password" name="password"
                                class="field-input @error('password') is-invalid @enderror"
                                placeholder="Min. 8 characters"
                                required autocomplete="new-password"
                                oninput="checkStrength(this.value)">
                            <button type="button" class="toggle-pw" onclick="togglePw('password','pwEye')">
                                <i class="fas fa-eye" id="pwEye"></i>
                            </button>
                        </div>
                        <div class="pw-strength" id="pwStrength">
                            <div class="pw-bar" id="pwBar1"></div>
                            <div class="pw-bar" id="pwBar2"></div>
                            <div class="pw-bar" id="pwBar3"></div>
                            <div class="pw-bar" id="pwBar4"></div>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="password-confirm">Confirm Password</label>
                        <div class="field-wrap">
                            <i class="fas fa-lock field-icon"></i>
                            <input type="password" id="password-confirm" name="password_confirmation"
                                class="field-input"
                                placeholder="Re-enter your password"
                                required autocomplete="new-password">
                            <button type="button" class="toggle-pw" onclick="togglePw('password-confirm','pwEye2')">
                                <i class="fas fa-eye" id="pwEye2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Manager Extra Fields -->
                <div class="manager-fields" id="managerFields">
                    <div class="section-divider" style="margin-top:0.5rem;">
                        <div class="section-divider-line"></div>
                        <span class="section-divider-label">Team Details</span>
                        <div class="section-divider-line"></div>
                    </div>

                    <div class="inv-hint">
                        <i class="fas fa-key"></i>
                        An invitation code is required — obtained from the tournament organizer.
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="invitation_code">Manager Invitation Code</label>
                        <div class="field-wrap">
                            <i class="fas fa-key field-icon"></i>
                            <input type="text" id="invitation_code" name="invitation_code"
                                class="field-input role-manager @error('invitation_code') is-invalid @enderror"
                                value="{{ old('invitation_code') }}"
                                placeholder="Enter your invitation code">
                        </div>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="team_name">Team Name</label>
                        <div class="field-wrap">
                            <i class="fas fa-shield-alt field-icon"></i>
                            <input type="text" id="team_name" name="team_name"
                                class="field-input role-manager @error('team_name') is-invalid @enderror"
                                value="{{ old('team_name') }}"
                                placeholder="Enter your team name">
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="btn-row">
                    <button type="button" class="btn-cancel" onclick="handleCancel()">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button type="submit" class="btn-register" id="regBtn">
                        <i class="fas fa-check-circle" id="regBtnIcon"></i>
                        <span id="regBtnText">Create Account</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="reg-footer">
            Already have an account? <a href="{{ route('login') }}">Sign in here</a>
        </div>
    </div>

    <script>
        const logoWrap  = document.getElementById('regLogoWrap');
        const logoIcon  = document.getElementById('regLogoIcon');
        const titleEl   = document.getElementById('regTitle');
        const subEl     = document.getElementById('regSub');
        const regBtn    = document.getElementById('regBtn');
        const regBtnIcon = document.getElementById('regBtnIcon');
        const regBtnText = document.getElementById('regBtnText');
        const mgFields  = document.getElementById('managerFields');
        const invCode   = document.getElementById('invitation_code');
        const teamName  = document.getElementById('team_name');

        function onRoleChange(role) {
            if (role === 'manager') {
                logoWrap.style.background = 'linear-gradient(135deg, #0ea5e9, #38bdf8)';
                logoWrap.style.boxShadow = '0 0 35px rgba(14,165,233,0.4)';
                logoIcon.className = 'fas fa-users';
                titleEl.textContent = 'Register as Manager';
                subEl.textContent = 'Manage your squad & enter rugby tournaments';
                regBtn.className = 'btn-register manager';
                regBtnIcon.className = 'fas fa-users';
                regBtnText.textContent = 'Create Manager Account';
                mgFields.classList.add('show');
                if (invCode) invCode.setAttribute('required','');
                if (teamName) teamName.setAttribute('required','');
            } else if (role === 'spectator') {
                logoWrap.style.background = 'linear-gradient(135deg, #7c3aed, #a78bfa)';
                logoWrap.style.boxShadow = '0 0 35px rgba(124,58,237,0.4)';
                logoIcon.className = 'fas fa-eye';
                titleEl.textContent = 'Register as Spectator';
                subEl.textContent = 'Watch live matches for free';
                regBtn.className = 'btn-register spectator';
                regBtnIcon.className = 'fas fa-eye';
                regBtnText.textContent = 'Create Spectator Account';
                mgFields.classList.remove('show');
                if (invCode) invCode.removeAttribute('required');
                if (teamName) teamName.removeAttribute('required');
            } else {
                logoWrap.style.background = 'linear-gradient(135deg, #00A86B, #00d4ff)';
                logoWrap.style.boxShadow = '0 0 35px rgba(0,168,107,0.4)';
                logoIcon.className = 'fas fa-user-plus';
                titleEl.textContent = 'Create New Account';
                subEl.textContent = 'Join the Tournamate platform today';
                regBtn.className = 'btn-register';
                regBtnIcon.className = 'fas fa-check-circle';
                regBtnText.textContent = 'Create Account';
                mgFields.classList.remove('show');
                if (invCode) invCode.removeAttribute('required');
                if (teamName) teamName.removeAttribute('required');
            }
        }

        function togglePw(inputId, iconId) {
            const inp = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (inp.type === 'password') {
                inp.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                inp.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        function checkStrength(val) {
            const bars = [
                document.getElementById('pwBar1'),
                document.getElementById('pwBar2'),
                document.getElementById('pwBar3'),
                document.getElementById('pwBar4'),
            ];
            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;
            const colors = ['#ef4444','#f59e0b','#00A86B','#00d4ff'];
            bars.forEach((b, i) => {
                b.style.background = i < score ? colors[score - 1] : 'rgba(255,255,255,0.1)';
            });
        }

        function handleCancel() {
            const params = new URLSearchParams(window.location.search);
            const role = params.get('role');
            if (role === 'manager') window.location.href = "{{ route('login.manager') }}";
            else if (role === 'spectator') window.location.href = "{{ route('login.spectator') }}";
            else window.location.href = "{{ route('welcome') }}";
        }

        // Run on load for old() redirect
        const oldRole = "{{ old('role', request('role')) }}";
        if (oldRole) onRoleChange(oldRole);
    </script>
</body>
</html>