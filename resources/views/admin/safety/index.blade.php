@extends('layouts.dashboard')

@section('content')
    <div class="page-header mb-4" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 class="page-title text-white font-weight-bold mb-1">Safety & Weather Operations</h1>
            <p class="page-subtitle text-tertiary mb-0">Monitor WBGT, lightning alerts, and protocols per tournament</p>
        </div>
        
        @if($tournaments->count() > 0)
            <div style="display: flex; align-items: center; gap: 10px; background: rgba(30, 41, 59, 0.7); padding: 8px 15px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                <i class="fas fa-trophy text-warning"></i>
                <label for="tournament_select" class="text-tertiary mb-0 mr-2" style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">Active Tournament:</label>
                <select id="tournament_select" class="form-control-glass" style="max-width: 250px; padding: 6px 12px; font-size: 0.85rem;" onchange="window.location.href='?tournament_id=' + this.value">
                    @foreach($tournaments as $t)
                        <option value="{{ $t->id }}" {{ $selectedTournament && $selectedTournament->id == $t->id ? 'selected' : '' }}>
                            {{ $t->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    {{-- Hero Status Section --}}
    <div class="safety-hero mb-4">
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
            
            <div class="glass-card-premium {{ $alertClass }} p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="text-white font-weight-bold mb-0 d-flex align-items-center">
                        <i class="fas {{ $alertIcon }} mr-2"></i>
                        <span>Current Safety Status: <span class="text-uppercase">{{ $latestLog->alert_level }}</span></span>
                    </h2>
                    <div class="last-updated">
                        <i class="fas fa-circle pulse-icon mr-2"></i>
                        <span class="text-tertiary">Updated {{ $latestLog->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <div class="row">
                    {{-- WBGT Circular Gauge --}}
                    <div class="col-md-3 text-center">
                        <div class="circular-gauge-wrapper">
                            @php
                                $wbgtValue = $latestLog->wbgt ?? 0;
                                $wbgtPercent = min(($wbgtValue / 40) * 100, 100);
                                $wbgtColor = $wbgtValue >= 32 ? '#ef4444' : ($wbgtValue >= 28 ? '#f59e0b' : '#10b981');
                            @endphp
                            <svg class="circular-gauge" viewBox="0 0 200 200">
                                <circle class="gauge-bg" cx="100" cy="100" r="85" />
                                <circle class="gauge-progress" cx="100" cy="100" r="85" 
                                    style="stroke: {{ $wbgtColor }}; stroke-dasharray: {{ $wbgtPercent * 5.34 }} 534;" />
                            </svg>
                            <div class="gauge-content">
                                <div class="gauge-value">{{ number_format($wbgtValue, 1) }}</div>
                                <div class="gauge-unit">°C</div>
                                <div class="gauge-label">WBGT</div>
                            </div>
                        </div>
                    </div>

                    {{-- Lightning Circular Gauge --}}
                    <div class="col-md-3 text-center">
                        <div class="circular-gauge-wrapper">
                            @php
                                $lightningValue = $latestLog->lightning_risk ?? 50;
                                $lightningPercent = min(($lightningValue / 50) * 100, 100);
                                $lightningColor = $lightningValue <= 10 ? '#ef4444' : ($lightningValue <= 20 ? '#f59e0b' : '#10b981');
                            @endphp
                            <svg class="circular-gauge" viewBox="0 0 200 200">
                                <circle class="gauge-bg" cx="100" cy="100" r="85" />
                                <circle class="gauge-progress" cx="100" cy="100" r="85" 
                                    style="stroke: {{ $lightningColor }}; stroke-dasharray: {{ $lightningPercent * 5.34 }} 534;" />
                            </svg>
                            <div class="gauge-content">
                                <div class="gauge-value">{{ number_format($lightningValue, 1) }}</div>
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
                                    <div class="metric-value">{{ $latestLog->temperature ? number_format($latestLog->temperature, 1) . '°C' : 'N/A' }}</div>
                                    <div class="metric-label">Temperature</div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="metric-card glass-card-mini p-3">
                                    <i class="fas fa-droplet text-info mb-2" style="font-size: 1.5rem;"></i>
                                    <div class="metric-value">{{ $latestLog->humidity ? number_format($latestLog->humidity, 0) . '%' : 'N/A' }}</div>
                                    <div class="metric-label">Humidity</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="metric-card glass-card-mini p-3">
                                    <i class="fas fa-wind text-primary mb-2" style="font-size: 1.5rem;"></i>
                                    <div class="metric-value">{{ $latestLog->wind_speed ? number_format($latestLog->wind_speed, 1) . ' km/h' : 'N/A' }}</div>
                                    <div class="metric-label">Wind Speed</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="metric-card glass-card-mini p-3">
                                    <i class="fas fa-shield-halved text-success mb-2" style="font-size: 1.5rem;"></i>
                                    <div class="metric-value text-uppercase">{{ $latestLog->alert_level }}</div>
                                    <div class="metric-label">Alert Level</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="glass-card-premium alert-safe-glow p-4 text-center">
                <i class="fas fa-cloud-sun" style="font-size: 4rem; color: var(--color-electric-blue); opacity: 0.3;"></i>
                <h3 class="text-white mt-3">No Weather Data Available</h3>
                <p class="text-tertiary">Click "Refresh Weather Data" to fetch current conditions</p>
            </div>
        @endif
    </div>

    {{-- Action Button --}}
    <div class="mb-4 text-right">
        <form action="{{ route('admin.safety.refresh') }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="tournament_id" value="{{ $selectedTournament->id ?? '' }}">
            <button type="submit" class="btn-glow btn-primary-glow" {{ !$selectedTournament ? 'disabled' : '' }}>
                <i class="fas fa-sync-alt mr-2"></i> Refresh Weather Data
            </button>
        </form>
    </div>

    {{-- Dual Column Layout --}}
    <div class="row">
        {{-- Left Column: Manual Update Form --}}
        <div class="col-lg-4 mb-4">
            <div class="glass-card-premium form-card">
                <div class="card-header-accent">
                    <h3 class="text-white font-weight-bold mb-0">
                        <i class="fas fa-edit mr-2"></i> Manual Entry
                    </h3>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.safety.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tournament_id" value="{{ $selectedTournament->id ?? '' }}">
                        
                        <div class="form-group-icon mb-3">
                            <i class="fas fa-temperature-high form-icon"></i>
                            <input type="number" step="0.1" class="form-control-glass" 
                                name="temperature" placeholder="Temperature (°C)">
                        </div>

                        <div class="form-group-icon mb-3">
                            <i class="fas fa-droplet form-icon"></i>
                            <input type="number" step="0.1" class="form-control-glass" 
                                name="humidity" placeholder="Humidity (%)">
                        </div>

                        <div class="form-group-icon mb-3">
                            <i class="fas fa-wind form-icon"></i>
                            <input type="number" step="0.1" class="form-control-glass" 
                                name="wind_speed" placeholder="Wind Speed (km/h)">
                        </div>

                        <div class="form-group-icon mb-3">
                            <i class="fas fa-fire form-icon"></i>
                            <input type="number" step="0.1" class="form-control-glass" 
                                name="wbgt" placeholder="WBGT (°C)" required>
                            <small class="text-tertiary ml-4 pl-2">Wet Bulb Globe Temperature</small>
                        </div>

                        <div class="form-group-icon mb-3">
                            <i class="fas fa-bolt form-icon"></i>
                            <input type="number" step="0.1" class="form-control-glass" 
                                name="lightning_risk" placeholder="Lightning Distance (km)" required>
                        </div>

                        <div class="form-group-icon mb-4">
                            <i class="fas fa-comment form-icon"></i>
                            <textarea class="form-control-glass" name="notes" rows="3" 
                                placeholder="Notes or alert message..."></textarea>
                        </div>

                        <button type="submit" class="btn-glow btn-danger-glow w-100">
                            <i class="fas fa-broadcast-tower mr-2"></i> Broadcast Alert
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right Column: Recent Safety Logs --}}
        <div class="col-lg-8 mb-4">
            <div class="glass-card-premium">
                <div class="card-header-accent">
                    <h3 class="text-white font-weight-bold mb-0">
                        <i class="fas fa-history mr-2"></i> Recent Safety Logs
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table-premium">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Temp (°C)</th>
                                    <th>WBGT (°C)</th>
                                    <th>Lightning (km)</th>
                                    <th>Alert Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>
                                            <i class="far fa-clock mr-2 text-tertiary"></i>
                                            {{ $log->created_at->format('d M H:i') }}
                                        </td>
                                        <td>{{ $log->temperature ? number_format($log->temperature, 1) : 'N/A' }}</td>
                                        <td>{{ $log->wbgt ? number_format($log->wbgt, 1) : 'N/A' }}</td>
                                        <td>{{ $log->lightning_risk ? number_format($log->lightning_risk, 1) : 'N/A' }}</td>
                                        <td>
                                            @if($log->alert_level == 'danger')
                                                <span class="badge-glow badge-danger">DANGER</span>
                                            @elseif($log->alert_level == 'warning')
                                                <span class="badge-glow badge-warning">WARNING</span>
                                            @elseif($log->alert_level == 'caution')
                                                <span class="badge-glow badge-caution">CAUTION</span>
                                            @else
                                                <span class="badge-glow badge-success">SAFE</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox" style="font-size: 2rem; opacity: 0.3;"></i>
                                            <p class="mt-2">No safety logs available</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
    width: 180px;
    height: 180px;
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
    transition: stroke-dasharray 1s ease;
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
    font-size: 2.5rem;
    font-weight: bold;
    color: #fff;
    line-height: 1;
}

.gauge-unit {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.6);
    margin-top: -5px;
}

.gauge-label {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.5);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 5px;
}

/* Metric Cards */
.metric-card {
    transition: transform 0.3s ease;
}

.metric-card:hover {
    transform: translateY(-5px);
}

.metric-value {
    font-size: 1.5rem;
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

/* Form Styling */
.card-header-accent {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.form-card {
    border-top: 3px solid #3b82f6;
}

.form-group-icon {
    position: relative;
}

.form-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, 0.4);
    z-index: 1;
}

.form-control-glass {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    color: #fff;
    padding: 12px 15px 12px 45px;
    width: 100%;
    transition: all 0.3s ease;
}

.form-control-glass:focus {
    background: rgba(255, 255, 255, 0.08);
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    outline: none;
}

.form-control-glass::placeholder {
    color: rgba(255, 255, 255, 0.3);
}

textarea.form-control-glass {
    resize: vertical;
    min-height: 80px;
}

/* Glow Buttons */
.btn-glow {
    padding: 12px 24px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-primary-glow {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: #fff;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.btn-primary-glow:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(59, 130, 246, 0.5);
}

.btn-danger-glow {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
}

.btn-danger-glow:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(239, 68, 68, 0.5);
}

/* Premium Table */
.table-premium {
    width: 100%;
    color: #fff;
    margin: 0;
}

.table-premium thead th {
    background: rgba(255, 255, 255, 0.05);
    padding: 16px 20px;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: rgba(255, 255, 255, 0.6);
    font-weight: 600;
    border: none;
}

.table-premium tbody tr {
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    transition: background 0.2s ease;
}

.table-premium tbody tr:nth-child(even) {
    background: rgba(255, 255, 255, 0.02);
}

.table-premium tbody tr:hover {
    background: rgba(255, 255, 255, 0.05);
}

.table-premium tbody td {
    padding: 16px 20px;
    border: none;
}

/* Glow Badges */
.badge-glow {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-block;
}

.badge-danger {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
    box-shadow: 0 0 15px rgba(239, 68, 68, 0.3);
}

.badge-warning {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.3);
    box-shadow: 0 0 15px rgba(245, 158, 11, 0.3);
}

.badge-caution {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.3);
    box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);
}

.badge-success {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
    box-shadow: 0 0 15px rgba(16, 185, 129, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .circular-gauge-wrapper {
        width: 140px;
        height: 140px;
    }
    
    .gauge-value {
        font-size: 2rem;
    }
    
    .metric-value {
        font-size: 1.2rem;
    }
    
    .safety-hero .row > div {
        margin-bottom: 20px;
    }
}

@media (max-width: 576px) {
    .table-premium {
        font-size: 0.85rem;
    }
    
    .table-premium thead th,
    .table-premium tbody td {
        padding: 12px 10px;
    }
}
</style>
@endpush