@extends('layouts.dashboard')

@section('title', $tournament->name)
@section('page-title', 'Tournament Overview')

@section('content')

    <!-- Tournament Hero Section -->
    <div
        style="background: linear-gradient(135deg, var(--color-rugby-green), var(--color-rugby-green-dark)); border-radius: 16px; padding: var(--spacing-2xl); margin-bottom: var(--spacing-2xl); position: relative; overflow: hidden;">
        <!-- Background Pattern -->
        <div style="position: absolute; top: 0; right: 0; bottom: 0; left: 0; opacity: 0.1;">
            <i class="fas fa-trophy"
                style="position: absolute; top: -50px; right: -50px; font-size: 300px; color: white; transform: rotate(-15deg);"></i>
        </div>

        <!-- Hero Content -->
        <div style="position: relative; z-index: 1;">
            <div
                style="display: flex; justify-content: space-between; align-items: start; margin-bottom: var(--spacing-lg);">
                <div style="flex: 1;">
                    <div
                        style="display: inline-block; padding: var(--spacing-xs) var(--spacing-md); background: rgba(255, 255, 255, 0.2); border-radius: 20px; margin-bottom: var(--spacing-md);">
                        <span style="color: white; font-size: var(--font-size-sm); font-weight: 600;">
                            <i class="fas fa-calendar-alt"></i> Tournament
                            #{{ str_pad($tournament->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                    <h1
                        style="font-size: var(--font-size-4xl); font-weight: 800; color: white; margin: 0 0 var(--spacing-md) 0; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                        {{ $tournament->name }}
                    </h1>
                    <div style="display: flex; gap: var(--spacing-xl); flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: var(--spacing-sm);">
                            <div
                                style="width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-day" style="color: white; font-size: 1.25rem;"></i>
                            </div>
                            <div>
                                <div style="font-size: var(--font-size-xs); color: rgba(255,255,255,0.8);">Date</div>
                                <div style="font-size: var(--font-size-base); font-weight: 600; color: white;">
                                    {{ \Carbon\Carbon::parse($tournament->tournament_date)->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: var(--spacing-sm);">
                            <div
                                style="width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-map-marker-alt" style="color: white; font-size: 1.25rem;"></i>
                            </div>
                            <div>
                                <div style="font-size: var(--font-size-xs); color: rgba(255,255,255,0.8);">Venue</div>
                                <div style="font-size: var(--font-size-base); font-weight: 600; color: white;">
                                    {{ $tournament->venue }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="display: flex; gap: var(--spacing-sm);">
                    <a href="{{ route('tournaments.edit', $tournament->id) }}" class="btn btn-secondary"
                        style="background: rgba(255,255,255,0.9); color: #0f172a; border: none; font-weight: 600;">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('tournaments.index') }}" class="btn btn-secondary"
                        style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="grid grid-cols-4" style="gap: var(--spacing-lg); margin-bottom: var(--spacing-2xl);">
        <div class="card" style="text-align: center; padding: var(--spacing-xl);">
            <div
                style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--color-electric-blue), var(--color-electric-blue-dark)); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-md);">
                <i class="fas fa-users" style="font-size: 1.75rem; color: white;"></i>
            </div>
            <div
                style="font-size: var(--font-size-3xl); font-weight: 800; color: var(--color-text-primary); margin-bottom: var(--spacing-xs);">
                0
            </div>
            <div style="font-size: var(--font-size-sm); color: var(--color-text-muted);">
                Teams Registered
            </div>
        </div>

        <div class="card" style="text-align: center; padding: var(--spacing-xl);">
            <div
                style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--color-rugby-green), var(--color-rugby-green-dark)); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-md);">
                <i class="fas fa-clipboard-list" style="font-size: 1.75rem; color: white;"></i>
            </div>
            <div
                style="font-size: var(--font-size-3xl); font-weight: 800; color: var(--color-text-primary); margin-bottom: var(--spacing-xs);">
                0
            </div>
            <div style="font-size: var(--font-size-sm); color: var(--color-text-muted);">
                Matches Scheduled
            </div>
        </div>

        <div class="card" style="text-align: center; padding: var(--spacing-xl);">
            <div
                style="width: 60px; height: 60px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-md);">
                <i class="fas fa-flag" style="font-size: 1.75rem; color: white;"></i>
            </div>
            <div
                style="font-size: var(--font-size-3xl); font-weight: 800; color: var(--color-text-primary); margin-bottom: var(--spacing-xs);">
                0
            </div>
            <div style="font-size: var(--font-size-sm); color: var(--color-text-muted);">
                Officials Assigned
            </div>
        </div>

        <div class="card" style="text-align: center; padding: var(--spacing-xl);">
            <div
                style="width: 60px; height: 60px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-md);">
                <i class="fas fa-eye" style="font-size: 1.75rem; color: white;"></i>
            </div>
            <div
                style="font-size: var(--font-size-3xl); font-weight: 800; color: var(--color-text-primary); margin-bottom: var(--spacing-xs);">
                0
            </div>
            <div style="font-size: var(--font-size-sm); color: var(--color-text-muted);">
                Spectators
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-3" style="gap: var(--spacing-xl);">
        <!-- Left Column -->
        <div style="grid-column: span 2;">
            <!-- Tournament Description -->
            <div class="card" style="margin-bottom: var(--spacing-xl);">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-info-circle" style="color: var(--color-electric-blue);"></i>
                        Tournament Description
                    </h2>
                </div>
                <div class="card-body">
                    @if($tournament->description)
                        <p style="color: var(--color-text-secondary); line-height: 1.8; margin: 0; white-space: pre-wrap;">
                            {{ $tournament->description }}</p>
                    @else
                        <div style="text-align: center; padding: var(--spacing-2xl); color: var(--color-text-muted);">
                            <i class="fas fa-file-alt"
                                style="font-size: 3rem; opacity: 0.3; margin-bottom: var(--spacing-md);"></i>
                            <p style="margin: 0;">No description provided for this tournament.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Registered Teams -->
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">
                            <i class="fas fa-users" style="color: var(--color-rugby-green);"></i>
                            Registered Teams
                        </h2>
                        <p class="card-subtitle">Teams participating in this tournament</p>
                    </div>
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Team
                    </button>
                </div>
                <div class="card-body">
                    <div style="text-align: center; padding: var(--spacing-2xl); color: var(--color-text-muted);">
                        <i class="fas fa-users"
                            style="font-size: 3rem; opacity: 0.3; margin-bottom: var(--spacing-md);"></i>
                        <p style="margin: 0 0 var(--spacing-md) 0;">No teams registered yet</p>
                        <button class="btn btn-sm btn-secondary">
                            <i class="fas fa-plus-circle"></i> Register First Team
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div>
            <!-- Tournament Info -->
            <div class="card" style="margin-bottom: var(--spacing-xl);">
                <div class="card-header">
                    <h3 class="card-title" style="font-size: var(--font-size-lg);">
                        <i class="fas fa-file-alt"></i> Details
                    </h3>
                </div>
                <div class="card-body">
                    <div style="display: flex; flex-direction: column; gap: var(--spacing-lg);">
                        <!-- Status -->
                        <div>
                            <div
                                style="font-size: var(--font-size-xs); color: var(--color-text-muted); margin-bottom: var(--spacing-xs);">
                                Status
                            </div>
                            <span class="badge badge-info" style="font-size: var(--font-size-sm);">
                                <i class="fas fa-clock"></i> Upcoming
                            </span>
                        </div>

                        <!-- Created Date -->
                        <div>
                            <div
                                style="font-size: var(--font-size-xs); color: var(--color-text-muted); margin-bottom: var(--spacing-xs);">
                                Created
                            </div>
                            <div
                                style="font-size: var(--font-size-sm); color: var(--color-text-secondary); font-weight: 500;">
                                {{ $tournament->created_at->format('d M Y, g:i A') }}
                            </div>
                        </div>

                        <!-- Last Updated -->
                        <div>
                            <div
                                style="font-size: var(--font-size-xs); color: var(--color-text-muted); margin-bottom: var(--spacing-xs);">
                                Last Updated
                            </div>
                            <div
                                style="font-size: var(--font-size-sm); color: var(--color-text-secondary); font-weight: 500;">
                                {{ $tournament->updated_at->diffForHumans() }}
                            </div>
                        </div>

                        <!-- Days Until Tournament -->
                        <div>
                            <div
                                style="font-size: var(--font-size-xs); color: var(--color-text-muted); margin-bottom: var(--spacing-xs);">
                                Countdown
                            </div>
                            <div style="font-size: var(--font-size-lg); color: var(--color-rugby-green); font-weight: 700;">
                                {{ \Carbon\Carbon::parse($tournament->tournament_date)->diffInDays(now()) }} days
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title" style="font-size: var(--font-size-lg);">
                        <i class="fas fa-bolt"></i> Quick Actions
                    </h3>
                </div>
                <div class="card-body">
                    <div style="display: flex; flex-direction: column; gap: var(--spacing-sm);">
                        <button class="btn btn-secondary" style="justify-content: flex-start;">
                            <i class="fas fa-calendar-plus"></i> Schedule Matches
                        </button>
                        <button class="btn btn-secondary" style="justify-content: flex-start;">
                            <i class="fas fa-sitemap"></i> Configure Pools
                        </button>
                        <button class="btn btn-secondary" style="justify-content: flex-start;">
                            <i class="fas fa-user-tie"></i> Assign Officials
                        </button>
                        <button class="btn btn-secondary" style="justify-content: flex-start;">
                            <i class="fas fa-share-alt"></i> Share Tournament
                        </button>
                        <hr style="border: none; border-top: 1px solid var(--color-border); margin: var(--spacing-sm) 0;">
                        <form action="{{ route('tournaments.destroy', $tournament->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="width: 100%; justify-content: flex-start;"
                                onclick="return confirm('Are you sure you want to delete this tournament? This action cannot be undone.')">
                                <i class="fas fa-trash"></i> Delete Tournament
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        /* Tournament show page enhancements */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .card:hover {
            transform: translateY(-2px);
            transition: all var(--transition-base);
        }

        /* Stat card hover effect */
        .grid>.card:hover {
            box-shadow: var(--shadow-lg);
        }
    </style>
@endpush