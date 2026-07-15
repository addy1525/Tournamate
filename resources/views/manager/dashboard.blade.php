@extends('layouts.dashboard')

@section('title', 'Team Manager Dashboard')
@section('page-title', 'Manager Dashboard')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
    * { font-family: 'Inter', sans-serif; box-sizing: border-box; }

    /* ─── Welcome Hero ─────────────────────────────────────────────── */
    .mgr-hero {
        background: linear-gradient(135deg, rgba(0,168,107,0.13) 0%, rgba(0,132,255,0.09) 60%, rgba(124,58,237,0.07) 100%);
        border: 1px solid rgba(0,168,107,0.22);
        border-radius: 20px;
        padding: 1.75rem 2rem;
        margin-bottom: 1.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        position: relative;
        overflow: hidden;
    }
    .mgr-hero::after {
        content: '';
        position: absolute; top: -60px; right: -60px;
        width: 220px; height: 220px;
        background: radial-gradient(circle, rgba(0,168,107,0.1), transparent 70%);
        pointer-events: none;
    }
    .mgr-hero-greeting { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: #34d399; font-weight: 600; margin-bottom: 4px; }
    .mgr-hero-name     { font-size: 1.65rem; font-weight: 800; color: #fff; margin: 0 0 4px; }
    .mgr-hero-sub      { font-size: 0.85rem; color: rgba(255,255,255,0.45); margin: 0; }

    .mgr-hero-actions  { display: flex; gap: 0.65rem; flex-wrap: wrap; }
    .mgr-hero-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 0.6rem 1.15rem; border-radius: 10px;
        font-size: 0.82rem; font-weight: 700;
        text-decoration: none; transition: all 0.25s;
        white-space: nowrap;
    }
    .mgr-hero-btn.primary { background: linear-gradient(135deg,#00a86b,#008a5a); color:#fff; box-shadow:0 4px 14px rgba(0,168,107,0.3); }
    .mgr-hero-btn.primary:hover { transform:translateY(-2px); box-shadow:0 8px 22px rgba(0,168,107,0.4); color:#fff; text-decoration:none; }
    .mgr-hero-btn.secondary { background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.12); color:rgba(255,255,255,0.7); }
    .mgr-hero-btn.secondary:hover { background:rgba(255,255,255,0.12); color:#fff; text-decoration:none; }

    /* ─── Weather Alert ────────────────────────────────────────────── */
    .weather-alert {
        display: flex; align-items: center; gap: 1rem;
        border-radius: 14px; padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .weather-alert-icon { font-size: 1.6rem; flex-shrink: 0; }
    .weather-alert-body { flex: 1; }
    .weather-alert-title { font-size: 0.95rem; font-weight: 700; color: #fff; margin-bottom: 2px; }
    .weather-alert-desc  { font-size: 0.8rem; color: rgba(255,255,255,0.6); }

    /* ─── Stat Cards ───────────────────────────────────────────────── */
    .mgr-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.75rem;
    }
    @media(max-width:900px) { .mgr-stats { grid-template-columns: repeat(2,1fr); } }
    @media(max-width:540px) { .mgr-stats { grid-template-columns: 1fr; } }

    .mgr-stat {
        background: rgba(15,23,42,0.7);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 16px;
        padding: 1.1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .mgr-stat:hover { transform:translateY(-3px); box-shadow:0 10px 30px rgba(0,0,0,0.3); }

    .mgr-stat-icon {
        width: 46px; height: 46px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem; flex-shrink: 0;
    }
    .si-green  { background:rgba(0,168,107,0.18); color:#34d399; }
    .si-blue   { background:rgba(59,130,246,0.18); color:#60a5fa; }
    .si-amber  { background:rgba(245,158,11,0.18); color:#fbbf24; }
    .si-purple { background:rgba(124,58,237,0.18); color:#a78bfa; }
    .si-red    { background:rgba(239,68,68,0.18);  color:#f87171; }

    .mgr-stat-val { font-size: 1.5rem; font-weight: 800; color: #fff; line-height: 1; }
    .mgr-stat-lbl { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; color: rgba(255,255,255,0.4); margin-top: 3px; }

    /* ─── Section Header ───────────────────────────────────────────── */
    .sec-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;
    }
    .sec-title {
        font-size: 1rem; font-weight: 800; color: #fff;
        display: flex; align-items: center; gap: 8px;
    }
    .sec-title i { color: #34d399; font-size: 0.9rem; }
    .sec-link {
        font-size: 0.78rem; font-weight: 600;
        color: #60a5fa; text-decoration: none;
        display: flex; align-items: center; gap: 4px;
        transition: color 0.2s;
    }
    .sec-link:hover { color: #93c5fd; text-decoration: none; }

    /* ─── Tournament Discovery Cards ───────────────────────────────── */
    .td-card {
        background: rgba(15,23,42,0.75);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 18px;
        overflow: hidden;
        margin-bottom: 1rem;
        transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
    }
    .td-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 40px rgba(0,0,0,0.4);
        border-color: rgba(0,168,107,0.25);
    }
    .td-card-accent { height: 3px; background: linear-gradient(90deg,#00a86b,#34d399,#3b82f6); }

    .td-card-inner { padding: 1.25rem 1.5rem; }

    .td-top { display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1rem; }

    .td-icon {
        width: 50px; height: 50px; border-radius: 14px;
        background: linear-gradient(135deg,rgba(0,168,107,0.25),rgba(0,132,255,0.15));
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; color: #34d399; flex-shrink: 0;
    }

    .td-meta { flex: 1; }
    .td-badge-row { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 5px; }
    .td-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 2px 9px; border-radius: 20px;
        font-size: 0.63rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .td-badge-open     { background:rgba(0,168,107,0.15); color:#34d399; border:1px solid rgba(0,168,107,0.3); }
    .td-badge-closing  { background:rgba(251,146,60,0.15); color:#fb923c; border:1px solid rgba(251,146,60,0.3); animation:bpulse 2s infinite; }
    @keyframes bpulse { 0%,100%{opacity:1} 50%{opacity:0.6} }

    .td-name { font-size: 1.15rem; font-weight: 800; color: #fff; margin: 0; }

    .td-slots-badge {
        background: rgba(251,146,60,0.12);
        border: 1px solid rgba(251,146,60,0.25);
        border-radius: 8px; padding: 5px 10px;
        font-size: 0.72rem; color: #fb923c; font-weight: 600;
        text-align: center; white-space: nowrap; flex-shrink: 0;
    }

    /* Info chips */
    .td-chips { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem; }
    .td-chip {
        display: flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 9px; padding: 0.4rem 0.75rem;
        font-size: 0.78rem; color: rgba(255,255,255,0.65);
    }
    .td-chip i { font-size: 0.72rem; color: #34d399; }
    .td-chip strong { color: #fff; font-weight: 600; }

    .td-chip.fee {
        background: rgba(0,168,107,0.08);
        border-color: rgba(0,168,107,0.18);
        color: #34d399; font-weight: 700; font-size: 0.9rem;
    }
    .td-chip.fee i { color: #34d399; }

    /* Category pills */
    .td-cats { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 1rem; }
    .td-cat {
        background: rgba(0,132,255,0.1); color: #60a5fa;
        border: 1px solid rgba(0,132,255,0.2); border-radius: 20px;
        padding: 2px 9px; font-size: 0.7rem; font-weight: 600;
    }

    /* Capacity */
    .td-cap { margin-bottom: 1rem; }
    .td-cap-header { display:flex; justify-content:space-between; margin-bottom:5px; }
    .td-cap-lbl { font-size:0.7rem; color:rgba(255,255,255,0.35); text-transform:uppercase; letter-spacing:0.4px; }
    .td-cap-cnt { font-size:0.75rem; font-weight:700; color:rgba(255,255,255,0.65); }
    .td-cap-track { height:5px; background:rgba(255,255,255,0.07); border-radius:10px; overflow:hidden; }
    .td-cap-fill  { height:100%; border-radius:10px; transition:width 0.8s ease; }
    .cf-ok   { background:linear-gradient(90deg,#00a86b,#34d399); }
    .cf-warn { background:linear-gradient(90deg,#f59e0b,#fb923c); }
    .cf-full { background:linear-gradient(90deg,#ef4444,#b91c1c); }

    /* Actions row */
    .td-actions { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; }

    .td-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 0.65rem 1.25rem; border-radius: 11px;
        font-size: 0.85rem; font-weight: 700; text-decoration: none;
        transition: all 0.25s; border: none; cursor: pointer;
        white-space: nowrap;
    }
    .td-btn-primary { background:linear-gradient(135deg,#00a86b,#008a5a); color:#fff; box-shadow:0 4px 15px rgba(0,168,107,0.3); }
    .td-btn-primary:hover { transform:scale(1.03); box-shadow:0 8px 24px rgba(0,168,107,0.45); color:#fff; text-decoration:none; }
    .td-btn-outline { background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.12); color:rgba(255,255,255,0.6); }
    .td-btn-outline:hover { background:rgba(255,255,255,0.1); color:#fff; text-decoration:none; }

    .td-hint { font-size:0.75rem; color:rgba(255,255,255,0.3); display:flex; align-items:center; gap:5px; margin-left:auto; }

    /* ─── 2-col layout ─────────────────────────────────────────────── */
    .mgr-cols { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.75rem; }
    @media(max-width:900px) { .mgr-cols { grid-template-columns:1fr; } }

    /* Panel card */
    .mgr-panel {
        background: rgba(15,23,42,0.7);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 18px;
        overflow: hidden;
    }
    .mgr-panel-head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(255,255,255,0.06);
        display: flex; align-items: center; justify-content: space-between;
    }
    .mgr-panel-title { font-size:0.9rem; font-weight:700; color:#fff; display:flex; align-items:center; gap:7px; }
    .mgr-panel-title i { color:#34d399; font-size:0.82rem; }
    .mgr-panel-body { padding:1rem 1.25rem; }
    .mgr-panel-foot {
        padding:0.75rem 1.25rem;
        border-top:1px solid rgba(255,255,255,0.05);
    }

    /* ─── Team Registration Rows ───────────────────────────────────── */
    .reg-row {
        display: flex; align-items: center; gap: 1rem;
        padding: 0.9rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .reg-row:last-child { border-bottom: none; }

    .reg-icon {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; flex-shrink: 0;
    }

    .reg-body { flex: 1; min-width: 0; }
    .reg-team { font-size: 0.9rem; font-weight: 700; color: #fff; }
    .reg-tourney { font-size: 0.75rem; color: rgba(255,255,255,0.4); margin-top: 2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

    .reg-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px; border-radius: 20px;
        font-size: 0.65rem; font-weight: 700; text-transform: uppercase;
        flex-shrink: 0;
    }
    .rb-paid { background:rgba(0,168,107,0.15); color:#34d399; border:1px solid rgba(0,168,107,0.3); }
    .rb-pending { background:rgba(245,158,11,0.15); color:#fbbf24; border:1px solid rgba(245,158,11,0.3); }

    .reg-action {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 0.35rem 0.8rem; border-radius: 8px;
        font-size: 0.75rem; font-weight: 700;
        text-decoration: none; transition: all 0.2s;
        flex-shrink: 0;
    }
    .ra-manage { background:rgba(59,130,246,0.15); color:#60a5fa; border:1px solid rgba(59,130,246,0.25); }
    .ra-manage:hover { background:rgba(59,130,246,0.25); color:#93c5fd; text-decoration:none; }
    .ra-pay { background:rgba(245,158,11,0.15); color:#fbbf24; border:1px solid rgba(245,158,11,0.25); }
    .ra-pay:hover { background:rgba(245,158,11,0.25); color:#fde68a; text-decoration:none; }

    /* ─── Match Feed ───────────────────────────────────────────────── */
    .match-row {
        display: flex; align-items: center;
        padding: 0.85rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        gap: 0.75rem;
    }
    .match-row:last-child { border-bottom: none; }

    .match-info { flex: 1; min-width: 0; }
    .match-tourney { font-size: 0.68rem; color: rgba(255,255,255,0.35); text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 3px; }
    .match-teams  { font-size: 0.88rem; font-weight: 600; color: #fff; display:flex; align-items:center; gap:6px; flex-wrap:wrap; }
    .match-score  { font-weight: 800; font-size: 1rem; padding: 0 4px; }
    .match-score.live { color: #ef4444; }
    .match-score.done { color: rgba(255,255,255,0.5); }

    .live-dot {
        display: inline-flex; align-items: center; gap: 4px;
        background: rgba(239,68,68,0.15); color: #f87171;
        border: 1px solid rgba(239,68,68,0.3);
        border-radius: 20px; padding: 2px 8px;
        font-size: 0.62rem; font-weight: 700;
        animation: bpulse 1.5s infinite; flex-shrink: 0;
    }
    .sched-dot {
        display: inline-flex; align-items: center; gap: 4px;
        background: rgba(59,130,246,0.12); color: #60a5fa;
        border: 1px solid rgba(59,130,246,0.25);
        border-radius: 20px; padding: 2px 8px;
        font-size: 0.62rem; font-weight: 700; flex-shrink: 0;
    }

    /* ─── Quick Actions ────────────────────────────────────────────── */
    .qa-grid { display:flex; flex-direction:column; gap:0.6rem; }
    .qa-btn {
        display: flex; align-items: center; gap: 0.75rem;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px; padding: 0.8rem 1rem;
        text-decoration: none; transition: all 0.2s;
        color: rgba(255,255,255,0.7);
    }
    .qa-btn:hover { background:rgba(255,255,255,0.08); border-color:rgba(255,255,255,0.15); color:#fff; text-decoration:none; transform:translateX(3px); }
    .qa-btn-icon {
        width:38px; height:38px; border-radius:10px;
        display:flex; align-items:center; justify-content:center;
        font-size:0.95rem; flex-shrink:0;
    }
    .qa-label { font-size:0.88rem; font-weight:700; color:#fff; }
    .qa-sub   { font-size:0.72rem; color:rgba(255,255,255,0.35); margin-top:1px; }
    .qa-arrow { margin-left:auto; color:rgba(255,255,255,0.25); font-size:0.8rem; }

    /* Empty state */
    .mgr-empty { text-align:center; padding:2.5rem 1rem; }
    .mgr-empty i { font-size:2.5rem; color:rgba(255,255,255,0.1); margin-bottom:0.75rem; }
    .mgr-empty h4 { font-size:0.95rem; font-weight:700; color:rgba(255,255,255,0.4); margin-bottom:4px; }
    .mgr-empty p  { font-size:0.8rem; color:rgba(255,255,255,0.25); }
</style>
@endpush

@section('content')

    @php
        $registeredTournamentIds = \App\Models\TournamentRegistration::where('manager_id', Auth::id())->pluck('tournament_id')->toArray();
        $latestSafetyLog = \App\Models\SafetyLog::whereIn('tournament_id', $registeredTournamentIds)->latest()->first();
        $hasAlert = $latestSafetyLog && ($latestSafetyLog->alert_level === 'danger' || $latestSafetyLog->alert_level === 'warning');
        $registrations = \App\Models\TournamentRegistration::with(['tournament','team'])
            ->where('manager_id', Auth::id())
            ->orderBy('created_at','desc')
            ->get();
    @endphp

    {{-- ─── Weather Alert ─────────────────────────────────────────── --}}
    @if($hasAlert)
    <div id="weather-safety-alert"
         class="weather-alert"
         style="background:rgba(30,41,59,0.9);border:2px solid {{ $latestSafetyLog->alert_level === 'danger' ? '#ef4444' : '#f59e0b' }};box-shadow:0 0 28px {{ $latestSafetyLog->alert_level === 'danger' ? 'rgba(239,68,68,0.35)' : 'rgba(245,158,11,0.35)' }};">
        <div class="weather-alert-icon" style="color:{{ $latestSafetyLog->alert_level === 'danger' ? '#f87171' : '#fbbf24' }};">
            <i class="fas fa-triangle-exclamation"></i>
        </div>
        <div class="weather-alert-body">
            <div class="weather-alert-title" id="weather-alert-title">
                WEATHER DELAY: {{ strtoupper($latestSafetyLog->alert_level) }}
                @if($latestSafetyLog->tournament) ({{ $latestSafetyLog->tournament->name }}) @endif
            </div>
            <div class="weather-alert-desc" id="weather-alert-desc">
                {{ $latestSafetyLog->notes ?: "Severe weather detected (WBGT: {$latestSafetyLog->wbgt}°C, Lightning: {$latestSafetyLog->lightning_risk}km). Teams advised to seek shelter." }}
            </div>
        </div>
        <a href="{{ route('shared.live-stream') }}" style="background:rgba(239,68,68,0.2);border:1px solid rgba(239,68,68,0.4);color:#f87171;padding:0.5rem 1rem;border-radius:9px;font-size:0.8rem;font-weight:700;text-decoration:none;white-space:nowrap;flex-shrink:0;">
            <i class="fas fa-shield-alt"></i> Safety Status
        </a>
    </div>
    @else
    <div id="weather-safety-alert" style="display:none;">
        <div class="weather-alert-title" id="weather-alert-title"></div>
        <div class="weather-alert-desc" id="weather-alert-desc"></div>
    </div>
    @endif

    {{-- ─── Welcome Hero ───────────────────────────────────────────── --}}
    <div class="mgr-hero">
        <div>
            <div class="mgr-hero-greeting"><i class="fas fa-hand-wave"></i> Welcome back</div>
            <h1 class="mgr-hero-name">{{ Auth::user()->name }}</h1>
            <p class="mgr-hero-sub">Manage your squads, track tournaments, and stay on top of schedules.</p>
        </div>
        <div class="mgr-hero-actions">
            <a href="{{ route('manager.browse-tournaments') }}" class="mgr-hero-btn primary">
                <i class="fas fa-search"></i> Browse Tournaments
            </a>
            <a href="{{ route('manager.schedule') }}" class="mgr-hero-btn secondary">
                <i class="fas fa-calendar-alt"></i> My Schedule
            </a>
            <a href="{{ route('manager.payment-history') }}" class="mgr-hero-btn secondary">
                <i class="fas fa-credit-card"></i> Payments
            </a>
        </div>
    </div>

    {{-- ─── Quick Stats ────────────────────────────────────────────── --}}
    <div class="mgr-stats">
        <div class="mgr-stat">
            <div class="mgr-stat-icon si-green"><i class="fas fa-users"></i></div>
            <div>
                <div class="mgr-stat-val">{{ $myTeams->count() }}</div>
                <div class="mgr-stat-lbl">My Teams</div>
            </div>
        </div>
        <div class="mgr-stat">
            <div class="mgr-stat-icon si-blue"><i class="fas fa-user-check"></i></div>
            <div>
                <div class="mgr-stat-val">{{ $totalPlayers }}</div>
                <div class="mgr-stat-lbl">Players</div>
            </div>
        </div>
        <div class="mgr-stat">
            <div class="mgr-stat-icon si-purple"><i class="fas fa-trophy"></i></div>
            <div>
                <div class="mgr-stat-val">{{ $registrations->count() }}</div>
                <div class="mgr-stat-lbl">Entries</div>
            </div>
        </div>
        <div class="mgr-stat">
            <div class="mgr-stat-icon {{ $pendingPayments > 0 ? 'si-amber' : 'si-green' }}">
                <i class="fas fa-{{ $pendingPayments > 0 ? 'exclamation-circle' : 'check-circle' }}"></i>
            </div>
            <div>
                <div class="mgr-stat-val" style="font-size:1.1rem;">{{ $pendingPayments > 0 ? $pendingPayments.' Pending' : 'All Paid' }}</div>
                <div class="mgr-stat-lbl">Payments</div>
            </div>
        </div>
    </div>

    {{-- ─── Open Tournaments Discovery ─────────────────────────────── --}}
    @if($openTournaments->count() > 0)
    <div class="sec-header">
        <div class="sec-title"><i class="fas fa-bolt"></i> Open for Registration</div>
        <a href="{{ route('manager.browse-tournaments') }}" class="sec-link">Browse All <i class="fas fa-arrow-right"></i></a>
    </div>

    @foreach($openTournaments as $ot)
    @php
        $otDate    = $ot->start_date ?? $ot->tournament_date ?? null;
        $otVenue   = $ot->venue_name ?? $ot->venue ?? null;
        $otDays    = $ot->daysUntilDeadline();
        $otClosing = $otDays !== null && $otDays <= 3 && $otDays > 0;
        $otCats    = $ot->categories ? array_map('trim', explode(',', $ot->categories)) : [];
        $confirmedCnt = $ot->confirmed_count ?? 0;
        $fillPct   = $ot->max_teams ? min(100, round(($confirmedCnt / $ot->max_teams) * 100)) : 0;
        $fillClass = $fillPct >= 100 ? 'cf-full' : ($fillPct >= 75 ? 'cf-warn' : 'cf-ok');
    @endphp

    <div class="td-card">
        <div class="td-card-accent"></div>
        <div class="td-card-inner">

            <div class="td-top">
                <div class="td-icon"><i class="fas fa-trophy"></i></div>
                <div class="td-meta">
                    <div class="td-badge-row">
                        @if($otClosing)
                            <span class="td-badge td-badge-closing"><i class="fas fa-fire"></i> Closing in {{ $otDays }} day{{ $otDays != 1 ? 's' : '' }}</span>
                        @else
                            <span class="td-badge td-badge-open"><i class="fas fa-circle" style="font-size:0.45rem;"></i> Open for Registration</span>
                        @endif
                    </div>
                    <div class="td-name">{{ $ot->name }}</div>
                </div>
                @if($ot->max_teams && $ot->max_teams - $confirmedCnt <= 5 && $ot->max_teams - $confirmedCnt > 0)
                <div class="td-slots-badge">
                    <div style="font-size:1rem;font-weight:800;">{{ $ot->max_teams - $confirmedCnt }}</div>
                    <div style="font-size:0.6rem;">slots left</div>
                </div>
                @endif
            </div>

            {{-- Info chips --}}
            <div class="td-chips">
                @if($otDate)
                <div class="td-chip">
                    <i class="fas fa-calendar-alt"></i> <strong>{{ $otDate->format('d M Y') }}</strong>
                </div>
                @endif
                @if($otVenue)
                <div class="td-chip">
                    <i class="fas fa-map-marker-alt"></i> <strong>{{ Str::limit($otVenue, 30) }}</strong>
                </div>
                @endif
                @if($ot->registration_deadline)
                <div class="td-chip">
                    <i class="fas fa-hourglass-half" style="color:{{ $otClosing ? '#fb923c' : '#34d399' }};"></i>
                    Deadline: <strong>{{ $ot->registration_deadline->format('d M, H:i') }}</strong>
                </div>
                @endif
                @if($ot->fee)
                <div class="td-chip fee">
                    <i class="fas fa-tag"></i> RM {{ number_format($ot->fee, 2) }} / entry
                </div>
                @endif
            </div>

            {{-- Categories --}}
            @if(count($otCats) > 0)
            <div class="td-cats">
                @foreach($otCats as $cat)
                <span class="td-cat">{{ $cat }}</span>
                @endforeach
            </div>
            @endif

            {{-- Description snippet --}}
            @if($ot->description)
            <p style="font-size:0.8rem;color:rgba(255,255,255,0.38);margin-bottom:0.9rem;line-height:1.5;">
                {{ Str::limit($ot->description, 160) }}
            </p>
            @endif

            {{-- Capacity bar --}}
            @if($ot->max_teams)
            <div class="td-cap">
                <div class="td-cap-header">
                    <span class="td-cap-lbl"><i class="fas fa-users" style="margin-right:4px;"></i> Team Slots</span>
                    <span class="td-cap-cnt">{{ $confirmedCnt }} / {{ $ot->max_teams }}</span>
                </div>
                <div class="td-cap-track">
                    <div class="td-cap-fill {{ $fillClass }}" style="width:{{ $fillPct }}%;"></div>
                </div>
            </div>
            @endif

            {{-- Actions --}}
            <div class="td-actions">
                <a href="{{ route('manager.tournaments.register', $ot->id) }}" class="td-btn td-btn-primary">
                    <i class="fas fa-rocket"></i> Register My Team
                </a>
                <a href="{{ route('shared.venue-map', ['tournament_id' => $ot->id]) }}" class="td-btn td-btn-outline">
                    <i class="fas fa-map-marked-alt"></i> Venue Map
                </a>
                <div class="td-hint">
                    <i class="fas fa-info-circle"></i> Limited slots — register early!
                </div>
            </div>

        </div>
    </div>
    @endforeach

    @else
    <div class="sec-header">
        <div class="sec-title"><i class="fas fa-bolt"></i> Open for Registration</div>
    </div>
    <div class="mgr-panel" style="margin-bottom:1.75rem;">
        <div class="mgr-empty">
            <i class="fas fa-calendar-times"></i>
            <h4>No Open Tournaments</h4>
            <p>No tournaments are currently open for registration. Check back soon!</p>
        </div>
    </div>
    @endif

    {{-- ─── Two-column: Registrations + Matches/Quick Actions ─────── --}}
    <div class="mgr-cols">

        {{-- My Team Registrations --}}
        <div class="mgr-panel">
            <div class="mgr-panel-head">
                <div class="mgr-panel-title"><i class="fas fa-shield-alt"></i> My Registrations</div>
                <a href="{{ route('manager.browse-tournaments') }}" class="sec-link">+ New <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="mgr-panel-body" style="padding:0 1.25rem;">
                @forelse($registrations as $reg)
                @php
                    $isPaid = $reg->payment_status === 'paid';
                @endphp
                <div class="reg-row">
                    <div class="reg-icon {{ $isPaid ? 'si-green' : 'si-amber' }}">
                        <i class="fas fa-{{ $isPaid ? 'shield-alt' : 'clock' }}"></i>
                    </div>
                    <div class="reg-body">
                        <div class="reg-team">{{ $reg->team->name ?? 'My Team' }}</div>
                        <div class="reg-tourney">{{ $reg->tournament->name ?? '—' }}</div>
                    </div>
                    <span class="reg-badge {{ $isPaid ? 'rb-paid' : 'rb-pending' }}">
                        {{ $isPaid ? 'Active' : 'Pending' }}
                    </span>
                    @if($isPaid)
                        <a href="{{ route('manager.teams.detail', $reg->team_id) }}" class="reg-action ra-manage">
                            <i class="fas fa-users"></i> Manage
                        </a>
                    @else
                        <a href="{{ route('manager.tournaments.register', $reg->tournament_id) }}" class="reg-action ra-pay">
                            <i class="fas fa-credit-card"></i> Pay
                        </a>
                    @endif
                </div>
                @empty
                <div class="mgr-empty">
                    <i class="fas fa-users"></i>
                    <h4>No Registrations Yet</h4>
                    <p>Register for a tournament to get started.</p>
                </div>
                @endforelse
            </div>
            @if($registrations->count() > 0)
            <div class="mgr-panel-foot">
                <a href="{{ route('manager.my-applications') }}" class="sec-link">View All Applications <i class="fas fa-arrow-right"></i></a>
            </div>
            @endif
        </div>

        {{-- Matches + Quick Actions stacked --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            {{-- Match Feed --}}
            <div class="mgr-panel">
                <div class="mgr-panel-head">
                    <div class="mgr-panel-title"><i class="fas fa-futbol"></i> My Team Matches</div>
                </div>
                <div class="mgr-panel-body" style="padding:0 1.25rem;">
                    @forelse($fixtures as $fix)
                    @php $isLive = $fix->status === 'in_progress'; @endphp
                    <div class="match-row" data-fixture-id="{{ $fix->id }}">
                        <div class="match-info">
                            <div class="match-tourney">
                                {{ $fix->tournament->name ?? 'Tournament' }}
                                @if($fix->start_time) · {{ $fix->start_time->format('h:i A') }} @endif
                            </div>
                            <div class="match-teams">
                                <span>{{ $fix->homeTeam->name ?? 'TBD' }}</span>
                                <span class="match-score {{ $isLive ? 'live' : 'done' }}">
                                    <span class="home-score">{{ $fix->home_score ?? 0 }}</span>
                                    <span style="color:rgba(255,255,255,0.25);margin:0 2px;">–</span>
                                    <span class="away-score">{{ $fix->away_score ?? 0 }}</span>
                                </span>
                                <span>{{ $fix->awayTeam->name ?? 'TBD' }}</span>
                            </div>
                        </div>
                        @if($isLive)
                            <span class="live-dot"><i class="fas fa-circle" style="font-size:0.45rem;"></i> LIVE</span>
                        @else
                            <span class="sched-dot"><i class="fas fa-calendar"></i> Scheduled</span>
                        @endif
                    </div>
                    @empty
                    <div class="mgr-empty" style="padding:1.75rem 1rem;">
                        <i class="fas fa-calendar-alt"></i>
                        <h4>No Scheduled Matches</h4>
                        <p>Match schedule appears once tournaments begin.</p>
                    </div>
                    @endforelse
                </div>
                <div class="mgr-panel-foot">
                    <a href="{{ route('manager.schedule') }}" class="sec-link">Full Schedule <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="mgr-panel">
                <div class="mgr-panel-head">
                    <div class="mgr-panel-title"><i class="fas fa-zap"></i> Quick Actions</div>
                </div>
                <div class="mgr-panel-body">
                    <div class="qa-grid">
                        <a href="{{ route('manager.browse-tournaments') }}" class="qa-btn">
                            <div class="qa-btn-icon si-green"><i class="fas fa-user-plus"></i></div>
                            <div><div class="qa-label">Register New Team</div><div class="qa-sub">Browse open tournaments</div></div>
                            <i class="fas fa-chevron-right qa-arrow"></i>
                        </a>
                        <a href="{{ route('manager.payment-history') }}" class="qa-btn">
                            <div class="qa-btn-icon si-blue"><i class="fas fa-credit-card"></i></div>
                            <div><div class="qa-label">Process Payment</div><div class="qa-sub">Complete registration fee</div></div>
                            <i class="fas fa-chevron-right qa-arrow"></i>
                        </a>
                        <a href="{{ route('shared.brackets') }}" class="qa-btn">
                            <div class="qa-btn-icon si-purple"><i class="fas fa-sitemap"></i></div>
                            <div><div class="qa-label">View Bracket</div><div class="qa-sub">Check standings & fixtures</div></div>
                            <i class="fas fa-chevron-right qa-arrow"></i>
                        </a>
                        <a href="{{ route('shared.live-stream') }}" class="qa-btn">
                            <div class="qa-btn-icon si-red"><i class="fas fa-broadcast-tower"></i></div>
                            <div><div class="qa-label">Live Stream</div><div class="qa-sub">Watch matches live</div></div>
                            <i class="fas fa-chevron-right qa-arrow"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pusherAppKey  = "{{ env('PUSHER_APP_KEY', '96f393e214601452f8c3') }}";
    const pusherCluster = "{{ env('PUSHER_APP_CLUSTER', 'ap1') }}";

    if (!pusherAppKey) return;

    const pusher  = new Pusher(pusherAppKey, { cluster: pusherCluster, forceTLS: true });
    const channel = pusher.subscribe('live-matches');

    channel.bind('score-updated', function(data) {
        const fixture = data.fixture;
        if (!fixture) return;
        const row = document.querySelector(`[data-fixture-id="${fixture.id}"]`);
        if (!row) return;
        const hs = row.querySelector('.home-score');
        const as = row.querySelector('.away-score');
        if (!hs || !as) return;
        if (parseInt(hs.textContent) !== parseInt(fixture.home_score) || parseInt(as.textContent) !== parseInt(fixture.away_score)) {
            hs.textContent = fixture.home_score ?? 0;
            as.textContent = fixture.away_score ?? 0;
            row.style.transition = 'none';
            row.style.backgroundColor = 'rgba(20,184,166,0.15)';
            setTimeout(() => {
                row.style.transition = 'background-color 1s ease';
                row.style.backgroundColor = '';
            }, 100);
        }
    });

    channel.bind('safety-updated', function(data) {
        const log = data.safetyLog;
        if (!log) return;
        const registeredIds = @json($registeredTournamentIds);
        if (log.tournament_id && !registeredIds.map(String).includes(log.tournament_id.toString())) return;

        const banner = document.getElementById('weather-safety-alert');
        if (!banner) return;
        if (log.alert_level === 'danger' || log.alert_level === 'warning') {
            const suffix = log.tournament ? ` (${log.tournament.name})` : '';
            document.getElementById('weather-alert-title').textContent = `WEATHER DELAY: ${log.alert_level.toUpperCase()}${suffix}`;
            document.getElementById('weather-alert-desc').textContent  = log.notes || `Severe weather detected (WBGT: ${log.wbgt}°C, Lightning: ${log.lightning_risk}km).`;
            const color = log.alert_level === 'danger' ? '#ef4444' : '#f59e0b';
            banner.style.borderColor = color;
            banner.style.boxShadow   = `0 0 28px ${log.alert_level === 'danger' ? 'rgba(239,68,68,0.35)' : 'rgba(245,158,11,0.35)'}`;
            banner.style.display = 'flex';
        } else {
            window.location.reload();
        }
    });
});
</script>
@endpush