<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password — Tournamate</title>
    <link rel="stylesheet" href="{{ asset('css/tournamate-styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            background: #070d1a;
            display: flex; align-items: center; justify-content: center;
            padding: 2rem 1.25rem;
            position: relative; overflow: hidden;
        }
        .auth-bg { position: fixed; inset: 0; z-index: 0; overflow: hidden; pointer-events: none; }
        .auth-bg::before {
            content: ''; position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(0,168,107,0.12) 0%, transparent 70%);
            top: -200px; left: -200px;
            animation: drift 16s ease-in-out infinite alternate;
        }
        @keyframes drift { to { transform: translate(50px,30px) scale(1.1); } }
        .auth-grid {
            position: fixed; inset: 0; z-index: 0;
            background-image: linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 48px 48px;
        }
        .wrapper { position: relative; z-index: 10; width: 100%; max-width: 420px; }
        .header { text-align: center; margin-bottom: 2rem; }
        .logo-wrap {
            width: 68px; height: 68px;
            background: linear-gradient(135deg, #00A86B, #00d4ff);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.9rem; color: #fff;
            box-shadow: 0 0 35px rgba(0,168,107,0.4);
            margin: 0 auto 1.25rem;
        }
        .title { font-size: 1.85rem; font-weight: 900; color: #fff; letter-spacing: -0.6px; margin-bottom: 0.3rem; }
        .sub { font-size: 0.875rem; color: rgba(255,255,255,0.38); }
        .card-box {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.09);
            border-radius: 22px;
            backdrop-filter: blur(20px);
            padding: 2.25rem;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5);
        }
        .info-box {
            display: flex; align-items: flex-start; gap: 0.65rem;
            padding: 0.9rem 1rem;
            background: rgba(0,168,107,0.07);
            border: 1px solid rgba(0,168,107,0.2);
            border-radius: 10px;
            font-size: 0.83rem; color: rgba(255,255,255,0.55);
            line-height: 1.55; margin-bottom: 1.5rem;
        }
        .info-box i { color: #00A86B; margin-top: 2px; flex-shrink: 0; }
        .field-label { display: block; font-size: 0.78rem; font-weight: 600; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem; }
        .field-wrap { position: relative; margin-bottom: 1.5rem; }
        .field-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,0.28); font-size: 0.88rem; pointer-events: none; }
        .field-input { width: 100%; padding: 0.8rem 1rem 0.8rem 2.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 11px; color: #fff; font-family: 'Outfit', sans-serif; font-size: 0.92rem; outline: none; transition: all 0.25s; }
        .field-input::placeholder { color: rgba(255,255,255,0.22); }
        .field-input:focus { border-color: rgba(0,168,107,0.55); background: rgba(0,168,107,0.05); box-shadow: 0 0 0 3px rgba(0,168,107,0.1); }
        .btn-send { width: 100%; padding: 0.9rem; border: none; border-radius: 12px; background: linear-gradient(135deg, #00A86B, #00d4ff); color: #fff; font-family: 'Outfit', sans-serif; font-size: 0.97rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.25s; box-shadow: 0 8px 25px rgba(0,168,107,0.3); }
        .btn-send:hover { transform: translateY(-2px); filter: brightness(1.1); }
        .auth-alert { display: flex; align-items: flex-start; gap: 0.65rem; padding: 0.85rem 1rem; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); border-radius: 10px; color: #fca5a5; font-size: 0.875rem; margin-bottom: 1.25rem; }
        .success-alert { display: flex; align-items: flex-start; gap: 0.65rem; padding: 0.85rem 1rem; background: rgba(0,168,107,0.1); border: 1px solid rgba(0,168,107,0.3); border-radius: 10px; color: #6ee7b7; font-size: 0.875rem; margin-bottom: 1.25rem; }
        .footer-link { text-align: center; margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid rgba(255,255,255,0.07); font-size: 0.85rem; color: rgba(255,255,255,0.3); }
        .footer-link a { color: #00A86B; font-weight: 700; text-decoration: none; }
        .footer-link a:hover { color: #4ade80; }
    </style>
</head>
<body>
    <div class="auth-bg"></div>
    <div class="auth-grid"></div>
    <div class="wrapper">
        <div class="header">
            <div class="logo-wrap"><i class="fas fa-key"></i></div>
            <h1 class="title">Forgot Your Password?</h1>
            <p class="sub">Enter your email to reset your password</p>
        </div>
        <div class="card-box">
            @if (session('status'))
                <div class="success-alert">
                    <i class="fas fa-check-circle" style="margin-top:2px;flex-shrink:0;"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="auth-alert">
                    <i class="fas fa-exclamation-circle" style="margin-top:2px;flex-shrink:0;"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <span>Enter the email address associated with your account. A password reset link will be sent to you.</span>
            </div>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <label class="field-label" for="email">Email Address</label>
                <div class="field-wrap">
                    <i class="fas fa-envelope field-icon"></i>
                    <input type="email" id="email" name="email" class="field-input"
                        value="{{ old('email') }}"
                        placeholder="your.email@example.com"
                        required autofocus autocomplete="email">
                </div>
                <button type="submit" class="btn-send">
                    <i class="fas fa-paper-plane"></i> Send Reset Link
                </button>
            </form>
            <div class="footer-link">
                Remember your password? <a href="{{ route('login') }}">Sign in</a>
            </div>
        </div>
    </div>
</body>
</html>
