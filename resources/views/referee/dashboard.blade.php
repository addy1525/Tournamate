@extends('layouts.dashboard')

@section('title', 'Referee Dashboard')
@section('page-title', 'Match Official Console')

@push('styles')
<style>
.fixture-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.25rem;
    background: var(--color-bg-secondary);
    border: 1px solid var(--color-border);
    border-radius: 10px;
    transition: all 0.2s ease;
}
.fixture-row:hover {
    border-color: var(--color-border-light);
    transform: translateX(3px);
}
.fixture-row.live {
    border-color: var(--color-warning);
    background: rgba(255, 167, 38, 0.05);
}
.fixture-teams {
    flex: 1;
    font-weight: 600;
    color: var(--color-text-primary);
    font-size: 0.95rem;
}
.fixture-meta {
    font-size: 0.75rem;
    color: var(--color-text-muted);
    margin-top: 2px;
}
.fixture-time {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--color-electric-blue);
    min-width: 60px;
    text-align: right;
}
</style>
@endpush

@section('content')
@php
    use App\Models\Fixture;
    use Carbon\Carbon;

    $todayFixtures = Fixture::with(['homeTeam','awayTeam','tournament','pool'])
        ->whereIn('status', ['scheduled','in_progress'])
        ->whereDate('start_time', Carbon::today())
        ->orderBy('start_time','asc')
        ->get();

    $liveFixtures = Fixture::where('status','in_progress')->count();
    $completedTotal = Fixture::where('status','completed')->count();

    $nextMatch = Fixture::with(['homeTeam','awayTeam','tournament'])
        ->where('status','scheduled')
        ->where('start_time','>=', now())
        ->orderBy('start_time','asc')
        ->first();
@endphp

    <div class="page-header">
        <h1 class="page-title">Welcome, {{ Auth::user()->name }}</h1>
        <p class="page-subtitle">Your match assignments and officiating tools</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-3 mb-xl">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Today's Matches</div>
                <div class="stat-value" data-stat="assignments">{{ $todayFixtures->count() }}</div>
                <div class="stat-change">
                    @if($nextMatch && $nextMatch->start_time)
                        <i class="fas fa-clock"></i> Next: {{ $nextMatch->start_time->format('h:i A') }}
                    @else
                        <i class="fas fa-check-circle"></i> No upcoming matches
                    @endif
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon {{ $liveFixtures > 0 ? 'orange' : 'green' }}">
                <i class="fas fa-{{ $liveFixtures > 0 ? 'circle-notch fa-spin' : 'check-circle' }}"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Live Matches</div>
                <div class="stat-value" data-stat="live">{{ $liveFixtures }}</div>
                <div class="stat-change {{ $liveFixtures > 0 ? 'positive' : '' }}">
                    <i class="fas fa-broadcast-tower"></i>
                    {{ $liveFixtures > 0 ? 'In progress now' : 'None in progress' }}
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-flag-checkered"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Matches Officiated</div>
                <div class="stat-value" data-stat="completed">{{ $completedTotal }}</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> Total completed
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Assignments -->
    <div class="card mb-xl">
        <div class="card-header">
            <div>
                <h2 class="card-title">Today's Match Assignments</h2>
                <p class="card-subtitle">{{ Carbon::today()->format('l, d F Y') }}</p>
            </div>
            <a href="{{ route('referee.console') }}" class="btn btn-primary">
                <i class="fas fa-play-circle"></i> Open Match Console
            </a>
        </div>
        <div class="card-body">
            @if($todayFixtures->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach($todayFixtures as $fix)
                        <div class="fixture-row {{ $fix->status === 'in_progress' ? 'live' : '' }}">
                            <div style="min-width: 52px; text-align: center;">
                                @if($fix->start_time)
                                    <div style="font-size: 0.8rem; font-weight: 700; color: var(--color-electric-blue);">
                                        {{ $fix->start_time->format('h:i') }}
                                    </div>
                                    <div style="font-size: 0.6rem; color: var(--color-text-muted); text-transform: uppercase;">
                                        {{ $fix->start_time->format('A') }}
                                    </div>
                                @else
                                    <div style="font-size: 0.75rem; color: var(--color-text-muted);">TBD</div>
                                @endif
                            </div>
                            <div style="width: 1px; height: 36px; background: var(--color-border);"></div>
                            <div class="fixture-teams" style="flex: 1;">
                                {{ $fix->homeTeam->name ?? 'TBD' }} <span style="color: var(--color-text-muted); font-weight: 400;">vs</span> {{ $fix->awayTeam->name ?? 'TBD' }}
                                <div class="fixture-meta">
                                    {{ $fix->tournament->name ?? '' }}
                                    @if($fix->field_name) · {{ $fix->field_name }} @endif
                                    @if($fix->pool) · {{ $fix->pool->name }} @endif
                                </div>
                            </div>
                            <div>
                                @if($fix->status === 'in_progress')
                                    <span class="badge badge-warning" style="animation: pulse 1.5s infinite;">● LIVE</span>
                                @else
                                    <span class="badge badge-info">Scheduled</span>
                                @endif
                            </div>
                            <a href="{{ route('referee.console') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-{{ $fix->status === 'in_progress' ? 'sync' : 'play' }}"></i>
                                {{ $fix->status === 'in_progress' ? 'Manage' : 'Start' }}
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="padding: 3rem 2rem; text-align: center;">
                    <div style="width: 70px; height: 70px; background: var(--color-bg-tertiary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem;">
                        <i class="fas fa-calendar-check" style="font-size: 1.75rem; color: var(--color-text-muted); opacity: 0.5;"></i>
                    </div>
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--color-text-primary); margin-bottom: 0.5rem;">No Matches Today</h3>
                    <p style="color: var(--color-text-secondary); font-size: 0.9rem; max-width: 400px; margin: 0 auto;">
                        You have no match assignments for today. Check your full schedule in Assignments.
                    </p>
                    <a href="{{ route('referee.assignments') }}" class="btn btn-outline btn-sm" style="margin-top: 1.25rem;">
                        <i class="fas fa-clipboard-list"></i> View All Assignments
                    </a>
                </div>
            @endif
        </div>
        @if($todayFixtures->count() > 0)
        <div class="card-footer">
            <a href="{{ route('referee.assignments') }}" style="color: var(--color-electric-blue); font-size: var(--font-size-sm); font-weight: 600;">
                View Full Schedule <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        @endif
    </div>

    <!-- Recent Matches -->
    @php
        $recentCompleted = Fixture::with(['homeTeam','awayTeam','tournament'])
            ->where('status','completed')
            ->orderBy('updated_at','desc')
            ->limit(5)
            ->get();
    @endphp
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recently Officiated Matches</h3>
            <p class="card-subtitle">Latest completed results</p>
        </div>
        <div class="card-body">
            @if($recentCompleted->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach($recentCompleted as $fix)
                        <div style="padding: 1rem 1.25rem; background: var(--color-bg-tertiary); border-radius: 8px; display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                            <div>
                                <div style="font-weight: 600; color: var(--color-text-primary); margin-bottom: 3px;">
                                    {{ $fix->homeTeam->name ?? 'TBD' }}
                                    <span style="color: var(--color-rugby-green); font-size: 1.1rem; font-weight: 800; margin: 0 6px;">
                                        {{ $fix->home_score ?? 0 }} – {{ $fix->away_score ?? 0 }}
                                    </span>
                                    {{ $fix->awayTeam->name ?? 'TBD' }}
                                </div>
                                <div style="font-size: 0.75rem; color: var(--color-text-muted);">
                                    {{ $fix->tournament->name ?? '' }}
                                    @if($fix->start_time) · {{ $fix->start_time->format('d M Y, h:i A') }} @endif
                                    @if($fix->field_name) · {{ $fix->field_name }} @endif
                                </div>
                            </div>
                            <span class="badge badge-success">Completed</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="padding: 2rem; text-align: center; color: var(--color-text-muted);">
                    <i class="fas fa-history fa-2x" style="margin-bottom: 0.75rem; opacity: 0.3;"></i>
                    <p style="font-size: 0.9rem;">No completed matches yet.</p>
                </div>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('referee.history') }}" style="color: var(--color-electric-blue); font-size: var(--font-size-sm); font-weight: 600;">
                View Full History <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

@endsection