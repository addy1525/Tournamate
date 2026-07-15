@extends('layouts.dashboard')

@section('title', 'Team Roster')

@section('content')
    <!-- Header -->
    <div class="page-header" style="margin-bottom: var(--spacing-xl);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="page-title">{{ $team->name }}</h1>
                <p style="color: var(--color-text-secondary); margin-top: var(--spacing-sm);">
                    <i class="fas fa-trophy"></i> {{ $tournament->name }}
                </p>
            </div>
            <a href="{{ route('admin.tournaments.registrations', $tournament->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Registrations
            </a>
        </div>
    </div>

    <!-- Team Info Card -->
    <div class="card mb-xl">
        <div class="card-header">
            <h3 class="card-title">Team Information</h3>
            @if($registration)
                @if($registration->payment_status === 'paid')
                    <span class="badge badge-success">
                        <i class="fas fa-check-circle"></i> Payment Confirmed
                    </span>
                @else
                    <span class="badge badge-warning">
                        <i class="fas fa-clock"></i> Payment Pending
                    </span>
                @endif
            @endif
        </div>
        <div class="card-body">
            <div class="grid grid-cols-3" style="gap: var(--spacing-xl);">
                <div>
                    <h4
                        style="color: var(--color-text-muted); font-size: var(--font-size-xs); text-transform: uppercase; margin-bottom: var(--spacing-sm);">
                        Team Logo
                    </h4>
                    @if($team->logo)
                        <img src="{{ asset('storage/' . $team->logo) }}"
                            style="width: 100px; height: 100px; border-radius: 12px; object-fit: cover; border: 2px solid var(--color-border);">
                    @else
                        <div
                            style="width: 100px; height: 100px; border-radius: 12px; background: var(--color-bg-tertiary); display: flex; align-items: center; justify-content: center; border: 2px solid var(--color-border);">
                            <i class="fas fa-shield-alt fa-3x" style="color: var(--color-text-muted);"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <div style="margin-bottom: var(--spacing-lg);">
                        <h4
                            style="color: var(--color-text-muted); font-size: var(--font-size-xs); text-transform: uppercase; margin-bottom: var(--spacing-sm);">
                            Manager
                        </h4>
                        <p style="font-size: var(--font-size-lg); font-weight: 600;">{{ $team->manager->name }}</p>
                        <p style="color: var(--color-text-secondary); font-size: var(--font-size-sm);">
                            <i class="fas fa-envelope"></i> {{ $team->manager->email }}
                        </p>
                        @if($team->manager->phone)
                            <p style="color: var(--color-text-secondary); font-size: var(--font-size-sm);">
                                <i class="fas fa-phone"></i> {{ $team->manager->phone }}
                            </p>
                        @endif
                    </div>
                </div>
                <div>
                    <div style="margin-bottom: var(--spacing-md);">
                        <h4
                            style="color: var(--color-text-muted); font-size: var(--font-size-xs); text-transform: uppercase; margin-bottom: var(--spacing-sm);">
                            Head Coach
                        </h4>
                        <p style="font-size: var(--font-size-lg); font-weight: 600;">
                            {{ $team->head_coach ?? 'Not specified' }}
                        </p>
                    </div>
                    @if($registration)
                        <div>
                            <h4
                                style="color: var(--color-text-muted); font-size: var(--font-size-xs); text-transform: uppercase; margin-bottom: var(--spacing-sm);">
                                Registration Date
                            </h4>
                            <p style="font-size: var(--font-size-md);">
                                {{ $registration->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Player Roster -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Player Roster</h3>
                <p
                    style="color: var(--color-text-secondary); font-size: var(--font-size-sm); margin-top: var(--spacing-xs);">
                    <i class="fas fa-users"></i> {{ $team->players->count() }} /
                    {{ $tournament->max_players_per_team ?? 23 }} Players Registered
                </p>
            </div>
            <button onclick="window.print()" class="btn btn-secondary">
                <i class="fas fa-print"></i> Print Roster
            </button>
        </div>
        <div class="card-body">
            @if($team->players->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Jersey No</th>
                            <th>Player Name</th>
                            <th>Position</th>
                            <th>IC/ID Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($team->players->sortBy('jersey_number') as $player)
                            <tr>
                                <td>
                                    <strong style="color: var(--color-rugby-green); font-size: var(--font-size-lg);">
                                        #{{ $player->jersey_number }}
                                    </strong>
                                </td>
                                <td>
                                    <strong>{{ $player->name }}</strong>
                                </td>
                                <td>
                                    <span class="badge {{ $player->position == 'Forward' ? 'badge-info' : 'badge-warning' }}">
                                        {{ $player->position }}
                                    </span>
                                </td>
                                <td>{{ $player->ic_number }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="text-align: center; padding: var(--spacing-xxl); color: var(--color-text-muted);">
                    <i class="fas fa-users-slash fa-4x" style="margin-bottom: var(--spacing-lg);"></i>
                    <h3 style="color: var(--color-text-secondary); margin-bottom: var(--spacing-sm);">No Players Added</h3>
                    <p style="font-size: var(--font-size-sm);">The manager hasn't added any players to this team yet</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        @media print {

            .page-header a,
            .card-header button,
            .sidebar,
            .navbar {
                display: none !important;
            }

            .card {
                border: 1px solid #000;
                page-break-inside: avoid;
            }
        }
    </style>
@endsection