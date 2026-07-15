@extends('layouts.dashboard')

@section('title', 'My Assignments')
@section('page-title', 'Match Assignments')

@push('styles')
<style>
.assign-card {
    background: var(--color-bg-secondary);
    border: 1px solid var(--color-border);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.2s ease;
}
.assign-card:hover {
    border-color: var(--color-border-light);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}
.assign-card-header {
    padding: 0.85rem 1.25rem;
    background: var(--color-bg-tertiary);
    border-bottom: 1px solid var(--color-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
}
.assign-card-body {
    padding: 1.25rem;
}
.team-vs-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 0.85rem;
}
.team-name-pill {
    flex: 1;
    font-size: 1rem;
    font-weight: 700;
    color: var(--color-text-primary);
    background: var(--color-bg-tertiary);
    border-radius: 8px;
    padding: 0.6rem 1rem;
    text-align: center;
    border: 1px solid var(--color-border);
}
.vs-divider {
    font-size: 0.75rem;
    font-weight: 800;
    color: var(--color-text-muted);
    letter-spacing: 1px;
    flex-shrink: 0;
}
.assign-meta-row {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    font-size: 0.78rem;
    color: var(--color-text-muted);
}
.assign-meta-item {
    display: flex;
    align-items: center;
    gap: 4px;
}
.date-group-label {
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: var(--color-text-muted);
    padding: 0.5rem 0;
    margin-bottom: 0.75rem;
    border-bottom: 1px solid var(--color-border);
    display: flex;
    align-items: center;
    gap: 6px;
}
</style>
@endpush

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #fff; margin: 0;">Match Assignments</h2>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin: 0;">All upcoming scheduled matches</p>
        </div>
        <a href="{{ route('referee.console') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-play-circle"></i> Open Console
        </a>
    </div>

    @if($upcoming->count() > 0)
        @php
            $grouped = $upcoming->groupBy(function($f) {
                return $f->start_time ? $f->start_time->format('l, d F Y') : 'Date TBD';
            });
        @endphp

        @foreach($grouped as $dateLabel => $fixtures)
            <div class="date-group-label">
                <i class="fas fa-calendar-alt" style="color: var(--color-rugby-green);"></i>
                {{ $dateLabel }}
                <span style="margin-left: auto; font-size: 0.7rem; background: var(--color-bg-tertiary); border-radius: 10px; padding: 1px 8px; border: 1px solid var(--color-border);">
                    {{ $fixtures->count() }} match{{ $fixtures->count() > 1 ? 'es' : '' }}
                </span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                @foreach($fixtures as $fix)
                    <div class="assign-card">
                        <div class="assign-card-header">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-clock" style="color: var(--color-electric-blue); font-size: 0.8rem;"></i>
                                <span style="font-weight: 700; color: #fff; font-size: 0.9rem;">
                                    {{ $fix->start_time ? $fix->start_time->format('h:i A') : 'TBD' }}
                                </span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                @if($fix->field_name)
                                    <span style="font-size: 0.7rem; font-weight: 700; color: var(--color-rugby-green-light); background: rgba(0,168,107,0.1); border: 1px solid rgba(0,168,107,0.2); padding: 1px 7px; border-radius: 4px; text-transform: uppercase;">
                                        {{ $fix->field_name }}
                                    </span>
                                @endif
                                <span class="badge badge-info" style="font-size: 0.6rem;">Scheduled</span>
                            </div>
                        </div>
                        <div class="assign-card-body">
                            <div class="team-vs-row">
                                <div class="team-name-pill">{{ $fix->homeTeam->name ?? 'TBD' }}</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name-pill">{{ $fix->awayTeam->name ?? 'TBD' }}</div>
                            </div>
                            <div class="assign-meta-row">
                                <div class="assign-meta-item">
                                    <i class="fas fa-trophy"></i>
                                    <span>{{ $fix->tournament->name ?? 'Tournament TBD' }}</span>
                                </div>
                                @if($fix->pool)
                                    <div class="assign-meta-item">
                                        <i class="fas fa-layer-group"></i>
                                        <span>{{ $fix->pool->name }}</span>
                                    </div>
                                @endif
                                @if($fix->stage)
                                    <div class="assign-meta-item">
                                        <i class="fas fa-sitemap"></i>
                                        <span>{{ ucfirst($fix->stage) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div style="margin-top: 1rem;">
                                <a href="{{ route('referee.console') }}" class="btn btn-sm btn-primary" style="width: 100%;">
                                    <i class="fas fa-flag"></i> Start Officiating
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

    @else
        <div class="glass-card" style="height: auto !important; transform: none !important;">
            <div class="card-body" style="padding: 4rem 2rem; text-align: center;">
                <div style="width: 80px; height: 80px; background: var(--color-bg-tertiary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                    <i class="fas fa-clipboard-list" style="font-size: 2rem; color: var(--color-text-muted); opacity: 0.4;"></i>
                </div>
                <h3 style="font-size: 1.2rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem;">No Upcoming Assignments</h3>
                <p style="color: var(--color-text-secondary); max-width: 400px; margin: 0 auto; font-size: 0.9rem; line-height: 1.6;">
                    No matches are currently scheduled. Assignments will appear here once the tournament organizer creates and publishes fixtures.
                </p>
            </div>
        </div>
    @endif
@endsection