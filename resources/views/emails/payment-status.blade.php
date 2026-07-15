<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Status Update - Tournamate</title>
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
        .details-box {
            background-color: #0f172a;
            border: 1px solid #334155;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .details-row {
            margin-bottom: 12px;
            font-size: 15px;
            display: flex;
            justify-content: space-between;
        }
        .details-row:last-child {
            margin-bottom: 0;
        }
        .label {
            font-weight: bold;
            color: #94a3b8;
        }
        .value {
            color: #f8fafc;
            font-weight: 600;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            font-weight: 700;
            font-size: 12px;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .status-badge.paid {
            background-color: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }
        .status-badge.partial {
            background-color: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }
        .status-badge.unpaid {
            background-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
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
                <p>Hello,</p>
                <p>The payment status for your registered team **{{ $team->name }}** has been updated by the administrator.</p>

                <div class="details-box">
                    <div class="details-row">
                        <span class="label">Team Name:</span>
                        <span class="value">{{ $team->name }}</span>
                    </div>
                    <div class="details-row">
                        <span class="label">Payment Status:</span>
                        <span class="value">
                            @if($team->payment_status === 'paid')
                                <span class="status-badge paid">PAID</span>
                            @elseif($team->payment_status === 'partial')
                                <span class="status-badge partial">PARTIAL</span>
                            @else
                                <span class="status-badge unpaid">UNPAID</span>
                            @endif
                        </span>
                    </div>
                    <div class="details-row">
                        <span class="label">Amount Paid:</span>
                        <span class="value">RM {{ number_format($team->amount_paid ?? 0, 2) }}</span>
                    </div>
                </div>

                @if($team->payment_status === 'paid')
                    <p>Thank you! Your payment has been fully confirmed and approved. Your team's spot is secured in the upcoming tournament.</p>
                @elseif($team->payment_status === 'partial')
                    <p>A partial payment has been recorded. Please ensure the remaining balance is paid as soon as possible to secure your team's confirmation.</p>
                @else
                    <p>Your team's payment status is currently unpaid. If you have already made the transfer, please ensure you upload the correct payment receipt through the dashboard.</p>
                @endif

                <div class="btn-container">
                    <a href="{{ url('/login/manager') }}" class="btn">View My Dashboard</a>
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
