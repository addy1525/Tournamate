@extends('layouts.dashboard')

@section('title', 'Browse Tournaments')
@section('page-title', 'Browse Tournaments')

@push('styles')
<style>
    /* ─── Google Font ─────────────────────────────────────────────── */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

    * { font-family: 'Inter', sans-serif; box-sizing: border-box; }

    /* ─── Page Wrapper ────────────────────────────────────────────── */
    .bt-page { padding-bottom: 3rem; }

    /* ─── Hero Header ─────────────────────────────────────────────── */
    .bt-hero {
        background: linear-gradient(135deg, rgba(0,168,107,0.12) 0%, rgba(0,132,255,0.08) 50%, rgba(124,58,237,0.06) 100%);
        border: 1px solid rgba(0,168,107,0.2);
        border-radius: 20px;
        padding: 1.75rem 2rem;
        margin-bottom: 1.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1.25rem;
        position: relative;
        overflow: hidden;
    }

    .bt-hero::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 180px; height: 180px;
        background: radial-gradient(circle, rgba(0,168,107,0.12), transparent 70%);
        pointer-events: none;
    }

    .bt-hero-left h1 {
        font-size: 1.6rem;
        font-weight: 800;
        color: #fff;
        margin: 0 0 4px;
    }

    .bt-hero-left p {
        font-size: 0.88rem;
        color: rgba(255,255,255,0.5);
        margin: 0;
    }

    .bt-hero-stats {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .bt-stat {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        padding: 0.6rem 1.1rem;
        text-align: center;
        min-width: 80px;
    }

    .bt-stat-val {
        font-size: 1.5rem;
        font-weight: 800;
        line-height: 1;
        color: #34d399;
    }

    .bt-stat-val.blue { color: #60a5fa; }
    .bt-stat-val.amber { color: #fbbf24; }

    .bt-stat-lbl {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: rgba(255,255,255,0.4);
        margin-top: 3px;
    }

    /* ─── Team Banner ─────────────────────────────────────────────── */
    .team-banner {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: rgba(0,168,107,0.08);
        border: 1px solid rgba(0,168,107,0.25);
        border-radius: 14px;
        padding: 0.9rem 1.25rem;
        margin-bottom: 1.5rem;
    }

    .team-banner-icon {
        width: 44px; height: 44px;
        background: rgba(0,168,107,0.18);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: #34d399;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .team-banner-name {
        font-weight: 700;
        color: #fff;
        font-size: 0.95rem;
    }

    .team-banner-sub {
        font-size: 0.78rem;
        color: rgba(255,255,255,0.45);
        margin-top: 2px;
    }

    /* ─── Toolbar ─────────────────────────────────────────────────── */
    .bt-toolbar {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .bt-search-wrap {
        position: relative;
        flex: 1;
        min-width: 200px;
        max-width: 340px;
    }

    .bt-search-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255,255,255,0.3);
        font-size: 0.85rem;
        pointer-events: none;
    }

    .bt-search {
        width: 100%;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 0.6rem 0.75rem 0.6rem 2.25rem;
        color: #fff;
        font-size: 0.875rem;
        outline: none;
        transition: border-color 0.2s;
    }

    .bt-search::placeholder { color: rgba(255,255,255,0.3); }
    .bt-search:focus { border-color: rgba(0,168,107,0.5); }

    /* Filter pills */
    .bt-filters {
        display: flex;
        gap: 0.4rem;
        flex-wrap: wrap;
    }

    .bt-filter-pill {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        padding: 0.45rem 0.85rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: rgba(255,255,255,0.5);
        cursor: pointer;
        transition: all 0.2s;
        user-select: none;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .bt-filter-pill:hover {
        border-color: rgba(255,255,255,0.2);
        color: rgba(255,255,255,0.8);
    }

    .bt-filter-pill.active {
        background: rgba(0,168,107,0.2);
        border-color: rgba(0,168,107,0.45);
        color: #34d399;
    }

    .bt-filter-pill.active.blue {
        background: rgba(59,130,246,0.18);
        border-color: rgba(59,130,246,0.4);
        color: #60a5fa;
    }

    .bt-filter-pill.active.amber {
        background: rgba(251,146,60,0.18);
        border-color: rgba(251,146,60,0.4);
        color: #fb923c;
    }

    /* Sort */
    .bt-sort {
        margin-left: auto;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 0.5rem 0.85rem;
        color: rgba(255,255,255,0.5);
        font-size: 0.78rem;
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .bt-sort:focus { border-color: rgba(255,255,255,0.25); color: #fff; }

    /* Results count */
    .bt-count {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.35);
        margin-bottom: 1rem;
    }

    .bt-count strong { color: rgba(255,255,255,0.7); }

    /* ─── Grid ────────────────────────────────────────────────────── */
    .bt-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 1.25rem;
    }

    @media (max-width: 768px) {
        .bt-grid { grid-template-columns: 1fr; }
        .bt-toolbar { flex-direction: column; align-items: stretch; }
        .bt-sort { margin-left: 0; }
        .bt-search-wrap { max-width: 100%; }
    }

    /* ─── Tournament Card ─────────────────────────────────────────── */
    .tc {
        background: rgba(15,23,42,0.75);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 18px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        position: relative;
    }

    .tc:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(0,0,0,0.4);
        border-color: rgba(255,255,255,0.14);
    }

    /* Status accent bar */
    .tc-accent {
        height: 4px;
        width: 100%;
    }
    .tc-accent.open     { background: linear-gradient(90deg, #00a86b, #34d399); }
    .tc-accent.closing  { background: linear-gradient(90deg, #f59e0b, #fb923c); }
    .tc-accent.full     { background: linear-gradient(90deg, #ef4444, #b91c1c); }
    .tc-accent.closed   { background: linear-gradient(90deg, #475569, #64748b); }
    .tc-accent.registered { background: linear-gradient(90deg, #3b82f6, #60a5fa); }

    /* Card header */
    .tc-head {
        padding: 1.1rem 1.25rem 0;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem;
    }

    .tc-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .tc-icon.open     { background: rgba(0,168,107,0.15); color: #34d399; }
    .tc-icon.closing  { background: rgba(245,158,11,0.15); color: #fbbf24; }
    .tc-icon.full     { background: rgba(239,68,68,0.15);  color: #f87171; }
    .tc-icon.closed   { background: rgba(100,116,139,0.1); color: #94a3b8; }
    .tc-icon.registered { background: rgba(59,130,246,0.15); color: #60a5fa; }

    .tc-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        justify-content: flex-end;
        margin-top: 2px;
    }

    .tb {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .tb-open     { background: rgba(0,168,107,0.15); color: #34d399; border: 1px solid rgba(0,168,107,0.3); }
    .tb-closing  { background: rgba(251,146,60,0.15); color: #fb923c; border: 1px solid rgba(251,146,60,0.3); animation: pulse-b 2s infinite; }
    .tb-full     { background: rgba(239,68,68,0.15);  color: #f87171; border: 1px solid rgba(239,68,68,0.3); }
    .tb-closed   { background: rgba(100,116,139,0.12); color: #94a3b8; border: 1px solid rgba(100,116,139,0.25); }
    .tb-registered { background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.3); }

    @keyframes pulse-b {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }

    /* Card body */
    .tc-body {
        padding: 0.75rem 1.25rem 1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .tc-name {
        font-size: 1.05rem;
        font-weight: 800;
        color: #fff;
        line-height: 1.35;
        margin: 0;
    }

    /* Info row */
    .tc-info-row {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .tc-info-chip {
        display: flex;
        align-items: center;
        gap: 5px;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 8px;
        padding: 0.3rem 0.6rem;
        font-size: 0.75rem;
        color: rgba(255,255,255,0.55);
        flex-shrink: 0;
    }

    .tc-info-chip i {
        font-size: 0.7rem;
        color: #34d399;
    }

    .tc-info-chip strong {
        color: rgba(255,255,255,0.8);
        font-weight: 600;
    }

    /* Category pills */
    .tc-cats {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .tc-cat {
        background: rgba(0,132,255,0.1);
        color: #60a5fa;
        border: 1px solid rgba(0,132,255,0.2);
        border-radius: 20px;
        padding: 2px 9px;
        font-size: 0.7rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .tc-cat.done {
        background: rgba(0,168,107,0.12);
        color: #34d399;
        border-color: rgba(0,168,107,0.3);
    }

    /* Registered strip */
    .tc-registered-strip {
        display: flex;
        align-items: center;
        gap: 7px;
        background: rgba(59,130,246,0.08);
        border: 1px solid rgba(59,130,246,0.2);
        border-radius: 9px;
        padding: 0.45rem 0.75rem;
        font-size: 0.76rem;
        color: #93c5fd;
    }

    /* Deadline warning */
    .tc-deadline-warn {
        display: flex;
        align-items: center;
        gap: 7px;
        background: rgba(251,146,60,0.08);
        border: 1px solid rgba(251,146,60,0.22);
        border-radius: 9px;
        padding: 0.4rem 0.75rem;
        font-size: 0.76rem;
        color: #fb923c;
        font-weight: 600;
    }

    /* Fee row */
    .tc-fee-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(0,168,107,0.06);
        border: 1px solid rgba(0,168,107,0.14);
        border-radius: 10px;
        padding: 0.55rem 0.9rem;
    }

    .tc-fee-label {
        font-size: 0.72rem;
        color: rgba(255,255,255,0.4);
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .tc-fee-amount {
        font-size: 1.15rem;
        font-weight: 800;
        color: #34d399;
    }

    .tc-fee-per {
        font-size: 0.65rem;
        color: rgba(255,255,255,0.35);
        margin-top: 1px;
    }

    /* Capacity bar */
    .tc-cap-wrap {}
    .tc-cap-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }
    .tc-cap-lbl { font-size: 0.7rem; color: rgba(255,255,255,0.35); text-transform: uppercase; letter-spacing: 0.4px; }
    .tc-cap-cnt { font-size: 0.78rem; font-weight: 700; color: rgba(255,255,255,0.7); }
    .tc-cap-track { height: 5px; background: rgba(255,255,255,0.07); border-radius: 10px; overflow: hidden; }
    .tc-cap-fill  { height: 100%; border-radius: 10px; transition: width 0.8s ease; }
    .fill-ok   { background: linear-gradient(90deg, #00a86b, #34d399); }
    .fill-warn { background: linear-gradient(90deg, #f59e0b, #fb923c); }
    .fill-full { background: linear-gradient(90deg, #ef4444, #b91c1c); }

    /* Card footer action */
    .tc-footer {
        padding: 0 1.25rem 1.25rem;
    }

    .tc-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        font-size: 0.88rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.25s ease;
        border: none;
        cursor: pointer;
    }

    .tc-btn-open {
        background: linear-gradient(135deg, #00a86b, #008a5a);
        color: #fff;
        box-shadow: 0 4px 15px rgba(0,168,107,0.3);
    }
    .tc-btn-open:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 24px rgba(0,168,107,0.45);
        color: #fff; text-decoration: none;
    }

    .tc-btn-registered {
        background: rgba(59,130,246,0.12);
        color: #60a5fa;
        border: 1px solid rgba(59,130,246,0.3);
    }
    .tc-btn-registered:hover {
        background: rgba(59,130,246,0.2);
        color: #93c5fd;
        text-decoration: none;
    }

    .tc-btn-full {
        background: rgba(239,68,68,0.08);
        color: #f87171;
        border: 1px solid rgba(239,68,68,0.2);
        cursor: not-allowed;
    }

    .tc-btn-closed {
        background: rgba(100,116,139,0.08);
        color: #64748b;
        border: 1px solid rgba(100,116,139,0.18);
        cursor: not-allowed;
    }

    /* ─── Empty State ─────────────────────────────────────────────── */
    .bt-empty {
        text-align: center;
        padding: 4rem 2rem;
        background: rgba(255,255,255,0.02);
        border: 1px dashed rgba(255,255,255,0.08);
        border-radius: 20px;
    }
    .bt-empty i { font-size: 3rem; color: rgba(255,255,255,0.15); margin-bottom: 1.25rem; }
    .bt-empty h3 { font-size: 1.2rem; font-weight: 700; color: rgba(255,255,255,0.6); margin-bottom: 0.5rem; }
    .bt-empty p { font-size: 0.875rem; color: rgba(255,255,255,0.3); margin-bottom: 1.5rem; }

    /* Hidden via filter */
    .tc-hidden { display: none !important; }
</style>
@endpush

@section('content')
<div class="bt-page">

    {{-- Flash errors --}}
    @if(session('error'))
        <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:12px;padding:0.9rem 1.25rem;margin-bottom:1.25rem;color:#f87171;display:flex;align-items:center;gap:10px;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div style="background:rgba(0,168,107,0.1);border:1px solid rgba(0,168,107,0.3);border-radius:12px;padding:0.9rem 1.25rem;margin-bottom:1.25rem;color:#34d399;display:flex;align-items:center;gap:10px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- ─── Hero Header ───────────────────────────────────────────── --}}
    @php
        $openCount     = $tournaments->filter(fn($t) => $t->isRegistrationOpen() && !$t->isFull())->count();
        $closingSoon   = $tournaments->filter(fn($t) => $t->daysUntilDeadline() !== null && $t->daysUntilDeadline() <= 3 && $t->daysUntilDeadline() > 0 && $t->isRegistrationOpen())->count();
        $myEntryCount  = $myRegistrations->count();
    @endphp

    <div class="bt-hero">
        <div class="bt-hero-left">
            <h1><i class="fas fa-trophy" style="color:#34d399;margin-right:0.5rem;"></i>Browse Tournaments</h1>
            <p>Find, explore, and register your team for upcoming rugby tournaments</p>
        </div>
        <div class="bt-hero-stats">
            <div class="bt-stat">
                <div class="bt-stat-val">{{ $openCount }}</div>
                <div class="bt-stat-lbl">Open</div>
            </div>
            @if($closingSoon > 0)
            <div class="bt-stat">
                <div class="bt-stat-val amber">{{ $closingSoon }}</div>
                <div class="bt-stat-lbl">Closing Soon</div>
            </div>
            @endif
            <div class="bt-stat">
                <div class="bt-stat-val blue">{{ $myEntryCount }}</div>
                <div class="bt-stat-lbl">My Entries</div>
            </div>
            <div class="bt-stat">
                <div class="bt-stat-val" style="color:rgba(255,255,255,0.7);">{{ $tournaments->count() }}</div>
                <div class="bt-stat-lbl">Total</div>
            </div>
        </div>
    </div>

    {{-- ─── Team Banner ────────────────────────────────────────────── --}}
    @if($myTeam)
    <div class="team-banner">
        <div class="team-banner-icon"><i class="fas fa-shield-alt"></i></div>
        <div style="flex:1;">
            <div class="team-banner-name">{{ $myTeam->name }}</div>
            <div class="team-banner-sub">Your registered team · Manager: {{ $myTeam->manager_name ?? Auth::user()->name }}</div>
        </div>
        @if($myEntryCount > 0)
        <span style="font-size:0.8rem;color:#60a5fa;font-weight:600;background:rgba(59,130,246,0.12);border:1px solid rgba(59,130,246,0.25);padding:5px 12px;border-radius:20px;white-space:nowrap;">
            <i class="fas fa-check-circle"></i> {{ $myEntryCount }} Active {{ Str::plural('Entry', $myEntryCount) }}
        </span>
        @endif
    </div>
    @else
    <div class="team-banner" style="background:rgba(251,146,60,0.07);border-color:rgba(251,146,60,0.25);">
        <div class="team-banner-icon" style="background:rgba(251,146,60,0.15);color:#fb923c;"><i class="fas fa-info-circle"></i></div>
        <div style="flex:1;">
            <div class="team-banner-name" style="color:#fb923c;">No Team Yet</div>
            <div class="team-banner-sub">A team will be created when you register for your first tournament.</div>
        </div>
    </div>
    @endif

    {{-- ─── Toolbar: Search + Filters ──────────────────────────────── --}}
    <div class="bt-toolbar">
        {{-- Search --}}
        <div class="bt-search-wrap">
            <i class="fas fa-search"></i>
            <input type="text" id="btSearch" class="bt-search" placeholder="Search tournament or venue…">
        </div>

        {{-- Filter pills --}}
        <div class="bt-filters">
            <div class="bt-filter-pill active" data-filter="all" onclick="setFilter(this,'all')">
                <i class="fas fa-th-large"></i> All
            </div>
            <div class="bt-filter-pill" data-filter="open" onclick="setFilter(this,'open')">
                <i class="fas fa-circle" style="font-size:0.5rem;"></i> Open
            </div>
            <div class="bt-filter-pill" data-filter="closing" onclick="setFilter(this,'closing')">
                <i class="fas fa-fire"></i> Closing Soon
            </div>
            <div class="bt-filter-pill" data-filter="registered" onclick="setFilter(this,'registered')">
                <i class="fas fa-check"></i> My Entries
            </div>
            <div class="bt-filter-pill" data-filter="full" onclick="setFilter(this,'full')">
                <i class="fas fa-ban"></i> Full
            </div>
        </div>

        {{-- Sort --}}
        <select class="bt-sort" id="btSort" onchange="doSort()">
            <option value="date-asc">Date ↑ (Earliest)</option>
            <option value="date-desc">Date ↓ (Latest)</option>
            <option value="name-asc">Name A–Z</option>
            <option value="fee-asc">Fee ↑ (Lowest)</option>
        </select>
    </div>

    {{-- Results count --}}
    <div class="bt-count" id="btCount"></div>

    {{-- ─── Tournament Grid ─────────────────────────────────────────── --}}
    @if($tournaments->count() > 0)
    <div class="bt-grid" id="btGrid">

        @foreach($tournaments as $t)
        @php
            $isFull           = $t->isFull();
            $isOpen           = $t->isRegistrationOpen();
            $daysLeft         = $t->daysUntilDeadline();
            $spotsLeft        = $t->spotsRemaining();
            $confirmedCnt     = $t->confirmed_count ?? 0;
            $isClosingSoon    = $daysLeft !== null && $daysLeft <= 3 && $daysLeft > 0 && $isOpen;
            $isDeadlinePassed = $t->registration_deadline && now()->isAfter($t->registration_deadline);
            $myEntries        = $myRegistrations->get($t->id, collect());
            $isRegistered     = $myEntries->count() > 0;
            $fillPct          = $t->max_teams ? min(100, round(($confirmedCnt / $t->max_teams) * 100)) : 0;
            $fillClass        = $fillPct >= 100 ? 'fill-full' : ($fillPct >= 75 ? 'fill-warn' : 'fill-ok');
            $catList          = $t->categories ? array_map('trim', explode(',', $t->categories)) : [];
            $registeredCats   = $myEntries->pluck('registered_category')->toArray();

            // Determine card state
            if ($isRegistered)        $state = 'registered';
            elseif ($isFull)          $state = 'full';
            elseif ($isDeadlinePassed || $t->status === 'registration_closed') $state = 'closed';
            elseif ($isClosingSoon)   $state = 'closing';
            elseif ($isOpen)          $state = 'open';
            else                      $state = 'closed';

            $tDate = $t->start_date ?? $t->tournament_date ?? null;
            $tVenue = $t->venue_name ?? $t->venue ?? null;
        @endphp

        <div class="tc"
             data-state="{{ $state }}"
             data-name="{{ strtolower($t->name) }}"
             data-venue="{{ strtolower($tVenue ?? '') }}"
             data-date="{{ $tDate ? $tDate->format('Y-m-d') : '' }}"
             data-fee="{{ $t->fee ?? 0 }}"
             id="tc-{{ $t->id }}">

            {{-- Status accent bar --}}
            <div class="tc-accent {{ $state }}"></div>

            {{-- Card Header --}}
            <div class="tc-head">
                <div class="tc-icon {{ $state }}">
                    @if($state === 'open')         <i class="fas fa-trophy"></i>
                    @elseif($state === 'closing')  <i class="fas fa-fire"></i>
                    @elseif($state === 'full')     <i class="fas fa-users-slash"></i>
                    @elseif($state === 'closed')   <i class="fas fa-lock"></i>
                    @elseif($state === 'registered') <i class="fas fa-shield-check" style="font-size:1rem;"></i>
                    @endif
                </div>

                <div class="tc-badges">
                    @if($state === 'open')
                        <span class="tb tb-open"><i class="fas fa-circle" style="font-size:0.4rem;"></i> Open</span>
                    @elseif($state === 'closing')
                        <span class="tb tb-closing"><i class="fas fa-fire"></i> Closing Soon</span>
                    @elseif($state === 'full')
                        <span class="tb tb-full"><i class="fas fa-ban"></i> Full</span>
                    @elseif($state === 'closed')
                        <span class="tb tb-closed"><i class="fas fa-lock"></i> Closed</span>
                    @endif
                    @if($isRegistered)
                        <span class="tb tb-registered"><i class="fas fa-check"></i> Registered</span>
                    @endif
                </div>
            </div>

            {{-- Card Body --}}
            <div class="tc-body">
                <div class="tc-name">{{ $t->name }}</div>

                {{-- Info chips row --}}
                <div class="tc-info-row">
                    @if($tDate)
                    <div class="tc-info-chip">
                        <i class="fas fa-calendar-alt"></i>
                        <strong>{{ $tDate->format('d M Y') }}</strong>
                    </div>
                    @endif
                    @if($tVenue)
                    <div class="tc-info-chip">
                        <i class="fas fa-map-marker-alt"></i>
                        <strong>{{ Str::limit($tVenue, 25) }}</strong>
                    </div>
                    @endif
                    @if($t->registration_deadline)
                    <div class="tc-info-chip">
                        <i class="fas fa-hourglass-half" style="color:{{ $isClosingSoon ? '#fb923c' : '#34d399' }};"></i>
                        <span>Deadline: <strong>{{ $t->registration_deadline->format('d M, H:i') }}</strong></span>
                    </div>
                    @endif
                </div>

                {{-- Categories --}}
                @if(count($catList) > 0)
                <div class="tc-cats">
                    @foreach($catList as $cat)
                    <span class="tc-cat {{ in_array($cat, $registeredCats) ? 'done' : '' }}">
                        @if(in_array($cat, $registeredCats))
                            <i class="fas fa-check" style="font-size:0.55rem;"></i>
                        @endif
                        {{ $cat }}
                    </span>
                    @endforeach
                </div>
                @endif

                {{-- Registered strip --}}
                @if($isRegistered)
                <div class="tc-registered-strip">
                    <i class="fas fa-shield-alt"></i>
                    <span>{{ $myTeam->name }} entered: <strong>{{ implode(', ', $registeredCats) }}</strong></span>
                </div>
                @endif

                {{-- Closing soon warning --}}
                @if($isClosingSoon)
                <div class="tc-deadline-warn">
                    <i class="fas fa-clock"></i>
                    Registration closes in <strong>{{ $daysLeft }} {{ Str::plural('day', $daysLeft) }}</strong>! Register now.
                </div>
                @endif

                {{-- Fee row --}}
                @if($t->fee)
                <div class="tc-fee-row">
                    <div>
                        <div class="tc-fee-label">Registration Fee</div>
                        <div class="tc-fee-per">Per category entry</div>
                    </div>
                    <div class="tc-fee-amount">RM {{ number_format($t->fee, 2) }}</div>
                </div>
                @endif

                {{-- Capacity bar --}}
                @if($t->max_teams)
                <div class="tc-cap-wrap">
                    <div class="tc-cap-header">
                        <span class="tc-cap-lbl"><i class="fas fa-users" style="margin-right:4px;"></i> Team Slots</span>
                        <span class="tc-cap-cnt">
                            {{ $confirmedCnt }} / {{ $t->max_teams }}
                            @if($spotsLeft !== null && !$isFull)
                                <span style="color:rgba(255,255,255,0.3);font-weight:400;"> · {{ $spotsLeft }} left</span>
                            @endif
                        </span>
                    </div>
                    <div class="tc-cap-track">
                        <div class="tc-cap-fill {{ $fillClass }}" style="width:{{ $fillPct }}%;"></div>
                    </div>
                </div>
                @endif

            </div>{{-- .tc-body --}}

            {{-- Footer CTA --}}
            <div class="tc-footer">
                @if($isFull)
                    <div class="tc-btn tc-btn-full">
                        <i class="fas fa-ban"></i> Registration Full
                    </div>
                @elseif($isDeadlinePassed || $t->status === 'registration_closed')
                    <div class="tc-btn tc-btn-closed">
                        <i class="fas fa-lock"></i> Registration Closed
                    </div>
                @elseif($isRegistered && count($catList) > 0 && count($registeredCats) >= count($catList))
                    <div class="tc-btn tc-btn-registered" style="cursor:default;">
                        <i class="fas fa-check-circle"></i> All Categories Registered
                    </div>
                @elseif($isOpen)
                    <a href="{{ route('manager.tournaments.register', $t->id) }}" class="tc-btn tc-btn-open">
                        <i class="fas fa-rocket"></i>
                        {{ $isRegistered ? 'Register for Another Category' : 'Register My Team' }}
                    </a>
                @else
                    <div class="tc-btn tc-btn-closed">
                        <i class="fas fa-lock"></i> Not Available
                    </div>
                @endif
            </div>

        </div>{{-- .tc --}}
        @endforeach

    </div>{{-- .bt-grid --}}

    {{-- No-results message (shown by JS) --}}
    <div id="btNoResults" class="bt-empty" style="display:none;">
        <i class="fas fa-search"></i>
        <h3>No Tournaments Found</h3>
        <p>Try adjusting your search or filter.</p>
    </div>

    @else
    <div class="bt-empty">
        <i class="fas fa-calendar-times"></i>
        <h3>No Tournaments Available</h3>
        <p>There are currently no tournaments open for registration.<br>Check back soon or contact the administrator.</p>
        <a href="{{ route('manager.dashboard') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
(function() {
    const grid       = document.getElementById('btGrid');
    const noResults  = document.getElementById('btNoResults');
    const countEl    = document.getElementById('btCount');
    const searchInput = document.getElementById('btSearch');
    let currentFilter = 'all';

    function getCards() {
        return grid ? Array.from(grid.querySelectorAll('.tc')) : [];
    }

    function updateCount() {
        const visible = getCards().filter(c => !c.classList.contains('tc-hidden')).length;
        const total   = getCards().length;
        countEl.innerHTML = `Showing <strong>${visible}</strong> of <strong>${total}</strong> tournaments`;
        noResults.style.display = visible === 0 ? 'block' : 'none';
    }

    function applyFilters() {
        const q = (searchInput ? searchInput.value.toLowerCase().trim() : '');
        getCards().forEach(card => {
            const state  = card.dataset.state;
            const name   = card.dataset.name  || '';
            const venue  = card.dataset.venue || '';
            const matchFilter =
                currentFilter === 'all'        ? true :
                currentFilter === 'open'       ? (state === 'open') :
                currentFilter === 'closing'    ? (state === 'closing') :
                currentFilter === 'registered' ? (state === 'registered') :
                currentFilter === 'full'       ? (state === 'full') :
                true;
            const matchSearch = !q || name.includes(q) || venue.includes(q);
            card.classList.toggle('tc-hidden', !(matchFilter && matchSearch));
        });
        updateCount();
    }

    // Filter pills
    window.setFilter = function(el, filter) {
        currentFilter = filter;
        document.querySelectorAll('.bt-filter-pill').forEach(p => {
            p.classList.remove('active','blue','amber');
        });
        el.classList.add('active');
        if (filter === 'registered') el.classList.add('blue');
        if (filter === 'closing')    el.classList.add('amber');
        applyFilters();
    };

    // Search
    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }

    // Sort
    window.doSort = function() {
        if (!grid) return;
        const sort  = document.getElementById('btSort').value;
        const cards = getCards();
        cards.sort((a, b) => {
            if (sort === 'date-asc')  return (a.dataset.date || 'z').localeCompare(b.dataset.date || 'z');
            if (sort === 'date-desc') return (b.dataset.date || '').localeCompare(a.dataset.date || '');
            if (sort === 'name-asc')  return (a.dataset.name || '').localeCompare(b.dataset.name || '');
            if (sort === 'fee-asc')   return parseFloat(a.dataset.fee) - parseFloat(b.dataset.fee);
            return 0;
        });
        cards.forEach(c => grid.appendChild(c));
        applyFilters();
    };

    // Initial count
    updateCount();
})();
</script>
@endpush