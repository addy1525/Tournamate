<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmed – Tournamate</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
            background-color: #0a0f1e;
            color: #f1f5f9;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #0a0f1e;
            padding: 40px 16px;
        }
        .card {
            max-width: 600px;
            margin: 0 auto;
            background: #111827;
            border-radius: 16px;
            border: 1px solid #1e293b;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        }

        /* ── Header ─────────────────────────────────── */
        .header {
            background: linear-gradient(135deg, #00a86b 0%, #0084ff 100%);
            padding: 36px 32px;
            text-align: center;
        }
        .checkmark {
            width: 64px;
            height: 64px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 32px;
        }
        .header h1 {
            font-size: 22px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 6px;
            letter-spacing: -0.3px;
        }
        .header p {
            font-size: 14px;
            color: rgba(255,255,255,0.8);
        }

        /* ── Body ────────────────────────────────────── */
        .body {
            padding: 36px 32px;
        }
        .greeting {
            font-size: 16px;
            color: #cbd5e1;
            margin-bottom: 24px;
            line-height: 1.6;
        }

        /* ── Info Box ────────────────────────────────── */
        .info-box {
            background: #0f172a;
            border: 1px solid #1e293b;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 28px;
        }
        .info-box-header {
            background: rgba(0,168,107,0.1);
            border-bottom: 1px solid #1e293b;
            padding: 12px 20px;
            font-size: 11px;
            font-weight: 700;
            color: #34d399;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 20px;
            border-bottom: 1px solid #1e293b;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }
        .info-value {
            font-size: 13px;
            color: #f1f5f9;
            font-weight: 600;
            text-align: right;
            max-width: 60%;
        }

        /* ── Amount Row ──────────────────────────────── */
        .amount-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 20px;
            background: rgba(0,168,107,0.06);
            border-top: 1px solid rgba(0,168,107,0.2);
        }
        .amount-label {
            font-size: 14px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .amount-value {
            font-size: 24px;
            font-weight: 800;
            color: #34d399;
        }

        /* ── Status Badge ────────────────────────────── */
        .status-wrap { text-align: center; margin-bottom: 28px; }
        .status-badge {
            display: inline-block;
            padding: 6px 18px;
            background: rgba(0,168,107,0.15);
            color: #34d399;
            border: 1px solid rgba(0,168,107,0.3);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        /* ── CTA Button ──────────────────────────────── */
        .cta-wrap { text-align: center; margin: 28px 0; }
        .cta-btn {
            display: inline-block;
            padding: 14px 36px;
            background: linear-gradient(135deg, #00a86b, #0084ff);
            color: #ffffff !important;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            border-radius: 10px;
            letter-spacing: 0.2px;
        }

        /* ── Note ────────────────────────────────────── */
        .note {
            background: rgba(59,130,246,0.08);
            border: 1px solid rgba(59,130,246,0.2);
            border-radius: 10px;
            padding: 14px 18px;
            font-size: 13px;
            color: #93c5fd;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .note strong { color: #60a5fa; }

        /* ── Footer ──────────────────────────────────── */
        .footer {
            background: #0a0f1e;
            border-top: 1px solid #1e293b;
            padding: 20px 32px;
            text-align: center;
        }
        .footer p {
            font-size: 12px;
            color: #334155;
            margin-bottom: 4px;
        }
        .footer a { color: #00a86b; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="card">

        <!-- Header -->
        <div class="header">
            <div class="checkmark">✅</div>
            <h1>Payment Confirmed!</h1>
            <p>Your tournament registration has been successfully processed.</p>
        </div>

        <!-- Body -->
        <div class="body">
            <p class="greeting">
                Hello, <strong style="color:#f1f5f9;">{{ $registration->manager->name ?? 'Team Manager' }}</strong>,<br><br>
                Great news! Your payment has been verified and your team is now <strong style="color:#34d399;">officially registered</strong>
                for the following tournament. Please keep this email as your proof of registration.
            </p>

            <!-- Status Badge -->
            <div class="status-wrap">
                <span class="status-badge">✓ CONFIRMED &amp; PAID</span>
            </div>

            <!-- Registration Details -->
            <div class="info-box">
                <div class="info-box-header">🏉 Registration Details</div>
                <div class="info-row">
                    <span class="info-label">Registration ID</span>
                    <span class="info-value">#{{ str_pad($registration->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tournament</span>
                    <span class="info-value">{{ $registration->tournament->name ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Team Name</span>
                    <span class="info-value">{{ $registration->team->name ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Category</span>
                    <span class="info-value">{{ $registration->registered_category ?? 'Open' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Venue</span>
                    <span class="info-value">
                        {{ $registration->tournament->venue_name ?? $registration->tournament->venue ?? 'TBD' }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tournament Date</span>
                    <span class="info-value">
                        @if($registration->tournament->start_date)
                            {{ $registration->tournament->start_date->format('d F Y') }}
                        @else
                            TBD
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Date</span>
                    <span class="info-value">{{ now()->format('d F Y, H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Transaction ID</span>
                    <span class="info-value" style="font-family: monospace; font-size: 11px;">
                        {{ $registration->payment_intent_id ?? '—' }}
                    </span>
                </div>

                <!-- Amount Paid -->
                <div class="amount-row">
                    <span class="amount-label">Total Paid</span>
                    <span class="amount-value">RM {{ number_format($registration->amount_paid ?? 0, 2) }}</span>
                </div>
            </div>

            <!-- Note -->
            <div class="note">
                <strong>📌 Note:</strong> Please ensure your complete squad roster is submitted through the Manager Dashboard
                before the tournament. Your team spot is secured, but squad details must be finalized in advance.
            </div>

            <!-- CTA -->
            <div class="cta-wrap">
                <a href="{{ route('registrations.show', $registration->id) }}" class="cta-btn">
                    View Registration Ticket →
                </a>
            </div>

            <p style="font-size: 14px; color: #64748b; line-height: 1.6;">
                Best regards,<br>
                <strong style="color: #94a3b8;">The Tournamate Team</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© {{ date('Y') }} Tournamate Rugby Management Platform. All rights reserved.</p>
            <p>This is an automated confirmation email. Please do not reply to this message.</p>
        </div>

    </div>
</div>
</body>
</html>
