@extends('layouts.dashboard')

@section('title', 'Safety Conditions')
@section('page-title', 'Tournament Safety & Weather Monitor')

@section('content')
<div class="page-header" style="margin-bottom: 1.5rem;">
    <h1 class="page-title">Safety & Weather Monitor</h1>
    <p class="page-subtitle">Real-time weather parameters and safety protocols</p>
</div>

@if(isset($tournaments) && $tournaments->count() > 0)
    <div class="card mb-lg" style="margin-bottom: 1.5rem; background: var(--color-bg-secondary); border: 1px solid var(--color-border); border-radius: 12px;">
        <div class="card-body" style="padding: 1rem 1.25rem; display: flex; align-items: center; gap: 1rem;">
            <i class="fas fa-trophy" style="color: var(--color-rugby-green); font-size: 1.25rem;"></i>
            <div style="flex: 1;">
                <label style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--color-text-muted); display: block; margin-bottom: 4px;">Pilih Kejohanan / Select Tournament</label>
                <select class="form-control" style="max-width: 400px; background: var(--color-bg-primary); border-color: var(--color-border); color: var(--color-text-primary); font-weight: 600;"
                    onchange="window.location.href='{{ route('referee.safety') }}?tournament_id=' + this.value">
                    @foreach($tournaments as $t)
                        <option value="{{ $t->id }}" {{ $selectedTournament && $selectedTournament->id == $t->id ? 'selected' : '' }}>
                            {{ $t->name }} —
                            {{ $t->start_date ? \Carbon\Carbon::parse($t->start_date)->format('M d, Y') : 'TBD' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
@endif

<div id="safety-monitor-container">
    @if($latestLog)
        @php
            $alertClass = match($latestLog->alert_level) {
                'danger' => 'alert-danger-glow',
                'warning' => 'alert-warning-glow',
                'caution' => 'alert-caution-glow',
                default => 'alert-safe-glow'
            };
            $alertIcon = match($latestLog->alert_level) {
                'danger' => 'fa-triangle-exclamation',
                'warning' => 'fa-exclamation-circle',
                'caution' => 'fa-info-circle',
                default => 'fa-check-circle'
            };
        @endphp
        
        <div class="glass-card-premium {{ $alertClass }} p-4 mb-4" id="safetyHeroCard">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="text-white font-weight-bold mb-0 d-flex align-items-center" style="font-size: 1.35rem;">
                    <i class="fas {{ $alertIcon }} mr-2" id="alertIcon"></i>
                    <span>Current Safety Status: <span class="text-uppercase" id="alertLevelText">{{ $latestLog->alert_level }}</span></span>
                </h2>
                <div class="last-updated">
                    <i class="fas fa-circle pulse-icon mr-2"></i>
                    <span class="text-tertiary" id="timeText">Updated {{ $latestLog->created_at->diffForHumans() }}</span>
                </div>
            </div>

            @if($latestLog->notes)
                <div class="alert alert-info-dark mb-4" id="alertNotesBlock" style="background: rgba(30, 41, 59, 0.6); border: 1px dashed rgba(255,255,255,0.15); padding: 12px 15px; border-radius: 8px; font-size: 0.9rem; color: #cbd5e1;">
                    <i class="fas fa-bullhorn text-warning mr-2"></i> <strong class="text-white">Official Note:</strong> <span id="alertNotes">{{ $latestLog->notes }}</span>
                </div>
            @else
                <div class="alert alert-info-dark mb-4 d-none" id="alertNotesBlock" style="background: rgba(30, 41, 59, 0.6); border: 1px dashed rgba(255,255,255,0.15); padding: 12px 15px; border-radius: 8px; font-size: 0.9rem; color: #cbd5e1;">
                    <i class="fas fa-bullhorn text-warning mr-2"></i> <strong class="text-white">Official Note:</strong> <span id="alertNotes"></span>
                </div>
            @endif

            <div class="row">
                {{-- WBGT Circular Gauge --}}
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <div class="circular-gauge-wrapper">
                        @php
                            $wbgtValue = $latestLog->wbgt ?? 0;
                            $wbgtPercent = min(($wbgtValue / 40) * 100, 100);
                            $wbgtColor = $wbgtValue >= 32 ? '#ef4444' : ($wbgtValue >= 28 ? '#f59e0b' : '#10b981');
                        @endphp
                        <svg class="circular-gauge" viewBox="0 0 200 200">
                            <circle class="gauge-bg" cx="100" cy="100" r="85" />
                            <circle class="gauge-progress" id="wbgtGaugeCircle" cx="100" cy="100" r="85" 
                                style="stroke: {{ $wbgtColor }}; stroke-dasharray: {{ $wbgtPercent * 5.34 }} 534;" />
                        </svg>
                        <div class="gauge-content">
                            <div class="gauge-value" id="wbgtVal">{{ number_format($wbgtValue, 1) }}</div>
                            <div class="gauge-unit">°C</div>
                            <div class="gauge-label">WBGT</div>
                        </div>
                    </div>
                </div>

                {{-- Lightning Circular Gauge --}}
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <div class="circular-gauge-wrapper">
                        @php
                            $lightningValue = $latestLog->lightning_risk ?? 50;
                            $lightningPercent = min(($lightningValue / 50) * 100, 100);
                            $lightningColor = $lightningValue <= 10 ? '#ef4444' : ($lightningValue <= 20 ? '#f59e0b' : '#10b981');
                        @endphp
                        <svg class="circular-gauge" viewBox="0 0 200 200">
                            <circle class="gauge-bg" cx="100" cy="100" r="85" />
                            <circle class="gauge-progress" id="lightningGaugeCircle" cx="100" cy="100" r="85" 
                                style="stroke: {{ $lightningColor }}; stroke-dasharray: {{ $lightningPercent * 5.34 }} 534;" />
                        </svg>
                        <div class="gauge-content">
                            <div class="gauge-value" id="lightningVal">{{ number_format($lightningValue, 1) }}</div>
                            <div class="gauge-unit">km</div>
                            <div class="gauge-label">Lightning</div>
                        </div>
                    </div>
                </div>

                {{-- Weather Metrics --}}
                <div class="col-md-6">
                    <div class="row h-100">
                        <div class="col-6 mb-3">
                            <div class="metric-card glass-card-mini p-3">
                                <i class="fas fa-temperature-high text-danger mb-2" style="font-size: 1.5rem;"></i>
                                <div class="metric-value" id="tempVal">{{ $latestLog->temperature ? number_format($latestLog->temperature, 1) . '°C' : 'N/A' }}</div>
                                <div class="metric-label">Temperature</div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="metric-card glass-card-mini p-3">
                                <i class="fas fa-droplet text-info mb-2" style="font-size: 1.5rem;"></i>
                                <div class="metric-value" id="humidityVal">{{ $latestLog->humidity ? number_format($latestLog->humidity, 0) . '%' : 'N/A' }}</div>
                                <div class="metric-label">Humidity</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="metric-card glass-card-mini p-3">
                                <i class="fas fa-wind text-primary mb-2" style="font-size: 1.5rem;"></i>
                                <div class="metric-value" id="windVal">{{ $latestLog->wind_speed ? number_format($latestLog->wind_speed, 1) . ' km/h' : 'N/A' }}</div>
                                <div class="metric-label">Wind Speed</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="metric-card glass-card-mini p-3">
                                <i class="fas fa-shield-halved text-success mb-2" style="font-size: 1.5rem;"></i>
                                <div class="metric-value text-uppercase" id="alertLabelVal">{{ $latestLog->alert_level }}</div>
                                <div class="metric-label">Alert Level</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="glass-card-premium alert-safe-glow p-5 text-center mb-4">
            <i class="fas fa-cloud-sun" style="font-size: 4rem; color: var(--color-electric-blue); opacity: 0.3;"></i>
            <h3 class="text-white mt-3">No Weather Data Available</h3>
            <p class="text-tertiary">Waiting for weather updates from tournament operations center...</p>
        </div>
    @endif
</div>

{{-- Protocols Guidelines Section --}}
<div class="row">
    {{-- World Rugby Heat Stress (WBGT) Guidelines --}}
    <div class="col-md-6 mb-4">
        <div class="glass-card-premium h-100">
            <div class="card-header-accent">
                <h3 class="text-white font-weight-bold mb-0" style="font-size: 1rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-hot-tub-person text-danger"></i> World Rugby Heat Stress Protocols
                </h3>
            </div>
            <div class="card-body p-4" style="font-size: 0.85rem; line-height: 1.6; color: #94a3b8;">
                <div class="protocol-item mb-3 pb-3" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span class="badge badge-success float-right" style="padding: 4px 8px;">SAFE</span>
                    <strong class="text-white d-block mb-1">WBGT < 28.0°C</strong>
                    Normal match play. Ensure regular hydration breaks are available on the sidelines.
                </div>
                <div class="protocol-item mb-3 pb-3" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span class="badge badge-warning float-right" style="background: #eab308; color: #000; padding: 4px 8px;">CAUTION</span>
                    <strong class="text-white d-block mb-1">WBGT 28.0°C – 29.9°C</strong>
                    Mandatory water breaks at the 20th and 60th minutes of the match (each half).
                </div>
                <div class="protocol-item mb-3 pb-3" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span class="badge badge-warning float-right" style="background: #f97316; color: #fff; padding: 4px 8px;">WARNING</span>
                    <strong class="text-white d-block mb-1">WBGT 30.0°C – 31.9°C</strong>
                    Mandatory water breaks. Active monitoring of players. Halves can be shortened to 35 mins upon team agreement.
                </div>
                <div class="protocol-item">
                    <span class="badge badge-danger float-right" style="padding: 4px 8px;">DANGER</span>
                    <strong class="text-white d-block mb-1">WBGT ≥ 32.0°C</strong>
                    <strong>SUSPEND PLAY IMMEDIATELY.</strong> High risk of heat stroke. Evacuate players to shaded cooling areas.
                </div>
            </div>
        </div>
    </div>

    {{-- Lightning Safety 30-30 Rules --}}
    <div class="col-md-6 mb-4">
        <div class="glass-card-premium h-100">
            <div class="card-header-accent">
                <h3 class="text-white font-weight-bold mb-0" style="font-size: 1rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-cloud-bolt text-warning"></i> Lightning 30-30 Evacuation Protocol
                </h3>
            </div>
            <div class="card-body p-4" style="font-size: 0.85rem; line-height: 1.6; color: #94a3b8;">
                <div class="protocol-item mb-3 pb-3" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span class="badge badge-success float-right" style="padding: 4px 8px;">SAFE</span>
                    <strong class="text-white d-block mb-1">Distance > 20 km</strong>
                    Normal play. Continue checking weather radars and monitoring thunderstorm clouds.
                </div>
                <div class="protocol-item mb-3 pb-3" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span class="badge badge-warning float-right" style="background: #eab308; color: #000; padding: 4px 8px;">CAUTION</span>
                    <strong class="text-white d-block mb-1">Distance 15 km – 20 km</strong>
                    Alert mode. Monitor flash-to-bang times. Be prepared to halt the game if lightning moves closer.
                </div>
                <div class="protocol-item mb-3 pb-3" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span class="badge badge-warning float-right" style="background: #f97316; color: #fff; padding: 4px 8px;">WARNING</span>
                    <strong class="text-white d-block mb-1">Distance 10 km – 15 km</strong>
                    Pre-evacuation prep. Mobilize tournament guests, players, and staff to shelter coordinates.
                </div>
                <div class="protocol-item">
                    <span class="badge badge-danger float-right" style="padding: 4px 8px;">DANGER</span>
                    <strong class="text-white d-block mb-1">Distance ≤ 10 km</strong>
                    <strong>SUSPEND PLAY IMMEDIATELY.</strong> Referees must sound the whistle to suspend play. Direct everyone to fully enclosed shelters. <strong>Do not resume play until 30 minutes after the last strike.</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Glassmorphism Cards */
.glass-card-premium {
    background: rgba(30, 41, 59, 0.7);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.glass-card-mini {
    background: rgba(30, 41, 59, 0.5);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    text-align: center;
}

/* Alert Glow Effects */
.alert-danger-glow {
    border-top: 3px solid #ef4444;
    box-shadow: 0 0 30px rgba(239, 68, 68, 0.3), 0 8px 32px rgba(0, 0, 0, 0.3);
    animation: pulse-danger 2s infinite;
}

.alert-warning-glow {
    border-top: 3px solid #f59e0b;
    box-shadow: 0 0 30px rgba(245, 158, 11, 0.3), 0 8px 32px rgba(0, 0, 0, 0.3);
    animation: pulse-warning 2s infinite;
}

.alert-caution-glow {
    border-top: 3px solid #3b82f6;
    box-shadow: 0 0 30px rgba(59, 130, 246, 0.3), 0 8px 32px rgba(0, 0, 0, 0.3);
}

.alert-safe-glow {
    border-top: 3px solid #10b981;
    box-shadow: 0 0 30px rgba(16, 185, 129, 0.2), 0 8px 32px rgba(0, 0, 0, 0.3);
}

@keyframes pulse-danger {
    0%, 100% { box-shadow: 0 0 30px rgba(239, 68, 68, 0.3), 0 8px 32px rgba(0, 0, 0, 0.3); }
    50% { box-shadow: 0 0 50px rgba(239, 68, 68, 0.5), 0 8px 32px rgba(0, 0, 0, 0.3); }
}

@keyframes pulse-warning {
    0%, 100% { box-shadow: 0 0 30px rgba(245, 158, 11, 0.3), 0 8px 32px rgba(0, 0, 0, 0.3); }
    50% { box-shadow: 0 0 50px rgba(245, 158, 11, 0.5), 0 8px 32px rgba(0, 0, 0, 0.3); }
}

/* Pulse Icon */
.pulse-icon {
    color: #10b981;
    animation: pulse-dot 2s infinite;
}

@keyframes pulse-dot {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

/* Circular Gauges */
.circular-gauge-wrapper {
    position: relative;
    width: 150px;
    height: 150px;
    margin: 0 auto;
}

.circular-gauge {
    width: 100%;
    height: 100%;
    transform: rotate(-90deg);
}

.gauge-bg {
    fill: none;
    stroke: rgba(255, 255, 255, 0.1);
    stroke-width: 12;
}

.gauge-progress {
    fill: none;
    stroke-width: 12;
    stroke-linecap: round;
    transition: stroke-dasharray 1s ease, stroke 1s ease;
    filter: drop-shadow(0 0 8px currentColor);
}

.gauge-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.gauge-value {
    font-size: 2.2rem;
    font-weight: bold;
    color: #fff;
    line-height: 1;
}

.gauge-unit {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.6);
    margin-top: -3px;
}

.gauge-label {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.5);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 3px;
}

/* Metric Cards */
.metric-card {
    transition: transform 0.3s ease;
}

.metric-card:hover {
    transform: translateY(-3px);
}

.metric-value {
    font-size: 1.35rem;
    font-weight: bold;
    color: #fff;
    margin: 8px 0 4px;
}

.metric-label {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.5);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-header-accent {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

@media (max-width: 768px) {
    .circular-gauge-wrapper {
        width: 130px;
        height: 130px;
    }
    .gauge-value {
        font-size: 1.8rem;
    }
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
        channel.bind('safety-updated', function(data) {
            console.log('Real-time safety update received:', data);
            const log = data.safetyLog;
            if (!log) return;

            // Only update if the log tournament_id matches the currently selected tournament
            const currentTournamentId = "{{ $selectedTournament ? $selectedTournament->id : '' }}";
            if (log.tournament_id && currentTournamentId && log.tournament_id.toString() !== currentTournamentId.toString()) {
                console.log('Safety log tournament ID does not match currently viewed tournament safety page. Ignoring.');
                return;
            }

            // Update safety values in UI dynamically
            const tempVal = document.getElementById('tempVal');
            const humidityVal = document.getElementById('humidityVal');
            const windVal = document.getElementById('windVal');
            const alertLabelVal = document.getElementById('alertLabelVal');
            const wbgtVal = document.getElementById('wbgtVal');
            const lightningVal = document.getElementById('lightningVal');
            const alertLevelText = document.getElementById('alertLevelText');
            const timeText = document.getElementById('timeText');

            if (tempVal) tempVal.textContent = log.temperature ? parseFloat(log.temperature).toFixed(1) + '°C' : 'N/A';
            if (humidityVal) humidityVal.textContent = log.humidity ? parseFloat(log.humidity).toFixed(0) + '%' : 'N/A';
            if (windVal) windVal.textContent = log.wind_speed ? parseFloat(log.wind_speed).toFixed(1) + ' km/h' : 'N/A';
            if (alertLabelVal) alertLabelVal.textContent = log.alert_level;
            if (wbgtVal) wbgtVal.textContent = log.wbgt ? parseFloat(log.wbgt).toFixed(1) : '0.0';
            if (lightningVal) lightningVal.textContent = log.lightning_risk ? parseFloat(log.lightning_risk).toFixed(1) : '50.0';
            if (alertLevelText) alertLevelText.textContent = log.alert_level;
            if (timeText) timeText.textContent = 'Updated just now';

            // Show notes if present
            const notesBlock = document.getElementById('alertNotesBlock');
            const notesText = document.getElementById('alertNotes');
            if (notesBlock && notesText) {
                if (log.notes) {
                    notesText.textContent = log.notes;
                    notesBlock.classList.remove('d-none');
                } else {
                    notesText.textContent = '';
                    notesBlock.classList.add('d-none');
                }
            }

            // Update gauges circles
            const wbgtCircle = document.getElementById('wbgtGaugeCircle');
            if (wbgtCircle) {
                const wbgtValNum = parseFloat(log.wbgt) || 0;
                const wbgtPercent = Math.min((wbgtValNum / 40) * 100, 100);
                const color = wbgtValNum >= 32 ? '#ef4444' : (wbgtValNum >= 28 ? '#f59e0b' : '#10b981');
                wbgtCircle.style.stroke = color;
                wbgtCircle.style.strokeDasharray = (wbgtPercent * 5.34) + ' 534';
            }

            const lightningCircle = document.getElementById('lightningGaugeCircle');
            if (lightningCircle) {
                const lightValNum = parseFloat(log.lightning_risk) || 50;
                const lightPercent = Math.min((lightValNum / 50) * 100, 100);
                const color = lightValNum <= 10 ? '#ef4444' : (lightValNum <= 20 ? '#f59e0b' : '#10b981');
                lightningCircle.style.stroke = color;
                lightningCircle.style.strokeDasharray = (lightPercent * 5.34) + ' 534';
            }

            // Update hero card styling & icons
            const heroCard = document.getElementById('safetyHeroCard');
            const alertIcon = document.getElementById('alertIcon');
            if (heroCard && alertIcon) {
                heroCard.className = 'glass-card-premium p-4 mb-4';
                alertIcon.className = 'fas mr-2';

                if (log.alert_level === 'danger') {
                    heroCard.classList.add('alert-danger-glow');
                    alertIcon.classList.add('fa-triangle-exclamation');
                } else if (log.alert_level === 'warning') {
                    heroCard.classList.add('alert-warning-glow');
                    alertIcon.classList.add('fa-exclamation-circle');
                } else if (log.alert_level === 'caution') {
                    heroCard.classList.add('alert-caution-glow');
                    alertIcon.classList.add('fa-info-circle');
                } else {
                    heroCard.classList.add('alert-safe-glow');
                    alertIcon.classList.add('fa-check-circle');
                }
            }
        });
    }
});
</script>
@endpush