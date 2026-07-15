<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Tournament – Tournamate</title>
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
            background: linear-gradient(135deg, #1d4ed8 0%, #7c3aed 100%);
            padding: 36px 32px;
            text-align: center;
            position: relative;
        }
        .header-icon {
            font-size: 48px;
            margin-bottom: 14px;
            display: block;
        }
        .header h1 {
            font-size: 24px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 6px;
            letter-spacing: -0.4px;
        }
        .header p {
            font-size: 14px;
            color: rgba(255,255,255,0.75);
        }
        .new-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 4px 14px;
            border-radius: 20px;
            margin-bottom: 12px;
        }

        /* ── Body ────────────────────────────────────── */
        .body { padding: 36px 32px; }
        .greeting {
            font-size: 16px;
            color: #cbd5e1;
            margin-bottom: 24px;
            line-height: 1.7;
        }

        /* ── Tournament Card ─────────────────────────── */
        .tournament-card {
            background: #0f172a;
            border: 1px solid #1e293b;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 28px;
        }
        .tournament-name {
            padding: 20px 24px 16px;
            font-size: 20px;
            font-weight: 800;
            color: #f1f5f9;
            border-bottom: 1px solid #1e293b;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
        }
        .info-cell {
            padding: 14px 20px;
            border-bottom: 1px solid #1e293b;
            border-right: 1px solid #1e293b;
        }
        .info-cell:nth-child(even) { border-right: none; }
        .info-cell:nth-last-child(-n+2) { border-bottom: none; }
        .cell-label {
            font-size: 10px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 5px;
        }
        .cell-value {
            font-size: 14px;
            font-weight: 600;
            color: #f1f5f9;
        }
        .cell-value.highlight { color: #34d399; }
        .cell-value.price {
            font-size: 18px;
            font-weight: 800;
            color: #34d399;
        }

        /* ── Category Pills ──────────────────────────── */
        .categories-wrap {
            padding: 14px 20px;
            border-top: 1px solid #1e293b;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }
        .cat-label {
            font-size: 11px;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-right: 4px;
        }
        .cat-pill {
            display: inline-block;
            padding: 4px 12px;
            background: rgba(99,102,241,0.12);
            color: #a5b4fc;
            border: 1px solid rgba(99,102,241,0.25);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        /* ── Deadline Banner ─────────────────────────── */
        .deadline-banner {
            background: rgba(251,146,60,0.08);
            border: 1px solid rgba(251,146,60,0.2);
            border-radius: 10px;
            padding: 12px 18px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #fb923c;
            line-height: 1.6;
        }
        .deadline-banner strong { color: #fbbf24; }

        /* ── CTA ─────────────────────────────────────── */
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

        /* ── Responsive ──────────────────────────────── */
        @media (max-width: 480px) {
            .info-grid { grid-template-columns: 1fr; }
            .info-cell:nth-child(even) { border-right: 1px solid #1e293b; }
            .info-cell:last-child { border-bottom: none; }
            .body { padding: 24px 20px; }
            .header { padding: 28px 20px; }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="card">

        <!-- Header -->
        <div class="header">
            <span class="new-badge">🏉 New Tournament</span>
            <span class="header-icon">🏆</span>
            <h1>{{ $tournament->name }}</h1>
            <p>A new tournament is now open for registration on Tournamate!</p>
        </div>

        <!-- Body -->
        <div class="body">
            <p class="greeting">
                Hello Team Manager,<br><br>
                A <strong style="color:#f1f5f9;">new rugby tournament</strong> has just been published on Tournamate.
                Registration slots are limited — secure your team's spot before they fill up!
            </p>

            <!-- Tournament Card -->
            <div class="tournament-card">
                <div class="tournament-name">{{ $tournament->name }}</div>

                <div class="info-grid">
                    <div class="info-cell">
                        <div class="cell-label">📅 Tournament Date</div>
                        <div class="cell-value">
                            @if($tournament->start_date)
                                {{ $tournament->start_date->format('d F Y') }}
                            @else
                                TBD
                            @endif
                        </div>
                    </div>
                    <div class="info-cell">
                        <div class="cell-label">📍 Venue</div>
                        <div class="cell-value">
                            {{ $tournament->venue_name ?? $tournament->venue ?? 'TBD' }}
                        </div>
                    </div>
                    <div class="info-cell">
                        <div class="cell-label">💰 Registration Fee</div>
                        <div class="cell-value price">RM {{ number_format($tournament->fee ?? 0, 2) }}</div>
                    </div>
                    @if($tournament->max_teams)
                        <div class="info-cell">
                            <div class="cell-label">👥 Team Slots</div>
                            <div class="cell-value highlight">{{ $tournament->max_teams }} Teams Max</div>
                        </div>
                    @endif
                    @if($tournament->end_date && $tournament->start_date && $tournament->end_date->ne($tournament->start_date))
                        <div class="info-cell">
                            <div class="cell-label">🏁 End Date</div>
                            <div class="cell-value">{{ $tournament->end_date->format('d F Y') }}</div>
                        </div>
                    @endif
                    @if($tournament->registration_deadline)
                        <div class="info-cell">
                            <div class="cell-label">⏰ Registration Deadline</div>
                            <div class="cell-value" style="color: #fb923c;">
                                {{ $tournament->registration_deadline->format('d M Y, H:i') }}
                            </div>
                        </div>
                    @endif
                </div>

                @if($tournament->categories)
                    <div class="categories-wrap">
                        <span class="cat-label">Categories:</span>
                        @foreach(array_map('trim', explode(',', $tournament->categories)) as $cat)
                            <span class="cat-pill">{{ $cat }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            @if($tournament->registration_deadline)
                <div class="deadline-banner">
                    ⚠️ <strong>Registration closes on {{ $tournament->registration_deadline->format('d F Y \a\t H:i') }}.</strong>
                    Don't miss out — register your team as soon as possible!
                </div>
            @endif

            @if($tournament->description)
                <p style="font-size: 14px; color: #94a3b8; line-height: 1.7; margin-bottom: 24px;">
                    {{ $tournament->description }}
                </p>
            @endif

            <!-- CTA -->
            <div class="cta-wrap">
                <a href="{{ route('manager.browse-tournaments') }}" class="cta-btn">
                    Register My Team Now →
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
            <p>You received this email because you are a registered Team Manager on Tournamate.</p>
        </div>

    </div>
</div>
</body>
</html>
