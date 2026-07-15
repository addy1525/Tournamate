@extends('layouts.dashboard')

@section('title', 'Live Streams')
@section('page-title', 'Live Streams')

@push('styles')
<style>
/* ===== SPECTATOR LIVE STREAM PAGE ===== */

/* Pulsing LIVE badge */
.badge-live {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.4);
    color: #ef4444;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.badge-live::before {
    content: '';
    width: 7px;
    height: 7px;
    background: #ef4444;
    border-radius: 50%;
    animation: blink 1.4s ease-in-out infinite;
    flex-shrink: 0;
}

@keyframes blink {
    0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(239,68,68,0.5); }
    50% { opacity: 0.5; box-shadow: 0 0 0 5px rgba(239,68,68,0); }
}

/* Offline badge */
.badge-offline {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(100, 116, 139, 0.15);
    border: 1px solid rgba(100, 116, 139, 0.3);
    color: var(--color-text-muted);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.badge-scheduled {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(0, 212, 255, 0.1);
    border: 1px solid rgba(0, 212, 255, 0.3);
    color: var(--color-electric-blue);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
}

/* Main Video Embed */
.stream-embed-wrapper {
    position: relative;
    padding-top: 56.25%; /* 16:9 */
    background: #000;
    border-radius: 0 0 12px 12px;
    overflow: hidden;
}

.stream-embed-wrapper iframe {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    border: none;
}

/* Stream Channel Cards (tab selector) */
.channel-tabs {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.channel-tab {
    background: var(--color-bg-secondary);
    border: 1px solid var(--color-border);
    border-radius: 8px;
    padding: 0.65rem 1rem;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: var(--color-text-secondary);
    font-weight: 500;
}

.channel-tab:hover {
    border-color: var(--color-border-light);
    color: var(--color-text-primary);
}

.channel-tab.active {
    background: linear-gradient(135deg, rgba(0,168,107,0.15), rgba(0,168,107,0.05));
    border-color: var(--color-rugby-green);
    color: var(--color-rugby-green);
}

.channel-tab.live-tab {
    border-color: rgba(239,68,68,0.4);
    color: #ef4444;
}

.channel-tab.live-tab.active {
    background: linear-gradient(135deg, rgba(239,68,68,0.15), rgba(239,68,68,0.05));
}

/* Offline placeholder */
.stream-offline-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #0a0f1e 0%, #0a1628 100%);
    border-radius: 0 0 12px 12px;
    min-height: 400px;
    text-align: center;
    padding: 2rem;
}

/* Score overlay card */
.score-overlay {
    background: linear-gradient(135deg, var(--color-bg-secondary), var(--color-bg-tertiary));
    border: 1px solid var(--color-border);
    border-radius: 12px;
    padding: 1.25rem;
}

.score-team {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.score-num {
    font-size: 3rem;
    font-weight: 800;
    color: var(--color-text-primary);
    line-height: 1;
    min-width: 60px;
    text-align: center;
}

.score-divider {
    font-size: 1.5rem;
    color: var(--color-text-muted);
    font-weight: 700;
}

.team-name-score {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--color-text-secondary);
    text-align: center;
    max-width: 120px;
}

/* Grid for offline streams */
.offline-streams-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 0.75rem;
}

.offline-stream-card {
    background: var(--color-bg-secondary);
    border: 1px solid var(--color-border);
    border-radius: 10px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.offline-stream-card i {
    font-size: 1.5rem;
    color: var(--color-text-muted);
    opacity: 0.4;
    flex-shrink: 0;
}
</style>
@endpush

@section('content')

{{-- Page Title --}}
<div class="page-header" style="margin-bottom: 1.5rem;">
    <h1 class="page-title">Live Tournament Streams</h1>
    <p class="page-subtitle">Watch matches live from all pitches</p>
</div>

{{-- Tournament Selector --}}
@if(isset($tournaments) && $tournaments->count() > 1)
    <div class="card mb-lg" style="margin-bottom: 1.5rem;">
        <div class="card-body" style="padding: 1rem 1.25rem; display: flex; align-items: center; gap: 1rem;">
            <i class="fas fa-trophy" style="color: var(--color-rugby-green);"></i>
            <div style="flex: 1;">
                <label style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--color-text-muted); display: block; margin-bottom: 4px;">Select Tournament</label>
                <select class="form-select" style="max-width: 400px;"
                    onchange="const url = new URL(window.location.href); url.searchParams.set('tournament_id', this.value); window.location.href = url.toString();">
                    @foreach($tournaments as $t)
                        <option value="{{ $t->id }}" {{ $tournament && $tournament->id == $t->id ? 'selected' : '' }}>
                            {{ $t->name }} —
                            {{ $t->start_date ? \Carbon\Carbon::parse($t->start_date)->format('M d, Y') : 'TBD' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
@endif

@if(isset($tournament) && $tournament)

    @php
        // Load streams for this tournament with eager loaded relations
        $allStreams   = $tournament->liveStreams()
            ->with([
                'fixture.homeTeam',
                'fixture.awayTeam',
                'fixture.matchEvents' => function($q) {
                    $q->orderBy('minute', 'desc')->orderBy('created_at', 'desc');
                },
                'fixture.matchEvents.team'
            ])
            ->orderBy('status')
            ->orderBy('field_name')
            ->get();
        $liveStreams  = $allStreams->where('status', 'live');
        $otherStreams = $allStreams->where('status', '!=', 'live');

        // Default stream to show in player
        $activeStream = $liveStreams->first() ?? $allStreams->first();
        $latestSafetyLog = $tournament ? \App\Models\SafetyLog::where('tournament_id', $tournament->id)->latest()->first() : null;
    @endphp

    @if($allStreams->count() > 0)

        {{-- ===== HAS STREAMS CONFIGURED ===== --}}

        <!-- Real-time Weather Warning Banner for Spectators -->
        @php
            $hasSafetyAlert = $latestSafetyLog && ($latestSafetyLog->alert_level === 'danger' || $latestSafetyLog->alert_level === 'warning');
            $spectatorBannerBorder = $hasSafetyAlert && $latestSafetyLog->alert_level === 'danger' ? '#ef4444' : '#f59e0b';
        @endphp
        <div id="spectator-safety-alert" class="alert alert-danger-glow mb-4 {{ $hasSafetyAlert ? '' : 'd-none' }}" 
             style="background: rgba(30, 41, 59, 0.85); border: 2px solid {{ $spectatorBannerBorder }}; border-radius: 12px; padding: 15px 20px; box-shadow: 0 0 25px rgba(239, 68, 68, 0.25);">
            <div class="d-flex align-items-center">
                <i class="fas fa-cloud-bolt mr-3" style="font-size: 1.8rem; color: {{ $spectatorBannerBorder }} !important; animation: pulse-icon 1.5s infinite;"></i>
                <div>
                    <h4 class="text-white font-weight-bold mb-1" id="spectator-alert-title">WEATHER DELAY: {{ $hasSafetyAlert ? strtoupper($latestSafetyLog->alert_level) : '' }}</h4>
                    <p class="text-tertiary mb-0" style="font-size: 0.85rem;" id="spectator-alert-desc">
                        {{ $hasSafetyAlert ? ($latestSafetyLog->notes ?: "Tournament play is temporarily delayed due to weather conditions. Please seek shelter.") : '' }}
                    </p>
                </div>
            </div>
        </div>

        @if($liveStreams->count() > 0)
            {{-- LIVE indicator bar --}}
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; flex-wrap: wrap;">
                <span class="badge-live">LIVE</span>
                <span style="font-size: 0.85rem; color: var(--color-text-secondary);">
                    {{ $liveStreams->count() }} {{ Str::plural('pitch', $liveStreams->count()) }} broadcasting live
                </span>
            </div>
        @endif

        {{-- ===== CHANNEL TABS (jika > 1 stream) ===== --}}
        @if($allStreams->count() > 1)
            <div class="channel-tabs" id="channelTabs">
                @foreach($allStreams as $i => $stream)
                    <button
                        class="channel-tab {{ $stream->status === 'live' ? 'live-tab' : '' }} {{ $i === 0 ? 'active' : '' }}"
                        onclick="switchChannel(this, {{ $stream->id }})"
                        id="tab-{{ $stream->id }}">
                        @if($stream->status === 'live')
                            <span style="width:7px;height:7px;background:#ef4444;border-radius:50%;display:inline-block;animation:blink 1.4s infinite;flex-shrink:0;"></span>
                        @else
                            <i class="fas fa-video" style="font-size: 0.75rem; opacity: 0.5;"></i>
                        @endif
                        {{ $stream->field_name }}
                    </button>
                @endforeach
            </div>
        @endif

        {{-- ===== MAIN VIDEO PLAYER ===== --}}
        <div class="card" style="margin-bottom: 1.5rem; border: none; overflow: hidden;">

            {{-- Player Header --}}
            <div style="padding: 1rem 1.25rem; border-bottom: 1px solid var(--color-border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
                <div>
                    <div id="playerFieldName" style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--color-rugby-green); margin-bottom: 2px;">
                        {{ $activeStream->field_name }}
                    </div>
                    <div id="playerTitle" style="font-size: 0.95rem; font-weight: 600; color: var(--color-text-primary);">
                        {{ $activeStream->title ?: $tournament->name }}
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span id="playerStatusBadge">
                        @if($activeStream->status === 'live')
                            <span class="badge-live">LIVE</span>
                        @elseif($activeStream->status === 'scheduled')
                            <span class="badge-scheduled">SCHEDULED</span>
                        @else
                            <span class="badge-offline">OFFLINE</span>
                        @endif
                    </span>
                    @if($activeStream->watch_url)
                        <a href="{{ $activeStream->watch_url }}" target="_blank"
                           class="btn btn-sm btn-secondary" title="Watch on {{ ucfirst($activeStream->provider) }}">
                            <i class="fas fa-external-link-alt"></i> Open on {{ ucfirst($activeStream->provider) }}
                        </a>
                    @endif
                </div>
            </div>

            {{-- Video Player / Offline State --}}
            <div id="playerContainer">
                @if($activeStream->status === 'live')
                    <div class="stream-embed-wrapper">
                        <iframe id="streamIframe"
                            src="{{ $activeStream->embed_url }}"
                            allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                        </iframe>
                    </div>
                @elseif($activeStream->status === 'scheduled')
                    <div class="stream-offline-placeholder" id="offlinePlaceholder">
                        <div style="width: 80px; height: 80px; background: rgba(0,212,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                            <i class="fas fa-clock" style="font-size: 2rem; color: var(--color-electric-blue);"></i>
                        </div>
                        <h3 id="offlineTitle" style="font-size: 1.2rem; font-weight: 700; margin-bottom: 0.5rem;">Stream Starting Soon</h3>
                        <p id="offlineDesc" style="color: var(--color-text-secondary); max-width: 400px; line-height: 1.6; margin: 0 auto 1.5rem;">
                            The broadcast for <strong>{{ $activeStream->field_name }}</strong> will begin before the first match.
                        </p>
                        <span class="badge-scheduled">SCHEDULED</span>
                    </div>
                @else
                    <div class="stream-offline-placeholder" id="offlinePlaceholder">
                        <div style="width: 80px; height: 80px; background: rgba(100,116,139,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                            <i class="fas fa-video-slash" style="font-size: 2rem; color: var(--color-text-muted);"></i>
                        </div>
                        <h3 id="offlineTitle" style="font-size: 1.2rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--color-text-secondary);">Stream Offline</h3>
                        <p id="offlineDesc" style="color: var(--color-text-muted); max-width: 400px; line-height: 1.6; margin: 0 auto 1.5rem;">
                            The stream for <strong>{{ $activeStream->field_name }}</strong> is currently offline. Broadcast will begin before the match.
                        </p>
                        <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
                            <a href="{{ route('shared.schedule') }}" class="btn btn-outline">
                                <i class="fas fa-calendar-alt"></i> View Schedule
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ===== SCORE OVERLAY (if fixture linked & live) ===== --}}
        @if($activeStream->fixture && $activeStream->status === 'live')
            @php $fx = $activeStream->fixture; @endphp
            <div class="score-overlay" style="margin-bottom: 1.5rem;" id="scoreOverlay">
                <div style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--color-text-muted); margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                    <div>
                        <i class="fas fa-broadcast-tower" style="color: #ef4444; margin-right: 4px;"></i>
                        Live Score — <span id="scoreOverlayField">{{ $activeStream->field_name }}</span>
                    </div>
                    <div id="liveWeatherWidget" style="display: flex; align-items: center; gap: 6px; background: rgba(255, 255, 255, 0.05); padding: 2px 8px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.05); font-size: 0.65rem; color: #fff;">
                        <i class="fas fa-cloud-sun text-info" id="widgetWeatherIcon"></i>
                        <span id="widgetTemp">{{ $latestSafetyLog && $latestSafetyLog->temperature ? number_format($latestSafetyLog->temperature, 1) . '°C' : 'N/A' }}</span>
                        <span style="opacity:0.3;">|</span>
                        <span id="widgetSafetyStatus" style="font-weight:700; text-transform:uppercase; color: {{ $latestSafetyLog && ($latestSafetyLog->alert_level === 'danger' || $latestSafetyLog->alert_level === 'warning') ? '#ef4444' : '#10b981' }}">{{ $latestSafetyLog ? $latestSafetyLog->alert_level : 'SAFE' }}</span>
                    </div>
                </div>
                <div class="score-team">
                    <div style="text-align: center; flex: 1;">
                        <div class="team-name-score" id="scoreOverlayHomeTeam">{{ $fx->homeTeam->name ?? 'Home Team' }}</div>
                    </div>
                    <div class="score-num" id="scoreOverlayHomeScore" style="color: var(--color-rugby-green);">{{ $fx->home_score ?? 0 }}</div>
                    <div class="score-divider">—</div>
                    <div class="score-num" id="scoreOverlayAwayScore" style="color: var(--color-electric-blue);">{{ $fx->away_score ?? 0 }}</div>
                    <div style="text-align: center; flex: 1;">
                        <div class="team-name-score" id="scoreOverlayAwayTeam">{{ $fx->awayTeam->name ?? 'Away Team' }}</div>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 0.75rem; font-size: 0.75rem; color: var(--color-text-muted); border-bottom: 1px dashed var(--color-border); padding-bottom: 0.75rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-sync-alt"></i> Score updated live by the referee
                </div>

                <!-- Timeline Section -->
                <div class="match-timeline-section" style="margin-top: 0.75rem; {{ $fx->matchEvents->isEmpty() ? 'display: none;' : '' }}">
                    <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--color-text-muted); margin-bottom: 0.75rem; text-align: left; display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-history" style="color: var(--color-electric-blue);"></i> Match Timeline
                    </div>
                    <div class="timeline-list" style="display: flex; flex-direction: column; gap: 8px; max-height: 250px; overflow-y: auto;">
                        @foreach($fx->matchEvents as $event)
                            @php
                                $badgeColor = $event->event_type === 'try' ? 'badge-success' : ($event->event_type === 'conversion' ? 'badge-info' : ($event->event_type === 'penalty' ? 'badge-warning' : ($event->event_type === 'drop_goal' ? 'badge-primary' : ($event->event_type === 'yellow_card' ? 'badge-warning' : ($event->event_type === 'red_card' ? 'badge-danger' : 'badge-neutral')))));
                                $icon = $event->event_type === 'yellow_card' || $event->event_type === 'red_card' ? 'fa-square' : ($event->event_type === 'info' ? 'fa-info-circle' : 'fa-football-ball');
                                $cardColorStyle = $event->event_type === 'yellow_card' ? 'color: #eab308;' : ($event->event_type === 'red_card' ? 'color: #ef4444;' : '');
                            @endphp
                            <div style="display: flex; align-items: center; gap: 10px; font-size: 0.825rem; padding: 6px 10px; background: rgba(255,255,255,0.03); border-radius: 6px; border: 1px solid var(--color-border);">
                                <span style="font-weight: 700; color: var(--color-electric-blue); min-width: 28px;">{{ $event->minute }}'</span>
                                <i class="fas {{ $icon }}" style="font-size: 0.75rem; {{ $cardColorStyle }} width: 12px; text-align: center;"></i>
                                <span style="font-weight: 700; color: #fff; text-transform: uppercase; font-size: 0.6rem; padding: 2px 6px;" class="badge {{ $badgeColor }}">
                                    {{ $event->event_type === 'yellow_card' ? 'YELLOW' : ($event->event_type === 'red_card' ? 'RED' : ($event->event_type === 'conversion' ? 'CONV' : ($event->event_type === 'drop_goal' ? 'DG' : ($event->event_type === 'penalty' ? 'PEN' : strtoupper($event->event_type))))) }}
                                </span>
                                <span style="color: var(--color-text-secondary); flex: 1; text-align: left;">
                                    <strong>{{ $event->team->name ?? '' }}</strong>
                                    @if($event->player_name || $event->player_jersey)
                                        — {{ $event->player_name ?? 'Player' }} 
                                        @if($event->player_jersey) (#{{ $event->player_jersey }}) @endif
                                    @endif
                                </span>
                                @if($event->points > 0)
                                    <span style="font-weight: 700; color: var(--color-rugby-green-light); font-size: 0.8rem;">+{{ $event->points }} Pts</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="score-overlay" style="margin-bottom: 1.5rem; display: none;" id="scoreOverlay">
                <div style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--color-text-muted); margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                    <div>
                        <i class="fas fa-broadcast-tower" style="color: #ef4444; margin-right: 4px;"></i>
                        Live Score — <span id="scoreOverlayField"></span>
                    </div>
                    <div id="liveWeatherWidget" style="display: flex; align-items: center; gap: 6px; background: rgba(255, 255, 255, 0.05); padding: 2px 8px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.05); font-size: 0.65rem; color: #fff;">
                        <i class="fas fa-cloud-sun text-info" id="widgetWeatherIcon"></i>
                        <span id="widgetTemp">{{ $latestSafetyLog && $latestSafetyLog->temperature ? number_format($latestSafetyLog->temperature, 1) . '°C' : 'N/A' }}</span>
                        <span style="opacity:0.3;">|</span>
                        <span id="widgetSafetyStatus" style="font-weight:700; text-transform:uppercase; color: {{ $latestSafetyLog && ($latestSafetyLog->alert_level === 'danger' || $latestSafetyLog->alert_level === 'warning') ? '#ef4444' : '#10b981' }}">{{ $latestSafetyLog ? $latestSafetyLog->alert_level : 'SAFE' }}</span>
                    </div>
                </div>
                <div class="score-team">
                    <div style="text-align: center; flex: 1;">
                        <div class="team-name-score" id="scoreOverlayHomeTeam">Home Team</div>
                    </div>
                    <div class="score-num" id="scoreOverlayHomeScore" style="color: var(--color-rugby-green);">0</div>
                    <div class="score-divider">—</div>
                    <div class="score-num" id="scoreOverlayAwayScore" style="color: var(--color-electric-blue);">0</div>
                    <div style="text-align: center; flex: 1;">
                        <div class="team-name-score" id="scoreOverlayAwayTeam">Away Team</div>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 0.75rem; font-size: 0.75rem; color: var(--color-text-muted); border-bottom: 1px dashed var(--color-border); padding-bottom: 0.75rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-sync-alt"></i> Score updated live by the referee
                </div>

                <!-- Timeline Section -->
                <div class="match-timeline-section" style="margin-top: 0.75rem; display: none;">
                    <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--color-text-muted); margin-bottom: 0.75rem; text-align: left; display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-history" style="color: var(--color-electric-blue);"></i> Match Timeline
                    </div>
                    <div class="timeline-list" style="display: flex; flex-direction: column; gap: 8px; max-height: 250px; overflow-y: auto;">
                        <!-- Events will be appended here -->
                    </div>
                </div>
            </div>
        @endif

        {{-- ===== OTHER FIELDS (offline/scheduled) ===== --}}
        @if($otherStreams->count() > 0)
            <div style="margin-bottom: 0.75rem;">
                <h3 style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--color-text-muted); margin: 0;">
                    <i class="fas fa-video" style="margin-right: 0.4rem;"></i> Other Pitches
                </h3>
            </div>
            <div class="offline-streams-grid">
                @foreach($otherStreams as $stream)
                    <div class="offline-stream-card"
                         style="cursor: pointer;"
                         onclick="switchChannelById({{ $stream->id }})">
                        <i class="fas fa-{{ $stream->provider === 'youtube' ? 'youtube' : ($stream->provider === 'twitch' ? 'twitch' : 'video') }}"></i>
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-size: 0.8rem; font-weight: 600; color: var(--color-text-secondary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $stream->field_name }}
                            </div>
                            <div style="font-size: 0.7rem; color: var(--color-text-muted);">
                                {{ $stream->status === 'scheduled' ? 'Starting soon' : 'Offline' }}
                            </div>
                        </div>
                        <span class="{{ $stream->status === 'scheduled' ? 'badge-scheduled' : 'badge-offline' }}" style="font-size: 0.6rem; padding: 2px 8px;">
                            {{ strtoupper($stream->status) }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif

    @else
        {{-- ===== NO STREAMS CONFIGURED YET ===== --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">{{ $tournament->name }}</h2>
                    <p class="card-subtitle">Official Broadcast Feed</p>
                </div>
                <span class="badge-offline">OFFLINE</span>
            </div>
            <div class="card-body">
                <div class="stream-offline-placeholder" style="background: var(--color-bg-primary); border-radius: 8px; min-height: 320px;">
                    <div style="width: 90px; height: 90px; background: var(--color-bg-tertiary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                        <i class="fas fa-satellite-dish" style="font-size: 2.5rem; color: var(--color-text-muted); opacity: 0.5;"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">Broadcast Not Yet Started</h3>
                    <p style="color: var(--color-text-secondary); max-width: 500px; margin: 0 auto 1.5rem; line-height: 1.6;">
                        The live stream for <strong>{{ $tournament->name }}</strong> has not been configured yet.
                        The broadcast will be available once the organiser activates the match stream.
                    </p>
                    <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
                        <a href="{{ route('shared.schedule', ['tournament_id' => $tournament->id]) }}"
                           class="btn btn-outline">
                            <i class="fas fa-calendar-alt"></i> Match Schedule
                        </a>
                        <a href="{{ route('shared.brackets', ['tournament_id' => $tournament->id]) }}"
                           class="btn btn-secondary">
                            <i class="fas fa-sitemap"></i> Bracket
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

@else
    {{-- ===== NO TOURNAMENT FOUND ===== --}}
    <div class="card">
        <div class="card-body" style="text-align: center; padding: 4rem 1rem;">
            <i class="fas fa-satellite-dish" style="font-size: 3.5rem; color: var(--color-text-muted); opacity: 0.3; display: block; margin-bottom: 1.25rem;"></i>
            <h3 style="font-weight: 600; margin-bottom: 0.5rem;">No Active Tournament</h3>
            <p style="color: var(--color-text-secondary);">There are no tournaments currently in progress.</p>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
<script>
// Stream data untuk JS switching
const streamData = {
    @if(isset($allStreams))
        @foreach($allStreams as $stream)
        {{ $stream->id }}: {
            embedUrl: "{{ $stream->embed_url }}",
            fieldName: "{{ addslashes($stream->field_name) }}",
            title: "{{ addslashes($stream->title ?? $tournament->name) }}",
            status: "{{ $stream->status }}",
            provider: "{{ $stream->provider }}",
            watchUrl: "{{ $stream->watch_url }}",
            fixture: @if($stream->fixture) {
                id: {{ $stream->fixture->id }},
                homeTeam: "{{ addslashes($stream->fixture->homeTeam->name ?? 'Home Team') }}",
                awayTeam: "{{ addslashes($stream->fixture->awayTeam->name ?? 'Away Team') }}",
                homeScore: {{ $stream->fixture->home_score ?? 0 }},
                awayScore: {{ $stream->fixture->away_score ?? 0 }},
                matchEvents: [
                    @foreach($stream->fixture->matchEvents as $event)
                    {
                        id: {{ $event->id }},
                        minute: {{ $event->minute }},
                        event_type: "{{ $event->event_type }}",
                        points: {{ $event->points }},
                        player_name: "{{ addslashes($event->player_name) }}",
                        player_jersey: "{{ $event->player_jersey }}",
                        team_name: "{{ addslashes($event->team->name ?? '') }}"
                    },
                    @endforeach
                ]
            } @else null @endif
        },
        @endforeach
    @endif
};

// State active stream
let currentStreamId = {{ $activeStream ? $activeStream->id : 'null' }};

// Switch channel via Tab click
function switchChannel(tabEl, streamId) {
    // Update active tab
    document.querySelectorAll('.channel-tab').forEach(t => t.classList.remove('active'));
    tabEl.classList.add('active');

    updatePlayer(streamId);
}

// Switch channel via field card click (by stream ID)
function switchChannelById(streamId) {
    // Find and activate corresponding tab
    const tab = document.getElementById('tab-' + streamId);
    if (tab) {
        switchChannel(tab, streamId);
    } else {
        updatePlayer(streamId);
    }
}

// Helper to render match events timeline as HTML
function renderTimelineHtml(matchEvents) {
    if (!matchEvents || matchEvents.length === 0) {
        return '';
    }

    // Sort events by minute desc, then by id desc (newest first)
    const sortedEvents = [...matchEvents].sort((a, b) => {
        if (b.minute !== a.minute) {
            return b.minute - a.minute;
        }
        return (b.id || 0) - (a.id || 0);
    });

    return sortedEvents.map(event => {
        let badgeColor = 'badge-neutral';
        let badgeText = (event.event_type || '').toUpperCase();
        let icon = 'fa-football-ball';
        let cardColorStyle = '';

        switch (event.event_type) {
            case 'try':
                badgeColor = 'badge-success';
                break;
            case 'conversion':
                badgeColor = 'badge-info';
                badgeText = 'CONV';
                break;
            case 'penalty':
                badgeColor = 'badge-warning';
                badgeText = 'PEN';
                break;
            case 'drop_goal':
                badgeColor = 'badge-primary';
                badgeText = 'DG';
                break;
            case 'yellow_card':
                badgeColor = 'badge-warning';
                badgeText = 'YELLOW';
                icon = 'fa-square';
                cardColorStyle = 'color: #eab308;';
                break;
            case 'red_card':
                badgeColor = 'badge-danger';
                badgeText = 'RED';
                icon = 'fa-square';
                cardColorStyle = 'color: #ef4444;';
                break;
            case 'info':
                badgeColor = 'badge-neutral';
                icon = 'fa-info-circle';
                break;
        }

        let playerDetails = '';
        if (event.player_name || event.player_jersey) {
            playerDetails = ` — ${event.player_name || 'Player'}`;
            if (event.player_jersey) {
                playerDetails += ` (#${event.player_jersey})`;
            }
        }

        let pointsBadge = '';
        if (event.points > 0) {
            pointsBadge = `<span style="font-weight: 700; color: var(--color-rugby-green-light); font-size: 0.8rem;">+${event.points} Pts</span>`;
        }

        return `
            <div style="display: flex; align-items: center; gap: 10px; font-size: 0.825rem; padding: 6px 10px; background: rgba(255,255,255,0.03); border-radius: 6px; border: 1px solid var(--color-border);">
                <span style="font-weight: 700; color: var(--color-electric-blue); min-width: 28px;">${event.minute}'</span>
                <i class="fas ${icon}" style="font-size: 0.75rem; ${cardColorStyle} width: 12px; text-align: center;"></i>
                <span style="font-weight: 700; color: #fff; text-transform: uppercase; font-size: 0.6rem; padding: 2px 6px;" class="badge ${badgeColor}">
                    ${badgeText}
                </span>
                <span style="color: var(--color-text-secondary); flex: 1; text-align: left;">
                    <strong>${event.team_name || ''}</strong>${playerDetails}
                </span>
                ${pointsBadge}
            </div>
        `;
    }).join('');
}

function updatePlayer(streamId) {
    const s = streamData[streamId];
    if (!s) return;

    currentStreamId = streamId;

    const container = document.getElementById('playerContainer');
    const fieldEl   = document.getElementById('playerFieldName');
    const titleEl   = document.getElementById('playerTitle');
    const badgeEl   = document.getElementById('playerStatusBadge');

    if (fieldEl) fieldEl.textContent = s.fieldName;
    if (titleEl) titleEl.textContent = s.title || s.fieldName;

    // Update status badge
    if (badgeEl) {
        if (s.status === 'live') {
            badgeEl.innerHTML = '<span class="badge-live">LIVE</span>';
        } else if (s.status === 'scheduled') {
            badgeEl.innerHTML = '<span class="badge-scheduled">SCHEDULED</span>';
        } else {
            badgeEl.innerHTML = '<span class="badge-offline">OFFLINE</span>';
        }
    }

    // Update player
    if (container) {
        if (s.status === 'live' && s.embedUrl) {
            container.innerHTML = `
                <div class="stream-embed-wrapper">
                    <iframe src="${s.embedUrl}"
                        allowfullscreen
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                    </iframe>
                </div>`;
        } else if (s.status === 'scheduled') {
            container.innerHTML = `
                <div class="stream-offline-placeholder">
                    <div style="width:80px;height:80px;background:rgba(0,212,255,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem;">
                        <i class="fas fa-clock" style="font-size:2rem;color:var(--color-electric-blue);"></i>
                    </div>
                    <h3 style="font-size:1.2rem;font-weight:700;margin-bottom:0.5rem;">Stream Starting Soon</h3>
                    <p style="color:var(--color-text-secondary);max-width:400px;line-height:1.6;margin:0 auto 1.5rem;">
                        The broadcast for <strong>${s.fieldName}</strong> will begin before the first match.
                    </p>
                    <span class="badge-scheduled">SCHEDULED</span>
                </div>`;
        } else {
            container.innerHTML = `
                <div class="stream-offline-placeholder">
                    <div style="width:80px;height:80px;background:rgba(100,116,139,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem;">
                        <i class="fas fa-video-slash" style="font-size:2rem;color:var(--color-text-muted);"></i>
                    </div>
                    <h3 style="font-size:1.2rem;font-weight:700;margin-bottom:0.5rem;color:var(--color-text-secondary);">Stream Offline</h3>
                    <p style="color:var(--color-text-muted);max-width:400px;line-height:1.6;margin:0 auto 1.5rem;">
                        The stream for <strong>${s.fieldName}</strong> is currently offline.
                    </p>
                    <a href="{{ route('shared.schedule') }}" class="btn btn-outline">
                        <i class="fas fa-calendar-alt"></i> View Schedule
                    </a>
                </div>`;
        }
    }

    // Update score overlay visibility and content
    const scoreOverlay = document.getElementById('scoreOverlay');
    if (scoreOverlay) {
        if (s.status === 'live' && s.fixture) {
            document.getElementById('scoreOverlayField').textContent = s.fieldName;
            document.getElementById('scoreOverlayHomeTeam').textContent = s.fixture.homeTeam;
            document.getElementById('scoreOverlayAwayTeam').textContent = s.fixture.awayTeam;
            document.getElementById('scoreOverlayHomeScore').textContent = s.fixture.homeScore;
            document.getElementById('scoreOverlayAwayScore').textContent = s.fixture.awayScore;
            scoreOverlay.style.display = 'block';

            // Update match events timeline
            const timelineSection = scoreOverlay.querySelector('.match-timeline-section');
            const timelineList = scoreOverlay.querySelector('.timeline-list');
            if (timelineSection && timelineList) {
                if (s.fixture.matchEvents && s.fixture.matchEvents.length > 0) {
                    timelineList.innerHTML = renderTimelineHtml(s.fixture.matchEvents);
                    timelineSection.style.display = 'block';
                } else {
                    timelineList.innerHTML = '';
                    timelineSection.style.display = 'none';
                }
            }
        } else {
            scoreOverlay.style.display = 'none';
        }
    }
}

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

            // Update local streamData cache
            for (let id in streamData) {
                if (streamData[id].fixture && streamData[id].fixture.id === fixture.id) {
                    streamData[id].fixture.homeScore = fixture.home_score ?? 0;
                    streamData[id].fixture.awayScore = fixture.away_score ?? 0;
                    
                    // Transform the server-sent match_events structure to match JavaScript format
                    if (fixture.match_events) {
                        streamData[id].fixture.matchEvents = fixture.match_events.map(event => {
                            return {
                                id: event.id,
                                minute: event.minute,
                                event_type: event.event_type,
                                points: event.points,
                                player_name: event.player_name || '',
                                player_jersey: event.player_jersey || '',
                                team_name: event.team ? event.team.name : ''
                            };
                        });
                    } else {
                        streamData[id].fixture.matchEvents = [];
                    }
                    
                    // If this is the currently viewed stream, update the UI live
                    if (parseInt(id) === currentStreamId) {
                        const homeScoreEl = document.getElementById('scoreOverlayHomeScore');
                        const awayScoreEl = document.getElementById('scoreOverlayAwayScore');
                        const scoreOverlay = document.getElementById('scoreOverlay');
                        
                        if (homeScoreEl && awayScoreEl) {
                            const oldHome = parseInt(homeScoreEl.textContent);
                            const oldAway = parseInt(awayScoreEl.textContent);
                            
                            homeScoreEl.textContent = fixture.home_score ?? 0;
                            awayScoreEl.textContent = fixture.away_score ?? 0;

                            // Re-render match timeline
                            const timelineSection = scoreOverlay.querySelector('.match-timeline-section');
                            const timelineList = scoreOverlay.querySelector('.timeline-list');
                            if (timelineSection && timelineList) {
                                if (streamData[id].fixture.matchEvents && streamData[id].fixture.matchEvents.length > 0) {
                                    timelineList.innerHTML = renderTimelineHtml(streamData[id].fixture.matchEvents);
                                    timelineSection.style.display = 'block';
                                } else {
                                    timelineList.innerHTML = '';
                                    timelineSection.style.display = 'none';
                                }
                            }
                            
                            if (oldHome !== parseInt(fixture.home_score) || oldAway !== parseInt(fixture.away_score)) {
                                // Premium Teal Highlight Flash Transition
                                scoreOverlay.style.transition = 'none';
                                scoreOverlay.style.backgroundColor = 'rgba(20, 184, 166, 0.25)';
                                scoreOverlay.style.borderColor = '#14b8a6';
                                
                                setTimeout(() => {
                                    scoreOverlay.style.transition = 'all 1s ease';
                                    scoreOverlay.style.backgroundColor = '';
                                    scoreOverlay.style.borderColor = '';
                                }, 100);
                            }
                        }
                    }
                }
            }
        });

        channel.bind('safety-updated', function(data) {
            console.log('Real-time safety update received in live stream:', data);
            const log = data.safetyLog;
            if (!log) return;

            // Only update if the log tournament_id matches the currently selected tournament
            const currentTournamentId = "{{ $tournament ? $tournament->id : '' }}";
            if (log.tournament_id && currentTournamentId && log.tournament_id.toString() !== currentTournamentId.toString()) {
                console.log('Safety log tournament ID does not match currently viewed live stream tournament. Ignoring.');
                return;
            }

            // Update weather widget
            const widgetTemp = document.getElementById('widgetTemp');
            const widgetSafetyStatus = document.getElementById('widgetSafetyStatus');
            const widgetIcon = document.getElementById('widgetWeatherIcon');
            
            if (widgetTemp) {
                widgetTemp.textContent = log.temperature ? parseFloat(log.temperature).toFixed(1) + '°C' : 'N/A';
            }
            if (widgetSafetyStatus) {
                widgetSafetyStatus.textContent = log.alert_level;
                widgetSafetyStatus.style.color = (log.alert_level === 'danger' || log.alert_level === 'warning') ? '#ef4444' : '#10b981';
            }
            if (widgetIcon) {
                if (log.alert_level === 'danger' || log.alert_level === 'warning') {
                    widgetIcon.className = 'fas fa-cloud-bolt text-danger';
                } else if (log.alert_level === 'caution') {
                    widgetIcon.className = 'fas fa-cloud-rain text-warning';
                } else {
                    widgetIcon.className = 'fas fa-cloud-sun text-info';
                }
            }

            // Update warning banner
            const specAlert = document.getElementById('spectator-safety-alert');
            const specTitle = document.getElementById('spectator-alert-title');
            const specDesc = document.getElementById('spectator-alert-desc');
            
            if (specAlert) {
                if (log.alert_level === 'danger' || log.alert_level === 'warning') {
                    specTitle.textContent = `WEATHER DELAY: ${log.alert_level.toUpperCase()}`;
                    specDesc.textContent = log.notes || `Tournament play is temporarily delayed due to weather conditions. Please seek shelter.`;
                    
                    const color = log.alert_level === 'danger' ? '#ef4444' : '#f59e0b';
                    specAlert.style.borderColor = color;
                    
                    const alertIconEl = specAlert.querySelector('i');
                    if (alertIconEl) {
                        alertIconEl.style.color = color;
                    }
                    
                    specAlert.classList.remove('d-none');
                } else {
                    specAlert.classList.add('d-none');
                }
            }
        });
    }
});

// Auto-refresh every 60 seconds when a live stream is active
@if(isset($liveStreams) && $liveStreams->count() > 0)
setTimeout(() => location.reload(), 60000);
@endif
</script>
@endpush