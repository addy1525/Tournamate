@extends('layouts.dashboard')

@section('title', 'Spectator Dashboard')
@section('page-title', 'Tournament View')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Welcome, {{ Auth::user()->name }}!</h1>
        <p class="page-subtitle">Follow live matches and tournament progress</p>
    </div>

    <!-- Weather Safety Alert Banner -->
    @php
        $latestSafetyLog = $tournament ? \App\Models\SafetyLog::where('tournament_id', $tournament->id)->latest()->first() : null;
        $hasAlert = $latestSafetyLog && ($latestSafetyLog->alert_level === 'danger' || $latestSafetyLog->alert_level === 'warning');
        $bannerBorderColor = $hasAlert && $latestSafetyLog->alert_level === 'danger' ? '#ef4444' : '#f59e0b';
        $bannerBoxShadow = $hasAlert && $latestSafetyLog->alert_level === 'danger' ? 'rgba(239, 68, 68, 0.4)' : 'rgba(245, 158, 11, 0.4)';
    @endphp
    <div id="weather-safety-alert" class="alert alert-danger-glow mb-4 {{ $hasAlert ? '' : 'd-none' }}" 
         style="background: rgba(30, 41, 59, 0.85); border: 2px solid {{ $bannerBorderColor }}; border-radius: 12px; padding: 15px 20px; box-shadow: 0 0 25px {{ $bannerBoxShadow }};">
        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-triangle-exclamation text-danger mr-3" style="font-size: 1.8rem; color: {{ $bannerBorderColor }} !important;"></i>
                <div>
                    <h4 class="text-white font-weight-bold mb-1" id="weather-alert-title">WEATHER DELAY: {{ $hasAlert ? strtoupper($latestSafetyLog->alert_level) : '' }}</h4>
                    <p class="text-tertiary mb-0" style="font-size: 0.85rem;" id="weather-alert-desc">
                        {{ $hasAlert ? ($latestSafetyLog->notes ?: "Severe weather conditions detected (WBGT: {$latestSafetyLog->wbgt}°C, Lightning: {$latestSafetyLog->lightning_risk}km). Play may be temporarily delayed.") : '' }}
                    </p>
                </div>
            </div>
            <a href="{{ route('shared.live-stream') }}" class="btn btn-sm btn-danger">Watch Live Stream</a>
        </div>
    </div>

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


    @if($tournament)
        <!-- Hero Tournament Card -->
        <div class="card mb-xl overflow-hidden" style="border: none; box-shadow: var(--shadow-lg);">
            <!-- Hero Header with Gradient -->
            <div
                style="background: linear-gradient(135deg, var(--color-bg-secondary) 0%, var(--color-bg-tertiary) 100%); padding: var(--spacing-2xl); position: relative; border-bottom: 1px solid var(--color-border);">
                <div style="position: absolute; top: 0; right: 0; padding: var(--spacing-md); opacity: 0.1;">
                    <i class="fas fa-trophy" style="font-size: 8rem; color: var(--color-text-primary);"></i>
                </div>

                <div style="position: relative; z-index: 1;">
                    <div
                        style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--spacing-md);">
                        <span
                            class="badge {{ $tournament->status == 'completed' ? 'badge-neutral' : ($tournament->status == 'live' ? 'badge-danger' : 'badge-info') }}"
                            style="font-size: var(--font-size-sm); padding: 0.5em 1em;">
                            {{ $tournament->status == 'live' ? '● LIVE' : strtoupper($tournament->status) }}
                        </span>
                    </div>

                    <h2
                        style="font-size: 3rem; font-weight: 800; color: var(--color-text-primary); margin-bottom: var(--spacing-lg); line-height: 1.1; letter-spacing: -1px;">
                        {{ $tournament->name }}
                    </h2>

                    <div style="display: flex; gap: var(--spacing-2xl); flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: var(--spacing-md);">
                            <div
                                style="width: 48px; height: 48px; background: rgba(0, 168, 107, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-alt" style="color: var(--color-rugby-green); font-size: 1.25rem;"></i>
                            </div>
                            <div>
                                <div
                                    style="font-size: var(--font-size-xs); color: var(--color-text-tertiary); text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                    Date</div>
                                <div style="font-weight: 600; color: var(--color-text-primary);">
                                    {{ \Carbon\Carbon::parse($tournament->start_date)->format('d M Y') }}
                                    @if($tournament->end_date)
                                        - {{ \Carbon\Carbon::parse($tournament->end_date)->format('d M Y') }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: var(--spacing-md);">
                            <div
                                style="width: 48px; height: 48px; background: rgba(0, 168, 107, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-map-marker-alt"
                                    style="color: var(--color-rugby-green); font-size: 1.25rem;"></i>
                            </div>
                            <div>
                                <div
                                    style="font-size: var(--font-size-xs); color: var(--color-text-tertiary); text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                    Location</div>
                                <div style="font-weight: 600; color: var(--color-text-primary);">
                                    {{ $tournament->venue ?? 'Venue TBD' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contextual Status Area -->
            <div class="card-body" style="background: var(--color-bg-primary);">
                @if($tournament->status == 'upcoming')
                    <!-- Upcoming Empty State -->
                    <div style="text-align: center; padding: var(--spacing-xl) 0;">
                        <div
                            style="width: 80px; height: 80px; background: var(--color-bg-tertiary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-lg);">
                            <i class="fas fa-hourglass-start" style="font-size: 2rem; color: var(--color-text-muted);"></i>
                        </div>

                        <h3 style="font-size: var(--font-size-xl); font-weight: 700; margin-bottom: var(--spacing-sm);">Tournament
                            Ready to Start</h3>
                        <p
                            style="color: var(--color-text-secondary); max-width: 450px; margin: 0 auto var(--spacing-xl); line-height: 1.6;">
                            All systems are go! Match schedules and live updates will appear here automatically when the first match
                            kicks off.
                        </p>

                        <div style="display: flex; gap: var(--spacing-md); justify-content: center;">
                            <a href="{{ route('shared.venue-map', ['tournament_id' => $tournament->id]) }}"
                                class="btn btn-outline-primary" style="min-width: 140px;">
                                <i class="fas fa-map"></i> View Map
                            </a>
                            <a href="{{ route('shared.info', ['tournament_id' => $tournament->id]) }}" class="btn btn-primary"
                                style="min-width: 140px;">
                                <i class="fas fa-info"></i> Info Guide
                            </a>
                        </div>
                    </div>
                @else
                    {{-- Active/Completed: Show live and upcoming matches --}}
                    @php
                        $liveFixtures = $tournament->fixtures()->with(['homeTeam','awayTeam','pool'])
                            ->where('status', 'in_progress')
                            ->orderBy('start_time','asc')->get();
                        $upcomingFixtures = $tournament->fixtures()->with(['homeTeam','awayTeam','pool'])
                            ->where('status', 'scheduled')
                            ->whereNotNull('start_time')
                            ->orderBy('start_time','asc')
                            ->limit(6)->get();
                        $completedFixtures = $tournament->fixtures()->with(['homeTeam','awayTeam','pool'])
                            ->where('status', 'completed')
                            ->orderBy('start_time','desc')
                            ->limit(4)->get();
                    @endphp

                    {{-- LIVE matches first --}}
                    @if($liveFixtures->count() > 0)
                        <div style="margin-bottom: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 0.85rem;">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background: #ef4444; animation: pulse 1s infinite; flex-shrink: 0;"></span>
                                <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #ef4444;">Live Matches</span>
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                                @foreach($liveFixtures as $fix)
                                    <div data-fixture-id="{{ $fix->id }}" style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.9rem 1.1rem; background: rgba(239,68,68,0.06); border: 1px solid rgba(239,68,68,0.25); border-radius: 10px;">
                                        <div style="flex: 1; font-weight: 600; color: #fff; font-size: 0.9rem;">
                                            <span>{{ $fix->homeTeam->name ?? 'TBD' }}</span>
                                            <span style="color: #ef4444; font-size: 1.1rem; font-weight: 800; margin: 0 6px;">
                                                <span class="home-score">{{ $fix->home_score ?? 0 }}</span> – <span class="away-score">{{ $fix->away_score ?? 0 }}</span>
                                            </span>
                                            <span>{{ $fix->awayTeam->name ?? 'TBD' }}</span>
                                        </div>
                                        @if($fix->field_name)
                                            <span style="font-size: 0.7rem; color: var(--color-text-muted);">{{ $fix->field_name }}</span>
                                        @endif
                                        <span class="badge" style="background: #ef4444; font-size: 0.6rem; animation: pulse 1.5s infinite;">● LIVE</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Upcoming matches --}}
                    @if($upcomingFixtures->count() > 0)
                        <div style="margin-bottom: 1.5rem;">
                            <div style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: var(--color-text-muted); margin-bottom: 0.85rem;">
                                <i class="fas fa-clock" style="color: var(--color-electric-blue);"></i> Upcoming Matches
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                                @foreach($upcomingFixtures as $fix)
                                    <div style="display: flex; align-items: center; gap: 0.85rem; padding: 0.75rem 1rem; background: var(--color-bg-tertiary); border: 1px solid var(--color-border); border-radius: 9px;">
                                        <div style="min-width: 52px; text-align: center; flex-shrink: 0;">
                                            @if($fix->start_time)
                                                <div style="font-size: 0.8rem; font-weight: 700; color: var(--color-electric-blue); line-height: 1.2;">{{ $fix->start_time->format('h:i') }}</div>
                                                <div style="font-size: 0.6rem; color: var(--color-text-muted); text-transform: uppercase;">{{ $fix->start_time->format('A') }}</div>
                                            @else
                                                <div style="font-size: 0.75rem; color: var(--color-text-muted);">TBD</div>
                                            @endif
                                        </div>
                                        <div style="flex: 1; font-size: 0.875rem; font-weight: 600; color: var(--color-text-secondary);">
                                            {{ $fix->homeTeam->name ?? 'TBD' }}
                                            <span style="color: var(--color-text-muted); font-weight: 400; margin: 0 4px;">vs</span>
                                            {{ $fix->awayTeam->name ?? 'TBD' }}
                                        </div>
                                        @if($fix->field_name)
                                            <span style="font-size: 0.7rem; color: var(--color-text-muted); flex-shrink: 0;">{{ $fix->field_name }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- No active fixtures --}}
                    @if($liveFixtures->count() === 0 && $upcomingFixtures->count() === 0)
                        @if($completedFixtures->count() > 0)
                            <div style="text-align: center; padding: 1.5rem 0;">
                                <i class="fas fa-flag-checkered fa-2x" style="color: var(--color-success); margin-bottom: 0.75rem; opacity: 0.6;"></i>
                                <p style="color: var(--color-text-secondary); font-size: 0.9rem;">All matches for this tournament have been completed.</p>
                            </div>
                        @else
                            <div style="text-align: center; padding: 1.5rem 0;">
                                <i class="fas fa-hourglass-half fa-2x" style="color: var(--color-text-muted); margin-bottom: 0.75rem; opacity: 0.4;"></i>
                                <p style="color: var(--color-text-secondary); font-size: 0.9rem;">Match schedule has not been published yet.</p>
                            </div>
                        @endif
                    @endif

                    <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px solid var(--color-border);">
                        <a href="{{ route('shared.schedule', ['tournament_id' => $tournament->id]) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-calendar-alt"></i> Full Schedule
                        </a>
                        <a href="{{ route('shared.standings', ['tournament_id' => $tournament->id]) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-list-ol"></i> Standings
                        </a>
                        <a href="{{ route('shared.live-stream', ['tournament_id' => $tournament->id]) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-broadcast-tower"></i> Live Stream
                        </a>
                    </div>
                @endif
            </div>
        </div>

    @else
        <!-- No Tournament Found State -->
        <div class="card">
            <div class="card-body" style="text-align: center; padding: var(--spacing-3xl);">
                <i class="fas fa-search fa-3x"
                    style="color: var(--color-text-muted); opacity: 0.3; margin-bottom: var(--spacing-lg);"></i>
                <h3 style="font-weight: 600;">No Active Tournaments</h3>
                <p style="color: var(--color-text-secondary);">There are currently no tournaments scheduled.</p>
            </div>
        </div>
    @endif

@endsection

@push('styles')
    <style>
        .page-title {
            letter-spacing: -0.5px;
        }
    </style>
@endpush

@push('scripts')
<script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pusherAppKey = "{{ env('PUSHER_APP_KEY', '96f393e214601452f8c3') }}";
        const pusherCluster = "{{ env('PUSHER_APP_CLUSTER', 'ap1') }}";
        
        if (pusherAppKey) {
            const pusher = new Pusher(pusherAppKey, {
                cluster: pusherCluster,
                forceTLS: true
            });

            const channel = pusher.subscribe('live-matches');
            channel.bind('score-updated', function(data) {
                console.log('Real-time score update received:', data);
                const fixture = data.fixture;
                if (!fixture) return;

                const fixtureRow = document.querySelector(`[data-fixture-id="${fixture.id}"]`);
                if (fixtureRow) {
                    const homeScoreEl = fixtureRow.querySelector('.home-score');
                    const awayScoreEl = fixtureRow.querySelector('.away-score');
                    
                    if (homeScoreEl && awayScoreEl) {
                        const oldHome = parseInt(homeScoreEl.textContent);
                        const oldAway = parseInt(awayScoreEl.textContent);
                        
                        if (oldHome !== parseInt(fixture.home_score) || oldAway !== parseInt(fixture.away_score)) {
                            homeScoreEl.textContent = fixture.home_score ?? 0;
                            awayScoreEl.textContent = fixture.away_score ?? 0;
                            
                            // Flash effect (teal highlight)
                            fixtureRow.style.transition = 'none';
                            fixtureRow.style.backgroundColor = 'rgba(20, 184, 166, 0.25)';
                            fixtureRow.style.borderColor = '#14b8a6';
                            
                            setTimeout(() => {
                                fixtureRow.style.transition = 'all 1s ease';
                                fixtureRow.style.backgroundColor = 'rgba(239, 68, 68, 0.06)';
                                fixtureRow.style.borderColor = 'rgba(239, 68, 68, 0.25)';
                            }, 100);
                        }
                    }
                } else {
                    if (fixture.status === 'in_progress') {
                        window.location.reload();
                    }
                }
            });

            channel.bind('safety-updated', function(data) {
                console.log('Real-time safety update received in dashboard:', data);
                const log = data.safetyLog;
                if (!log) return;

                // Check if the safety log tournament ID matches the currently selected tournament
                const currentTournamentId = "{{ $tournament ? $tournament->id : '' }}";
                if (log.tournament_id && currentTournamentId && log.tournament_id.toString() !== currentTournamentId.toString()) {
                    console.log('Safety log tournament ID does not match current dashboard tournament ID. Ignoring.');
                    return;
                }

                const alertBanner = document.getElementById('weather-safety-alert');
                const alertTitle = document.getElementById('weather-alert-title');
                const alertDesc = document.getElementById('weather-alert-desc');

                if (alertBanner) {
                    if (log.alert_level === 'danger' || log.alert_level === 'warning') {
                        alertTitle.textContent = `WEATHER DELAY: ${log.alert_level.toUpperCase()}`;
                        alertDesc.textContent = log.notes || `Severe weather conditions detected (WBGT: ${log.wbgt}°C, Lightning: ${log.lightning_risk}km). Play may be temporarily delayed.`;
                        
                        const color = log.alert_level === 'danger' ? '#ef4444' : '#f59e0b';
                        alertBanner.style.borderColor = color;
                        alertBanner.style.boxShadow = `0 0 25px ${log.alert_level === 'danger' ? 'rgba(239, 68, 68, 0.4)' : 'rgba(245, 158, 11, 0.4)'}`;
                        alertBanner.querySelector('i').style.color = color;
                        
                        alertBanner.classList.remove('d-none');
                    } else {
                        alertBanner.classList.add('d-none');
                    }
                }
            });
        }
    });
</script>
@endpush