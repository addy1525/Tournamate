@extends('layouts.dashboard')

@section('title', 'Tournaments')
@section('page-title', 'Tournament Management')

@section('content')

    <!-- Page Header & Stats -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: var(--spacing-2xl);">
        <div>
            <h1 class="page-title">Tournaments</h1>
            <p class="page-subtitle">Manage, schedule, and track all rugby tournaments</p>
        </div>
        <a href="{{ route('tournaments.create') }}" class="btn btn-primary"
            style="padding: var(--spacing-md) var(--spacing-xl); font-weight: 600;">
            <i class="fas fa-plus-circle" style="margin-right: var(--spacing-sm);"></i> Create Tournament
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="card"
        style="margin-bottom: var(--spacing-xl); background: rgba(17, 24, 39, 0.7); backdrop-filter: blur(10px);">
        <div class="card-body" style="padding: var(--spacing-md);">
            <div style="display: flex; gap: var(--spacing-md); align-items: center;">
                <div style="flex: 1; position: relative;">
                    <i class="fas fa-search"
                        style="position: absolute; left: var(--spacing-md); top: 50%; transform: translateY(-50%); color: var(--color-text-muted);"></i>
                    <input type="text" class="form-input" placeholder="Search tournaments..."
                        style="padding-left: 40px; margin-bottom: 0; background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.1);">
                </div>
                <select class="form-input"
                    style="width: 200px; margin-bottom: 0; background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.1);">
                    <option value="">All Statuses</option>
                    <option value="upcoming">Upcoming</option>
                    <option value="live">Live Now</option>
                    <option value="completed">Completed</option>
                </select>
                <div
                    style="display: flex; gap: var(--spacing-sm); border-left: 1px solid var(--color-border); padding-left: var(--spacing-md);">
                    <button class="btn btn-secondary active" style="padding: var(--spacing-sm);">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button class="btn btn-secondary"
                        style="padding: var(--spacing-sm); background: transparent; border: none;">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tournaments Grid -->
    @if($tournaments->count() > 0)
        <div class="grid grid-cols-3" style="gap: var(--spacing-xl);">
            @foreach ($tournaments as $tournament)
                @php
                    $date = \Carbon\Carbon::parse($tournament->tournament_date);
                    $isUpcoming = $date->isFuture();
                    $isToday = $date->isToday();
                    $statusColor = $isToday ? 'var(--color-rugby-green)' : ($isUpcoming ? 'var(--color-electric-blue)' : 'var(--color-text-muted)');
                    $statusText = $isToday ? 'Live Now' : ($isUpcoming ? 'Upcoming' : 'Completed');
                    $statusIcon = $isToday ? 'fa-circle-play' : ($isUpcoming ? 'fa-clock' : 'fa-check-circle');
                @endphp

                <div class="card tournament-card"
                    style="height: 100%; display: flex; flex-direction: column; position: relative; overflow: hidden; transition: all 0.3s ease;">
                    <!-- Status Stripe -->
                    <div style="position: absolute; top: 0; left: 0; bottom: 0; width: 4px; background: {{ $statusColor }};"></div>

                    <div class="card-body"
                        style="flex: 1; padding: var(--spacing-lg) var(--spacing-lg) var(--spacing-md) var(--spacing-xl);">
                        <!-- Header -->
                        <div
                            style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--spacing-md);">
                            <div class="badge"
                                style="background: {{ $statusColor }}20; color: {{ $statusColor }}; border: 1px solid {{ $statusColor }}40;">
                                <i class="fas {{ $statusIcon }}" style="margin-right: 4px; font-size: 0.7em;"></i> {{ $statusText }}
                            </div>
                            <div style="color: var(--color-text-muted); font-size: var(--font-size-xs); font-family: monospace;">
                                #{{ str_pad($tournament->id, 3, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>

                        <!-- Title -->
                        <h3 class="card-title" style="font-size: var(--font-size-lg); margin-bottom: var(--spacing-sm);">
                            <a href="{{ route('tournaments.show', $tournament->id) }}"
                                style="text-decoration: none; color: inherit; transition: color 0.2s;">
                                {{ $tournament->name }}
                            </a>
                        </h3>

                        <!-- Details -->
                        <div
                            style="display: flex; flex-direction: column; gap: var(--spacing-xs); margin-bottom: var(--spacing-lg);">
                            <div
                                style="display: flex; align-items: center; color: var(--color-text-secondary); font-size: var(--font-size-sm);">
                                <i class="fas fa-calendar-day" style="width: 20px; color: var(--color-text-muted);"></i>
                                {{ $date->format('d M Y') }}
                            </div>
                            <div
                                style="display: flex; align-items: center; color: var(--color-text-secondary); font-size: var(--font-size-sm);">
                                <i class="fas fa-map-marker-alt" style="width: 20px; color: var(--color-text-muted);"></i>
                                {{ $tournament->venue }}
                            </div>
                        </div>

                        <!-- Description Preview -->
                        <p
                            style="color: var(--color-text-muted); font-size: var(--font-size-sm); line-height: 1.5; margin-bottom: var(--spacing-lg); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $tournament->description ?? 'No description provided.' }}
                        </p>
                    </div>

                    <!-- Footer / Actions -->
                    <div
                        style="padding: var(--spacing-md) var(--spacing-xl); background: rgba(0,0,0,0.2); border-top: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center;">
                        <a href="{{ route('tournaments.show', $tournament->id) }}"
                            style="color: var(--color-text-secondary); font-size: var(--font-size-sm); font-weight: 500; text-decoration: none; display: flex; align-items: center; gap: var(--spacing-xs);">
                            View Details <i class="fas fa-arrow-right" style="font-size: 0.8em;"></i>
                        </a>

                        <div style="display: flex; gap: var(--spacing-sm);">
                            <a href="{{ route('tournaments.edit', $tournament->id) }}" class="btn-icon" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('tournaments.destroy', $tournament->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon delete-btn" title="Delete"
                                    onclick="return confirm('Are you sure you want to delete this tournament?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div style="text-align: center; padding: var(--spacing-4xl) 0;">
            <div
                style="width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-xl);">
                <i class="fas fa-trophy" style="font-size: 2.5rem; color: var(--color-text-muted); opacity: 0.5;"></i>
            </div>
            <h2 style="font-size: var(--font-size-2xl); font-weight: 700; margin-bottom: var(--spacing-md);">No Tournaments Yet
            </h2>
            <p style="color: var(--color-text-muted); max-width: 400px; margin: 0 auto var(--spacing-xl);">
                Get started by creating your first tournament. You can then add teams, schedule matches, and manage results.
            </p>
            <a href="{{ route('tournaments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create First Tournament
            </a>
        </div>
    @endif

@endsection

@push('styles')
    <style>
        .tournament-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
            border-color: var(--color-border-light);
        }

        .tournament-card:hover .card-title a {
            color: var(--color-electric-blue);
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            color: var(--color-text-muted);
            transition: all 0.2s;
            background: transparent;
            border: none;
            cursor: pointer;
        }

        .btn-icon:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--color-text-primary);
        }

        .btn-icon.delete-btn:hover {
            background: rgba(220, 38, 38, 0.1);
            color: #ef4444;
        }

        .form-input:focus {
            background: rgba(0, 0, 0, 0.4) !important;
        }
    </style>
@endpush