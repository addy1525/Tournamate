<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Account Status Update - Tournamate</title>
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
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            font-weight: 800;
            font-size: 14px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 25px;
        }
        .status-badge.approved {
            background-color: rgba(16, 185, 129, 0.2);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.4);
        }
        .status-badge.rejected {
            background-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.4);
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
                <p>Hello, <strong>{{ $manager->name }}</strong>,</p>
                <p>There is an update on your Team Manager account status on the Tournamate Rugby Management Platform.</p>

                <div style="text-align: center;">
                    @if($status === 'approved')
                        <span class="status-badge approved">APPROVED / ACTIVE</span>
                    @else
                        <span class="status-badge rejected">INACTIVE / DEACTIVATED</span>
                    @endif
                </div>

                @if($status === 'approved')
                    <p>Congratulations! Your account has been reviewed and approved by the system administrator. You now have full access to your Manager Dashboard where you can:</p>
                    <ul style="color: #cbd5e1; font-size: 15px; line-height: 1.6; margin-bottom: 25px; padding-left: 20px;">
                        <li>Register teams for upcoming rugby tournaments</li>
                        <li>Manage player rosters, jersey numbers, and team details</li>
                        <li>Upload payment proofs and track payment status</li>
                        <li>View upcoming schedules and match brackets for your teams</li>
                    </ul>
                    <div class="btn-container">
                        <a href="{{ url('/login/manager') }}" class="btn">Access Manager Dashboard</a>
                    </div>
                @else
                    <p>We regret to inform you that your Team Manager account has been deactivated or rejected by the system administrator. You will not be able to log in or manage teams at this time.</p>
                    <p>If you believe this is a mistake or if you need clarification, please contact our support team at <a href="mailto:support@tournamate.com" style="color: #00a86b; text-decoration: none;">support@tournamate.com</a>.</p>
                @endif

                <p>Best regards,<br>The Tournamate Team</p>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} Tournamate. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
