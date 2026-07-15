<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pending Approval - Tournamate</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1a1f2e 0%, #2d3748 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .pending-container {
            background: #2d3748;
            border-radius: 12px;
            padding: 48px 40px;
            width: 100%;
            max-width: 580px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .icon-container {
            margin-bottom: 24px;
        }

        .pending-icon {
            font-size: 64px;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .title {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 16px;
        }

        .message {
            color: #cbd5e1;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .info-box {
            background: #1e293b;
            border-left: 4px solid #14b8a6;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 32px;
            text-align: left;
        }

        .info-box p {
            color: #94a3b8;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .info-box p:last-child {
            margin-bottom: 0;
        }

        .info-box strong {
            color: #e2e8f0;
        }

        .logout-button {
            padding: 14px 32px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .logout-button:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
        }

        .logo-title {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 32px;
        }

        .logo-title .highlight {
            color: #14b8a6;
        }
    </style>
</head>

<body>
    <div class="pending-container">
        <h1 class="logo-title">TOURNA<span class="highlight">MATE</span></h1>

        <div class="icon-container">
            <div class="pending-icon">⏳</div>
        </div>

        <h2 class="title">Account Pending Approval</h2>

        <p class="message">
            Thank you for registering as a Team Manager! Your account is currently pending approval from our
            administrators.
        </p>

        <div class="info-box">
            <p><strong>What happens next?</strong></p>
            <p>✓ Our admin team will review your registration</p>
            <p>✓ You'll receive an email once your account is approved</p>
            <p>✓ After approval, you can access all manager features</p>
        </div>

        <p class="message" style="font-size: 14px; color: #94a3b8;">
            This usually takes 24-48 hours. If you have any questions, please contact support.
        </p>

        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </div>
</body>

</html>