@extends('layouts.dashboard')

@section('title', 'Match Management')
@section('page-title', 'Match Management: ' . $tournament->name)

@push('styles')
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
<style>
    /* Premium SaaS Dark Theme Settings */
    :root {
        --font-sans: 'Inter', sans-serif;
        --zinc-900: #18181b;
        --zinc-800: #27272a;
        --zinc-700: #3f3f46;
        --zinc-600: #52525b;
        --zinc-500: #71717a;
        --zinc-400: #a1a1aa;
        --zinc-300: #d4d4d8;
        --zinc-100: #f4f4f5;
        --zinc-50: #fafafa;
        
        --brand-primary: #10b981; /* Emerald 500 */
        --brand-primary-hover: #059669; /* Emerald 600 */
        --brand-danger: #ef4444; /* Red 500 */
        --brand-warning: #f59e0b; /* Amber 500 */
        --brand-info: #3b82f6; /* Blue 500 */
        
        --glass-border: rgba(255, 255, 255, 0.08);
        --glass-bg: rgba(39, 39, 42, 0.6); /* Zinc 800 with opacity */
        --glass-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
    }

    body {
        font-family: var(--font-sans);
        background-color: var(--zinc-900);
        color: var(--zinc-300);
    }

    /* Typography */
    h1, h2, h3, h4, h5, h6 {
        color: var(--zinc-50);
        font-weight: 600;
        letter-spacing: -0.025em;
    }

    /* Glassmorphism Cards */
    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-radius: 1rem; /* rounded-2xl */
        box-shadow: var(--glass-shadow);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
    }
    
    .glass-card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
        border-color: rgba(255, 255, 255, 0.12);
    }

    .glass-header {
        background: rgba(255, 255, 255, 0.03);
        border-bottom: 1px solid var(--glass-border);
        padding: 1.25rem 1.5rem;
    }
    
    .glass-body {
        padding: 1.5rem;
    }

    /* Stats Grid */
    .stat-card {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .stat-icon.primary { background: rgba(16, 185, 129, 0.1); color: var(--brand-primary); }
    .stat-icon.info { background: rgba(59, 130, 246, 0.1); color: var(--brand-info); }
    .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: var(--brand-warning); }
    
    .stat-content h4 { font-size: 0.875rem; color: var(--zinc-400); margin: 0; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; }
    .stat-content .stat-value { font-size: 1.75rem; color: var(--zinc-50); font-weight: 700; line-height: 1.2; margin-top: 0.25rem; }

    /* Forms & Inputs */
    .premium-input {
        background: rgba(0, 0, 0, 0.2) !important;
        border: 1px solid var(--glass-border) !important;
        color: var(--zinc-100) !important;
        border-radius: 0.5rem;
        padding: 0.625rem 1rem;
        transition: all 0.2s ease;
    }
    .premium-input:focus {
        background: rgba(0, 0, 0, 0.3) !important;
        border-color: var(--brand-info) !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
        color: white !important;
    }
    .premium-label {
        font-size: 0.875rem;
        color: var(--zinc-400);
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    /* Buttons */
    .btn-premium {
        border-radius: 0.5rem;
        font-weight: 500;
        letter-spacing: 0.025em;
        padding: 0.625rem 1.25rem;
        transition: all 0.2s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .btn-premium-success {
        background: linear-gradient(135deg, var(--brand-primary), #047857);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }
    .btn-premium-success:hover { background: linear-gradient(135deg, #047857, #065f46); color: white; transform: translateY(-1px); }
    
    .btn-premium-danger {
        background: transparent;
        border: 1px solid var(--brand-danger);
        color: var(--brand-danger);
    }
    .btn-premium-danger:hover { background: rgba(239, 68, 68, 0.1); color: var(--brand-danger); transform: translateY(-1px); }

    .btn-premium-warning {
        background: linear-gradient(135deg, var(--brand-warning), #d97706);
        color: #fff;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }
    .btn-premium-warning:hover { background: linear-gradient(135deg, #d97706, #b45309); color: white; transform: translateY(-1px); }
    
    .btn-action-icon {
        width: 32px; height: 32px;
        padding: 0;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 0.375rem;
        border: 1px solid var(--glass-border);
        background: rgba(255,255,255,0.05);
        color: var(--zinc-300);
        transition: all 0.2s;
    }
    .btn-action-icon:hover { background: rgba(255,255,255,0.1); color: white; }
    .btn-action-icon.edit:hover { border-color: var(--brand-info); color: var(--brand-info); }
    .btn-action-icon.delete:hover { border-color: var(--brand-danger); color: var(--brand-danger); }

    /* Pool Lists */
    .pool-list-item {
        background: transparent;
        border: none;
        border-bottom: 1px solid var(--glass-border);
        padding: 0.75rem 0;
        color: var(--zinc-300);
        display: flex;
        align-items: center;
    }
    .pool-list-item:last-child { border-bottom: none; }
    .pool-list-item::before {
        content: '';
        display: inline-block;
        width: 6px; height: 6px;
        border-radius: 50%;
        background: var(--zinc-600);
        margin-right: 0.75rem;
    }

    /* Custom Table */
    .premium-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .premium-table th {
        background: rgba(0,0,0,0.2);
        color: var(--zinc-400);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--glass-border);
    }
    .premium-table td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--glass-border);
        color: var(--zinc-100);
        vertical-align: middle;
        font-size: 0.9rem;
    }
    .premium-table tbody tr { transition: background 0.2s; }
    .premium-table tbody tr:hover { background: rgba(255,255,255,0.03); }
    .premium-table tbody tr:last-child td { border-bottom: none; }

    /* Glowing Badges */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .status-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
    }
    
    .status-draft { background: rgba(161, 161, 170, 0.1); color: var(--zinc-300); border: 1px solid rgba(161, 161, 170, 0.2); }
    .status-draft .status-dot { background: var(--zinc-400); box-shadow: 0 0 8px var(--zinc-400); }
    
    .status-scheduled { background: rgba(59, 130, 246, 0.1); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.2); }
    .status-scheduled .status-dot { background: #60a5fa; box-shadow: 0 0 8px #60a5fa; }
    
    .status-ongoing { background: rgba(245, 158, 11, 0.1); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.2); }
    .status-ongoing .status-dot { background: #fbbf24; box-shadow: 0 0 8px #fbbf24; }
    
    .status-completed { background: rgba(16, 185, 129, 0.1); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.2); }
    .status-completed .status-dot { background: #34d399; box-shadow: 0 0 8px #34d399; }

    .pool-badge {
        background: rgba(255,255,255,0.1);
        color: var(--zinc-100);
        padding: 0.125rem 0.625rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Custom Scrollbar for Table */
    .premium-scrollbar::-webkit-scrollbar { width: 8px; height: 8px; }
    .premium-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); border-radius: 4px; }
    .premium-scrollbar::-webkit-scrollbar-thumb { background: var(--zinc-600); border-radius: 4px; }
    .premium-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--zinc-500); }

    /* Modal override */
    .premium-modal .modal-content {
        background: var(--zinc-900);
        border: 1px solid var(--glass-border);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        border-radius: 1rem;
    }
    .premium-modal .modal-header { border-bottom: 1px solid var(--glass-border); padding: 1.5rem; }
    .premium-modal .modal-footer { border-top: 1px solid var(--glass-border); padding: 1.5rem; }
    .premium-modal .close { color: var(--zinc-400); text-shadow: none; opacity: 1; }
    .premium-modal .close:hover { color: white; }

    /* Bracket Layout Styles */
    .bracket-container {
        display: flex;
        gap: 2.5rem;
        align-items: stretch;
        justify-content: space-between;
        overflow-x: auto;
        padding: 1.5rem 0.5rem;
    }
    .bracket-column {
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        min-width: 240px;
        flex: 1;
        gap: 1.5rem;
        position: relative;
    }
    .bracket-match-node {
        background: rgba(24, 24, 27, 0.65);
        backdrop-filter: blur(8px);
        border: 1px solid var(--glass-border);
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .bracket-match-node:hover {
        border-color: var(--brand-info);
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.25);
        transform: translateY(-2px);
    }
    .bracket-match-header {
        background: rgba(255, 255, 255, 0.03);
        padding: 6px 12px;
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--zinc-400);
        border-bottom: 1px solid var(--glass-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .bracket-match-team {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 14px;
        font-size: 0.82rem;
        font-weight: 500;
        color: var(--zinc-300);
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    }
    .bracket-match-team:last-child {
        border-bottom: none;
    }
    .bracket-match-team.winner {
        background: rgba(16, 185, 129, 0.08);
        color: white;
        font-weight: 600;
    }
    .bracket-match-team.winner .score {
        color: #34d399;
        font-weight: 700;
    }
    .bracket-match-team .score {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--zinc-500);
        font-family: 'Inter', sans-serif;
    }
    .bracket-column-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--zinc-400);
        margin-bottom: 0.75rem;
        text-align: center;
        letter-spacing: 0.075em;
        background: rgba(255, 255, 255, 0.03);
        padding: 6px 12px;
        border-radius: 6px;
        border: 1px solid var(--glass-border);
    }
    .bracket-sub-section {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        flex: 1;
        justify-content: center;
        padding: 0.5rem 0;
    }
    .bracket-sub-section-title {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--zinc-500);
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
        text-align: center;
    }
</style>
@endpush

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.tournaments.index') }}" class="btn btn-premium" style="background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); color: var(--zinc-300);">
        <i class="fas fa-arrow-left mr-2"></i> Back to Tournaments
    </a>
</div>

<!-- Stats Row -->
@php
    $totalPools = $tournament->pools->count();
    $totalTeamsAssigned = $tournament->pools->flatMap->registrations->count();
    $totalFixtures = $tournament->fixtures->count();
    $publishedFixtures = $tournament->fixtures->where('status', '!=', 'draft')->count();
    $draftFixtures = $tournament->fixtures->where('status', 'draft')->count();
@endphp
<div class="row mb-4">
    <div class="col-md-4">
        <div class="glass-card stat-card glass-card-hover mb-3 mb-md-0">
            <div class="stat-icon info">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="stat-content">
                <h4>Total Pools</h4>
                <div class="stat-value">{{ $totalPools }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="glass-card stat-card glass-card-hover mb-3 mb-md-0">
            <div class="stat-icon primary">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="stat-content">
                <h4>Teams Assigned</h4>
                <div class="stat-value">{{ $totalTeamsAssigned }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="glass-card stat-card glass-card-hover">
            <div class="stat-icon warning">
                <i class="fas fa-stopwatch"></i>
            </div>
            <div class="stat-content">
                <h4>Matches Scheduled</h4>
                <div class="stat-value">
                    {{ $publishedFixtures }}
                    @if($draftFixtures > 0)
                        <span style="font-size: 0.85rem; color: #fbbf24; font-weight: normal; margin-left: 6px;" title="{{ $draftFixtures }} fixtures in draft stage. Click 'Confirm & Publish Drafts' to publish.">
                            (+{{ $draftFixtures }} Draft)
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tab Navigation -->
<ul class="nav nav-pills mb-4 p-2" id="tournament-tabs" role="tablist" style="border: 1px solid var(--glass-border); gap: 0.5rem; background: rgba(22, 33, 50, 0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-radius: 16px; margin-left: 0; margin-right: 0; box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37); height: auto !important;">
    <li class="nav-item m-0">
        <a class="nav-link active px-4 py-2 font-weight-bold" id="pools-tab" data-toggle="pill" href="#tab-pools" role="tab" aria-controls="tab-pools" aria-selected="true" style="border-radius: 8px; color: #fff;">
            <i class="fas fa-layer-group mr-2"></i> 1. Pools Setup
        </a>
    </li>
    <li class="nav-item m-0">
        <a class="nav-link px-4 py-2 font-weight-bold" id="fixtures-tab" data-toggle="pill" href="#tab-fixtures" role="tab" aria-controls="tab-fixtures" aria-selected="false" style="border-radius: 8px; color: #fff;">
            <i class="fas fa-magic mr-2"></i> 2. Fixture Generator
        </a>
    </li>
    <li class="nav-item m-0">
        <a class="nav-link px-4 py-2 font-weight-bold" id="knockout-tab" data-toggle="pill" href="#tab-knockout" role="tab" aria-controls="tab-knockout" aria-selected="false" style="border-radius: 8px; color: #fff;">
            <i class="fas fa-trophy mr-2"></i> 3. Knockout Stage
        </a>
    </li>
    <li class="nav-item m-0">
        <a class="nav-link px-4 py-2 font-weight-bold" id="list-tab" data-toggle="pill" href="#tab-list" role="tab" aria-controls="tab-list" aria-selected="false" style="border-radius: 8px; color: #fff;">
            <i class="fas fa-list-ul mr-2"></i> 4. Match Directory ({{ $totalFixtures }})
        </a>
    </li>
    <li class="nav-item m-0">
        <a class="nav-link px-4 py-2 font-weight-bold" id="standings-tab" data-toggle="pill" href="#tab-standings" role="tab" aria-controls="tab-standings" aria-selected="false" style="border-radius: 8px; color: #fff;">
            <i class="fas fa-list-ol mr-2"></i> 5. Standings
        </a>
    </li>
</ul>

<!-- Tab Content Wrapper -->
<div class="tab-content" id="tournament-tabs-content">
    
    <!-- Tab 1: Pools Setup -->
    <div class="tab-pane active" id="tab-pools" role="tabpanel" aria-labelledby="pools-tab">
        @php
            $totalUnassigned = $unassignedRegistrations->count();
            $hasExistingPools = $tournament->pools->isNotEmpty();
            $hasPoolFixtures = $tournament->fixtures->where('stage', 'Pool Stage')->isNotEmpty();
        @endphp
        <div class="row align-items-start">
            <!-- Left Panel: Controls -->
            <div class="col-lg-4 mb-4">

                {{-- ═══════════════════════════════════════════
                     PRIMARY: AUTO POOL DRAW
                ═══════════════════════════════════════════ --}}
                <div class="glass-card mb-4" style="border: 1px solid rgba(16, 185, 129, 0.25); box-shadow: 0 0 30px rgba(16,185,129,0.08);">
                    <div class="glass-header" style="background: rgba(16,185,129,0.06);">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:34px; height:34px; border-radius:8px; background:rgba(16,185,129,0.15); display:flex; align-items:center; justify-content:center; color:#34d399; font-size:1rem;">
                                <i class="fas fa-random"></i>
                            </div>
                            <div>
                                <h3 class="card-title m-0" style="font-size: 1rem; color: #34d399;">Auto Pool Draw</h3>
                                <p class="m-0" style="font-size: 0.7rem; color: var(--zinc-500); margin-top: 1px !important;">Malaysian Rugby Format</p>
                            </div>
                        </div>
                    </div>

                    @if($hasExistingPools)
                        {{-- Pools already exist — show warning --}}
                        <div class="glass-body">
                            <div style="background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.2); border-radius: 0.6rem; padding: 0.875rem 1rem;">
                                <p class="m-0" style="font-size: 0.82rem; color: #fbbf24; display:flex; align-items:flex-start; gap:8px;">
                                    <i class="fas fa-exclamation-triangle mt-1"></i>
                                    <span>Pools already exist. Please clear all pools below before running a new Auto Draw.</span>
                                </p>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('admin.tournaments.autoPools', $tournament->id) }}" method="POST">
                            @csrf
                            <div class="glass-body">
                                {{-- Team count indicator --}}
                                <div style="background: rgba(0,0,0,0.15); border-radius: 0.6rem; padding: 0.75rem 1rem; margin-bottom: 1.25rem; display:flex; align-items:center; justify-content:space-between; border: 1px solid var(--glass-border);">
                                    <span style="font-size: 0.8rem; color: var(--zinc-400);">
                                        <i class="fas fa-users mr-1" style="color: var(--brand-info);"></i> Available teams
                                    </span>
                                    <span id="unassigned-count-badge" style="font-size: 1.1rem; font-weight: 700; color: {{ $totalUnassigned > 0 ? '#34d399' : '#ef4444' }};">
                                        {{ $totalUnassigned }} team{{ $totalUnassigned != 1 ? 's' : '' }}
                                    </span>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="premium-label" for="num_pools">Number of Pools</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="background:rgba(0,0,0,0.2); border:1px solid var(--glass-border); border-right:none; color:var(--zinc-400);">
                                                <i class="fas fa-layer-group"></i>
                                            </span>
                                        </div>
                                        <select id="num_pools" name="num_pools" class="form-control premium-input" style="border-left:none; border-top-left-radius:0; border-bottom-left-radius:0;" onchange="updatePoolDrawPreview()" {{ $totalUnassigned < 4 ? 'disabled' : '' }}>
                                            <option value="2" {{ $totalUnassigned >= 4  ? '' : 'disabled' }}>2 Pools</option>
                                            <option value="3" {{ $totalUnassigned >= 6  ? '' : 'disabled' }} selected>3 Pools</option>
                                            <option value="4" {{ $totalUnassigned >= 8  ? '' : 'disabled' }}>4 Pools</option>
                                            <option value="5" {{ $totalUnassigned >= 10 ? '' : 'disabled' }}>5 Pools</option>
                                            <option value="6" {{ $totalUnassigned >= 12 ? '' : 'disabled' }}>6 Pools</option>
                                            <option value="7" {{ $totalUnassigned >= 14 ? '' : 'disabled' }}>7 Pools</option>
                                            <option value="8" {{ $totalUnassigned >= 16 ? '' : 'disabled' }}>8 Pools</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="premium-label" for="teams_per_pool">Teams per Pool</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="background:rgba(0,0,0,0.2); border:1px solid var(--glass-border); border-right:none; color:var(--zinc-400);">
                                                <i class="fas fa-users"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="teams_per_pool" name="teams_per_pool"
                                               class="form-control premium-input"
                                               style="border-left:none; border-top-left-radius:0; border-bottom-left-radius:0;"
                                               min="2" max="16"
                                               value="{{ $totalUnassigned > 0 ? (int)floor($totalUnassigned / 3) : 4 }}"
                                               onchange="updatePoolDrawPreview()"
                                               {{ $totalUnassigned < 4 ? 'disabled' : '' }}>
                                    </div>
                                </div>

                                {{-- Live Preview --}}
                                <div id="pool-draw-preview" style="background: rgba(16,185,129,0.05); border: 1px solid rgba(16,185,129,0.15); border-radius: 0.6rem; padding: 0.875rem 1rem; margin-bottom: 1.25rem; font-size: 0.82rem;">
                                    <div style="font-weight: 600; color: #34d399; margin-bottom: 6px; display:flex; align-items:center; gap:6px;">
                                        <i class="fas fa-eye" style="font-size:0.75rem;"></i> Preview Draw
                                    </div>
                                    <div id="preview-text" style="color: var(--zinc-300); line-height: 1.6;"></div>
                                </div>

                                <button type="submit" id="auto-draw-btn" class="btn btn-premium btn-premium-success w-100"
                                        style="background: linear-gradient(135deg, #10b981, #047857); box-shadow: 0 4px 20px rgba(16,185,129,0.35); font-size: 0.95rem; padding: 0.75rem;"
                                        {{ $totalUnassigned < 4 ? 'disabled' : '' }}
                                        onclick="return confirm('Confirm Auto Pool Draw? Teams will be randomly assigned to the configured pools.')">
                                    <i class="fas fa-random mr-2"></i> Generate Random Draw
                                </button>

                                @if($totalUnassigned < 4)
                                    <p class="text-center mt-2 mb-0" style="font-size:0.78rem; color: var(--zinc-500);">
                                        <i class="fas fa-info-circle mr-1"></i> A minimum of 4 teams is required to use Auto Draw.
                                    </p>
                                @endif
                            </div>
                        </form>
                    @endif
                </div>

                {{-- ═══════════════════════════════════════════
                     SECONDARY: MANUAL POOL (Collapsible)
                ═══════════════════════════════════════════ --}}
                <div class="glass-card mb-4">
                    <div class="glass-header" style="cursor:pointer;" data-toggle="collapse" data-target="#manual-pool-collapse" aria-expanded="false">
                        <div class="d-flex align-items-center justify-content-between">
                            <h3 class="card-title m-0" style="font-size: 0.95rem; color: var(--zinc-400);">
                                <i class="fas fa-edit mr-2" style="font-size:0.85rem;"></i> Manual Pool Setup
                            </h3>
                            <i class="fas fa-chevron-down" style="font-size:0.75rem; color:var(--zinc-500); transition: transform 0.2s;" id="manual-chevron"></i>
                        </div>
                        <p class="m-0 mt-1" style="font-size: 0.72rem; color: var(--zinc-600);">Create pools one by one with custom team selection (advanced)</p>
                    </div>
                    <div class="collapse" id="manual-pool-collapse">
                        <form action="{{ route('admin.tournaments.storePools', $tournament->id) }}" method="POST">
                            @csrf
                            <div class="glass-body">
                                <div class="form-group mb-3">
                                    <label class="premium-label" style="font-size:0.8rem;">Pool Name</label>
                                    <input type="text" name="pool_name" class="form-control premium-input" style="font-size:0.85rem;" placeholder="e.g. Pool A" required autocomplete="off">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="premium-label" style="font-size:0.8rem;">Select Teams</label>
                                    <div class="team-selection-area" style="max-height: 200px; overflow-y: auto; padding-right: 6px;">
                                        @forelse($unassignedRegistrations as $reg)
                                            <div class="custom-control custom-checkbox mb-2">
                                                <input type="checkbox" class="custom-control-input" name="team_ids[]" value="{{ $reg->team->id }}" id="mteam_{{ $reg->team->id }}">
                                                <label class="custom-control-label" for="mteam_{{ $reg->team->id }}" style="color: var(--zinc-300); font-size:0.82rem; font-weight: normal; cursor: pointer;">
                                                    {{ $reg->team->name }}
                                                    <span class="text-muted ml-1" style="font-size: 0.75em;">({{ $reg->registered_category ?: 'Open' }})</span>
                                                </label>
                                            </div>
                                        @empty
                                            <div class="p-3 text-center" style="background: rgba(0,0,0,0.1); border-radius: 0.5rem; border: 1px dashed var(--glass-border);">
                                                <span class="text-muted" style="font-size: 0.82rem;">No unassigned teams available.</span>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-premium w-100" style="background:rgba(255,255,255,0.06); border:1px solid var(--glass-border); color:var(--zinc-300); font-size:0.85rem;" {{ $unassignedRegistrations->isEmpty() ? 'disabled' : '' }}>
                                    <i class="fas fa-plus mr-1"></i> Create Pool
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════
                     DANGER ZONE: Clear Pools
                ═══════════════════════════════════════════ --}}
                @if($hasExistingPools)
                    <div class="glass-card" style="border: 1px solid rgba(239,68,68,0.2);">
                        <div class="glass-body" style="padding: 1rem 1.25rem;">
                            <p style="font-size: 0.78rem; color: var(--zinc-500); margin-bottom: 0.75rem;">
                                <i class="fas fa-exclamation-triangle mr-1" style="color: #ef4444;"></i>
                                <strong style="color: var(--zinc-400);">Danger Zone</strong> — Resets all pools and unassigns all teams.
                            </p>
                            <form action="{{ route('admin.tournaments.clearPools', $tournament->id) }}" method="POST"
                                  onsubmit="return confirm('{{ $hasPoolFixtures ? '⚠️ WARNING: Pool fixtures already exist! Clear pool fixtures first, then try again.' : 'Confirm reset all pools? Teams will be returned to unassigned.' }}')">
                                @csrf
                                <button type="submit" class="btn btn-premium btn-premium-danger w-100" style="font-size:0.85rem;" {{ $hasPoolFixtures ? "disabled title='Clear pool fixtures first'" : '' }}>
                                    <i class="fas fa-undo mr-1"></i> Clear All Pools
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

            </div><!-- /col-lg-4 -->

            <!-- Right Panel: Pool Cards -->
            <div class="col-lg-8">
                <div class="row">
                    @forelse($tournament->pools as $pool)
                        @php
                            $poolColors = [
                                'A' => ['bg' => 'rgba(59,130,246,0.08)',  'border' => 'rgba(59,130,246,0.25)',  'badge_bg' => 'rgba(59,130,246,0.12)',  'color' => '#60a5fa'],
                                'B' => ['bg' => 'rgba(16,185,129,0.08)', 'border' => 'rgba(16,185,129,0.25)', 'badge_bg' => 'rgba(16,185,129,0.12)', 'color' => '#34d399'],
                                'C' => ['bg' => 'rgba(245,158,11,0.08)', 'border' => 'rgba(245,158,11,0.25)', 'badge_bg' => 'rgba(245,158,11,0.12)', 'color' => '#fbbf24'],
                                'D' => ['bg' => 'rgba(239,68,68,0.08)',  'border' => 'rgba(239,68,68,0.25)',  'badge_bg' => 'rgba(239,68,68,0.12)',  'color' => '#f87171'],
                            ];
                            $poolLetter = strtoupper(substr(str_replace('Pool ', '', $pool->name), 0, 1));
                            $pc = $poolColors[$poolLetter] ?? ['bg' => 'rgba(139,92,246,0.08)', 'border' => 'rgba(139,92,246,0.25)', 'badge_bg' => 'rgba(139,92,246,0.12)', 'color' => '#a78bfa'];
                        @endphp
                        <div class="col-md-6 mb-4">
                            <div class="glass-card h-100" style="border: 1px solid {{ $pc['border'] }}; background: {{ $pc['bg'] }}; transition: transform 0.2s ease, box-shadow 0.2s ease;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 35px rgba(0,0,0,0.35)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                                <div class="glass-header d-flex justify-content-between align-items-center" style="border-bottom-color: {{ $pc['border'] }};">
                                    <h3 class="card-title m-0 d-flex align-items-center gap-2" style="font-size: 1.05rem; color: {{ $pc['color'] }};">
                                        <span style="width:28px; height:28px; border-radius:7px; background:{{ $pc['badge_bg'] }}; display:inline-flex; align-items:center; justify-content:center; font-size:0.8rem; font-weight:800;">
                                            {{ strtoupper(str_replace('Pool ', '', $pool->name)) }}
                                        </span>
                                        {{ $pool->name }}
                                    </h3>
                                    <span style="background: {{ $pc['badge_bg'] }}; color: {{ $pc['color'] }}; border: 1px solid {{ $pc['border'] }}; padding: 0.15rem 0.65rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 600;">
                                        {{ $pool->registrations->count() }} Teams
                                    </span>
                                </div>
                                <div class="glass-body pt-2 pb-2">
                                    <ul class="list-unstyled m-0">
                                        @foreach($pool->registrations as $idx => $reg)
                                            <li style="padding: 0.6rem 0; border-bottom: 1px solid rgba(255,255,255,0.04); display:flex; align-items:center; gap:0.6rem; color: var(--zinc-200); font-size:0.88rem;">
                                                <span style="width:20px; height:20px; border-radius:50%; background:{{ $pc['badge_bg'] }}; border:1px solid {{ $pc['border'] }}; display:inline-flex; align-items:center; justify-content:center; font-size:0.65rem; font-weight:700; color:{{ $pc['color'] }}; flex-shrink:0;">
                                                    {{ $idx + 1 }}
                                                </span>
                                                {{ $reg->team->name }}
                                                @if($reg->registered_category)
                                                    <span style="font-size:0.7rem; color: var(--zinc-600); margin-left:auto;">{{ $reg->registered_category }}</span>
                                                @endif
                                            </li>
                                        @endforeach
                                        @if($pool->registrations->isEmpty())
                                            <li style="padding: 0.75rem 0; color: var(--zinc-600); font-style: italic; font-size: 0.85rem; text-align: center;">No teams assigned</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="glass-card" style="border: 1px dashed rgba(255,255,255,0.08);">
                                <div class="glass-body text-center py-5">
                                    <div style="width:70px; height:70px; border-radius:50%; background:rgba(16,185,129,0.06); border:2px dashed rgba(16,185,129,0.2); display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem; font-size:1.75rem; color:rgba(16,185,129,0.3);">
                                        <i class="fas fa-random"></i>
                                    </div>
                                    <h5 style="color: var(--zinc-400); font-weight: 600; margin-bottom: 0.5rem;">No pools created yet</h5>
                                    <p style="color: var(--zinc-600); font-size: 0.85rem; margin: 0;">
                                        Use <strong style="color: #34d399;">Auto Pool Draw</strong> on the left to automatically assign teams to pools.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div><!-- /col-lg-8 -->
        </div>
    </div>
    
    <!-- Tab 2: Fixture Generator -->
    <div class="tab-pane" id="tab-fixtures" role="tabpanel" aria-labelledby="fixtures-tab">
        <div class="row align-items-start">
            <div class="col-lg-4 mb-4">
                <!-- Fixture Generator Card -->
                <div class="glass-card">
                    <div class="glass-header">
                        <h3 class="card-title m-0" style="font-size: 1.1rem;"><i class="fas fa-magic text-muted mr-2"></i> Fixture Tools</h3>
                    </div>
                    <div class="glass-body">
                        <form id="fixture-generator-form" action="{{ route('admin.tournaments.generateFixtures', $tournament->id) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="premium-label">First Match Start Time</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-right: none; color: var(--zinc-400);"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" name="start_datetime" class="form-control premium-input datetime-picker" placeholder="Select Date & Time..." style="border-left: none; border-top-left-radius: 0; border-bottom-left-radius: 0;" required>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="premium-label">Match Duration (Minutes)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-right: none; color: var(--zinc-400);"><i class="fas fa-clock"></i></span>
                                    </div>
                                    <input type="number" name="match_duration" class="form-control premium-input" value="15" min="5" style="border-left: none; border-top-left-radius: 0; border-bottom-left-radius: 0;" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-premium btn-premium-success w-100" onclick="previewGeneratedFixtures()">
                                <i class="fas fa-eye mr-1"></i> Generate Preview
                            </button>
                        </form>
                        
                        <hr style="border-top: 1px solid var(--glass-border); margin: 1.5rem 0;">
                        
                        <form action="{{ route('admin.tournaments.clearFixtures', $tournament->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-premium btn-premium-danger w-100" onclick="return confirm('Are you sure you want to delete ALL generated fixtures? This cannot be undone.')">
                                <i class="fas fa-trash-alt mr-1"></i> Clear All Fixtures
                            </button>
                        </form>
                        
                        @if($tournament->fixtures()->where('status', 'draft')->exists())
                        <div class="mt-4 pt-4" style="border-top: 1px dashed var(--glass-border);">
                            <form action="{{ route('admin.tournaments.publishFixtures', $tournament->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-premium btn-premium-warning w-100" onclick="return confirm('Are you sure you want to publish these fixtures? They will now be visible to Users and Referees.')">
                                    <i class="fas fa-bullhorn mr-1"></i> Confirm & Publish Drafts
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8" id="generator-preview-panel">
                <div class="glass-card p-5 text-center">
                    <i class="fas fa-info-circle text-info mb-4" style="font-size: 3.5rem;"></i>
                    <h4 style="font-weight: 700; color: #fff; margin-bottom: 0.75rem;">Group Stage Fixture Generator</h4>
                    <p style="color: var(--zinc-400); max-width: 580px; margin: 0 auto 1.5rem; line-height: 1.6; font-size: 0.92rem;">
                        Use the control panel on the left to automatically generate all group stage matches based on the active pools created in the system.
                    </p>
                    <div style="background: rgba(0,0,0,0.15); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--glass-border); text-align: left; max-width: 580px; margin: 0 auto;">
                        <h5 style="font-size: 0.85rem; font-weight: 700; color: var(--color-electric-blue-light); text-transform: uppercase; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                            <i class="fas fa-clipboard-check"></i> Generation Prerequisites:
                        </h5>
                        <ul style="font-size: 0.82rem; color: var(--zinc-300); margin: 0; padding-left: 1.25rem; line-height: 1.7;">
                            <li>Please ensure all teams have been assigned to pools under the <strong>Pools Setup</strong> tab.</li>
                            <li>The system will generate round-robin group fixtures within each pool.</li>
                            <li>Once generated, fixtures will have a <strong>Draft</strong> status. Click <strong>Confirm & Publish Drafts</strong> to make them visible to Managers and Spectators.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tab 3: Knockout Stage -->
    <div class="tab-pane" id="tab-knockout" role="tabpanel" aria-labelledby="knockout-tab">
        @php
            $pools = $tournament->pools;
            $poolCount = $pools->count();
            $seededTeamsList = [];

            if ($poolCount > 0) {
                $rankedPools = [];
                foreach ($pools as $pool) {
                    $rankedPools[$pool->name] = $pool->calculateStandings();
                }

                $poolNames = $pools->pluck('name')->sort()->values()->toArray();
                $byPosition = [];

                foreach ($poolNames as $poolName) {
                    $standings = $rankedPools[$poolName] ?? collect();
                    foreach ($standings as $pos => $row) {
                        $byPosition[$pos][] = [
                            'team' => $row['team'],
                            'pts'  => $row['points'],
                            'pd'   => $row['points_difference'],
                            'pool' => $poolName
                        ];
                    }
                }

                ksort($byPosition);

                foreach ($byPosition as $pos => $posGroup) {
                    usort($posGroup, function ($a, $b) {
                        if ($b['pts'] !== $a['pts']) return $b['pts'] - $a['pts'];
                        return $b['pd'] - $a['pd'];
                    });
                    foreach ($posGroup as $entry) {
                        $seededTeamsList[] = [
                            'team' => $entry['team'],
                            'pool' => $entry['pool'],
                            'pool_pos' => $pos + 1,
                            'pts' => $entry['pts'],
                            'pd' => $entry['pd']
                        ];
                    }
                }
            }

            $knockoutFixtures = $tournament->fixtures()->where('stage', '!=', 'Pool Stage')->orderBy('start_time', 'asc')->get();
            $hasDraftKnockouts = $knockoutFixtures->where('status', 'draft')->isNotEmpty();
        @endphp

        <div class="row align-items-start">
            <!-- Left Side: Controls & Seeding Preview -->
            <div class="col-lg-4 mb-4">
                <!-- Knockout Controls -->
                <div class="glass-card mb-4">
                    <div class="glass-header">
                        <h3 class="card-title m-0" style="font-size: 1.1rem;"><i class="fas fa-sliders-h text-muted mr-2"></i> Control Center</h3>
                    </div>
                    <div class="glass-body">
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <button type="button" class="btn btn-premium btn-premium-success w-100" style="background: linear-gradient(135deg, var(--brand-success), #16a34a); color: white; box-shadow: 0 4px 15px rgba(22, 163, 74, 0.3);" onclick="openGenerateKnockoutsModal()">
                                <i class="fas fa-magic mr-1"></i> Auto-Generate Knockouts
                            </button>
                            <button type="button" class="btn btn-premium btn-premium-info w-100" style="background: linear-gradient(135deg, var(--brand-info), #2563eb); color: white; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);" onclick="openAddMatchModal()">
                                <i class="fas fa-plus mr-1"></i> Add Custom Match
                            </button>
                            @if($knockoutFixtures->isNotEmpty())
                                <form action="{{ route('admin.tournaments.clearFixtures', $tournament->id) }}?type=knockout" method="POST" onsubmit="return confirm('Are you sure you want to clear all knockout fixtures? Group stage fixtures will remain untouched.')">
                                    @csrf
                                    <button type="submit" class="btn btn-premium btn-premium-danger w-100" style="border-color: var(--glass-border); color: #ef4444;">
                                        <i class="fas fa-trash-alt mr-1"></i> Clear Knockout Fixtures
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Seeding Standings Preview -->
                <div class="glass-card">
                    <div class="glass-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title m-0" style="font-size: 1.1rem;"><i class="fas fa-medal text-muted mr-2"></i> Seeding Preview</h3>
                        <span class="pool-badge" style="background: rgba(255,255,255,0.05); font-size: 0.7rem;">Live Rankings</span>
                    </div>
                    <div class="glass-body p-0">
                        @if(count($seededTeamsList) > 0)
                            <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                                <table class="premium-table" style="width: 100%; margin: 0; font-size: 0.85rem;">
                                    <thead>
                                        <tr style="background: rgba(0,0,0,0.15);">
                                            <th style="padding: 10px 12px; text-align: center; width: 40px;">Seed</th>
                                            <th style="padding: 10px 12px; text-align: left;">Team</th>
                                            <th style="padding: 10px 12px; text-align: center; width: 50px;">PTS</th>
                                            <th style="padding: 10px 12px; text-align: center; width: 50px;">PD</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($seededTeamsList as $idx => $seed)
                                            @php $rank = $idx + 1; @endphp
                                            <tr style="border-bottom: 1px solid var(--glass-border); background: {{ $rank <= 8 ? 'rgba(16,185,129,0.03)' : 'transparent' }};">
                                                <td style="padding: 10px 12px; text-align: center; font-weight: 700; color: {{ $rank <= 8 ? '#34d399' : 'var(--zinc-500)' }}">
                                                    #{{ $rank }}
                                                </td>
                                                <td style="padding: 10px 12px; font-weight: 600; color: var(--zinc-100);">
                                                    {{ $seed['team']->name }}
                                                    <span style="font-size: 0.68rem; color: var(--zinc-500); display: block; font-weight: normal;">
                                                        Pos {{ $seed['pool_pos'] }} in {{ $seed['pool'] }}
                                                    </span>
                                                </td>
                                                <td style="padding: 10px 12px; text-align: center; font-weight: 600; color: var(--brand-warning);">{{ $seed['pts'] }}</td>
                                                <td style="padding: 10px 12px; text-align: center; color: {{ $seed['pd'] > 0 ? '#34d399' : ($seed['pd'] < 0 ? '#ef4444' : 'var(--zinc-400)') }}">{{ $seed['pd'] > 0 ? '+' : '' }}{{ $seed['pd'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div style="padding: 12px; border-top: 1px solid var(--glass-border); background: rgba(0,0,0,0.15); font-size: 0.78rem; color: var(--zinc-400);">
                                <i class="fas fa-info-circle mr-1" style="color: var(--brand-info);"></i>
                                Seeds <strong>#1 to #8</strong> qualify for Cup/Plate QFs. Baki <strong>#9 to #12</strong> qualify for Bowl/Shield SFs.
                            </div>
                        @else
                            <div class="p-4 text-center text-muted" style="font-size: 0.85rem;">
                                Pools Standings not loaded. Create pools and assign teams first.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Side: Knockout Fixtures List & Bracket Preview -->
            <div class="col-lg-8">
                @if($knockoutFixtures->isNotEmpty())
                    <!-- Double-Check & Publish Banner -->
                    @if($hasDraftKnockouts)
                        <div class="glass-card mb-4" style="border: 1px solid rgba(245,158,11,0.3); background: rgba(245,158,11,0.06); box-shadow: 0 0 20px rgba(245,158,11,0.15);">
                            <div class="glass-body d-flex justify-content-between align-items-center flex-wrap" style="gap: 1rem; padding: 1.25rem 1.5rem;">
                                <div>
                                    <h4 class="m-0" style="font-size: 1rem; color: #fbbf24; display: flex; align-items: center; gap: 8px;">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Draft Knockout Fixtures Generated!
                                    </h4>
                                    <p class="m-0 text-muted" style="font-size: 0.85rem; margin-top: 4px !important;">
                                        Please review the seed matchups and timing below. Once verified, click publish to make them visible to Referees and Spectators.
                                    </p>
                                </div>
                                <form action="{{ route('admin.tournaments.publishFixtures', $tournament->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-premium btn-premium-warning" onclick="return confirm('Are you sure you want to publish all draft knockout fixtures?')" style="background: linear-gradient(135deg, var(--brand-warning), #d97706); border: none; color: white;">
                                        <i class="fas fa-bullhorn mr-1"></i> Verify & Publish Knockouts
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Knockout CRUD List -->
                    <div class="glass-card">
                        <div class="glass-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title m-0" style="font-size: 1.1rem;"><i class="fas fa-sitemap text-muted mr-2"></i> Knockout Fixtures</h3>
                            <span class="pool-badge" style="background: rgba(255,255,255,0.05);">{{ $knockoutFixtures->count() }} Matches</span>
                        </div>
                        <div class="table-responsive premium-scrollbar" style="max-height: 500px; overflow-y: auto;">
                            <table class="premium-table">
                                <thead>
                                    <tr>
                                        <th style="width: 5%; text-align: center; color: var(--zinc-500);">#</th>
                                        <th style="width: 25%;">Knockout Stage</th>
                                        <th style="width: 20%;">Time</th>
                                        <th style="width: 18%;">Home</th>
                                        <th style="width: 10%; text-align: center;">Score</th>
                                        <th style="width: 18%;">Away</th>
                                        <th style="width: 12%;">Status</th>
                                        <th style="width: 10%; text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($knockoutFixtures as $idx => $fixture)
                                        <tr>
                                            <td style="text-align: center; font-size: 0.75rem; font-weight: 700; color: var(--zinc-500);">{{ $idx + 1 }}</td>
                                            <td>
                                                <strong style="color: var(--color-electric-blue-light);">{{ $fixture->stage }}</strong>
                                            </td>
                                            <td>
                                                @if($fixture->start_time)
                                                    <div style="font-size: 0.72rem; color: var(--zinc-500); text-transform: uppercase;">{{ \Carbon\Carbon::parse($fixture->start_time)->format('d M') }}</div>
                                                    <div style="font-weight: 500; color: var(--zinc-100);">{{ \Carbon\Carbon::parse($fixture->start_time)->format('h:i A') }}</div>
                                                @else
                                                    <span class="text-muted">TBD</span>
                                                @endif
                                            </td>
                                            <td><strong style="color: var(--zinc-100);">{{ $fixture->homeTeam ? $fixture->homeTeam->name : 'TBD' }}</strong></td>
                                            <td style="text-align: center; font-weight: 700; font-size: 1.05rem; color: var(--brand-info);">
                                                {{ $fixture->home_score ?? '-' }} : {{ $fixture->away_score ?? '-' }}
                                            </td>
                                            <td><strong style="color: var(--zinc-100);">{{ $fixture->awayTeam ? $fixture->awayTeam->name : 'TBD' }}</strong></td>
                                            <td>
                                                @if($fixture->status == 'completed')
                                                    <span class="status-pill status-completed"><span class="status-dot"></span> Completed</span>
                                                @elseif($fixture->status == 'draft')
                                                    <span class="status-pill status-draft"><span class="status-dot"></span> Draft</span>
                                                @elseif($fixture->status == 'ongoing')
                                                    <span class="status-pill status-ongoing"><span class="status-dot"></span> Ongoing</span>
                                                @else
                                                    <span class="status-pill status-scheduled"><span class="status-dot"></span> Scheduled</span>
                                                @endif
                                            </td>
                                            <td style="text-align: right;">
                                                <div style="display: inline-flex; gap: 0.25rem;">
                                                    <button type="button" class="btn-action-icon edit" title="Edit Fixture" 
                                                        onclick="openEditModal({{ $fixture->id }}, {{ $fixture->home_team_id }}, {{ $fixture->away_team_id }}, '{{ $fixture->status }}', {{ $fixture->home_score ?? 'null' }}, {{ $fixture->away_score ?? 'null' }}, '{{ $fixture->start_time ? \Carbon\Carbon::parse($fixture->start_time)->format('Y-m-d\TH:i') : '' }}')">
                                                        <i class="fas fa-edit" style="font-size: 0.85rem;"></i>
                                                    </button>
                                                    <form action="{{ route('admin.tournaments.deleteFixture', [$tournament->id, $fixture->id]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-action-icon delete" onclick="return confirm('Are you sure you want to delete this knockout fixture?');" title="Delete Fixture">
                                                            <i class="fas fa-trash" style="font-size: 0.85rem;"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Visual Bracket Display (Real iRugby style) -->
                    <div class="glass-card mt-4">
                        <div class="glass-header d-flex justify-content-between align-items-center" style="padding: 1rem 1.5rem;">
                            <h3 class="card-title m-0" style="font-size: 1.1rem; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-sitemap text-muted"></i>
                                Knockout Bracket Visualizer
                            </h3>
                            <ul class="nav nav-pills" id="bracket-pills" role="tablist" style="border: 1px solid var(--glass-border); gap: 0.25rem; background: rgba(0,0,0,0.2); padding: 4px; border-radius: 8px;">
                                <li class="nav-item">
                                    <a class="nav-link active py-1 px-3" id="cup-plate-tab" data-toggle="pill" href="#bracket-cup-plate" role="tab" style="font-size: 0.75rem; border-radius: 6px; color: #fff; font-weight: 600;">🏆 Cup & Plate</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link py-1 px-3" id="bowl-shield-tab" data-toggle="pill" href="#bracket-bowl-shield" role="tab" style="font-size: 0.75rem; border-radius: 6px; color: #fff; font-weight: 600;">🛡️ Bowl & Shield</a>
                                </li>
                            </ul>
                        </div>
                        <div class="glass-body" style="padding: 1.5rem;">
                            <div class="tab-content" id="bracket-tab-contents" style="margin-top: 0 !important;">
                                <!-- Cup & Plate Bracket Pane -->
                                <div class="tab-pane active" id="bracket-cup-plate" role="tabpanel">
                                    @php
                                        // Helper finder function
                                        $findMatch = function($stageName) use ($knockoutFixtures) {
                                            return $knockoutFixtures->first(function($f) use ($stageName) {
                                                return strtolower($f->stage) === strtolower($stageName);
                                            });
                                        };

                                        $qp1 = $findMatch('Cup/Plate - Quarter-Final 1');
                                        $qp2 = $findMatch('Cup/Plate - Quarter-Final 2');
                                        $qp3 = $findMatch('Cup/Plate - Quarter-Final 3');
                                        $qp4 = $findMatch('Cup/Plate - Quarter-Final 4');

                                        $cupSf1 = $findMatch('Cup - Semi-Final 1');
                                        $cupSf2 = $findMatch('Cup - Semi-Final 2');
                                        $plateSf1 = $findMatch('Plate - Semi-Final 1');
                                        $plateSf2 = $findMatch('Plate - Semi-Final 2');

                                        $cupFinal = $findMatch('Cup - Final');
                                        $plateFinal = $findMatch('Plate - Final');
                                    @endphp

                                    <div class="bracket-container">
                                        <!-- Column 1: Quarter-Finals -->
                                        <div class="bracket-column">
                                            <div class="bracket-column-title">Quarter-Finals</div>
                                            @foreach([['name' => 'Cup/Plate QF 1', 'm' => $qp1], ['name' => 'Cup/Plate QF 2', 'm' => $qp2], ['name' => 'Cup/Plate QF 3', 'm' => $qp3], ['name' => 'Cup/Plate QF 4', 'm' => $qp4]] as $qf)
                                                @include('admin.tournaments.partials.bracket-match-node', [
                                                    'title' => $qf['name'], 
                                                    'match' => $qf['m'],
                                                    'info' => 'WINNER ➔ CUP SF | LOSER ➔ PLATE SF'
                                                ])
                                            @endforeach
                                        </div>

                                        <!-- Column 2: Semi-Finals -->
                                        <div class="bracket-column">
                                            <div class="bracket-sub-section">
                                                <div class="bracket-sub-section-title">🏆 Cup Semi-Finals</div>
                                                @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Cup SF 1', 'match' => $cupSf1, 'info' => 'WINNER ➔ CUP FINAL'])
                                                @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Cup SF 2', 'match' => $cupSf2, 'info' => 'WINNER ➔ CUP FINAL'])
                                            </div>
                                            <div class="bracket-sub-section">
                                                <div class="bracket-sub-section-title">🥈 Plate Semi-Finals</div>
                                                @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Plate SF 1', 'match' => $plateSf1, 'info' => 'WINNER ➔ PLATE FINAL'])
                                                @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Plate SF 2', 'match' => $plateSf2, 'info' => 'WINNER ➔ PLATE FINAL'])
                                            </div>
                                        </div>

                                        <!-- Column 3: Finals -->
                                        <div class="bracket-column">
                                            <div class="bracket-sub-section">
                                                <div class="bracket-sub-section-title">🏆 Cup Final</div>
                                                @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Cup Final', 'match' => $cupFinal, 'info' => 'CHAMPIONSHIP MATCH'])
                                            </div>
                                            <div class="bracket-sub-section">
                                                <div class="bracket-sub-section-title">🥈 Plate Final</div>
                                                @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Plate Final', 'match' => $plateFinal, 'info' => 'CHAMPIONSHIP MATCH'])
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bowl & Shield Bracket Pane -->
                                <div class="tab-pane" id="bracket-bowl-shield" role="tabpanel">
                                    @php
                                        $bsSf1 = $findMatch('Bowl/Shield - Semi-Final 1');
                                        $bsSf2 = $findMatch('Bowl/Shield - Semi-Final 2');

                                        $bowlFinal = $findMatch('Bowl - Final');
                                        $shieldFinal = $findMatch('Shield - Final');
                                    @endphp

                                    <div class="bracket-container" style="justify-content: flex-start; gap: 4rem;">
                                        <!-- Column 1: Semi-Finals -->
                                        <div class="bracket-column">
                                            <div class="bracket-column-title">Semi-Finals</div>
                                            @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Bowl/Shield SF 1', 'match' => $bsSf1, 'info' => 'WINNER ➔ BOWL FINAL | LOSER ➔ SHIELD FINAL'])
                                            @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Bowl/Shield SF 2', 'match' => $bsSf2, 'info' => 'WINNER ➔ BOWL FINAL | LOSER ➔ SHIELD FINAL'])
                                        </div>

                                        <!-- Column 2: Finals -->
                                        <div class="bracket-column">
                                            <div class="bracket-sub-section">
                                                <div class="bracket-sub-section-title">🥉 Bowl Final</div>
                                                @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Bowl Final', 'match' => $bowlFinal, 'info' => 'CHAMPIONSHIP MATCH'])
                                            </div>
                                            <div class="bracket-sub-section">
                                                <div class="bracket-sub-section-title">🛡️ Shield Final</div>
                                                @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Shield Final', 'match' => $shieldFinal, 'info' => 'CHAMPIONSHIP MATCH'])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="glass-card p-5 text-center">
                        <i class="fas fa-sitemap text-warning mb-4" style="font-size: 3.5rem;"></i>
                        <h4 style="font-weight: 700; color: #fff; margin-bottom: 0.75rem;">Knockout Bracket Setup</h4>
                        <p style="color: var(--zinc-400); max-width: 580px; margin: 0 auto 1.5rem; line-height: 1.6; font-size: 0.92rem;">
                            Set up knockout matches once final pool standings are determined. Use the control center on the left to auto-generate brackets based on pool standings.
                        </p>
                        <div style="display: flex; justify-content: center; gap: 12px; margin-top: 1rem;">
                            <a href="{{ route('shared.brackets') }}" target="_blank" class="btn btn-premium btn-premium-info" style="background: linear-gradient(135deg, var(--brand-info), #2563eb); padding: 0.6rem 1.5rem;">
                                <i class="fas fa-external-link-alt mr-1"></i> Open Bracket Viewer
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Tab 4: Match Directory -->
    <div class="tab-pane" id="tab-list" role="tabpanel" aria-labelledby="list-tab">
        @if($draftFixtures > 0)
            <div class="glass-card mb-4" style="border: 1px solid rgba(245,158,11,0.3); background: rgba(245,158,11,0.06); box-shadow: 0 0 20px rgba(245,158,11,0.15);">
                <div class="glass-body d-flex justify-content-between align-items-center flex-wrap" style="gap: 1rem; padding: 1.25rem 1.5rem;">
                    <div>
                        <h4 class="m-0" style="font-size: 1rem; color: #fbbf24; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-exclamation-triangle"></i>
                            Draft Fixtures Pending Publication
                        </h4>
                        <p class="m-0 text-muted" style="font-size: 0.85rem; margin-top: 4px !important;">
                            You have {{ $draftFixtures }} fixtures in Draft. Spectators and Managers cannot see them yet.
                        </p>
                    </div>
                    <form action="{{ route('admin.tournaments.publishFixtures', $tournament->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-premium btn-premium-warning" onclick="return confirm('Are you sure you want to publish all draft fixtures? They will become public.')" style="background: linear-gradient(135deg, var(--brand-warning), #d97706); border: none; color: white;">
                            <i class="fas fa-bullhorn mr-1"></i> Verify & Publish Drafts
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <div class="glass-card">
            <div class="glass-header d-flex justify-content-between align-items-center">
                <h3 class="card-title m-0" style="font-size: 1.1rem;"><i class="fas fa-list-ul text-muted mr-2"></i> Generated Fixtures</h3>
                <span class="pool-badge" style="background: rgba(255,255,255,0.05);">{{ $totalFixtures }} Matches</span>
            </div>
            <div class="table-responsive premium-scrollbar" style="max-height: 600px; overflow-y: auto;">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th style="width: 5%; text-align: center; color: var(--zinc-500);">#</th>
                            <th style="width: 14%;">Stage / Pool</th>
                            <th style="width: 14%;">Time</th>
                            <th style="width: 19%;">Home</th>
                            <th style="width: 10%; text-align: center;">Score</th>
                            <th style="width: 19%;">Away</th>
                            <th style="width: 11%;">Status</th>
                            <th style="width: 8%; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tournament->fixtures()->orderBy('start_time', 'asc')->get() as $fixtureIndex => $fixture)
                            <tr>
                                <td style="text-align: center; font-size: 0.75rem; font-weight: 700; color: var(--zinc-500); letter-spacing: 0.5px;">{{ $fixtureIndex + 1 }}</td>
                                <td><span style="color: var(--zinc-400);">{{ $fixture->pool ? $fixture->pool->name : $fixture->stage }}</span></td>
                                <td>
                                    @if($fixture->start_time)
                                        <div style="font-size: 0.75rem; color: var(--zinc-500); text-transform: uppercase;">{{ \Carbon\Carbon::parse($fixture->start_time)->format('d M') }}</div>
                                        <div style="font-weight: 500; color: var(--zinc-100);">{{ \Carbon\Carbon::parse($fixture->start_time)->format('h:i A') }}</div>
                                    @else
                                        <span class="text-muted">TBD</span>
                                    @endif
                                </td>
                                <td><strong style="color: var(--zinc-50);">{{ $fixture->homeTeam ? $fixture->homeTeam->name : 'TBD' }}</strong></td>
                                <td style="text-align: center; font-weight: 700; font-size: 1.1rem; letter-spacing: 1px; color: var(--brand-info);">
                                    {{ $fixture->home_score ?? '-' }} : {{ $fixture->away_score ?? '-' }}
                                </td>
                                <td><strong style="color: var(--zinc-50);">{{ $fixture->awayTeam ? $fixture->awayTeam->name : 'TBD' }}</strong></td>
                                <td>
                                    @if($fixture->status == 'completed')
                                        <span class="status-pill status-completed"><span class="status-dot"></span> Completed</span>
                                    @elseif($fixture->status == 'draft')
                                        <span class="status-pill status-draft"><span class="status-dot"></span> Draft</span>
                                    @elseif($fixture->status == 'ongoing')
                                        <span class="status-pill status-ongoing"><span class="status-dot"></span> Ongoing</span>
                                    @else
                                        <span class="status-pill status-scheduled"><span class="status-dot"></span> Scheduled</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; gap: 0.25rem;">
                                        <button type="button" class="btn-action-icon edit" title="Edit Fixture" 
                                            onclick="openEditModal({{ $fixture->id }}, {{ $fixture->home_team_id }}, {{ $fixture->away_team_id }}, '{{ $fixture->status }}', {{ $fixture->home_score ?? 'null' }}, {{ $fixture->away_score ?? 'null' }}, '{{ $fixture->start_time ? \Carbon\Carbon::parse($fixture->start_time)->format('Y-m-d\TH:i') : '' }}')">
                                            <i class="fas fa-edit" style="font-size: 0.875rem;"></i>
                                        </button>
                                        <form action="{{ route('admin.tournaments.deleteFixture', [$tournament->id, $fixture->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action-icon delete" onclick="return confirm('Are you sure you want to delete this fixture?');" title="Delete Fixture">
                                                <i class="fas fa-trash" style="font-size: 0.875rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center" style="padding: 4rem 1rem;">
                                    <i class="fas fa-calendar-times text-muted mb-3" style="font-size: 2.5rem; opacity: 0.5;"></i>
                                    <p class="text-muted mb-0">No fixtures have been generated yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab 5: Standings -->
    <div class="tab-pane" id="tab-standings" role="tabpanel" aria-labelledby="standings-tab">
        @if($tournament->pools->count() > 0)
            @foreach($tournament->pools as $pool)
                @php $standings = $pool->calculateStandings(); @endphp
                <div class="glass-card mb-4">
                    <div class="glass-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title m-0" style="font-size:1.1rem; display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-list-ol" style="color: var(--brand-primary);"></i>
                            {{ $pool->name }} Standings
                        </h3>
                        <span class="pool-badge" style="background:rgba(16,185,129,0.1); color:#34d399; border:1px solid rgba(16,185,129,0.2);">
                            {{ $pool->registrations->count() }} Teams
                        </span>
                    </div>
                    <div class="glass-body" style="padding:0;">
                        <div class="table-responsive">
                            <table class="premium-table" style="width:100%; margin:0;">
                                <thead>
                                    <tr style="background:rgba(0,0,0,0.2);">
                                        <th style="padding:12px 16px; text-align:center; width:60px;" title="Position">Pos</th>
                                        <th style="padding:12px 16px; text-align:left;">Team</th>
                                        <th style="padding:12px 10px; text-align:center; width:50px;" title="Played">P</th>
                                        <th style="padding:12px 10px; text-align:center; width:50px; color:#34d399;" title="Won">W</th>
                                        <th style="padding:12px 10px; text-align:center; width:50px;" title="Drawn">D</th>
                                        <th style="padding:12px 10px; text-align:center; width:50px; color:#ef4444;" title="Lost">L</th>
                                        <th style="padding:12px 10px; text-align:center; width:60px;" title="Points For">PF</th>
                                        <th style="padding:12px 10px; text-align:center; width:60px;" title="Points Against">PA</th>
                                        <th style="padding:12px 10px; text-align:center; width:60px;" title="Points Difference">PD</th>
                                        <th style="padding:12px 16px; text-align:center; width:70px; color:var(--brand-warning);" title="Points (Win=3, Draw=2, Loss=1)">PTS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($standings as $index => $row)
                                        @php 
                                            $rank = $index + 1; 
                                            $hasPlayed = $row['played'] > 0;
                                            $isQualifying = $rank <= 2 && $hasPlayed;
                                        @endphp
                                        <tr style="border-bottom:1px solid var(--glass-border); background:{{ $isQualifying ? 'rgba(16,185,129,0.05)' : 'transparent' }};">
                                            <td style="padding:14px 16px; text-align:center; font-weight:700; color:{{ $isQualifying ? '#34d399' : 'var(--zinc-500)' }};">
                                                @if($isQualifying && $rank == 1) <i class="fas fa-crown" style="color:#ffd700;"></i>
                                                @elseif($isQualifying && $rank == 2) <i class="fas fa-medal" style="color:#c0c0c0;"></i>
                                                @else {{ $rank }}
                                                @endif
                                            </td>
                                            <td style="padding:14px 16px; font-weight:600; color:var(--zinc-50);">
                                                {{ $row['team']->name }}
                                                @if($isQualifying)
                                                    <span style="margin-left:6px; background:rgba(16,185,129,0.1); color:#34d399; border:1px solid rgba(16,185,129,0.2); font-size:0.62rem; font-weight:700; padding:2px 7px; border-radius:20px; text-transform:uppercase; letter-spacing:0.5px;">Qualifies</span>
                                                @endif
                                            </td>
                                            <td style="padding:14px 10px; text-align:center; color:var(--zinc-300);">{{ $row['played'] }}</td>
                                            <td style="padding:14px 10px; text-align:center; font-weight:600; color:#34d399;">{{ $row['won'] }}</td>
                                            <td style="padding:14px 10px; text-align:center; color:var(--zinc-400);">{{ $row['drawn'] }}</td>
                                            <td style="padding:14px 10px; text-align:center; color:#ef4444;">{{ $row['lost'] }}</td>
                                            <td style="padding:14px 10px; text-align:center; color:var(--zinc-300);">{{ $row['points_for'] }}</td>
                                            <td style="padding:14px 10px; text-align:center; color:var(--zinc-300);">{{ $row['points_against'] }}</td>
                                            <td style="padding:14px 10px; text-align:center; font-weight:600; color:{{ $row['points_difference'] > 0 ? '#34d399' : ($row['points_difference'] < 0 ? '#ef4444' : 'var(--zinc-400)') }};">
                                                {{ $row['points_difference'] > 0 ? '+' : '' }}{{ $row['points_difference'] }}
                                            </td>
                                            <td style="padding:14px 16px; text-align:center; font-weight:800; font-size:1.1rem; color:var(--brand-warning);">{{ $row['points'] }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="10" style="padding:2rem; text-align:center; color:var(--zinc-500);">No teams assigned to this pool yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="glass-card p-5 text-center">
                <i class="fas fa-list-ol text-muted mb-3" style="font-size:2.5rem; opacity:0.4; display:block;"></i>
                <h5 class="text-muted mb-1">No Pools Created Yet</h5>
                <p class="text-muted small mb-0">Create pools in the Pools Setup tab first, then generate fixtures to see standings here.</p>
            </div>
        @endif
    </div>

</div>{{-- end tab-content --}}

<!-- Edit Fixture Modal -->
<div class="modal fade premium-modal" id="editFixtureModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-size: 1.25rem; color: var(--zinc-100);"><i class="fas fa-sliders-h mr-2 text-muted"></i> Edit Fixture</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeEditModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editFixtureForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body" style="padding: 1.5rem;">
                    <div class="form-group mb-3">
                        <label class="premium-label">Home Team</label>
                        <select name="home_team_id" id="edit_home_team" class="form-control premium-input" required>
                            @foreach($allRegistrations as $reg)
                                <option value="{{ $reg->team->id }}">{{ $reg->team->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="premium-label">Away Team</label>
                        <select name="away_team_id" id="edit_away_team" class="form-control premium-input" required>
                            @foreach($allRegistrations as $reg)
                                <option value="{{ $reg->team->id }}">{{ $reg->team->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label class="premium-label">Start Time</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-right: none; color: var(--zinc-400);"><i class="fas fa-clock"></i></span>
                            </div>
                            <input type="text" name="start_time" id="edit_start_time" class="form-control premium-input datetime-picker" placeholder="Select Date & Time..." style="border-left: none; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="premium-label">Home Score</label>
                                <input type="number" name="home_score" id="edit_home_score" class="form-control premium-input" min="0" placeholder="-">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="premium-label">Away Score</label>
                                <input type="number" name="away_score" id="edit_away_score" class="form-control premium-input" min="0" placeholder="-">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="premium-label">Match Status</label>
                        <select name="status" id="edit_status" class="form-control premium-input" required>
                            <option value="draft">Draft</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="gap: 0.5rem; justify-content: flex-end;">
                    <button type="button" class="btn btn-premium btn-premium-danger" onclick="closeEditModal()" style="border-color: var(--glass-border); color: var(--zinc-300);">Cancel</button>
                    <button type="submit" class="btn btn-premium btn-premium-success">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Custom Match Modal -->
<div class="modal fade premium-modal" id="addMatchModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-size: 1.25rem; color: var(--zinc-100);"><i class="fas fa-trophy mr-2 text-muted"></i> Add Custom Match</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeAddMatchModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.tournaments.generateFixtures', $tournament->id) }}" method="POST">
                @csrf
                <input type="hidden" name="is_custom" value="1">
                <div class="modal-body" style="padding: 1.5rem;">
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="premium-label">Trophy Category</label>
                                <select name="custom_category" class="form-control premium-input" required>
                                    <option value="Cup">Cup</option>
                                    <option value="Plate">Plate</option>
                                    <option value="Bowl">Bowl</option>
                                    <option value="Shield">Shield</option>
                                    <option value="Spoon">Spoon</option>
                                    <option value="Fork">Fork</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="premium-label">Match Round</label>
                                <select name="custom_round" class="form-control premium-input" required>
                                    <option value="Final">Final</option>
                                    <option value="3rd/4th Place">3rd/4th Place</option>
                                    <option value="Semi-Final">Semi-Final</option>
                                    <option value="Quarter-Final">Quarter-Final</option>
                                    <option value="Round of 16">Round of 16</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="premium-label">Home Team</label>
                        <select name="home_team_id" class="form-control premium-input" required>
                            <option value="">-- Select Team --</option>
                            @foreach($allRegistrations as $reg)
                                <option value="{{ $reg->team->id }}">{{ $reg->team->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="premium-label">Away Team</label>
                        <select name="away_team_id" class="form-control premium-input" required>
                            <option value="">-- Select Team --</option>
                            @foreach($allRegistrations as $reg)
                                <option value="{{ $reg->team->id }}">{{ $reg->team->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label class="premium-label">Start Time</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-right: none; color: var(--zinc-400);"><i class="fas fa-clock"></i></span>
                            </div>
                            <input type="text" name="custom_start_time" class="form-control premium-input datetime-picker" placeholder="Select Date & Time..." style="border-left: none; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="gap: 0.5rem; justify-content: flex-end;">
                    <button type="button" class="btn btn-premium btn-premium-danger" onclick="closeAddMatchModal()" style="border-color: var(--glass-border); color: var(--zinc-300);">Cancel</button>
                    <button type="submit" class="btn btn-premium btn-premium-info" style="background: linear-gradient(135deg, var(--brand-info), #2563eb); color: white; border: none;">Create Match</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Auto-Generate Knockouts Modal -->
<div class="modal fade premium-modal" id="generateKnockoutsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-size: 1.25rem; color: var(--zinc-100);"><i class="fas fa-magic mr-2 text-muted"></i> Auto-Generate Knockouts</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeGenerateKnockoutsModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.tournaments.generateKnockouts', $tournament->id) }}" method="POST">
                @csrf
                <div class="modal-body" style="padding: 1.5rem;">
                    <div style="background: rgba(59, 130, 246, 0.1); border-left: 3px solid var(--brand-info); padding: 12px; border-radius: 4px; margin-bottom: 1.5rem; font-size: 0.85rem; line-height: 1.5; color: var(--zinc-300);">
                        <i class="fas fa-info-circle mr-1" style="color: var(--brand-info);"></i>
                        The system will automatically calculate current pool standings using standard points format (Win=3, Draw=2, Loss=1).
                        <ul style="margin-top: 5px; padding-left: 1.25rem; list-style-type: disc;">
                            @if($tournament->pools->count() == 4)
                                <li><strong>4 Pools detected:</strong> Generates 4 Cup/Plate Quarter-Finals (1st vs 2nd) and 4 Bowl/Shield Quarter-Finals (3rd vs 4th).</li>
                            @elseif($tournament->pools->count() == 3)
                                <li><strong>3 Pools detected (12-team Rugby 7s format):</strong></li>
                                <li>🏆 <strong>Cup</strong> Semi-Finals — Seeds 1 vs 4, Seeds 2 vs 3</li>
                                <li>🥈 <strong>Plate</strong> Semi-Finals — Seeds 5 vs 8, Seeds 6 vs 7</li>
                                <li>🥉 <strong>Bowl</strong> Final — Seeds 9 vs 10</li>
                                <li>🛡 <strong>Shield</strong> Final — Seeds 11 vs 12</li>
                                <li style="margin-top:4px; color:#a1a1aa; font-size:0.8rem;">Teams are cross-ranked by position group (all Pool 1sts ranked together, then all 2nds, etc.) using PTS then PD.</li>
                            @elseif($tournament->pools->count() == 2)
                                <li><strong>2 Pools detected:</strong> Generates 2 Cup Semi-Finals (1st vs 2nd) and 2 Bowl Semi-Finals (3rd vs 4th).</li>
                            @else
                                <li class="text-danger">{{ $tournament->pools->count() }} pools detected — auto-generation supports 2, 3, or 4 pools only. Please use Add Custom Match.</li>
                            @endif
                        </ul>
                    </div>

                    @php
                        $uncompletedPoolFixtures = $tournament->fixtures()->whereNotNull('pool_id')->where('status', '!=', 'completed')->exists();
                    @endphp
                    @if($uncompletedPoolFixtures)
                        <div style="background: rgba(245, 158, 11, 0.1); border-left: 3px solid var(--brand-warning); padding: 12px; border-radius: 4px; margin-bottom: 1.5rem; font-size: 0.85rem; line-height: 1.5; color: var(--zinc-300);">
                            <i class="fas fa-exclamation-triangle mr-1" style="color: var(--brand-warning);"></i>
                            <strong>Warning:</strong> Uncompleted group stage matches detected. Live standings will be used to seed teams.
                        </div>
                    @endif

                    <div class="form-group mb-3">
                        <label class="premium-label">First Match Start Time</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-right: none; color: var(--zinc-400);"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" name="start_datetime" class="form-control premium-input datetime-picker" placeholder="Select Date & Time..." style="border-left: none; border-top-left-radius: 0; border-bottom-left-radius: 0;" required>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="premium-label">Match Duration (Minutes)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-right: none; color: var(--zinc-400);"><i class="fas fa-clock"></i></span>
                            </div>
                            <input type="number" name="match_duration" class="form-control premium-input" value="15" min="5" style="border-left: none; border-top-left-radius: 0; border-bottom-left-radius: 0;" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="gap: 0.5rem; justify-content: flex-end;">
                    <button type="button" class="btn btn-premium btn-premium-danger" onclick="closeGenerateKnockoutsModal()" style="border-color: var(--glass-border); color: var(--zinc-300);">Cancel</button>
                    <button type="submit" class="btn btn-premium btn-premium-success" {{ (!in_array($tournament->pools->count(), [2, 3, 4])) ? 'disabled' : '' }}>
                        <i class="fas fa-magic mr-1"></i> Generate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Flatpickr
        flatpickr(".datetime-picker", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: false,
            minuteIncrement: 5,
            altInput: true,
            altFormat: "d M Y, h:i K"
        });
        // Restore active tab from localStorage if it exists (Pure JS)
        const activeTab = localStorage.getItem('activeMatchManagementTab');
        if (activeTab && activeTab !== '#tab-pools') {
            const tabLink = document.querySelector(`#tournament-tabs a[href="${activeTab}"]`);
            if (tabLink) {
                // Remove active class from all nav links and tab panes
                document.querySelectorAll('#tournament-tabs .nav-link').forEach(el => el.classList.remove('active'));
                document.querySelectorAll('#tournament-tabs-content .tab-pane').forEach(el => el.classList.remove('active'));

                // Add active class to selected tab link and pane
                tabLink.classList.add('active');
                const targetPane = document.querySelector(activeTab);
                if (targetPane) {
                    targetPane.classList.add('active');
                }
            }
        }

        // Save and toggle active tab on click (Pure JS)
        document.querySelectorAll('#tournament-tabs a').forEach(link => {
            link.addEventListener('click', function(e) {
                const targetHref = this.getAttribute('href');
                localStorage.setItem('activeMatchManagementTab', targetHref);

                // Toggle active class on nav links
                document.querySelectorAll('#tournament-tabs .nav-link').forEach(el => el.classList.remove('active'));
                this.classList.add('active');

                // Toggle active class on tab panes
                document.querySelectorAll('#tournament-tabs-content .tab-pane').forEach(el => el.classList.remove('active'));
                const targetPane = document.querySelector(targetHref);
                if (targetPane) {
                    targetPane.classList.add('active');
                }

                e.preventDefault(); // Prevent scroll jump
            });
        });
    });

    function openEditModal(fixtureId, homeId, awayId, status, homeScore, awayScore, startTime) {
        const modal = document.getElementById('editFixtureModal');
        const form = document.getElementById('editFixtureForm');
        
        form.action = `/admin/tournaments/{{ $tournament->id }}/fixtures/${fixtureId}`;
        
        document.getElementById('edit_home_team').value = homeId;
        document.getElementById('edit_away_team').value = awayId;
        document.getElementById('edit_status').value = status;
        document.getElementById('edit_home_score').value = homeScore !== null ? homeScore : '';
        document.getElementById('edit_away_score').value = awayScore !== null ? awayScore : '';
        
        const startTimeInput = document.getElementById('edit_start_time');
        if (startTimeInput._flatpickr) {
            startTimeInput._flatpickr.setDate(startTime || null);
        } else {
            startTimeInput.value = startTime || '';
        }
        
        modal.classList.add('show');
        modal.style.display = 'block';
        
        if(!document.querySelector('.modal-backdrop')) {
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
        }
    }

    function closeEditModal() {
        const modal = document.getElementById('editFixtureModal');
        modal.classList.remove('show');
        modal.style.display = 'none';
        
        const backdrop = document.querySelector('.modal-backdrop');
        if(backdrop) {
            backdrop.remove();
        }
    }

    function openAddMatchModal() {
        const modal = document.getElementById('addMatchModal');
        modal.classList.add('show');
        modal.style.display = 'block';
        
        if(!document.querySelector('.modal-backdrop')) {
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
        }
    }

    function closeAddMatchModal() {
        const modal = document.getElementById('addMatchModal');
        modal.classList.remove('show');
        modal.style.display = 'none';
        
        const backdrop = document.querySelector('.modal-backdrop');
        if(backdrop) {
            backdrop.remove();
        }
    }

    function openGenerateKnockoutsModal() {
        const modal = document.getElementById('generateKnockoutsModal');
        modal.classList.add('show');
        modal.style.display = 'block';
        
        if(!document.querySelector('.modal-backdrop')) {
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
        }
    }

    function closeGenerateKnockoutsModal() {
        const modal = document.getElementById('generateKnockoutsModal');
        modal.classList.remove('show');
        modal.style.display = 'none';
        
        const backdrop = document.querySelector('.modal-backdrop');
        if(backdrop) {
            backdrop.remove();
        }
    }

    let originalRightPanelHtml = '';

    function previewGeneratedFixtures() {
        const form = document.getElementById('fixture-generator-form');
        const startDatetime = form.querySelector('input[name="start_datetime"]').value;
        const matchDuration = form.querySelector('input[name="match_duration"]').value;
        
        if (!startDatetime) {
            alert('Please select a first match start time.');
            return;
        }

        const panel = document.getElementById('generator-preview-panel');
        if (!originalRightPanelHtml) {
            originalRightPanelHtml = panel.innerHTML;
        }

        // Show loading state
        panel.innerHTML = `
            <div class="glass-card p-5 text-center">
                <div class="spinner-border text-info mb-4" role="status" style="width: 3rem; height: 3rem; border-width: 0.25em;"></div>
                <h4 style="font-weight: 700; color: #fff; margin-bottom: 0.5rem;">Generating Fixtures Preview...</h4>
                <p class="text-muted">Please wait while the system calculates the schedule.</p>
            </div>
        `;

        fetch(`{{ route('admin.tournaments.previewFixtures', $tournament->id) }}?start_datetime=${encodeURIComponent(startDatetime)}&match_duration=${matchDuration}`)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.fixtures.length > 0) {
                    let rowsHtml = '';
                    data.fixtures.forEach(f => {
                        rowsHtml += `
                            <tr style="border-bottom: 1px solid var(--glass-border);">
                                <td style="text-align: center; font-weight: 700; color: var(--zinc-500); padding: 12px;">${f.match_no}</td>
                                <td style="padding: 12px;"><span class="pool-badge" style="background: rgba(0, 132, 255, 0.15); color: #60a5fa; font-weight: 600;">${f.pool_name}</span></td>
                                <td style="padding: 12px;"><strong style="color: var(--zinc-50);">${f.home_team_name}</strong> <span class="text-muted mx-1" style="font-size: 0.8rem;">vs</span> <strong style="color: var(--zinc-50);">${f.away_team_name}</strong></td>
                                <td style="padding: 12px;"><span style="color: var(--zinc-100); font-weight: 500;">${f.formatted_time}</span></td>
                            </tr>
                        `;
                    });

                    panel.innerHTML = `
                        <div class="glass-card">
                            <div class="glass-header d-flex justify-content-between align-items-center" style="padding: 15px 20px; border-bottom: 1px solid var(--glass-border);">
                                <h3 class="card-title m-0" style="font-size: 1.1rem; color: #fff; font-weight: 700;"><i class="fas fa-clipboard-list text-muted mr-2"></i> Fixture Preview Checklist</h3>
                                <span class="pool-badge" style="background: rgba(255,255,255,0.05); font-weight: 600;">${data.fixtures.length} Matches</span>
                            </div>
                            <div class="table-responsive premium-scrollbar" style="max-height: 400px; overflow-y: auto;">
                                <table class="premium-table" style="width: 100%; border-collapse: collapse; margin: 0; font-size: 0.88rem;">
                                    <thead>
                                        <tr style="background: rgba(0,0,0,0.15);">
                                            <th style="padding: 12px; text-align: center; width: 60px; color: var(--zinc-400); font-weight: 600;">Match</th>
                                            <th style="padding: 12px; text-align: left; width: 100px; color: var(--zinc-400); font-weight: 600;">Pool</th>
                                            <th style="padding: 12px; text-align: left; color: var(--zinc-400); font-weight: 600;">Matchup</th>
                                            <th style="padding: 12px; text-align: left; width: 180px; color: var(--zinc-400); font-weight: 600;">Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${rowsHtml}
                                    </tbody>
                                </table>
                            </div>
                            <div class="glass-body d-flex justify-content-end" style="border-top: 1px solid var(--glass-border); padding: 15px; background: rgba(0,0,0,0.15); display: flex; gap: 10px; justify-content: flex-end;">
                                <button type="button" class="btn btn-premium btn-premium-danger" onclick="discardPreview()" style="font-size: 0.85rem; padding: 8px 16px;">
                                    <i class="fas fa-times-circle"></i> Discard Preview
                                </button>
                                <button type="button" class="btn btn-premium btn-premium-success" onclick="savePreview()" style="font-size: 0.85rem; padding: 8px 16px;">
                                    <i class="fas fa-save"></i> Save Drafts to Directory
                                </button>
                            </div>
                        </div>
                    `;
                } else {
                    panel.innerHTML = `
                        <div class="glass-card p-5 text-center">
                            <i class="fas fa-exclamation-circle text-danger mb-4" style="font-size: 3.5rem;"></i>
                            <h4 style="font-weight: 700; color: #fff;">Failed to Generate Preview</h4>
                            <p class="text-muted">Ensure you have assigned at least two teams to pools before generating fixtures.</p>
                            <button type="button" class="btn btn-premium btn-premium-danger mt-3" onclick="discardPreview()">Back</button>
                        </div>
                    `;
                }
            })
            .catch(err => {
                console.error(err);
                panel.innerHTML = `
                    <div class="glass-card p-5 text-center">
                        <i class="fas fa-exclamation-circle text-danger mb-4" style="font-size: 3.5rem;"></i>
                        <h4 style="font-weight: 700; color: #fff;">Error Fetching Preview</h4>
                        <p class="text-muted">Something went wrong. Please check your connection and try again.</p>
                        <button type="button" class="btn btn-premium btn-premium-danger mt-3" onclick="discardPreview()">Back</button>
                    </div>
                `;
            });
    }

    function discardPreview() {
        if (originalRightPanelHtml) {
            document.getElementById('generator-preview-panel').innerHTML = originalRightPanelHtml;
        }
    }

    function savePreview() {
        if (confirm('Are you sure you want to save these fixtures as drafts? They will be added to the Match Directory.')) {
            document.getElementById('fixture-generator-form').submit();
        }
    }

    // ─── AUTO POOL DRAW: Live Preview ─────────────────────────────────────
    const TOTAL_UNASSIGNED = {{ $totalUnassigned ?? 0 }};
    const POOL_LABELS = ['A','B','C','D','E','F','G','H'];

    function updatePoolDrawPreview() {
        const numPools   = parseInt(document.getElementById('num_pools')?.value) || 3;
        const perPool    = parseInt(document.getElementById('teams_per_pool')?.value) || 4;
        const previewEl  = document.getElementById('preview-text');
        if (!previewEl) return;

        const total     = TOTAL_UNASSIGNED;
        const remainder = total % numPools;
        const lines     = [];

        for (let i = 0; i < numPools; i++) {
            const size = perPool + (i < remainder ? 1 : 0);
            const extra = (i < remainder) ? ' <span style="font-size:0.72rem; color:#fbbf24;">(+1 overflow)</span>' : '';
            lines.push(`<span style="color:#34d399; font-weight:600;">Pool ${POOL_LABELS[i]}</span> → ${size} teams${extra}`);
        }

        const totalAssigned = perPool * numPools + remainder;
        const unPlaced = Math.max(0, total - totalAssigned);

        let summaryColor = '#34d399';
        let summaryMsg = `✅ ${totalAssigned} / ${total} teams will be randomly distributed.`;
        if (unPlaced > 0) {
            summaryColor = '#fbbf24';
            summaryMsg = `⚠️ ${totalAssigned} / ${total} teams will be assigned. ${unPlaced} team(s) won't fit — try reducing the number of pools or increasing teams per pool.`;
        }

        previewEl.innerHTML = lines.join('<br>') +
            `<div style="margin-top:8px; padding-top:8px; border-top:1px solid rgba(16,185,129,0.15); font-size:0.78rem; color:${summaryColor};">${summaryMsg}</div>`;
    }

    // Chevron rotate for manual collapse
    document.addEventListener('DOMContentLoaded', function() {
        const collapseEl = document.getElementById('manual-pool-collapse');
        const chevron    = document.getElementById('manual-chevron');
        if (collapseEl && chevron) {
            collapseEl.addEventListener('show.bs.collapse', () => chevron.style.transform = 'rotate(180deg)');
            collapseEl.addEventListener('hide.bs.collapse', () => chevron.style.transform = 'rotate(0deg)');
            // jQuery fallback (Bootstrap 4)
            $(collapseEl).on('show.bs.collapse', () => chevron.style.transform = 'rotate(180deg)');
            $(collapseEl).on('hide.bs.collapse', () => chevron.style.transform = 'rotate(0deg)');
        }

        // Init preview on load
        updatePoolDrawPreview();
    });
</script>
@endpush
