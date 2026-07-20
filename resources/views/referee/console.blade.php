@extends('layouts.dashboard')

@section('title', 'Match Console')
@section('page-title', 'Match Official Console')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Match Official Console</h1>
        <p class="page-subtitle">Real-time match execution and scoring</p>
    </div>

    <!-- Weather Safety Alert Banner (Managed via JS depending on selected match) -->
    <div id="weather-safety-alert" class="alert alert-danger-glow mb-4 d-none" 
         style="background: rgba(30, 41, 59, 0.85); border: 2px solid #ef4444; border-radius: 12px; padding: 15px 20px; box-shadow: 0 0 25px rgba(239, 68, 68, 0.4);">
        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-triangle-exclamation text-danger mr-3" style="font-size: 1.8rem; color: #ef4444 !important;"></i>
                <div>
                    <h4 class="text-white font-weight-bold mb-1" id="weather-alert-title">WEATHER DELAY</h4>
                    <p class="text-tertiary mb-0" style="font-size: 0.85rem;" id="weather-alert-desc">
                        Severe weather conditions detected. Referees are advised to inspect safety limits.
                    </p>
                </div>
            </div>
            <a href="{{ route('referee.safety') }}" id="weather-safety-link" class="btn btn-sm btn-danger">View Safety Protocols</a>
        </div>
    </div>

    <div id="referee-console">
        <!-- Match Selection -->
        <div class="glass-card mb-xl" style="height: auto !important; transform: none !important;">
            <div class="glass-header">
                <h3 class="card-title text-white font-weight-bold m-0" style="font-size: 1rem;"><i class="fas fa-whistle text-success mr-2"></i> Select Active Match</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Active Matches</label>
                    <select class="form-select" id="match-select" onchange="showMatch(this.value)">
                        <option value="">Select a match to score...</option>
                        @foreach($fixtures as $fixture)
                            <option value="{{ $fixture->id }}"
                                {{ isset($selectedFixtureId) && $selectedFixtureId == $fixture->id ? 'selected' : '' }}>
                                {{ $fixture->tournament->name ?? '' }} —
                                {{ $fixture->homeTeam->name ?? 'TBD' }} vs {{ $fixture->awayTeam->name ?? 'TBD' }}
                                ({{ $fixture->stage }})
                                @if($fixture->status === 'in_progress') 🟢 LIVE
                                @elseif($fixture->status === 'completed') ✅ Completed
                                @else ⏳ Scheduled
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        @if($fixtures->isEmpty())
        <!-- Empty State -->
        <div class="glass-card" style="height: auto !important; transform: none !important;">
            <div class="card-body" style="padding: var(--spacing-3xl); text-align: center;">
                <div style="max-width: 600px; margin: 0 auto;">
                    <i class="fas fa-whistle fa-4x"
                        style="color: var(--color-text-muted); opacity: 0.3; margin-bottom: var(--spacing-xl);"></i>
                    <h2
                        style="font-size: var(--font-size-2xl); font-weight: 700; color: var(--color-text-primary); margin-bottom: var(--spacing-md);">
                        No Active Matches
                    </h2>
                    <p style="font-size: var(--font-size-md); color: var(--color-text-secondary); margin-bottom: var(--spacing-xl); line-height: 1.6;">
                        There are no matches currently scheduled or assigned.
                    </p>
                </div>
            </div>
        </div>
        @endif

        @foreach($fixtures as $fixture)
        <div id="match-card-{{ $fixture->id }}" class="match-card" style="display: none;">
            <!-- Live Scoreboard -->
            <form action="{{ route('referee.score.update', $fixture->id) }}" method="POST">
                @csrf
                <div class="glass-card mb-xl" style="background: linear-gradient(135deg, rgba(22, 33, 50, 0.8), rgba(15, 23, 42, 0.7)); height: auto !important; transform: none !important;">
                    <div class="card-body" style="padding: var(--spacing-2xl);">
                        <div class="grid grid-cols-3" style="gap: var(--spacing-2xl); align-items: center;">
                            
                            <!-- Home Team -->
                            <div class="text-center">
                                <h3 style="color: var(--color-text-primary); margin-bottom: var(--spacing-md); font-weight: 700;">{{ $fixture->homeTeam->name ?? 'TBD' }}</h3>
                                <input type="number" name="home_score" class="form-control text-center font-weight-bold" style="font-size: 3.5rem; height: auto; background: rgba(0,0,0,0.3); border: 1px solid var(--color-border); color: var(--color-rugby-green-light); border-radius: 12px; font-family: 'Outfit', sans-serif;" value="{{ $fixture->home_score ?? 0 }}">
                            </div>

                            <!-- Controls -->
                            <div class="text-center">
                                <div style="font-size: 1.5rem; color: var(--color-text-secondary); margin-bottom: var(--spacing-md); font-weight: 700; font-family: 'Outfit', sans-serif; letter-spacing: 2px;">VS</div>
                                <select name="status" class="form-control text-center mb-3 font-weight-bold status-select-dropdown" onchange="handleStatusChange(this)" style="background: rgba(0,0,0,0.3); border: 1px solid var(--color-border); color: #fff; border-radius: 8px; height: 42px;">
                                    <option value="scheduled" {{ $fixture->status == 'scheduled' ? 'selected' : '' }} style="background: var(--color-bg-secondary);">Scheduled ⏳</option>
                                    <option value="in_progress" {{ $fixture->status == 'in_progress' ? 'selected' : '' }} style="background: var(--color-bg-secondary);">In Progress 🟢</option>
                                    <option value="completed" {{ $fixture->status == 'completed' ? 'selected' : '' }} style="background: var(--color-bg-secondary);">Completed ✅</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-lg w-100 save-score-btn" style="border-radius: 8px; transition: all 0.3s ease;">
                                    <i class="fas fa-save mr-1"></i> <span class="btn-text">Save Score</span>
                                </button>
                            </div>

                            <!-- Away Team -->
                            <div class="text-center">
                                <h3 style="color: var(--color-text-primary); margin-bottom: var(--spacing-md); font-weight: 700;">{{ $fixture->awayTeam->name ?? 'TBD' }}</h3>
                                <input type="number" name="away_score" class="form-control text-center font-weight-bold" style="font-size: 3.5rem; height: auto; background: rgba(0,0,0,0.3); border: 1px solid var(--color-border); color: var(--color-rugby-green-light); border-radius: 12px; font-family: 'Outfit', sans-serif;" value="{{ $fixture->away_score ?? 0 }}">
                            </div>

                        </div>
                    </div>
                </div>
            </form>

            <!-- Match Event Logger -->
            <div class="glass-card mb-xl" style="height: auto !important; transform: none !important;">
                <div class="glass-header" style="border-bottom: 1px solid rgba(255,255,255,0.06) !important;">
                    <h3 class="card-title text-white font-weight-bold m-0" style="font-size: 1rem;">
                        <i class="fas fa-plus-circle text-success mr-2"></i> Record Match Event
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('referee.event.add', $fixture->id) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-2 mb-3" style="gap: var(--spacing-md);">
                            
                            <!-- Team Selector -->
                            <div>
                                <label class="form-label text-tertiary" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; display: block; margin-bottom: 6px;">Team Involved</label>
                                <select name="team_id" class="form-select" required>
                                    <option value="">Select Team...</option>
                                    @if($fixture->homeTeam)
                                        <option value="{{ $fixture->home_team_id }}">{{ $fixture->homeTeam->name }} (Home)</option>
                                    @endif
                                    @if($fixture->awayTeam)
                                        <option value="{{ $fixture->away_team_id }}">{{ $fixture->awayTeam->name }} (Away)</option>
                                    @endif
                                </select>
                            </div>

                            <!-- Event Type -->
                            <div>
                                <label class="form-label text-tertiary" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; display: block; margin-bottom: 6px;">Event Type</label>
                                <select name="event_type" class="form-select" required>
                                    <option value="">Select Event...</option>
                                    <option value="try">Try (5 points)</option>
                                    <option value="conversion">Conversion (2 points)</option>
                                    <option value="penalty">Penalty (3 points)</option>
                                    <option value="drop_goal">Drop Goal (3 points)</option>
                                    <option value="yellow_card">Yellow Card</option>
                                    <option value="red_card">Red Card</option>
                                    <option value="info">Info / Commentary</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 mb-4" style="gap: var(--spacing-md);">
                            <!-- Player Jersey / Name -->
                            <div>
                                <label class="form-label text-tertiary" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; display: block; margin-bottom: 6px;">Jersey Number</label>
                                <input type="number" name="player_jersey" class="form-control" placeholder="Jersey #" min="1" max="99" style="background: rgba(0,0,0,0.2); border: 1px solid var(--color-border); color: #fff; border-radius: 8px;">
                            </div>

                            <div>
                                <label class="form-label text-tertiary" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; display: block; margin-bottom: 6px;">Player Name (Optional)</label>
                                <input type="text" name="player_name" class="form-control" placeholder="Name" style="background: rgba(0,0,0,0.2); border: 1px solid var(--color-border); color: #fff; border-radius: 8px;">
                            </div>

                            <!-- Minute -->
                            <div>
                                <label class="form-label text-tertiary" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; display: block; margin-bottom: 6px;">Minute</label>
                                <input type="number" name="minute" class="form-control" value="0" min="0" max="120" required style="background: rgba(0,0,0,0.2); border: 1px solid var(--color-border); color: #fff; border-radius: 8px;">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100" style="border-radius: 8px;">
                            <i class="fas fa-check mr-1"></i> Record Event & Add Score
                        </button>
                    </form>
                </div>
            </div>

            <!-- Logged Events List -->
            <div class="glass-card mb-xl" style="height: auto !important; transform: none !important;">
                <div class="glass-header" style="border-bottom: 1px solid rgba(255,255,255,0.06) !important;">
                    <h3 class="card-title text-white font-weight-bold m-0" style="font-size: 1rem;">
                        <i class="fas fa-history text-info mr-2"></i> Logged Match Events
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-valign-middle text-white m-0" style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th class="text-tertiary border-top-0 pl-3" style="font-size: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.06); width: 80px;">Min</th>
                                    <th class="text-tertiary border-top-0" style="font-size: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.06);">Event</th>
                                    <th class="text-tertiary border-top-0" style="font-size: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.06);">Team</th>
                                    <th class="text-tertiary border-top-0" style="font-size: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.06);">Player</th>
                                    <th class="text-tertiary border-top-0 text-center" style="font-size: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.06); width: 80px;">Points</th>
                                    <th class="text-tertiary border-top-0 text-right pr-3" style="font-size: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.06); width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fixture->matchEvents as $event)
                                    <tr>
                                        <td class="pl-3 border-secondary" style="vertical-align: middle; font-weight: 700; color: var(--color-electric-blue); font-size: 0.85rem;">{{ $event->minute }}'</td>
                                        <td class="border-secondary" style="vertical-align: middle; font-size: 0.85rem;">
                                            @if($event->event_type === 'try')
                                                <span class="badge badge-success" style="font-size: 0.65rem; padding: 3px 8px;"><i class="fas fa-football-ball mr-1"></i> TRY</span>
                                            @elseif($event->event_type === 'conversion')
                                                <span class="badge badge-info" style="font-size: 0.65rem; padding: 3px 8px;"><i class="fas fa-bullseye mr-1"></i> CONV</span>
                                            @elseif($event->event_type === 'penalty')
                                                <span class="badge badge-warning" style="font-size: 0.65rem; padding: 3px 8px; color: #1e293b;"><i class="fas fa-kickstarter mr-1"></i> PEN</span>
                                            @elseif($event->event_type === 'drop_goal')
                                                <span class="badge badge-primary" style="font-size: 0.65rem; padding: 3px 8px;"><i class="fas fa-football-ball mr-1"></i> DG</span>
                                            @elseif($event->event_type === 'yellow_card')
                                                <span class="badge" style="font-size: 0.65rem; padding: 3px 8px; background: #eab308; color: #000; font-weight: 700;"><i class="fas fa-square mr-1"></i> YELLOW</span>
                                            @elseif($event->event_type === 'red_card')
                                                <span class="badge" style="font-size: 0.65rem; padding: 3px 8px; background: #ef4444; color: #fff; font-weight: 700;"><i class="fas fa-square mr-1"></i> RED</span>
                                            @else
                                                <span class="badge badge-secondary" style="font-size: 0.65rem; padding: 3px 8px;"><i class="fas fa-info-circle mr-1"></i> INFO</span>
                                            @endif
                                        </td>
                                        <td class="border-secondary" style="vertical-align: middle; font-size: 0.85rem;">{{ $event->team->name ?? '-' }}</td>
                                        <td class="border-secondary" style="vertical-align: middle; font-size: 0.85rem;">
                                            @if($event->player_name || $event->player_jersey)
                                                {{ $event->player_name ?? 'Player' }} 
                                                @if($event->player_jersey)
                                                    (#{{ $event->player_jersey }})
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="border-secondary text-center" style="vertical-align: middle; font-weight: 700; color: {{ $event->points > 0 ? 'var(--color-rugby-green-light)' : 'inherit' }}; font-size: 0.85rem;">+{{ $event->points }}</td>
                                        <td class="pr-3 border-secondary text-right" style="vertical-align: middle;">
                                            <form action="{{ route('referee.event.delete', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this event? Scores will be recalculated.')" style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" style="padding: 2px 8px; border-radius: 6px; font-size: 0.75rem;">
                                                    <i class="fas fa-trash mr-1"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="pl-3 pr-3 text-center py-4 border-top-0" style="color: var(--color-text-muted);">
                                            No events logged for this match yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

@endsection

@push('styles')
    <style>
        #referee-console {
            min-height: 400px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
    <script>
        const fixtureTournamentMap = {
            @foreach($fixtures as $fixture)
                @if($fixture->tournament_id)
                {{ $fixture->id }}: {{ $fixture->tournament_id }},
                @endif
            @endforeach
        };

        const tournamentSafetyMap = {
            @foreach($latestSafetyLogs as $tId => $log)
                @if($tId)
                {{ $tId }}: {
                    alert_level: "{{ $log->alert_level }}",
                    notes: {!! json_encode($log->notes) !!},
                    wbgt: "{{ $log->wbgt }}",
                    lightning_risk: "{{ $log->lightning_risk }}"
                },
                @endif
            @endforeach
        };

        let currentSelectedFixtureId = null;

        function handleStatusChange(selectEl) {
            const btn = selectEl.form ? selectEl.form.querySelector('.save-score-btn') : null;
            if (!btn) return;
            const btnText = btn.querySelector('.btn-text');
            const icon = btn.querySelector('i');

            if (selectEl.value === 'completed') {
                if (btnText) btnText.textContent = 'Save & Complete Match';
                if (icon) icon.className = 'fas fa-check-circle mr-1';
                btn.style.background = 'linear-gradient(135deg, #059669, #10b981)';
                btn.style.border = 'none';
                btn.style.boxShadow = '0 0 20px rgba(16, 185, 129, 0.6)';
            } else if (selectEl.value === 'in_progress') {
                if (btnText) btnText.textContent = 'Save & Set Live';
                if (icon) icon.className = 'fas fa-play-circle mr-1';
                btn.style.background = 'linear-gradient(135deg, #3b82f6, #1d4ed8)';
                btn.style.border = 'none';
                btn.style.boxShadow = '0 0 15px rgba(59, 130, 246, 0.4)';
            } else {
                if (btnText) btnText.textContent = 'Save Score';
                if (icon) icon.className = 'fas fa-save mr-1';
                btn.style.background = '';
                btn.style.border = '';
                btn.style.boxShadow = '';
            }
        }

        function showMatch(matchId) {
            currentSelectedFixtureId = matchId;

            // Hide all match cards
            document.querySelectorAll('.match-card').forEach(card => {
                card.style.display = 'none';
            });
            // Show selected match card
            if(matchId) {
                document.getElementById('match-card-' + matchId).style.display = 'block';
            }

            // Update safety alert banner for the selected match's tournament
            updateSafetyAlertBanner();
        }

        function updateSafetyAlertBanner(playSiren = false) {
            const alertBanner = document.getElementById('weather-safety-alert');
            const alertTitle = document.getElementById('weather-alert-title');
            const alertDesc = document.getElementById('weather-alert-desc');
            const safetyLink = document.getElementById('weather-safety-link');

            if (!currentSelectedFixtureId) {
                if (alertBanner) alertBanner.classList.add('d-none');
                return;
            }

            const tId = fixtureTournamentMap[currentSelectedFixtureId];
            if (!tId) {
                if (alertBanner) alertBanner.classList.add('d-none');
                return;
            }

            // Update safety link query string
            if (safetyLink) {
                safetyLink.href = `{{ route('referee.safety') }}?tournament_id=${tId}`;
            }

            const log = tournamentSafetyMap[tId];
            if (log && (log.alert_level === 'danger' || log.alert_level === 'warning')) {
                if (alertTitle) alertTitle.textContent = `WEATHER DELAY: ${log.alert_level.toUpperCase()}`;
                if (alertDesc) alertDesc.textContent = log.notes || `Severe weather conditions detected (WBGT: ${log.wbgt}°C, Lightning: ${log.lightning_risk}km). Referees are advised to inspect safety limits.`;
                
                if (alertBanner) {
                    // Style color matching safety alert level
                    const color = log.alert_level === 'danger' ? '#ef4444' : '#f59e0b';
                    alertBanner.style.borderColor = color;
                    alertBanner.style.boxShadow = `0 0 25px ${log.alert_level === 'danger' ? 'rgba(239, 68, 68, 0.4)' : 'rgba(245, 158, 11, 0.4)'}`;
                    
                    const iconEl = alertBanner.querySelector('i');
                    if (iconEl) {
                        iconEl.style.color = color;
                    }
                    
                    alertBanner.classList.remove('d-none');
                }

                if (playSiren) {
                    // Audio Alert Siren sound simulation using Web Audio API (double beep)
                    try {
                        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                        for (let i = 0; i < 2; i++) {
                            setTimeout(() => {
                                const osc = audioCtx.createOscillator();
                                const gain = audioCtx.createGain();
                                osc.connect(gain);
                                gain.connect(audioCtx.destination);
                                osc.type = 'sine';
                                osc.frequency.setValueAtTime(880, audioCtx.currentTime); // A5 note
                                gain.gain.setValueAtTime(0.15, audioCtx.currentTime);
                                osc.start();
                                osc.stop(audioCtx.currentTime + 0.15);
                            }, i * 250);
                        }
                    } catch(e) {
                        console.warn('Could not play web audio alert:', e);
                    }
                }
            } else {
                if (alertBanner) alertBanner.classList.add('d-none');
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
                channel.bind('safety-updated', function(data) {
                    console.log('Real-time safety update received in console:', data);
                    const log = data.safetyLog;
                    if (!log || !log.tournament_id) return;

                    // Update local cache map
                    const isNewAlert = !tournamentSafetyMap[log.tournament_id] || 
                                       tournamentSafetyMap[log.tournament_id].alert_level !== log.alert_level;

                    tournamentSafetyMap[log.tournament_id] = {
                        alert_level: log.alert_level,
                        notes: log.notes,
                        wbgt: log.wbgt,
                        lightning_risk: log.lightning_risk
                    };

                    // If the updated tournament matches our currently viewed match's tournament, update UI
                    if (currentSelectedFixtureId && fixtureTournamentMap[currentSelectedFixtureId] === log.tournament_id) {
                        updateSafetyAlertBanner(isNewAlert && (log.alert_level === 'danger' || log.alert_level === 'warning'));
                    }
                });
            }

            // Initialize status dropdown button state for all matches
            document.querySelectorAll('.status-select-dropdown').forEach(select => {
                handleStatusChange(select);
            });

            // Auto trigger showMatch if a fixture_id was passed back (e.g. after save)
            const matchSelect = document.getElementById('match-select');
            if (matchSelect && matchSelect.value) {
                showMatch(matchSelect.value);
            } else {
                @if(isset($selectedFixtureId) && $selectedFixtureId)
                    showMatch('{{ $selectedFixtureId }}');
                @endif
            }
        });
    </script>
@endpush