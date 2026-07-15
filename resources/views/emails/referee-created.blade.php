<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to Tournamate</title>
    <style>
        body {
            font-family: 'Inter', Helvetica, Arial, sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #0f172a;
            padding: 40px 0;
        }
        .content {
            max-width: 600px;
            margin: 0 auto;
            background-color: #1e293b;
            border-radius: 12px;
            border: 1px solid #334155;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
        }
        .header {
            background: linear-gradient(135deg, #00a86b 0%, #007d4f 100%);
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }
        .body {
            padding: 40px 30px;
        }
        .body p {
            font-size: 16px;
            line-height: 1.6;
            color: #cbd5e1;
            margin: 0 0 20px 0;
        }
        .credentials-box {
            background-color: #0f172a;
            border: 1px solid #475569;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .credentials-row {
            margin-bottom: 10px;
            font-size: 15px;
        }
        .credentials-row:last-child {
            margin-bottom: 0;
        }
        .label {
            font-weight: bold;
            color: #00a86b;
            display: inline-block;
            width: 100px;
        }
        .value {
            color: #f8fafc;
            font-family: monospace;
            background-color: #1e293b;
            padding: 2px 6px;
            border-radius: 4px;
        }
        .btn-container {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            background: linear-gradient(135deg, #00a86b 0%, #0084ff 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 30px;
            font-weight: bold;
            font-size: 16px;
            border-radius: 8px;
            display: inline-block;
            box-shadow: 0 4px 6px -1px rgba(0, 168, 107, 0.2);
        }
        .footer {
            background-color: #0f172a;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #334155;
        }
        .footer p {
            margin: 0;
            font-size: 13px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="content">
            <div class="header">
                <h1>Tournamate</h1>
            </div>
            <div class="body">
                <p>Hello, <strong>{{ $name }}</strong>,</p>
                <p>An administrator has created a **Match Official (Referee)** account for you on the Tournamate Rugby Management Platform.</p>
                <p>You can now log in to the system and access your Referee Console to manage match assignments, log scores, and monitor safety logs.</p>
                
                <div class="credentials-box">
                    <div class="credentials-row">
                        <span class="label">Portal URL:</span>
                        <span class="value">{{ url('/login/referee') }}</span>
                    </div>
                    <div class="credentials-row">
                        <span class="label">Email:</span>
                        <span class="value">{{ $email }}</span>
                    </div>
                    <div class="credentials-row">
                        <span class="label">Password:</span>
                        <span class="value">{{ $password }}</span>
                    </div>
                </div>

                <p style="font-size: 14px; color: #94a3b8; font-style: italic;">Note: For security reasons, please change your password immediately after logging in for the first time.</p>

                <div class="btn-container">
                    <a href="{{ url('/login/referee') }}" class="btn">Log In to Console</a>
                </div>

                <p>Best regards,<br>The Tournamate Team</p>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} Tournamate. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
