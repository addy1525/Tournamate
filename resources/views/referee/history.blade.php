@extends('layouts.dashboard')

@section('title', 'Match History')
@section('page-title', 'Officiating History')

@push('styles')
<style>
.history-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1rem 1.25rem;
    background: var(--color-bg-secondary);
    border: 1px solid var(--color-border);
    border-radius: 10px;
    transition: all 0.2s ease;
}
.history-row:hover {
    border-color: var(--color-border-light);
}
.score-display {
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--color-rugby-green-light);
    font-family: 'Outfit', sans-serif;
    letter-spacing: -0.5px;
    white-space: nowrap;
}
</style>
@endpush

@section('content')
    <!-- Stats Row -->
    <div class="grid grid-cols-2 mb-xl" style="gap: 1rem;">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-flag-checkered"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Officiated</div>
                <div class="stat-value">{{ $totalMatches }}</div>
                <div class="stat-change positive"><i class="fas fa-check-circle"></i> Completed matches</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-trophy"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Tournaments</div>
                <div class="stat-value">{{ $totalTournaments }}</div>
                <div class="stat-change"><i class="fas fa-layer-group"></i> Different tournaments</div>
            </div>
        </div>
    </div>

    <div class="glass-card" style="height: auto !important; transform: none !important;">
        <div class="glass-header" style="border-bottom: 1px solid rgba(255,255,255,0.06) !important;">
            <div>
                <h3 class="card-title text-white font-weight-bold m-0" style="font-size: 1rem;"><i class="fas fa-history text-info mr-2"></i> Completed Match Records</h3>
                <p class="card-subtitle text-secondary m-0" style="font-size: 0.8rem; margin-top: 3px;">All matches you have officiated</p>
            </div>
        </div>
        <div class="card-body">
            @if($completed->count() > 0)
                @php
                    $grouped = $completed->groupBy(function($f) {
                        return $f->start_time ? $f->start_time->format('l, d F Y') : 'Date Unknown';
                    });
                @endphp

                @foreach($grouped as $dateLabel => $fixtures)
                    <div style="font-size: 0.75rem; font-weight: 800; letter-spacing: 0.5px; text-transform: uppercase; color: var(--color-text-muted); padding: 0.5rem 0; margin-bottom: 0.75rem; border-bottom: 1px solid var(--color-border); display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-calendar-check" style="color: var(--color-success);"></i>
                        {{ $dateLabel }}
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.65rem; margin-bottom: 1.75rem;">
                        @foreach($fixtures as $fix)
                            <div class="history-row">
                                <div style="flex: 1; min-width: 0;">
                                    <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                                        <span style="font-weight: 600; color: var(--color-text-primary); font-size: 0.95rem;">
                                            {{ $fix->homeTeam->name ?? 'TBD' }}
                                        </span>
                                        <span class="score-display">
                                            {{ $fix->home_score ?? 0 }} – {{ $fix->away_score ?? 0 }}
                                        </span>
                                        <span style="font-weight: 600; color: var(--color-text-primary); font-size: 0.95rem;">
                                            {{ $fix->awayTeam->name ?? 'TBD' }}
                                        </span>
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--color-text-muted); margin-top: 3px; display: flex; gap: 0.75rem; flex-wrap: wrap;">
                                        @if($fix->tournament)
                                            <span><i class="fas fa-trophy"></i> {{ $fix->tournament->name }}</span>
                                        @endif
                                        @if($fix->pool)
                                            <span><i class="fas fa-layer-group"></i> {{ $fix->pool->name }}</span>
                                        @endif
                                        @if($fix->field_name)
                                            <span><i class="fas fa-map-marker-alt"></i> {{ $fix->field_name }}</span>
                                        @endif
                                        @if($fix->start_time)
                                            <span><i class="fas fa-clock"></i> {{ $fix->start_time->format('h:i A') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="badge badge-success" style="flex-shrink: 0;">Completed</span>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                <div style="padding: 4rem 2rem; text-align: center;">
                    <div style="width: 80px; height: 80px; background: var(--color-bg-tertiary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                        <i class="fas fa-history" style="font-size: 2rem; color: var(--color-text-muted); opacity: 0.4;"></i>
                    </div>
                    <h3 style="font-size: 1.2rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem;">No Match History Yet</h3>
                    <p style="color: var(--color-text-secondary); max-width: 400px; margin: 0 auto; font-size: 0.9rem; line-height: 1.6;">
                        Your completed match records will appear here after officiating matches through the Match Console.
                    </p>
                    <a href="{{ route('referee.console') }}" class="btn btn-primary btn-sm" style="margin-top: 1.5rem;">
                        <i class="fas fa-whistle"></i> Go to Match Console
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection