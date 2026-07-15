@extends('layouts.dashboard')

@section('title', 'Tournament Management')
@section('page-title', 'Tournaments')

@section('content')
    <!-- Header Widgets (Safety & Status) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card p-2 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <!-- WBGT Widget -->
                    <div class="d-flex align-items-center mr-4">
                        <div class="icon-box bg-dark rounded-circle d-flex align-items-center justify-content-center mr-2"
                            style="width: 40px; height: 40px;">
                            <i
                                class="fas fa-temperature-high {{ $latestSafetyLog && $latestSafetyLog->alert_level != 'safe' ? 'text-warning' : 'text-success' }}"></i>
                        </div>
                        <div>
                            <div class="text-tertiary small font-weight-bold" style="font-size: 0.65rem;">WBGT</div>
                            <div class="text-white font-weight-bold">{{ $latestSafetyLog->wbgt_reading ?? 'N/A' }}°C</div>
                        </div>
                    </div>

                    <!-- Lightning Widget -->
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-dark rounded-circle d-flex align-items-center justify-content-center mr-2"
                            style="width: 40px; height: 40px;">
                            <i
                                class="fas fa-bolt {{ $latestSafetyLog && $latestSafetyLog->alert_level == 'danger' ? 'text-danger' : 'text-success' }}"></i>
                        </div>
                        <div>
                            <div class="text-tertiary small font-weight-bold" style="font-size: 0.65rem;">LIGHTNING</div>
                            <div class="text-white font-weight-bold">{{ $latestSafetyLog->lightning_distance ?? 'N/A' }} km
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-tertiary small">
                    <i class="fas fa-sync-alt mr-1"></i> Updated
                    {{ $latestSafetyLog ? $latestSafetyLog->created_at->diffForHumans() : 'Just now' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Tournament Summary Section -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="glass-card p-3 d-flex align-items-center">
                <div class="rounded-circle bg-dark d-flex align-items-center justify-content-center mr-3"
                    style="width: 50px; height: 50px;">
                    <i class="fas fa-trophy text-info" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <div class="text-tertiary text-uppercase font-weight-bold" style="font-size: 0.7rem;">Total Tournaments
                    </div>
                    <h3 class="text-white font-weight-bold m-0">{{ $totalTournaments }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-card p-3 d-flex align-items-center">
                <div class="rounded-circle bg-dark d-flex align-items-center justify-content-center mr-3"
                    style="width: 50px; height: 50px;">
                    <i class="fas fa-whistle text-success" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <div class="text-tertiary text-uppercase font-weight-bold" style="font-size: 0.7rem;">Ongoing</div>
                    <h3 class="text-white font-weight-bold m-0">{{ $ongoingTournaments }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-card p-3 d-flex align-items-center">
                <div class="rounded-circle bg-dark d-flex align-items-center justify-content-center mr-3"
                    style="width: 50px; height: 50px;">
                    <i class="fas fa-medal text-warning" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <div class="text-tertiary text-uppercase font-weight-bold" style="font-size: 0.7rem;">Completed</div>
                    <h3 class="text-white font-weight-bold m-0">{{ $completedTournaments }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Actions -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-dark border-secondary text-tertiary"><i
                            class="fas fa-search"></i></span>
                </div>
                <input type="text" class="form-control bg-dark border-secondary text-white"
                    placeholder="Search tournaments..." id="searchTournament">

                <div class="input-group-append ml-2">
                    <select class="custom-select bg-dark border-secondary text-white" id="filterStatus">
                        <option value="all">All Statuses</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('admin.tournaments.create') }}"
                class="btn btn-success font-weight-bold shadow-glow-green px-4 rounded-pill">
                <i class="fas fa-plus mr-1"></i> Create Tournament
            </a>
        </div>
    </div>

    <!-- Tournaments List (Refined Cards) -->
    <div class="row">
        @forelse($tournaments as $tournament)
            @php
                $statusColor = 'secondary';
                $statusGlow = '';
                $borderClass = 'border-left-secondary';
                $statusLabel = 'COMPLETED';

                if ($tournament->status == 'upcoming') {
                    $statusColor = 'info';
                    $statusGlow = 'shadow-glow-blue';
                    $borderClass = 'border-left-info';
                    $statusLabel = 'UPCOMING';
                } elseif ($tournament->status == 'ongoing') {
                    $statusColor = 'success';
                    $statusGlow = 'shadow-glow-green';
                    $borderClass = 'border-left-success';
                    $statusLabel = 'LIVE NOW';
                }
            @endphp

            <div class="col-md-6 col-lg-4 mb-4">
                <div class="glass-card h-100 hover-lift tour-card position-relative overflow-hidden"
                    style="border-left: 3px solid var(--{{ $statusColor }});">

                    <div class="card-body p-4 d-flex flex-column h-100">
                        <!-- Header: Status & ID -->
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <span
                                class="badge badge-{{ $statusColor }} rounded-pill px-3 py-1 font-weight-bold {{ $statusGlow }}"
                                style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                <i class="fas fa-circle mr-1" style="font-size: 6px; vertical-align: middle;"></i>
                                {{ $statusLabel }}
                            </span>
                            <span class="text-tertiary font-weight-normal font-monospace small"
                                style="opacity: 0.6;">#{{ str_pad($tournament->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        <!-- Title -->
                        <h3 class="text-white font-weight-bold mb-3" style="font-size: 1.35rem; letter-spacing: -0.5px;">
                            {{ $tournament->name }}
                        </h3>

                        <!-- Metadata -->
                        <div class="d-flex flex-column mb-4">
                            <div class="d-flex align-items-center mb-2 text-tertiary">
                                <div style="width: 20px;" class="text-center mr-2"><i class="far fa-calendar-alt small"></i>
                                </div>
                                <span class="small font-weight-medium"
                                    style="color: #a0aec0;">{{ optional($tournament->start_date)->format('d M Y') ?? 'Date TBA' }}</span>
                            </div>
                            <div class="d-flex align-items-center text-tertiary">
                                <div style="width: 20px;" class="text-center mr-2"><i class="fas fa-map-marker-alt small"></i>
                                </div>
                                <span class="small font-weight-medium"
                                    style="color: #a0aec0;">{{ $tournament->venue ?? 'Venue TBA' }}</span>
                            </div>
                        </div>

                        <!-- Category Tag -->
                        <div class="mb-4">
                            <span class="d-inline-block px-2 py-1 rounded small font-weight-bold text-uppercase"
                                style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: #cbd5e0; font-size: 0.65rem;">
                                {{ $tournament->description ?? 'Open Category' }}
                            </span>
                        </div>

                        <!-- Footer -->
                        <div class="mt-auto pt-3 d-flex justify-content-between align-items-center"
                            style="border-top: 1px solid rgba(255, 255, 255, 0.08);">
                            <a href="{{ route('admin.tournaments.edit', $tournament->id) }}"
                                class="text-white small font-weight-bold text-decoration-none group-hover-arrow">
                                View Details <i class="fas fa-arrow-right ml-1 transition-transform"></i>
                            </a>
                            <div class="action-icons">
                                <a href="{{ route('admin.tournaments.registrations', $tournament->id) }}"
                                    class="text-tertiary hover-text-success mr-3" title="View Registrations">
                                    <i class="fas fa-clipboard-list"></i>
                                </a>
                                <a href="{{ route('admin.tournaments.matches', $tournament->id) }}"
                                    class="text-tertiary hover-text-info mr-3" title="Manage Matches">
                                    <i class="fas fa-sitemap"></i>
                                </a>
                                <a href="{{ route('admin.tournaments.assignTeams', $tournament->id) }}"
                                    class="text-tertiary hover-text-info mr-3" title="Assign Teams">
                                    <i class="fas fa-user-plus"></i>
                                </a>
                                <form action="{{ route('admin.tournaments.destroy', $tournament->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link p-0 text-tertiary hover-text-danger"
                                        title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="glass-card p-5 text-center text-muted">
                    <i class="fas fa-trophy mb-3" style="font-size: 3rem; opacity: 0.2;"></i>
                    <p class="m-0">No tournaments found.</p>
                </div>
            </div>
        @endforelse
    </div>
@endsection

@push('styles')
    <style>
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
        }

        .hover-text-info:hover {
            color: #3490dc !important;
        }

        .hover-text-danger:hover {
            color: #e3342f !important;
        }

        .hover-text-success:hover {
            color: #38c172 !important;
        }

        .shadow-glow-green {
            box-shadow: 0 0 10px rgba(0, 168, 107, 0.5);
        }

        .shadow-glow-blue {
            box-shadow: 0 0 10px rgba(52, 144, 220, 0.5);
        }

        .font-monospace {
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        }

        .group-hover-arrow:hover .transition-transform {
            transform: translateX(3px);
        }

        .transition-transform {
            transition: transform 0.2s;
        }

        /* Variable Colors for Border Left */
        .border-left-info {
            border-left-color: #3490dc !important;
        }

        .border-left-success {
            border-left-color: #38c172 !important;
        }

        .border-left-secondary {
            border-left-color: #6c757d !important;
        }
    </style>
@endpush