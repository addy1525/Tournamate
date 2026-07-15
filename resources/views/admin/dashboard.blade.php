@extends('layouts.dashboard')

@section('content')
    <div class="content-header p-0 pt-2">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-sm-6">
                    <h1 class="m-0 text-white font-weight-bold" style="font-size: 1.8rem; letter-spacing: -0.5px;">
                        Dashboard <span class="text-secondary" style="font-size: 0.9rem; vertical-align: middle;">v2.1</span>
                    </h1>
                    <p class="text-tertiary small m-0">Welcome back, {{ Auth::user()->name }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            
            <!-- Quick Stats Row (Compact) -->
            <div class="row mb-3">
                <div class="col-6 col-md-3">
                    <div class="glass-card p-3 d-flex align-items-center justify-content-between position-relative overflow-hidden mb-2">
                        <div style="z-index: 2;">
                            <div class="text-tertiary text-uppercase font-weight-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Tournaments</div>
                            <div class="text-white font-weight-bold mt-1 neon-stat-value" style="font-size: 2rem; line-height: 1;">
                                {{ $tournamentsCount }}
                            </div>
                        </div>
                        <div class="position-absolute" style="right: -10px; top: -10px; opacity: 0.1; font-size: 5rem; color: white;">
                            <i class="fas fa-trophy"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="glass-card p-3 d-flex align-items-center justify-content-between position-relative overflow-hidden mb-2">
                        <div style="z-index: 2;">
                            <div class="text-tertiary text-uppercase font-weight-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Teams</div>
                            <div class="text-white font-weight-bold mt-1 neon-stat-value" style="font-size: 2rem; line-height: 1;">
                                {{ $activeTeamsCount }}
                            </div>
                        </div>
                        <div class="position-absolute" style="right: -10px; top: -10px; opacity: 0.1; font-size: 5rem; color: white;">
                            <i class="fas fa-tshirt"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="glass-card p-3 d-flex align-items-center justify-content-between position-relative overflow-hidden mb-2">
                        <div style="z-index: 2;">
                            <div class="text-tertiary text-uppercase font-weight-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Managers</div>
                            <div class="text-white font-weight-bold mt-1 neon-stat-value" style="font-size: 2rem; line-height: 1;">
                                {{ $managersCount }}
                            </div>
                        </div>
                        <div class="position-absolute" style="right: -10px; top: -10px; opacity: 0.1; font-size: 5rem; color: white;">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="glass-card p-2 position-relative overflow-hidden text-center mb-2">                         
                         <div class="d-flex justify-content-around align-items-center" style="height: 100%;">
                            <!-- WBGT Widget with Color Coding -->
                            <div class="text-center">
                                @php
                                    $wbgtValue = $latestSafetyLog->wbgt ?? null;
                                    $wbgtClass = 'safe'; // Default green
                                    if ($wbgtValue !== null) {
                                        if ($wbgtValue >= 32) {
                                            $wbgtClass = 'danger'; // Red
                                        } elseif ($wbgtValue >= 28) {
                                            $wbgtClass = 'warning'; // Yellow/Orange
                                        }
                                    }
                                @endphp
                                <div class="gauge-wrapper" style="width: 70px; height: 70px;">
                                    <div class="gauge-ring" style="border-width: 4px;"></div>
                                    <div class="gauge-fill {{ $wbgtClass }}" style="border-width: 4px;"></div>
                                    <div class="gauge-value">
                                        <div class="gauge-number" style="font-size: 1rem;">{{ $wbgtValue ? number_format($wbgtValue, 1) : 'N/A' }}</div>
                                        <div class="gauge-label" style="font-size: 0.5rem;">WBGT</div>
                                    </div>
                                </div>
                                @if($latestSafetyLog && $latestSafetyLog->temperature)
                                    <div class="text-tertiary mt-1" style="font-size: 0.6rem;">{{ number_format($latestSafetyLog->temperature, 1) }}°C</div>
                                @endif
                            </div>
                            
                            <!-- Lightning Widget -->
                             <div class="text-center">
                                @php
                                    $lightningValue = $latestSafetyLog->lightning_risk ?? null;
                                    $lightningClass = 'safe'; // Default green
                                    if ($lightningValue !== null) {
                                        if ($lightningValue <= 10) {
                                            $lightningClass = 'danger'; // Red - very close
                                        } elseif ($lightningValue <= 20) {
                                            $lightningClass = 'warning'; // Yellow - moderate
                                        }
                                    }
                                @endphp
                                <div class="gauge-wrapper" style="width: 70px; height: 70px;">
                                    <div class="gauge-ring" style="border-width: 4px;"></div>
                                    <div class="gauge-fill {{ $lightningClass }}" style="border-width: 4px;"></div>
                                    <div class="gauge-value">
                                        <div class="gauge-number" style="font-size: 1rem;">{{ $lightningValue ? number_format($lightningValue, 1) : 'N/A' }}</div>
                                        <div class="gauge-label" style="font-size: 0.5rem;">KM</div>
                                    </div>
                                </div>
                                @if($latestSafetyLog && $latestSafetyLog->humidity)
                                    <div class="text-tertiary mt-1" style="font-size: 0.6rem;">{{ number_format($latestSafetyLog->humidity, 0) }}% RH</div>
                                @endif
                             </div>
                         </div>
                    </div>
                </div>
            </div>

            <!-- Charts & Financials (Fixed Height) -->
            <div class="row mb-3">
                <div class="col-lg-8">
                    <div class="glass-card mb-2 h-100"> <!-- h-100 ensures full height -->
                        <div class="glass-header d-flex justify-content-between align-items-center p-3">
                            <h3 class="card-title text-white font-weight-bold m-0" style="font-size: 1rem;"><i class="fas fa-chart-line text-success mr-2"></i> Financial Performance</h3>
                        </div>
                        <div class="card-body p-3">
                            <!-- Wrapped in fixed-height container -->
                            <div class="position-relative" style="height: 300px;">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="glass-card mb-2 h-100"> <!-- h-100 ensures alignment -->
                        <div class="glass-header p-3">
                            <h3 class="card-title text-white font-weight-bold m-0" style="font-size: 1rem;"><i class="fas fa-chart-pie text-info mr-2"></i> Participation</h3>
                        </div>
                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                            <!-- Wrapped in fixed-height container to match -->
                            <div class="position-relative" style="height: 250px;">
                                <canvas id="participationChart"></canvas>
                            </div>
                            <div class="mt-2 text-center small text-tertiary">
                                Total: {{ $activeTeamsCount }} Teams
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Secondary Stats Row (Glow style) -->
            <div class="row mb-4">
                <div class="col-6 col-md-3">
                    <div class="glass-card p-3 d-flex align-items-center justify-content-between position-relative overflow-hidden mb-2" style="border-left: 3px solid var(--color-rugby-green-light); height: 100%;">
                        <div style="z-index: 2;">
                            <div class="text-tertiary text-uppercase font-weight-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Total Revenue</div>
                            <div class="text-white font-weight-bold mt-1 neon-stat-value" style="font-size: 1.5rem; line-height: 1;">
                                RM {{ number_format($totalRevenue, 2) }}
                            </div>
                        </div>
                        <div class="position-absolute" style="right: -10px; top: -10px; opacity: 0.06; font-size: 4rem; color: white;">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="glass-card p-3 d-flex align-items-center justify-content-between position-relative overflow-hidden mb-2" style="border-left: 3px solid var(--color-electric-blue-light); height: 100%;">
                        <div style="z-index: 2;">
                            <div class="text-tertiary text-uppercase font-weight-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Referees</div>
                            <div class="text-white font-weight-bold mt-1 neon-stat-value" style="font-size: 1.5rem; line-height: 1;">
                                {{ $refereesCount }}
                            </div>
                        </div>
                        <div class="position-absolute" style="right: -10px; top: -10px; opacity: 0.06; font-size: 4rem; color: white;">
                            <i class="fas fa-whistle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
                        <div class="glass-card p-3 d-flex align-items-center justify-content-between position-relative overflow-hidden mb-2" style="border-left: 3px solid var(--color-warning); height: 100%;">
                            <div style="z-index: 2;">
                                <div class="text-tertiary text-uppercase font-weight-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Pending Approval</div>
                                <div class="font-weight-bold mt-1 neon-stat-value" style="font-size: 1.5rem; line-height: 1; color: var(--color-warning);">
                                    {{ $pendingManagersCount }}
                                </div>
                            </div>
                            <div class="position-absolute" style="right: -10px; top: -10px; opacity: 0.06; font-size: 4rem; color: white;">
                                <i class="fas fa-user-clock"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <div class="glass-card p-3 d-flex align-items-center justify-content-between position-relative overflow-hidden mb-2" style="border-left: 3px solid var(--color-text-muted); height: 100%;">
                        <div style="z-index: 2;">
                            <div class="text-tertiary text-uppercase font-weight-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Spectators</div>
                            <div class="text-white font-weight-bold mt-1 neon-stat-value" style="font-size: 1.5rem; line-height: 1;">
                                {{ $spectatorsCount }}
                            </div>
                        </div>
                        <div class="position-absolute" style="right: -10px; top: -10px; opacity: 0.06; font-size: 4rem; color: white;">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Tournaments, ELO Rankings, & Activity (Compact 3-Column) -->
            <div class="row">
                <!-- Column 1: Live Tournaments -->
                <div class="col-lg-4 mb-3">
                    <div class="glass-card h-100" style="display: flex; flex-direction: column;">
                        <div class="glass-header border-0 p-3" style="border-bottom: 1px solid rgba(255,255,255,0.06) !important;">
                            <h3 class="card-title text-white font-weight-bold m-0" style="font-size: 0.95rem;">
                                <i class="fas fa-trophy text-info mr-2"></i> Tournaments List
                            </h3>
                        </div>
                        <div class="card-body table-responsive p-0" style="flex: 1;">
                            <table class="table table-striped table-valign-middle text-white table-sm" style="margin: 0;">
                                <thead>
                                    <tr>
                                        <th class="text-tertiary border-top-0 pl-3" style="font-size: 0.68rem; border-bottom: 1px solid rgba(255,255,255,0.06);">Name</th>
                                        <th class="text-tertiary border-top-0" style="font-size: 0.68rem; border-bottom: 1px solid rgba(255,255,255,0.06);">Status</th>
                                        <th class="text-tertiary border-top-0 text-right pr-3" style="font-size: 0.68rem; border-bottom: 1px solid rgba(255,255,255,0.06);">Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($latestTournaments) && count($latestTournaments) > 0)
                                        @foreach($latestTournaments as $t)
                                            <tr>
                                                <td class="font-weight-bold pl-3 border-secondary" style="font-size: 0.82rem; vertical-align: middle;">
                                                    {{ $t->name }}
                                                </td>
                                                <td class="border-secondary" style="vertical-align: middle;">
                                                    @if($t->status == 'upcoming')
                                                        <span class="badge badge-info rounded-pill" style="font-size: 0.62rem; padding: 2px 6px;">UPCOMING</span>
                                                    @elseif($t->status == 'ongoing')
                                                        <span class="badge badge-success rounded-pill" style="font-size: 0.62rem; padding: 2px 6px;">LIVE</span>
                                                    @else
                                                        <span class="badge badge-secondary rounded-pill" style="font-size: 0.62rem; padding: 2px 6px;">DONE</span>
                                                    @endif
                                                </td>
                                                <td class="text-right pr-3 border-secondary" style="vertical-align: middle;">
                                                    <a href="{{ route('admin.tournaments.edit', $t->id) }}" class="text-tertiary hover-text-info" title="Settings">
                                                        <i class="fas fa-cog"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="text-center text-muted border-secondary py-4" style="font-size: 0.85rem;">No recent tournaments</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Column 2: Top Elo Power Rankings -->
                <div class="col-lg-4 mb-3">
                    <div class="glass-card h-100" style="display: flex; flex-direction: column;">
                        <div class="glass-header border-0 p-3" style="border-bottom: 1px solid rgba(255,255,255,0.06) !important;">
                            <h3 class="card-title text-white font-weight-bold m-0" style="font-size: 0.95rem;">
                                <i class="fas fa-bolt text-warning mr-2"></i> ELO Power Rankings
                            </h3>
                        </div>
                        <div class="card-body table-responsive p-0" style="flex: 1;">
                            <table class="table table-striped table-valign-middle text-white table-sm" style="margin: 0;">
                                <thead>
                                    <tr>
                                        <th class="text-tertiary border-top-0 pl-3" style="font-size: 0.68rem; border-bottom: 1px solid rgba(255,255,255,0.06);">Rank / Team</th>
                                        <th class="text-tertiary border-top-0 text-right pr-3" style="font-size: 0.68rem; border-bottom: 1px solid rgba(255,255,255,0.06);">Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($topEloTeams) && count($topEloTeams) > 0)
                                        @foreach($topEloTeams as $index => $team)
                                            @php
                                                $rank = $index + 1;
                                                $badgeColor = 'badge-secondary';
                                                if ($rank == 1) $badgeColor = 'badge-warning';
                                                elseif ($rank == 2) $badgeColor = 'badge-light';
                                                elseif ($rank == 3) $badgeColor = 'badge-dark';
                                            @endphp
                                            <tr>
                                                <td class="font-weight-bold pl-3 border-secondary" style="font-size: 0.82rem; vertical-align: middle;">
                                                    <span class="badge {{ $badgeColor }} mr-1" style="font-size: 0.65rem; padding: 2px 5px;">#{{ $rank }}</span>
                                                    {{ $team->name }}
                                                </td>
                                                <td class="text-right pr-3 border-secondary font-weight-bold text-success" style="font-size: 0.82rem; vertical-align: middle;">
                                                    {{ $team->rating ?? 1500 }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="2" class="text-center text-muted border-secondary py-4" style="font-size: 0.85rem;">No registered teams</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Column 3: Activity Log -->
                <div class="col-lg-4 mb-3">
                    <div class="glass-card h-100" style="display: flex; flex-direction: column;">
                        <div class="glass-header p-3" style="border-bottom: 1px solid rgba(255,255,255,0.06) !important;">
                            <h3 class="card-title text-white font-weight-bold m-0" style="font-size: 0.95rem;">
                                <i class="fas fa-list-ul text-success mr-2"></i> Activity Log
                            </h3>
                        </div>
                        <div class="card-body p-3" style="flex: 1; overflow-y: auto;">
                             <div class="timeline-minimal">
                                @forelse($recentActivities as $activity)
                                    <div class="activity-item pb-2 pl-3" style="border-left: 1px solid rgba(255,255,255,0.08); position: relative; margin-bottom: 10px;">
                                        <div style="position: absolute; left: -4px; top: 4px; width: 8px; height: 8px; border-radius: 50%; background: var(--color-rugby-green-light);"></div>
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="text-white font-weight-bold" style="font-size: 0.75rem;">{{ $activity->user->name ?? 'System' }}</span>
                                            <span class="text-tertiary small ml-auto" style="font-size: 0.6rem;">{{ $activity->created_at->diffForHumans(null, true, true) }}</span>
                                        </div>
                                        <div class="text-secondary" style="font-size: 0.72rem; line-height: 1.3;">
                                            {{ Str::limit($activity->description, 50) }}
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-4 small">No recent activity</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        'use strict'

        // Revenue Chart (Fixed)
        var ctxDetail = document.getElementById('revenueChart').getContext('2d');
        var gradient = ctxDetail.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(0, 168, 107, 0.4)');
        gradient.addColorStop(1, 'rgba(0, 168, 107, 0)');

        new Chart(ctxDetail, {
            type: 'line',
            data: {
                labels: ['M', 'T', 'W', 'T', 'F', 'S', 'S'],
                datasets: [{
                    label: 'Revenue',
                    backgroundColor: gradient,
                    borderColor: '#00a86b',
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    fill: true,
                    tension: 0.4,
                    data: [1000, 2000, 3000, 2500, 4000, 5000, {{ $totalRevenue > 0 ? $totalRevenue : 6000 }}]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Vital for fixed height container
                plugins: { legend: { display: false } },
                scales: {
                    y: { display: false },
                    x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 10 } } }
                }
            }
        });

        // Participation Chart (Fixed)
        var ctxPie = document.getElementById('participationChart').getContext('2d');
        var paid = {{ $paidTeamsCount }};
        var partial = {{ $partialTeamsCount ?: 0 }};
        var unpaid = {{ $activeTeamsCount }} - paid - partial;
        var total = paid + partial + unpaid;
        
        var data = total === 0 ? [1] : [paid, partial, unpaid];
        var bgColors = total === 0 ? ['#2d3748'] : ['#00a86b', '#f39c12', '#ef4444'];

        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: total === 0 ? ['No Data'] : ['Paid', 'Partial', 'Unpaid'],
                datasets: [{
                    data: data,
                    backgroundColor: bgColors,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Vital for fixed height container
                cutout: '80%',
                plugins: {
                    legend: { position: 'right', labels: { boxWidth: 10, color: '#94a3b8', font: { size: 10 } } }
                }
            }
        });
    })
</script>
@endpush