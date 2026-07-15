@extends('layouts.dashboard')

@section('title', 'Match Schedule')
@section('page-title', 'Match Schedule')

@push('styles')
<style>
/* ===== TIMELINE AND FIXTURES STYLING ===== */
.schedule-container {
    max-width: 900px;
    margin: 0 auto;
}

.date-group-header {
    font-family: 'Outfit', sans-serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--color-text-primary);
    background: var(--color-bg-secondary);
    border: 1px solid var(--color-border);
    padding: 10px 18px;
    border-radius: 8px;
    margin: 2rem 0 1.25rem 0;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: var(--shadow-sm);
}

.date-group-header i {
    color: var(--color-rugby-green);
}

.timeline {
    position: relative;
    padding-left: 2rem;
    margin-left: 0.5rem;
}

.timeline::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 7px;
    width: 2px;
    background: var(--color-border);
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
}

.timeline-marker {
    position: absolute;
    top: 1.25rem;
    left: -2rem;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: var(--color-bg-primary);
    border: 3px solid var(--color-border);
    z-index: 2;
    transition: all 0.25s ease;
}

.timeline-item.scheduled .timeline-marker {
    border-color: var(--color-electric-blue);
}

.timeline-item.in_progress .timeline-marker {
    border-color: var(--color-warning);
    background: var(--color-warning);
    box-shadow: 0 0 0 4px rgba(255, 167, 38, 0.2);
    animation: markerPulse 1.5s infinite;
}

.timeline-item.completed .timeline-marker {
    border-color: var(--color-success);
}

@keyframes markerPulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(255, 167, 38, 0.4); }
    50% { box-shadow: 0 0 0 6px rgba(255, 167, 38, 0); }
}

.timeline-content {
    background: var(--color-bg-secondary);
    border: 1px solid var(--color-border);
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    transition: all 0.25s ease;
    box-shadow: var(--shadow-sm);
}

.timeline-content:hover {
    border-color: var(--color-border-light);
    transform: translateX(5px);
}

.match-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--color-border);
    padding-bottom: 0.75rem;
    margin-bottom: 0.75rem;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.match-time-pitch {
    display: flex;
    align-items: center;
    gap: 12px;
}

.match-time {
    font-weight: 700;
    color: #fff;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.match-pitch {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--color-rugby-green-light);
    background: rgba(0, 168, 107, 0.1);
    padding: 2px 8px;
    border-radius: 4px;
    border: 1px solid rgba(0, 168, 107, 0.2);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.match-meta-right {
    display: flex;
    align-items: center;
    gap: 10px;
}

.match-stage {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--color-text-tertiary);
    background: var(--color-bg-tertiary);
    padding: 2px 8px;
    border-radius: 4px;
}

.match-badge {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    padding: 3px 8px;
    border-radius: 4px;
    text-transform: uppercase;
}

.match-badge.scheduled {
    background: rgba(0, 212, 255, 0.08);
    color: var(--color-electric-blue);
    border: 1px solid rgba(0, 212, 255, 0.2);
}

.match-badge.in_progress {
    background: rgba(255, 167, 38, 0.08);
    color: var(--color-warning);
    border: 1px solid rgba(255, 167, 38, 0.2);
    animation: livePulse 1.5s infinite;
}

.match-badge.completed {
    background: rgba(0, 168, 107, 0.08);
    color: var(--color-success);
    border: 1px solid rgba(0, 168, 107, 0.2);
}

@keyframes livePulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.6; }
}

.match-tournament {
    font-size: 0.75rem;
    color: var(--color-text-muted);
    font-weight: 500;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 4px;
}

.match-teams-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin: 1.25rem 0 0.5rem 0;
}

.match-team {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 0; /* allows text truncation */
}

.match-team.home {
    justify-content: flex-end;
    text-align: right;
}

.match-team.away {
    justify-content: flex-start;
    text-align: left;
}

.team-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: var(--color-bg-tertiary);
    border: 1px solid var(--color-border-light);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.85rem;
    color: var(--color-text-secondary);
    flex-shrink: 0;
}

.match-team.my-team .team-avatar {
    border-color: var(--color-rugby-green);
    background: rgba(0, 168, 107, 0.15);
    color: var(--color-rugby-green-light);
    box-shadow: 0 0 10px rgba(0, 168, 107, 0.2);
}

.team-name {
    font-weight: 600;
    color: var(--color-text-secondary);
    font-size: 0.95rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.match-team.my-team .team-name {
    color: #fff;
    font-weight: 700;
}

/* My Team Indicator label */
.my-team-label {
    font-size: 0.55rem;
    font-weight: 800;
    letter-spacing: 0.5px;
    background: linear-gradient(135deg, var(--color-rugby-green) 0%, var(--color-rugby-green-dark) 100%);
    color: #fff;
    padding: 2px 6px;
    border-radius: 4px;
    text-transform: uppercase;
}

.match-team.home.my-team .my-team-label {
    margin-right: 4px;
}

.match-team.away.my-team .my-team-label {
    margin-left: 4px;
}

.match-vs-score {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-width: 90px;
    flex-shrink: 0;
}

.match-vs {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--color-text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.match-scores {
    font-size: 1.6rem;
    font-weight: 800;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-family: 'Outfit', sans-serif;
    line-height: 1;
    margin-top: 4px;
}

.score-num {
    min-width: 24px;
    text-align: center;
}

.score-divider {
    color: var(--color-text-muted);
    font-weight: 500;
}

/* Print Styles */
@media print {
    .dashboard-wrapper, header, aside, .btn, .page-header {
        display: none !important;
    }
    .main-content {
        margin-left: 0 !important;
        padding: 0 !important;
    }
    .schedule-container {
        max-width: 100%;
        color: #000;
    }
    .timeline::before {
        background: #ccc;
    }
    .timeline-content {
        border-color: #ccc;
        background: #fff;
        color: #000;
    }
    .team-name, .match-time, .match-scores {
        color: #000 !important;
    }
}
</style>
@endpush

@section('content')
<div class="schedule-container">

    {{-- ===== TEAM POOL COMPOSITION SECTION ===== --}}
    <div id="pool-draw"></div>
    @if(isset($tournamentPools) && $tournamentPools->count() > 0)
        @foreach($tournamentPools as $tourney)
            @if($tourney->pools->count() > 0)
                <div style="margin-bottom: 2rem;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 1rem;">
                        <div style="width: 36px; height: 36px; background: linear-gradient(135deg, var(--color-rugby-green), #0084ff); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-layer-group" style="color: #fff; font-size: 0.9rem;"></i>
                        </div>
                        <div>
                            <h2 style="font-size: 1.1rem; font-weight: 700; color: #fff; margin: 0;">{{ $tourney->name }} — Pool Draw</h2>
                            <p style="font-size: 0.8rem; color: var(--color-text-muted); margin: 0;">Team groupings for this tournament</p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem;">
                        @foreach($tourney->pools as $pool)
                            @php
                                $confirmedTeams = $pool->registrations->where('status', 'confirmed');
                                $hasMyTeam = $confirmedTeams->contains(function($reg) use ($managerTeamIds) {
                                    return $managerTeamIds->contains($reg->team_id);
                                });
                            @endphp
                            <div style="background: var(--color-bg-secondary); border: 1px solid {{ $hasMyTeam ? 'var(--color-rugby-green)' : 'var(--color-border)' }}; border-radius: 12px; overflow: hidden; box-shadow: {{ $hasMyTeam ? '0 0 16px rgba(0,168,107,0.15)' : 'var(--shadow-sm)' }};">
                                <div style="padding: 12px 16px; background: {{ $hasMyTeam ? 'rgba(0,168,107,0.15)' : 'var(--color-bg-tertiary)' }}; border-bottom: 1px solid var(--color-border); display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-folder{{ $hasMyTeam ? '-open' : '' }}" style="color: {{ $hasMyTeam ? 'var(--color-rugby-green-light)' : 'var(--color-text-muted)' }};"></i>
                                    <span style="font-weight: 700; font-size: 0.95rem; color: {{ $hasMyTeam ? '#fff' : 'var(--color-text-secondary)' }};">{{ $pool->name }}</span>
                                    @if($hasMyTeam)
                                        <span style="margin-left: auto; font-size: 0.55rem; font-weight: 800; background: var(--color-rugby-green); color: #fff; padding: 2px 7px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.5px;">My Pool</span>
                                    @endif
                                </div>
                                <div style="padding: 12px 16px;">
                                    @if($confirmedTeams->count() > 0)
                                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px;">
                                            @foreach($confirmedTeams as $reg)
                                                @php
                                                    $isMyTeamReg = $reg->team && $managerTeamIds->contains($reg->team_id);
                                                @endphp
                                                <li style="display: flex; align-items: center; gap: 10px;">
                                                    <div style="width: 28px; height: 28px; border-radius: 50%; background: {{ $isMyTeamReg ? 'rgba(0,168,107,0.2)' : 'var(--color-bg-tertiary)' }}; border: 1px solid {{ $isMyTeamReg ? 'var(--color-rugby-green)' : 'var(--color-border-light)' }}; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700; color: {{ $isMyTeamReg ? 'var(--color-rugby-green-light)' : 'var(--color-text-secondary)' }}; flex-shrink: 0;">
                                                        {{ $reg->team ? strtoupper(substr($reg->team->name, 0, 2)) : 'TB' }}
                                                    </div>
                                                    <span style="font-size: 0.875rem; font-weight: {{ $isMyTeamReg ? '700' : '500' }}; color: {{ $isMyTeamReg ? '#fff' : 'var(--color-text-secondary)' }}; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                        {{ $reg->team->name ?? 'TBD' }}
                                                    </span>
                                                    @if($isMyTeamReg)
                                                        <span style="margin-left: auto; font-size: 0.5rem; font-weight: 800; background: linear-gradient(135deg, var(--color-rugby-green), var(--color-rugby-green-dark)); color: #fff; padding: 2px 5px; border-radius: 3px; text-transform: uppercase; flex-shrink: 0;">Mine</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div style="text-align: center; padding: 16px 0; color: var(--color-text-muted); font-size: 0.8rem;">
                                            <i class="fas fa-users-slash" style="margin-bottom: 6px; opacity: 0.4;"></i>
                                            <div>No teams yet</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
        <div style="height: 1px; background: var(--color-border); margin: 0.5rem 0 2rem 0;"></div>
    @endif
    {{-- ===== END TEAM POOL COMPOSITION ===== --}}

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #fff; margin: 0;">My Team Fixtures</h2>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin: 0;">Real-time match schedule for your managed teams</p>
        </div>
        <button class="btn btn-outline btn-sm" onclick="window.print();">
            <i class="fas fa-print"></i> Print Schedule
        </button>
    </div>

    @if(isset($fixtures) && $fixtures->count() > 0)
        @php
            // Group fixtures by formatted date
            $groupedFixtures = $fixtures->groupBy(function($fixture) {
                return $fixture->start_time ? $fixture->start_time->format('l, d F Y') : 'Date TBD';
            });
        @endphp

        @foreach($groupedFixtures as $dateStr => $dateFixtures)
            <!-- Date Group Header -->
            <div class="date-group-header">
                <i class="fas fa-calendar-alt"></i>
                <span>{{ $dateStr }}</span>
            </div>

            <!-- Timeline for this Date -->
            <div class="timeline">
                @foreach($dateFixtures as $fixture)
                    @php
                        $isHomeTeamMyTeam = in_array($fixture->home_team_id, $managerTeamIds->toArray());
                        $isAwayTeamMyTeam = in_array($fixture->away_team_id, $managerTeamIds->toArray());
                    @endphp

                    <div class="timeline-item {{ $fixture->status }}" data-fixture-id="{{ $fixture->id }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <!-- Match Header -->
                            <div class="match-header">
                                <div class="match-time-pitch">
                                    <span class="match-time">
                                        <i class="far fa-clock"></i>
                                        {{ $fixture->start_time ? $fixture->start_time->format('h:i A') : 'TBD' }}
                                    </span>
                                    @if($fixture->field_name)
                                        <span class="match-pitch">{{ $fixture->field_name }}</span>
                                    @endif
                                </div>
                                <div class="match-meta-right">
                                    <span class="match-stage">
                                        {{ $fixture->pool ? $fixture->pool->name : $fixture->stage }}
                                    </span>
                                    <span class="match-badge {{ $fixture->status }} status-badge">
                                        {{ $fixture->status === 'in_progress' ? 'live' : $fixture->status }}
                                    </span>
                                </div>
                            </div>

                            <!-- Tournament Name -->
                            <div class="match-tournament">
                                <i class="fas fa-trophy"></i>
                                {{ $fixture->tournament->name }}
                            </div>

                            <!-- Match Teams Row -->
                            <div class="match-teams-row">
                                @if($fixture->status === 'scheduled' && $fixture->homeTeam && $fixture->awayTeam)
                                    @php
                                        $rA = $fixture->homeTeam->rating ?? 1500;
                                        $rB = $fixture->awayTeam->rating ?? 1500;
                                        $probA = round((1 / (1 + pow(10, ($rB - $rA) / 400))) * 100);
                                        $probB = 100 - $probA;
                                    @endphp
                                @endif

                                <!-- Home Team -->
                                <div class="match-team home {{ $isHomeTeamMyTeam ? 'my-team' : '' }}">
                                    @if($isHomeTeamMyTeam)
                                        <span class="my-team-label">My Team</span>
                                    @endif
                                    @if($fixture->status === 'scheduled' && $fixture->homeTeam && $fixture->awayTeam)
                                        <span class="badge" style="background: rgba(0, 168, 107, 0.12); color: var(--color-rugby-green-light); font-size: 0.72rem; padding: 2px 6px; font-weight: 700; border: 1px solid rgba(0, 168, 107, 0.25); margin-right: 6px;" title="Elo Win Probability: {{ $probA }}%">{{ $probA }}%</span>
                                    @endif
                                    <span class="team-name" title="{{ $fixture->homeTeam->name ?? 'TBD' }}">
                                        {{ $fixture->homeTeam->name ?? 'TBD' }}
                                    </span>
                                    <div class="team-avatar">
                                        {{ $fixture->homeTeam ? strtoupper(substr($fixture->homeTeam->name, 0, 2)) : 'TB' }}
                                    </div>
                                </div>

                                <!-- VS / Scores -->
                                <div class="match-vs-score">
                                    <div class="match-scores" style="{{ $fixture->status === 'scheduled' ? 'display: none;' : '' }}">
                                        <span class="score-num home-score">{{ $fixture->home_score ?? 0 }}</span>
                                        <span class="score-divider">-</span>
                                        <span class="score-num away-score">{{ $fixture->away_score ?? 0 }}</span>
                                    </div>
                                    <span class="match-vs" style="{{ $fixture->status !== 'scheduled' ? 'display: none;' : '' }}">VS</span>
                                </div>

                                <!-- Away Team -->
                                <div class="match-team away {{ $isAwayTeamMyTeam ? 'my-team' : '' }}">
                                    <div class="team-avatar">
                                        {{ $fixture->awayTeam ? strtoupper(substr($fixture->awayTeam->name, 0, 2)) : 'TB' }}
                                    </div>
                                    <span class="team-name" title="{{ $fixture->awayTeam->name ?? 'TBD' }}">
                                        {{ $fixture->awayTeam->name ?? 'TBD' }}
                                    </span>
                                    @if($fixture->status === 'scheduled' && $fixture->homeTeam && $fixture->awayTeam)
                                        <span class="badge" style="background: rgba(0, 212, 255, 0.12); color: var(--color-electric-blue); font-size: 0.72rem; padding: 2px 6px; font-weight: 700; border: 1px solid rgba(0, 212, 255, 0.25); margin-left: 6px;" title="Elo Win Probability: {{ $probB }}%">{{ $probB }}%</span>
                                    @endif
                                    @if($isAwayTeamMyTeam)
                                        <span class="my-team-label">My Team</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    @else
        <!-- Empty State Card -->
        <div class="card" style="margin-top: 1rem;">
            <div class="card-body" style="padding: 4rem 2rem; text-align: center;">
                <div style="width: 80px; height: 80px; background: var(--color-bg-tertiary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                    <i class="fas fa-calendar-xmark" style="font-size: 2.5rem; color: var(--color-text-muted); opacity: 0.5;"></i>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem;">No Matches Scheduled</h3>
                <p style="color: var(--color-text-secondary); max-width: 450px; margin: 0 auto 1.5rem; line-height: 1.6; font-size: 0.9rem;">
                    Match fixtures involving your registered teams will appear here in a structured timeline once the tournament organizer generates and publishes the schedule.
                </p>
                <a href="{{ route('manager.browse-tournaments') }}" class="btn btn-primary">
                    <i class="fas fa-search"></i> Browse & Register Tournaments
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

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
                console.log('Real-time score update received on Schedule page:', data);
                const fixture = data.fixture;
                if (!fixture) return;

                const timelineItem = document.querySelector(`[data-fixture-id="${fixture.id}"]`);
                if (timelineItem) {
                    const homeScoreEl = timelineItem.querySelector('.home-score');
                    const awayScoreEl = timelineItem.querySelector('.away-score');
                    const scoresDiv   = timelineItem.querySelector('.match-scores');
                    const vsSpan      = timelineItem.querySelector('.match-vs');
                    const contentEl   = timelineItem.querySelector('.timeline-content');
                    
                    // Keep track of score changes for flash animation
                    let scoreChanged = false;
                    
                    if (homeScoreEl && awayScoreEl) {
                        const oldHome = parseInt(homeScoreEl.textContent);
                        const oldAway = parseInt(awayScoreEl.textContent);
                        
                        if (oldHome !== parseInt(fixture.home_score) || oldAway !== parseInt(fixture.away_score)) {
                            homeScoreEl.textContent = fixture.home_score ?? 0;
                            awayScoreEl.textContent = fixture.away_score ?? 0;
                            scoreChanged = true;
                        }
                    }

                    // Handle status visibility toggle (VS vs Scores)
                    if (fixture.status !== 'scheduled') {
                        if (scoresDiv && scoresDiv.style.display === 'none') {
                            scoresDiv.style.display = 'flex';
                            scoreChanged = true;
                        }
                        if (vsSpan) vsSpan.style.display = 'none';
                    } else {
                        if (scoresDiv) scoresDiv.style.display = 'none';
                        if (vsSpan) vsSpan.style.display = 'inline';
                    }

                    // Update class status on timeline-item
                    if (!timelineItem.classList.contains(fixture.status)) {
                        timelineItem.className = `timeline-item ${fixture.status}`;
                        scoreChanged = true;
                    }

                    // Update status badge
                    const badge = timelineItem.querySelector('.status-badge');
                    if (badge && !badge.classList.contains(fixture.status)) {
                        badge.className = `match-badge ${fixture.status} status-badge`;
                        badge.textContent = fixture.status === 'in_progress' ? 'live' : fixture.status;
                        scoreChanged = true;
                    }

                    // Trigger flash if anything significant updated
                    if (scoreChanged && contentEl) {
                        contentEl.style.transition = 'none';
                        contentEl.style.backgroundColor = 'rgba(20, 184, 166, 0.25)';
                        contentEl.style.borderColor = '#14b8a6';
                        
                        setTimeout(() => {
                            contentEl.style.transition = 'all 1s ease';
                            contentEl.style.backgroundColor = '';
                            contentEl.style.borderColor = '';
                        }, 100);
                    }
                }
            });
        }
    });
</script>
@endpush