@extends('layouts.dashboard')

@section('title', 'Tournament Info')
@section('page-title', 'Tournament Information')

@section('content')
    @if($tournaments && $tournaments->count() > 1)
        <!-- Tournament Selector -->
        <div class="card mb-lg" style="background: var(--color-bg-secondary); border: 1px solid var(--color-border);">
            <div class="card-body" style="padding: var(--spacing-lg);">
                <div style="display: flex; align-items: center; gap: var(--spacing-md);">
                    <div style="flex: 0 0 auto;">
                        <i class="fas fa-trophy" style="color: var(--color-rugby-green); font-size: 1.25rem;"></i>
                    </div>
                    <div style="flex: 1;">
                        <label for="tournament-selector"
                            style="font-size: var(--font-size-sm); color: var(--color-text-muted); margin-bottom: var(--spacing-xs); display: block; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                            Select Tournament
                        </label>
                        <select id="tournament-selector" class="form-control"
                            style="max-width: 400px; background: var(--color-bg-primary); border-color: var(--color-border); color: var(--color-text-primary); font-weight: 600;"
                            onchange="const url = new URL(window.location.href); url.searchParams.set('tournament_id', this.value); window.location.href = url.toString();">
                            @foreach($tournaments as $t)
                                <option value="{{ $t->id }}" {{ $tournament && $tournament->id == $t->id ? 'selected' : '' }}>
                                    {{ $t->name }} - {{ $t->start_date ? $t->start_date->format('M d, Y') : 'TBD' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">About the Tournament</h3>
        </div>
        <div class="card-body">
            @if($tournament)
                <!-- Dynamic Tournament Info -->
                <h2 style="font-size: 2rem; color: var(--color-text-primary); margin-bottom: var(--spacing-sm);">
                    {{ $tournament->name }}
                </h2>
                <p style="color: var(--color-text-secondary); margin-bottom: var(--spacing-lg);">
                    {{ $tournament->description ?? 'No description available for this tournament.' }}
                </p>

                <hr style="margin: var(--spacing-lg) 0; border: 0; border-top: 1px solid var(--color-border);">

                <div class="grid grid-cols-2" style="gap: var(--spacing-xl);">
                    <div>
                        <h4
                            style="margin-bottom: var(--spacing-xs); color: var(--color-text-tertiary); text-transform: uppercase; font-size: var(--font-size-xs); letter-spacing: 0.5px;">
                            Dates</h4>
                        <div
                            style="font-size: var(--font-size-lg); font-weight: 600; display: flex; align-items: center; gap: var(--spacing-sm);">
                            <i class="fas fa-calendar-alt" style="color: var(--color-rugby-green);"></i>
                            {{ \Carbon\Carbon::parse($tournament->start_date)->format('d M Y') }}
                            @if($tournament->end_date)
                                - {{ \Carbon\Carbon::parse($tournament->end_date)->format('d M Y') }}
                            @endif
                        </div>
                    </div>
                    <div>
                        <h4
                            style="margin-bottom: var(--spacing-xs); color: var(--color-text-tertiary); text-transform: uppercase; font-size: var(--font-size-xs); letter-spacing: 0.5px;">
                            Venue</h4>
                        <div
                            style="font-size: var(--font-size-lg); font-weight: 600; display: flex; align-items: center; gap: var(--spacing-sm);">
                            <i class="fas fa-map-marker-alt" style="color: var(--color-rugby-green);"></i>
                            {{ $tournament->venue ?? 'Venue to be decided' }}
                        </div>
                    </div>
                    <div>
                        <h4
                            style="margin-bottom: var(--spacing-xs); color: var(--color-text-tertiary); text-transform: uppercase; font-size: var(--font-size-xs); letter-spacing: 0.5px;">
                            Status</h4>
                        <div>
                            <span
                                class="badge {{ $tournament->status == 'live' ? 'badge-danger' : ($tournament->status == 'completed' ? 'badge-neutral' : 'badge-info') }}">
                                {{ ucfirst($tournament->status) }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <h4
                            style="margin-bottom: var(--spacing-xs); color: var(--color-text-tertiary); text-transform: uppercase; font-size: var(--font-size-xs); letter-spacing: 0.5px;">
                            Organizer</h4>
                        <div
                            style="font-size: var(--font-size-md); display: flex; align-items: center; gap: var(--spacing-sm);">
                            <i class="fas fa-user-shield" style="color: var(--color-electric-blue);"></i>
                            Official Organizer
                        </div>
                    </div>
                </div>

                <div
                    style="margin-top: var(--spacing-2xl); padding-top: var(--spacing-xl); border-top: 1px solid var(--color-border);">
                    <a href="mailto:info@tournamate.com" class="btn btn-secondary">
                        <i class="fas fa-envelope"></i> Contact Organizer
                    </a>
                </div>

            @else
                <!-- No Tournament State -->
                <div style="text-align: center; padding: var(--spacing-2xl);">
                    <i class="fas fa-info-circle fa-3x"
                        style="color: var(--color-text-muted); opacity: 0.3; margin-bottom: var(--spacing-lg);"></i>
                    <h3>No Information Available</h3>
                    <p>There is currently no active tournament to display.</p>
                </div>
            @endif
        </div>
    </div>
@endsection