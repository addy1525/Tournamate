@extends('layouts.dashboard')

@section('title', 'Safety & Weather Operations')
@section('page-title', 'Safety & Weather Operations')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
<style>
/* ── Base Variables ─────────────────────────────────── */
:root {
    --safe:    #10b981;
    --caution: #3b82f6;
    --warning: #f59e0b;
    --danger:  #ef4444;
}

/* ── Glassmorphism Cards ────────────────────────────── */
.glass {
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.4);
}
.glass-header {
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid rgba(255,255,255,0.07);
    display: flex; align-items: center; gap: 10px;
}
.glass-header h3 { margin: 0; font-size: 0.95rem; font-weight: 700; color: #f1f5f9; text-transform: uppercase; letter-spacing: 0.06em; }
.glass-header .header-icon { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }

/* ── Alert Glow Borders ─────────────────────────────── */
.border-safe    { border-top: 3px solid var(--safe);    box-shadow: 0 0 35px rgba(16,185,129,0.2),  0 8px 32px rgba(0,0,0,0.4); }
.border-caution { border-top: 3px solid var(--caution); box-shadow: 0 0 35px rgba(59,130,246,0.2),  0 8px 32px rgba(0,0,0,0.4); }
.border-warning { border-top: 3px solid var(--warning); box-shadow: 0 0 35px rgba(245,158,11,0.25), 0 8px 32px rgba(0,0,0,0.4); animation: pulse-warning 2.5s ease-in-out infinite; }
.border-danger  { border-top: 3px solid var(--danger);  box-shadow: 0 0 40px rgba(239,68,68,0.35),  0 8px 32px rgba(0,0,0,0.4); animation: pulse-danger  2s ease-in-out infinite; }

@keyframes pulse-warning {
    0%,100% { box-shadow: 0 0 35px rgba(245,158,11,0.25), 0 8px 32px rgba(0,0,0,0.4); }
    50%      { box-shadow: 0 0 60px rgba(245,158,11,0.5),  0 8px 32px rgba(0,0,0,0.4); }
}
@keyframes pulse-danger {
    0%,100% { box-shadow: 0 0 40px rgba(239,68,68,0.35), 0 8px 32px rgba(0,0,0,0.4); }
    50%      { box-shadow: 0 0 70px rgba(239,68,68,0.6),  0 8px 32px rgba(0,0,0,0.4); }
}

/* ── Circular Gauges ────────────────────────────────── */
.gauge-wrap { position: relative; width: 160px; height: 160px; margin: 0 auto; }
.gauge-svg  { width: 100%; height: 100%; transform: rotate(-90deg); }
.gauge-bg   { fill: none; stroke: rgba(255,255,255,0.07); stroke-width: 13; }
.gauge-fill { fill: none; stroke-width: 13; stroke-linecap: round; transition: stroke-dasharray 1.2s cubic-bezier(.4,0,.2,1); filter: drop-shadow(0 0 6px currentColor); }
.gauge-center { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1px; }
.gauge-val  { font-size: 2.2rem; font-weight: 800; color: #fff; line-height: 1; }
.gauge-unit { font-size: 0.78rem; color: rgba(255,255,255,0.55); font-weight: 600; }
.gauge-lbl  { font-size: 0.7rem; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px; margin-top: 2px; }

/* ── Metric Mini Cards ──────────────────────────────── */
.metric { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 14px; text-align: center; transition: all .2s; }
.metric:hover { background: rgba(255,255,255,0.07); transform: translateY(-2px); }
.metric-val { font-size: 1.4rem; font-weight: 800; color: #fff; margin: 6px 0 2px; }
.metric-lbl { font-size: 0.7rem; color: rgba(255,255,255,0.45); text-transform: uppercase; letter-spacing: .5px; }

/* ── SOP Compliance Table ───────────────────────────── */
.sop-row {
    display: flex; align-items: flex-start; gap: 14px;
    padding: 14px 18px;
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.06);
    margin-bottom: 10px;
    transition: all .3s;
    opacity: 0.45;
}
.sop-row.active { opacity: 1; transform: scale(1.01); }
.sop-row.active.sop-safe    { background: rgba(16,185,129,0.08); border-color: rgba(16,185,129,0.3); box-shadow: 0 0 20px rgba(16,185,129,0.15); }
.sop-row.active.sop-caution { background: rgba(59,130,246,0.08); border-color: rgba(59,130,246,0.3); box-shadow: 0 0 20px rgba(59,130,246,0.15); }
.sop-row.active.sop-warning { background: rgba(245,158,11,0.08); border-color: rgba(245,158,11,0.3); box-shadow: 0 0 20px rgba(245,158,11,0.15); }
.sop-row.active.sop-danger  { background: rgba(239,68,68,0.1);   border-color: rgba(239,68,68,0.4);  box-shadow: 0 0 25px rgba(239,68,68,0.2); animation: pulse-danger 2s infinite; }
.sop-badge { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0; }
.sop-title { font-size: 0.88rem; font-weight: 700; color: #f1f5f9; }
.sop-desc  { font-size: 0.78rem; color: rgba(255,255,255,0.5); margin-top: 3px; line-height: 1.5; }
.sop-threshold { font-size: 0.72rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; padding: 2px 8px; border-radius: 99px; margin-left: auto; white-space: nowrap; align-self: flex-start; flex-shrink: 0; }

/* ── Lightning Countdown Timer ──────────────────────── */
#lightning-timer-panel { text-align: center; padding: 1.5rem; }
.timer-ring-wrap { position: relative; width: 200px; height: 200px; margin: 0 auto 1.25rem; }
#timer-ring { transform: rotate(-90deg); }
.ring-bg  { fill: none; stroke: rgba(255,255,255,0.06); stroke-width: 10; }
.ring-progress { fill: none; stroke-width: 10; stroke-linecap: round; transition: stroke-dasharray 1s linear; }
.timer-center { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; }
#timer-display { font-size: 3rem; font-weight: 900; color: #fff; line-height: 1; font-variant-numeric: tabular-nums; }
.timer-sub { font-size: 0.72rem; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px; margin-top: 4px; }
.timer-status { font-size: 0.85rem; font-weight: 600; padding: 6px 16px; border-radius: 99px; display: inline-block; }
.timer-safe    { background: rgba(16,185,129,0.15); color: var(--safe);    border: 1px solid rgba(16,185,129,0.3); }
.timer-active  { background: rgba(239,68,68,0.15);  color: var(--danger);  border: 1px solid rgba(239,68,68,0.3);  }
.timer-warning { background: rgba(245,158,11,0.15); color: var(--warning); border: 1px solid rgba(245,158,11,0.3); }
#timer-btn { margin-top: 1rem; padding: 10px 28px; border-radius: 8px; border: none; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: .5px; cursor: pointer; transition: all .2s; }
.btn-start-timer { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; box-shadow: 0 4px 15px rgba(239,68,68,0.3); }
.btn-reset-timer { background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.1) !important; }
.btn-start-timer:hover { transform: translateY(-2px); box-shadow: 0 6px 25px rgba(239,68,68,0.5); }

/* ── Chart Container ────────────────────────────────── */
.chart-container { position: relative; height: 220px; padding: 1rem 1rem 0; }

/* ── Map ────────────────────────────────────────────── */
#radar-map { width: 100%; height: 280px; border-radius: 0 0 14px 14px; z-index: 1; }

/* ── Manual Entry Form ──────────────────────────────── */
.form-row-glass { position: relative; margin-bottom: 0.75rem; }
.form-row-glass .fi { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,0.35); font-size: 0.9rem; z-index: 1; pointer-events: none; }
.form-row-glass input, .form-row-glass textarea, .form-row-glass select {
    background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.09);
    border-radius: 8px; color: #f1f5f9; padding: 11px 12px 11px 40px; width: 100%;
    font-size: 0.875rem; transition: border-color .2s, box-shadow .2s;
}
.form-row-glass input:focus, .form-row-glass textarea:focus {
    outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
}
.form-row-glass input::placeholder, .form-row-glass textarea::placeholder { color: rgba(255,255,255,0.25); }
.form-row-glass textarea { resize: none; padding-top: 11px; min-height: 72px; }
.wbgt-hint { margin: 0; font-size: 0.72rem; color: rgba(255,255,255,0.3); padding-left: 40px; margin-top: 4px; }

/* ── Premium Table ──────────────────────────────────── */
.t-premium { width: 100%; border-collapse: collapse; }
.t-premium thead th { background: rgba(255,255,255,0.04); padding: 12px 16px; font-size: 0.7rem; text-transform: uppercase; letter-spacing: .8px; color: rgba(255,255,255,0.45); font-weight: 700; border: none; }
.t-premium tbody tr { border-bottom: 1px solid rgba(255,255,255,0.04); transition: background .15s; }
.t-premium tbody tr:hover { background: rgba(255,255,255,0.04); }
.t-premium tbody td { padding: 12px 16px; font-size: 0.85rem; color: rgba(255,255,255,0.8); border: none; }

/* ── Badge ──────────────────────────────────────────── */
.lvl-badge { display: inline-block; padding: 3px 10px; border-radius: 99px; font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .5px; }
.lvl-safe    { background: rgba(16,185,129,0.15);  color: var(--safe);    border: 1px solid rgba(16,185,129,0.3); }
.lvl-caution { background: rgba(59,130,246,0.15);  color: var(--caution); border: 1px solid rgba(59,130,246,0.3); }
.lvl-warning { background: rgba(245,158,11,0.15);  color: var(--warning); border: 1px solid rgba(245,158,11,0.3); }
.lvl-danger  { background: rgba(239,68,68,0.18);   color: var(--danger);  border: 1px solid rgba(239,68,68,0.4);  box-shadow: 0 0 10px rgba(239,68,68,0.3); }

/* ── Glow Buttons ───────────────────────────────────── */
.btn-glow-blue { padding: 10px 22px; border-radius: 8px; border: none; font-weight: 700; font-size: 0.82rem; text-transform: uppercase; letter-spacing: .5px; cursor: pointer; background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; box-shadow: 0 4px 15px rgba(59,130,246,0.3); transition: all .2s; }
.btn-glow-blue:hover { transform: translateY(-2px); box-shadow: 0 6px 25px rgba(59,130,246,0.5); }
.btn-glow-red  { padding: 10px 22px; border-radius: 8px; border: none; font-weight: 700; font-size: 0.82rem; text-transform: uppercase; letter-spacing: .5px; cursor: pointer; background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; box-shadow: 0 4px 15px rgba(239,68,68,0.3); transition: all .2s; width: 100%; }
.btn-glow-red:hover { transform: translateY(-2px); box-shadow: 0 6px 25px rgba(239,68,68,0.5); }

/* ── Pulse dot ──────────────────────────────────────── */
.pulse-live { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: var(--safe); box-shadow: 0 0 0 0 rgba(16,185,129,.7); animation: pulse-dot 2s infinite; }
.pulse-live.red { background: var(--danger); box-shadow: 0 0 0 0 rgba(239,68,68,.7); animation: pulse-dot-red 1.2s infinite; }
@keyframes pulse-dot     { 0%,100%{box-shadow:0 0 0 0 rgba(16,185,129,.7)}70%{box-shadow:0 0 0 8px rgba(16,185,129,0)} }
@keyframes pulse-dot-red { 0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,.7)} 70%{box-shadow:0 0 0 8px rgba(239,68,68,0)}  }

/* ── Responsive ─────────────────────────────────────── */
@media (max-width: 767px) { .gauge-wrap { width: 130px; height: 130px; } .gauge-val { font-size: 1.75rem; } }
</style>
@endpush

@section('content')

@php
    $level = $latestLog?->alert_level ?? 'safe';
    $levelClass = ['safe'=>'border-safe','caution'=>'border-caution','warning'=>'border-warning','danger'=>'border-danger'][$level] ?? 'border-safe';
    $levelIcon  = ['safe'=>'fa-check-circle','caution'=>'fa-info-circle','warning'=>'fa-exclamation-circle','danger'=>'fa-triangle-exclamation'][$level] ?? 'fa-check-circle';
    $levelColor = ['safe'=>'#10b981','caution'=>'#3b82f6','warning'=>'#f59e0b','danger'=>'#ef4444'][$level] ?? '#10b981';

    // Chart data
    $chartLabels = $trendLogs->map(fn($l) => $l->created_at->format('H:i'))->values()->toJson();
    $chartWbgt   = $trendLogs->map(fn($l) => $l->wbgt ?? 0)->values()->toJson();
    $chartTemp   = $trendLogs->map(fn($l) => $l->temperature ?? 0)->values()->toJson();
    $chartLght   = $trendLogs->map(fn($l) => $l->lightning_risk ?? 50)->values()->toJson();

    // Venue coordinates
    $lat = 3.1390; $lng = 101.6869;
    if ($selectedTournament?->location_coordinates) {
        $coords = explode(',', $selectedTournament->location_coordinates);
        if (count($coords) === 2) { $lat = trim($coords[0]); $lng = trim($coords[1]); }
    }
@endphp

{{-- ════════════════════════════════════════════════════
     PAGE HEADER
═════════════════════════════════════════════════════ --}}
<div class="d-flex align-items-center justify-content-between flex-wrap mb-4" style="gap:1rem;">
    <div>
        <p class="mb-0" style="color:rgba(255,255,255,0.45); font-size:0.82rem;">
            <span class="pulse-live {{ in_array($level,['danger','warning']) ? 'red' : '' }}" style="margin-right:8px;"></span>
            <i class="fas fa-shield-halved mr-1"></i>
            World Rugby Safety Protocol · Real-time WBGT & Lightning Monitoring
        </p>
    </div>
    <div class="d-flex align-items-center" style="gap:10px; flex-wrap:wrap;">
        @if($tournaments->count() > 0)
            <select style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.12); border-radius:8px; color:#fff; padding:8px 14px; font-size:0.82rem; font-weight:600; cursor:pointer;" onchange="window.location.href='?tournament_id='+this.value">
                @foreach($tournaments as $t)
                    <option value="{{ $t->id }}" {{ $selectedTournament && $selectedTournament->id == $t->id ? 'selected' : '' }} style="background:#1e293b;">
                        🏆 {{ $t->name }}
                    </option>
                @endforeach
            </select>
        @endif
        <form action="{{ route('admin.safety.refresh') }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="tournament_id" value="{{ $selectedTournament->id ?? '' }}">
            <button type="submit" class="btn-glow-blue" {{ !$selectedTournament ? 'disabled' : '' }}>
                <i class="fas fa-sync-alt mr-1"></i> Refresh API Data
            </button>
        </form>
    </div>
</div>

{{-- ════════════════════════════════════════════════════
     ROW 1 — HERO STATUS PANEL
═════════════════════════════════════════════════════ --}}
<div class="glass {{ $levelClass }} mb-4 p-0" style="overflow:hidden;">
    <div class="glass-header" style="background:rgba(0,0,0,0.15);">
        <div class="header-icon" style="background:{{ $levelColor }}25;">
            <i class="fas {{ $levelIcon }}" style="color:{{ $levelColor }};"></i>
        </div>
        <h3>Current Safety Status:
            <span style="color:{{ $levelColor }};">{{ strtoupper($level) }}</span>
        </h3>
        @if($latestLog)
            <span style="margin-left:auto; font-size:0.75rem; color:rgba(255,255,255,0.35); font-weight:400;">
                <i class="far fa-clock mr-1"></i>
                Last updated {{ $latestLog->created_at->diffForHumans() }}
            </span>
        @endif
    </div>
    <div class="row m-0 p-3" style="gap:0;">
        {{-- WBGT Gauge --}}
        <div class="col-6 col-md-3 py-3 text-center">
            @php $wbgt = $latestLog?->wbgt ?? 0; $wPct = min(($wbgt/40)*100,100); $wColor = $wbgt>=32?'#ef4444':($wbgt>=28?'#f59e0b':'#10b981'); @endphp
            <div class="gauge-wrap">
                <svg class="gauge-svg" viewBox="0 0 200 200">
                    <circle class="gauge-bg"   cx="100" cy="100" r="80"/>
                    <circle class="gauge-fill" cx="100" cy="100" r="80" stroke="{{ $wColor }}"
                            style="stroke-dasharray:{{ $wPct * 5.03 }} 503;"/>
                </svg>
                <div class="gauge-center">
                    <div class="gauge-val">{{ number_format($wbgt,1) }}</div>
                    <div class="gauge-unit">°C</div>
                    <div class="gauge-lbl">WBGT</div>
                </div>
            </div>
        </div>
        {{-- Lightning Gauge --}}
        <div class="col-6 col-md-3 py-3 text-center">
            @php $lght = $latestLog?->lightning_risk ?? 50; $lPct = min(($lght/50)*100,100); $lColor = $lght<=10?'#ef4444':($lght<=15?'#f59e0b':'#10b981'); @endphp
            <div class="gauge-wrap">
                <svg class="gauge-svg" viewBox="0 0 200 200">
                    <circle class="gauge-bg"   cx="100" cy="100" r="80"/>
                    <circle class="gauge-fill" cx="100" cy="100" r="80" stroke="{{ $lColor }}"
                            style="stroke-dasharray:{{ $lPct * 5.03 }} 503;"/>
                </svg>
                <div class="gauge-center">
                    <div class="gauge-val">{{ $lght < 50 ? number_format($lght,1) : '50+' }}</div>
                    <div class="gauge-unit">km</div>
                    <div class="gauge-lbl">Lightning</div>
                </div>
            </div>
        </div>
        {{-- Metric Mini Cards --}}
        <div class="col-md-6 py-3">
            <div class="row m-0" style="gap:10px; height:100%; align-content:center;">
                <div class="col-5 p-0">
                    <div class="metric">
                        <i class="fas fa-temperature-high" style="color:#f87171; font-size:1.25rem;"></i>
                        <div class="metric-val">{{ $latestLog ? number_format($latestLog->temperature,1).'°C' : 'N/A' }}</div>
                        <div class="metric-lbl">Temperature</div>
                    </div>
                </div>
                <div class="col-5 p-0">
                    <div class="metric">
                        <i class="fas fa-droplet" style="color:#60a5fa; font-size:1.25rem;"></i>
                        <div class="metric-val">{{ $latestLog ? number_format($latestLog->humidity,0).'%' : 'N/A' }}</div>
                        <div class="metric-lbl">Humidity</div>
                    </div>
                </div>
                <div class="col-5 p-0">
                    <div class="metric">
                        <i class="fas fa-wind" style="color:#a78bfa; font-size:1.25rem;"></i>
                        <div class="metric-val">{{ $latestLog ? number_format($latestLog->wind_speed,1).' km/h' : 'N/A' }}</div>
                        <div class="metric-lbl">Wind Speed</div>
                    </div>
                </div>
                <div class="col-5 p-0">
                    <div class="metric">
                        <i class="fas fa-bolt" style="color:#fbbf24; font-size:1.25rem;"></i>
                        <div class="metric-val" style="color:{{ $levelColor }};">{{ strtoupper($level) }}</div>
                        <div class="metric-lbl">Alert Level</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════
     ROW 2 — TREND CHART  |  LIGHTNING TIMER
═════════════════════════════════════════════════════ --}}
<div class="row mb-4">
    {{-- FEATURE 1: WBGT Trend Chart --}}
    <div class="col-lg-7 mb-4">
        <div class="glass h-100">
            <div class="glass-header">
                <div class="header-icon" style="background:rgba(99,102,241,0.15);">
                    <i class="fas fa-chart-area" style="color:#818cf8;"></i>
                </div>
                <h3>WBGT & Conditions Trend</h3>
                <span style="margin-left:auto; font-size:0.7rem; color:rgba(255,255,255,0.3);">Last 10 Readings</span>
            </div>
            <div class="chart-container">
                @if($trendLogs->count() > 0)
                    <canvas id="trendChart"></canvas>
                @else
                    <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; height:100%; gap:10px; color:rgba(255,255,255,0.2);">
                        <i class="fas fa-chart-area" style="font-size:2.5rem;"></i>
                        <p style="font-size:0.82rem; margin:0;">No readings yet. Refresh weather data to populate chart.</p>
                    </div>
                @endif
            </div>
            {{-- Legend --}}
            <div style="display:flex; align-items:center; gap:20px; padding:12px 18px 14px; border-top:1px solid rgba(255,255,255,0.06);">
                <span style="font-size:0.72rem; color:rgba(255,255,255,0.45); display:flex; align-items:center; gap:6px;"><span style="width:20px; height:3px; background:#f59e0b; border-radius:2px; display:inline-block;"></span>WBGT (°C)</span>
                <span style="font-size:0.72rem; color:rgba(255,255,255,0.45); display:flex; align-items:center; gap:6px;"><span style="width:20px; height:3px; background:#f87171; border-radius:2px; display:inline-block;"></span>Temperature (°C)</span>
                <span style="font-size:0.72rem; color:rgba(255,255,255,0.45); display:flex; align-items:center; gap:6px;"><span style="width:20px; height:3px; background:#60a5fa; border-radius:2px; display:inline-block; border-style:dashed;"></span>Lightning (km)</span>
            </div>
        </div>
    </div>

    {{-- FEATURE 3: Lightning Safe-to-Resume Timer --}}
    <div class="col-lg-5 mb-4">
        <div class="glass h-100">
            <div class="glass-header">
                <div class="header-icon" style="background:rgba(239,68,68,0.12);">
                    <i class="fas fa-stopwatch" style="color:#f87171;"></i>
                </div>
                <h3>Lightning Safety Timer</h3>
            </div>
            <div id="lightning-timer-panel">
                <div class="timer-ring-wrap">
                    <svg id="timer-ring" viewBox="0 0 200 200" width="200" height="200">
                        <circle class="ring-bg" cx="100" cy="100" r="85"/>
                        <circle id="ring-fill" class="ring-progress" cx="100" cy="100" r="85"
                                stroke="#ef4444" style="stroke-dasharray:534 534;"/>
                    </svg>
                    <div class="timer-center">
                        <div id="timer-display">30:00</div>
                        <div class="timer-sub">minutes remaining</div>
                    </div>
                </div>
                <div id="timer-status-badge" class="timer-status timer-safe">
                    <i class="fas fa-check-circle mr-1"></i> Field Is Safe to Play
                </div>
                <p style="font-size:0.75rem; color:rgba(255,255,255,0.3); margin:10px 0 0; line-height:1.5;">
                    Per World Rugby SOP: Field must remain lightning-free for <strong style="color:rgba(255,255,255,0.55);">30 minutes</strong> before resuming play.
                </p>
                <div style="display:flex; gap:10px; margin-top:1rem; justify-content:center;">
                    <button id="timer-btn" class="btn-start-timer" onclick="toggleTimer()">
                        <i class="fas fa-bolt mr-1"></i> Lightning Detected!
                    </button>
                    <button class="btn-reset-timer" style="padding:10px 18px; border-radius:8px; cursor:pointer;" onclick="resetTimer()">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════
     ROW 3 — SOP COMPLIANCE  |  RADAR MAP
═════════════════════════════════════════════════════ --}}
<div class="row mb-4">
    {{-- FEATURE 2: World Rugby SOP Compliance Dashboard --}}
    <div class="col-lg-6 mb-4">
        <div class="glass h-100">
            <div class="glass-header">
                <div class="header-icon" style="background:rgba(16,185,129,0.12);">
                    <i class="fas fa-book-open" style="color:#34d399;"></i>
                </div>
                <h3>World Rugby SOP Compliance</h3>
                <span style="margin-left:auto; font-size:0.72rem; background:rgba(16,185,129,0.12); color:#34d399; border:1px solid rgba(16,185,129,0.25); padding:2px 9px; border-radius:99px;">Active Protocol</span>
            </div>
            <div class="p-3">
                {{-- SAFE --}}
                <div class="sop-row sop-safe {{ $level === 'safe' ? 'active' : '' }}">
                    <div class="sop-badge" style="background:rgba(16,185,129,0.12);"><i class="fas fa-check-circle" style="color:#10b981;"></i></div>
                    <div style="flex:1; min-width:0;">
                        <div class="sop-title">🟢 Safe – Play Normal</div>
                        <div class="sop-desc">All clear. No restrictions on play. Standard match protocols apply. Monitor conditions continuously.</div>
                    </div>
                    <span class="sop-threshold" style="background:rgba(16,185,129,0.12); color:#10b981; border:1px solid rgba(16,185,129,0.25);">WBGT &lt; 28°C</span>
                </div>
                {{-- CAUTION --}}
                <div class="sop-row sop-caution {{ $level === 'caution' ? 'active' : '' }}">
                    <div class="sop-badge" style="background:rgba(59,130,246,0.12);"><i class="fas fa-info-circle" style="color:#3b82f6;"></i></div>
                    <div style="flex:1; min-width:0;">
                        <div class="sop-title">🔵 Caution – Mandatory Rest Breaks</div>
                        <div class="sop-desc">5-minute mandatory water breaks every 20 minutes of play. Reduce warm-up intensity. Referees to monitor players for signs of heat stress.</div>
                    </div>
                    <span class="sop-threshold" style="background:rgba(59,130,246,0.12); color:#3b82f6; border:1px solid rgba(59,130,246,0.25);">WBGT 28–30°C</span>
                </div>
                {{-- WARNING --}}
                <div class="sop-row sop-warning {{ $level === 'warning' ? 'active' : '' }}">
                    <div class="sop-badge" style="background:rgba(245,158,11,0.12);"><i class="fas fa-exclamation-triangle" style="color:#f59e0b;"></i></div>
                    <div style="flex:1; min-width:0;">
                        <div class="sop-title">🟡 Warning – Reduced Exposure</div>
                        <div class="sop-desc">Shorten match halves. Activate medical tent on standby. Heat stroke monitoring is mandatory. No extended warm-up permitted. Consider postponement.</div>
                    </div>
                    <span class="sop-threshold" style="background:rgba(245,158,11,0.12); color:#f59e0b; border:1px solid rgba(245,158,11,0.25);">WBGT 30–32°C</span>
                </div>
                {{-- DANGER --}}
                <div class="sop-row sop-danger {{ $level === 'danger' ? 'active' : '' }}" style="margin-bottom:0;">
                    <div class="sop-badge" style="background:rgba(239,68,68,0.12);"><i class="fas fa-triangle-exclamation" style="color:#ef4444;"></i></div>
                    <div style="flex:1; min-width:0;">
                        <div class="sop-title">🔴 DANGER – Immediate Suspension</div>
                        <div class="sop-desc">STOP ALL PLAY IMMEDIATELY. Clear all players and spectators from open fields. Lightning 30-minute safe-resume protocol activated. Match to be rescheduled by tournament director.</div>
                    </div>
                    <span class="sop-threshold" style="background:rgba(239,68,68,0.12); color:#ef4444; border:1px solid rgba(239,68,68,0.35);">WBGT ≥ 32°C / Ltng ≤ 10km</span>
                </div>
            </div>
        </div>
    </div>

    {{-- FEATURE 4: Weather Radar Zone Map --}}
    <div class="col-lg-6 mb-4">
        <div class="glass h-100" style="overflow:hidden;">
            <div class="glass-header">
                <div class="header-icon" style="background:rgba(59,130,246,0.12);">
                    <i class="fas fa-map-marked-alt" style="color:#60a5fa;"></i>
                </div>
                <h3>Venue Radar & Lightning Zones</h3>
                <span style="margin-left:auto; font-size:0.7rem; color:rgba(255,255,255,0.3);">{{ $selectedTournament?->venue_name ?? $selectedTournament?->venue ?? 'Main Venue' }}</span>
            </div>
            <div id="radar-map"></div>
            <div style="padding:10px 14px; border-top:1px solid rgba(255,255,255,0.06); display:flex; gap:16px; flex-wrap:wrap;">
                <span style="font-size:0.7rem; color:rgba(255,255,255,0.4); display:flex; align-items:center; gap:6px;"><span style="width:12px; height:12px; border-radius:50%; background:rgba(239,68,68,0.25); border:1px solid #ef4444; display:inline-block;"></span>Danger Zone (≤10 km)</span>
                <span style="font-size:0.7rem; color:rgba(255,255,255,0.4); display:flex; align-items:center; gap:6px;"><span style="width:12px; height:12px; border-radius:50%; background:rgba(245,158,11,0.2); border:1px solid #f59e0b; display:inline-block;"></span>Warning Zone (≤15 km)</span>
                <span style="font-size:0.7rem; color:rgba(255,255,255,0.4); display:flex; align-items:center; gap:6px;"><span style="width:12px; height:12px; border-radius:50%; background:rgba(59,130,246,0.15); border:1px solid #3b82f6; display:inline-block;"></span>Caution Zone (≤20 km)</span>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════
     ROW 4 — MANUAL ENTRY  |  RECENT LOGS
═════════════════════════════════════════════════════ --}}
<div class="row">
    {{-- Manual Entry Form --}}
    <div class="col-lg-4 mb-4">
        <div class="glass h-100" style="border-top:3px solid #3b82f6;">
            <div class="glass-header">
                <div class="header-icon" style="background:rgba(59,130,246,0.12);">
                    <i class="fas fa-satellite-dish" style="color:#60a5fa;"></i>
                </div>
                <h3>Manual Broadcast Alert</h3>
            </div>
            <div class="p-4">
                <form action="{{ route('admin.safety.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tournament_id" value="{{ $selectedTournament?->id ?? '' }}">
                    <div class="form-row-glass">
                        <i class="fas fa-temperature-high fi"></i>
                        <input type="number" step="0.1" name="temperature" placeholder="Temperature (°C)">
                    </div>
                    <div class="form-row-glass">
                        <i class="fas fa-droplet fi"></i>
                        <input type="number" step="0.1" name="humidity" placeholder="Humidity (%)">
                    </div>
                    <div class="form-row-glass">
                        <i class="fas fa-wind fi"></i>
                        <input type="number" step="0.1" name="wind_speed" placeholder="Wind Speed (km/h)">
                    </div>
                    <div class="form-row-glass">
                        <i class="fas fa-fire fi"></i>
                        <input type="number" step="0.1" name="wbgt" placeholder="WBGT Reading (°C)" required>
                    </div>
                    <p class="wbgt-hint">Wet Bulb Globe Temperature</p>
                    <div class="form-row-glass mt-2">
                        <i class="fas fa-bolt fi"></i>
                        <input type="number" step="0.1" name="lightning_risk" placeholder="Nearest Lightning (km)" required>
                    </div>
                    <div class="form-row-glass">
                        <i class="fas fa-comment fi" style="top:18px; transform:none;"></i>
                        <textarea name="notes" placeholder="Alert notes / instructions..."></textarea>
                    </div>
                    <button type="submit" class="btn-glow-red mt-2" {{ !$selectedTournament ? 'disabled' : '' }}>
                        <i class="fas fa-broadcast-tower mr-2"></i> Broadcast Alert to All Users
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Recent Safety Logs --}}
    <div class="col-lg-8 mb-4">
        <div class="glass h-100">
            <div class="glass-header">
                <div class="header-icon" style="background:rgba(99,102,241,0.12);">
                    <i class="fas fa-history" style="color:#818cf8;"></i>
                </div>
                <h3>Recent Safety Log</h3>
                <a href="{{ route('admin.safety.history') }}" style="margin-left:auto; font-size:0.75rem; color:rgba(255,255,255,0.35); text-decoration:none;">
                    View Full History →
                </a>
            </div>
            <div style="overflow-x:auto;">
                <table class="t-premium">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Temp (°C)</th>
                            <th>WBGT (°C)</th>
                            <th>Lightning (km)</th>
                            <th>Humidity (%)</th>
                            <th>Alert Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>
                                    <div style="font-weight:600;">{{ $log->created_at->format('d M Y') }}</div>
                                    <div style="font-size:0.75rem; color:rgba(255,255,255,0.35);">{{ $log->created_at->format('H:i:s') }}</div>
                                </td>
                                <td>{{ $log->temperature ? number_format($log->temperature,1) : '—' }}</td>
                                <td style="font-weight:700; color:{{ $log->wbgt >= 32 ? '#ef4444' : ($log->wbgt >= 28 ? '#f59e0b' : '#10b981') }}">
                                    {{ $log->wbgt ? number_format($log->wbgt,1) : '—' }}
                                </td>
                                <td style="font-weight:700; color:{{ $log->lightning_risk <= 10 ? '#ef4444' : ($log->lightning_risk <= 15 ? '#f59e0b' : '#10b981') }}">
                                    {{ $log->lightning_risk ? number_format($log->lightning_risk,1) : '—' }}
                                </td>
                                <td>{{ $log->humidity ? number_format($log->humidity,0).'%' : '—' }}</td>
                                <td>
                                    <span class="lvl-badge lvl-{{ $log->alert_level ?? 'safe' }}">
                                        {{ strtoupper($log->alert_level ?? 'SAFE') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding:2.5rem; color:rgba(255,255,255,0.2);">
                                    <i class="fas fa-inbox" style="font-size:2rem;"></i>
                                    <p style="margin:8px 0 0; font-size:0.82rem;">No safety logs available.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ══════════════════════════════════════
       FEATURE 1 — WBGT Trend Chart
    ══════════════════════════════════════ */
    const chartCanvas = document.getElementById('trendChart');
    if (chartCanvas) {
        const labels = {!! $chartLabels !!};
        const wbgtData = {!! $chartWbgt !!};
        const tempData = {!! $chartTemp !!};
        const lghtData = {!! $chartLght !!};

        new Chart(chartCanvas, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'WBGT (°C)',
                        data: wbgtData,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245,158,11,0.08)',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#f59e0b',
                        pointRadius: 4,
                        fill: true,
                        tension: 0.4,
                    },
                    {
                        label: 'Temp (°C)',
                        data: tempData,
                        borderColor: '#f87171',
                        backgroundColor: 'rgba(248,113,113,0.06)',
                        borderWidth: 2,
                        pointBackgroundColor: '#f87171',
                        pointRadius: 3,
                        fill: false,
                        tension: 0.4,
                    },
                    {
                        label: 'Lightning (km)',
                        data: lghtData,
                        borderColor: '#60a5fa',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [6, 4],
                        pointBackgroundColor: '#60a5fa',
                        pointRadius: 3,
                        fill: false,
                        tension: 0.4,
                        yAxisID: 'y2',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15,23,42,0.95)',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1,
                        titleColor: 'rgba(255,255,255,0.7)',
                        bodyColor: '#f1f5f9',
                        padding: 12,
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        ticks: { color: 'rgba(255,255,255,0.4)', font: { size: 10 } }
                    },
                    y: {
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        ticks: { color: 'rgba(255,255,255,0.4)', font: { size: 10 } },
                        title: { display: true, text: '°C', color: 'rgba(255,255,255,0.3)', font: { size: 10 } },
                    },
                    y2: {
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: { color: 'rgba(96,165,250,0.6)', font: { size: 10 } },
                        title: { display: true, text: 'km', color: 'rgba(96,165,250,0.4)', font: { size: 10 } },
                    }
                }
            }
        });

        // WBGT danger threshold lines
        const dangerPlugin = {
            id: 'thresholdLines',
            afterDraw(chart) {
                const ctx = chart.ctx;
                const yAxis = chart.scales.y;
                const xStart = chart.chartArea.left;
                const xEnd   = chart.chartArea.right;

                const drawLine = (value, color, label) => {
                    const y = yAxis.getPixelForValue(value);
                    if (y < chart.chartArea.top || y > chart.chartArea.bottom) return;
                    ctx.save();
                    ctx.setLineDash([5, 5]);
                    ctx.strokeStyle = color;
                    ctx.lineWidth = 1.5;
                    ctx.globalAlpha = 0.5;
                    ctx.beginPath();
                    ctx.moveTo(xStart, y);
                    ctx.lineTo(xEnd, y);
                    ctx.stroke();
                    ctx.globalAlpha = 0.7;
                    ctx.setLineDash([]);
                    ctx.fillStyle = color;
                    ctx.font = '10px Inter, sans-serif';
                    ctx.fillText(label, xEnd - 80, y - 4);
                    ctx.restore();
                };

                drawLine(32, '#ef4444', 'DANGER ≥ 32°C');
                drawLine(30, '#f59e0b', 'WARNING ≥ 30°C');
                drawLine(28, '#3b82f6', 'CAUTION ≥ 28°C');
            }
        };
        Chart.register(dangerPlugin);
    }

    /* ══════════════════════════════════════
       FEATURE 3 — Lightning Safety Timer
    ══════════════════════════════════════ */
    const TOTAL_SECS  = 30 * 60;
    const CIRCUMFERENCE = 2 * Math.PI * 85; // r=85
    let timerInterval = null;
    let remaining = TOTAL_SECS;
    let running   = false;

    const display   = document.getElementById('timer-display');
    const ringFill  = document.getElementById('ring-fill');
    const statusBadge = document.getElementById('timer-status-badge');
    const timerBtn    = document.getElementById('timer-btn');

    function formatTime(s) {
        const m = Math.floor(s / 60).toString().padStart(2, '0');
        const sec = (s % 60).toString().padStart(2, '0');
        return m + ':' + sec;
    }

    function updateRing() {
        const pct = remaining / TOTAL_SECS;
        ringFill.style.strokeDasharray = (pct * CIRCUMFERENCE) + ' ' + CIRCUMFERENCE;

        if (remaining <= 0) {
            ringFill.style.stroke = '#10b981';
        } else if (remaining < TOTAL_SECS * 0.33) {
            ringFill.style.stroke = '#f59e0b';
        } else {
            ringFill.style.stroke = '#ef4444';
        }
    }

    window.toggleTimer = function () {
        if (!running) {
            running = true;
            timerBtn.innerHTML = '<i class="fas fa-pause mr-1"></i> Pause Timer';
            timerBtn.className = 'btn-start-timer';
            statusBadge.className = 'timer-status timer-active';
            statusBadge.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i> Field Suspended — Waiting';

            timerInterval = setInterval(() => {
                if (remaining <= 0) {
                    clearInterval(timerInterval);
                    running = false;
                    statusBadge.className = 'timer-status timer-safe';
                    statusBadge.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Safe to Resume Play!';
                    timerBtn.innerHTML = '<i class="fas fa-bolt mr-1"></i> Lightning Detected!';
                    timerBtn.className = 'btn-start-timer';
                    return;
                }
                remaining--;
                display.textContent = formatTime(remaining);
                updateRing();
            }, 1000);
        } else {
            // Pause
            clearInterval(timerInterval);
            running = false;
            timerBtn.innerHTML = '<i class="fas fa-play mr-1"></i> Resume Timer';
            statusBadge.className = 'timer-status timer-warning';
            statusBadge.innerHTML = '<i class="fas fa-pause mr-1"></i> Timer Paused';
        }
    };

    window.resetTimer = function () {
        clearInterval(timerInterval);
        running   = false;
        remaining = TOTAL_SECS;
        display.textContent = formatTime(TOTAL_SECS);
        updateRing();
        timerBtn.innerHTML = '<i class="fas fa-bolt mr-1"></i> Lightning Detected!';
        timerBtn.className = 'btn-start-timer';
        statusBadge.className = 'timer-status timer-safe';
        statusBadge.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Field Is Safe to Play';
    };

    updateRing();

    // Auto-start if current alert is danger
    @if(in_array($level, ['danger']))
        toggleTimer();
    @endif

    /* ══════════════════════════════════════
       FEATURE 4 — Radar Zone Map (Leaflet)
    ══════════════════════════════════════ */
    const venueLat = parseFloat('{{ $lat }}');
    const venueLng = parseFloat('{{ $lng }}');

    const radarMap = L.map('radar-map', { zoomControl: true, attributionControl: false }).setView([venueLat, venueLng], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        opacity: 0.6
    }).addTo(radarMap);

    // Danger zone (10 km) — red
    L.circle([venueLat, venueLng], {
        radius: 10000, color: '#ef4444', fillColor: '#ef4444', fillOpacity: 0.07, weight: 1.5, dashArray: '5 5'
    }).addTo(radarMap);

    // Warning zone (15 km) — amber
    L.circle([venueLat, venueLng], {
        radius: 15000, color: '#f59e0b', fillColor: '#f59e0b', fillOpacity: 0.05, weight: 1.5, dashArray: '5 5'
    }).addTo(radarMap);

    // Caution zone (20 km) — blue
    L.circle([venueLat, venueLng], {
        radius: 20000, color: '#3b82f6', fillColor: '#3b82f6', fillOpacity: 0.04, weight: 1.5, dashArray: '5 5'
    }).addTo(radarMap);

    // Venue marker
    const venueIcon = L.divIcon({
        className: '',
        html: `<div style="
            width:34px; height:34px; border-radius:50%;
            background:rgba(16,185,129,0.9); border:3px solid rgba(16,185,129,0.4);
            display:flex; align-items:center; justify-content:center;
            color:#fff; font-size:14px;
            box-shadow:0 0 20px rgba(16,185,129,0.6);
        "><i class="fas fa-flag"></i></div>`,
        iconSize: [34, 34], iconAnchor: [17, 17]
    });

    L.marker([venueLat, venueLng], { icon: venueIcon }).addTo(radarMap)
        .bindPopup(`<b>{{ $selectedTournament?->venue_name ?? $selectedTournament?->venue ?? 'Main Venue' }}</b><br>Lat: ${venueLat.toFixed(4)}, Lng: ${venueLng.toFixed(4)}`)
        .openPopup();

    @if($latestLog && $latestLog->lightning_risk && $latestLog->lightning_risk < 50)
        // Show approximate lightning strike position (random direction from venue at detected distance)
        const angle = Math.random() * 2 * Math.PI;
        const distM = {{ $latestLog->lightning_risk }} * 1000;
        const dLat  = (distM / 111000) * Math.cos(angle);
        const dLng  = (distM / (111000 * Math.cos(venueLat * Math.PI / 180))) * Math.sin(angle);

        const lightningIcon = L.divIcon({
            className: '',
            html: `<div style="
                font-size:22px; color:#f59e0b;
                filter: drop-shadow(0 0 8px rgba(245,158,11,0.9));
                animation: blink 1s infinite;
            ">⚡</div>`,
            iconSize: [30, 30], iconAnchor: [15, 15]
        });

        L.marker([venueLat + dLat, venueLng + dLng], { icon: lightningIcon }).addTo(radarMap)
            .bindPopup(`<b>⚡ Lightning Detected</b><br>~{{ $latestLog->lightning_risk }} km from venue`);
    @endif

    setTimeout(() => radarMap.invalidateSize(), 200);
});
</script>
<style>
@keyframes blink { 0%,100%{opacity:1;} 50%{opacity:0.3;} }
</style>
@endpush