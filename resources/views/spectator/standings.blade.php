@extends('layouts.dashboard')

@section('title', 'Standings')
@section('page-title', 'Tournament Standings')

@push('styles')
<style>
/* Custom style for Standings Tabs */
.standings-tabs {
    border-bottom: 1px solid var(--color-border) !important;
    margin-bottom: 1.5rem;
}

.standings-tabs .nav-link {
    background: transparent !important;
    border: none !important;
    color: var(--color-text-tertiary) !important;
    font-weight: 600;
    font-size: 0.95rem;
    padding: 10px 20px !important;
    border-bottom: 2px solid transparent !important;
    transition: all 0.2s ease;
}

.standings-tabs .nav-link:hover {
    color: #fff !important;
}

.standings-tabs .nav-link.active {
    color: var(--color-rugby-green-light) !important;
    border-bottom: 2px solid var(--color-rugby-green) !important;
}

/* Premium Visual Bracket Styles */
.bracket-container {
    display: flex;
    gap: 2.5rem;
    align-items: stretch;
    justify-content: space-between;
    overflow-x: auto;
    padding: 1.5rem 0.5rem;
}
.bracket-column {
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    min-width: 240px;
    flex: 1;
    gap: 1.5rem;
    position: relative;
}
.bracket-match-node {
    background: var(--color-bg-primary);
    border: 1px solid var(--color-border);
    border-radius: 0.75rem;
    overflow: hidden;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.bracket-match-node:hover {
    border-color: var(--color-rugby-green-light);
    box-shadow: var(--shadow-glow-green);
    transform: translateY(-2px);
}
.bracket-match-header {
    background: rgba(255, 255, 255, 0.03);
    padding: 6px 12px;
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--color-text-muted);
    border-bottom: 1px solid var(--color-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.bracket-match-team {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 14px;
    font-size: 0.82rem;
    font-weight: 500;
    color: var(--color-text-secondary);
    border-bottom: 1px solid rgba(255, 255, 255, 0.03);
}
.bracket-match-team:last-child {
    border-bottom: none;
}
.bracket-match-team.winner {
    background: linear-gradient(90deg, rgba(0, 168, 107, 0.15), transparent);
    border-left: 3px solid var(--color-rugby-green);
    color: white;
    font-weight: 600;
}
.bracket-match-team.winner .score {
    color: var(--color-rugby-green-light);
    font-weight: 700;
}
.bracket-match-team .score {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--color-text-muted);
}
.bracket-column-title {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--color-text-secondary);
    margin-bottom: 0.75rem;
    text-align: center;
    letter-spacing: 0.075em;
    background: rgba(255, 255, 255, 0.03);
    padding: 6px 12px;
    border-radius: 6px;
    border: 1px solid var(--color-border);
}
.bracket-sub-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    flex: 1;
    justify-content: center;
    padding: 0.5rem 0;
}
.bracket-sub-section-title {
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--color-text-muted);
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
    text-align: center;
}
</style>
@endpush

@section('content')
    @if($tournaments && $tournaments->count() > 1)
        <!-- Tournament Selector -->
        <div class="card mb-lg" style="background: var(--color-bg-secondary); border: 1px solid var(--color-border); margin-bottom: 1.5rem;">
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

    @if(isset($tournament) && $tournament)
        {{-- Dynamic Tabs --}}
        @php $activeTab = request()->query('tab', 'table'); @endphp
        <ul class="nav nav-tabs standings-tabs" id="standingsTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab !== 'composition' && $activeTab !== 'knockouts' && $activeTab !== 'power-rankings' ? 'active' : '' }}" id="table-tab" data-toggle="tab" href="#table" role="tab" aria-controls="table" aria-selected="{{ $activeTab !== 'composition' && $activeTab !== 'knockouts' && $activeTab !== 'power-rankings' ? 'true' : 'false' }}">
                    <i class="fas fa-list-ol mr-1"></i> Standings Table
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'power-rankings' ? 'active' : '' }}" id="power-rankings-tab" data-toggle="tab" href="#power-rankings" role="tab" aria-controls="power-rankings" aria-selected="{{ $activeTab === 'power-rankings' ? 'true' : 'false' }}">
                    <i class="fas fa-bolt mr-1" style="color: var(--color-warning);"></i> Power Rankings (Elo)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'composition' ? 'active' : '' }}" id="composition-tab" data-toggle="tab" href="#composition" role="tab" aria-controls="composition" aria-selected="{{ $activeTab === 'composition' ? 'true' : 'false' }}">
                    <i class="fas fa-layer-group mr-1"></i> Pool Draw
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'knockouts' ? 'active' : '' }}" id="knockouts-tab" data-toggle="tab" href="#knockouts" role="tab" aria-controls="knockouts" aria-selected="{{ $activeTab === 'knockouts' ? 'true' : 'false' }}">
                    <i class="fas fa-trophy mr-1"></i> Knockouts
                </a>
            </li>
        </ul>

        <div class="tab-content" id="standingsTabContent">
            <!-- Standings Table Tab -->
            <div class="tab-pane fade {{ $activeTab !== 'composition' && $activeTab !== 'knockouts' && $activeTab !== 'power-rankings' ? 'show active' : '' }}" id="table" role="tabpanel" aria-labelledby="table-tab">
                @if($tournament->pools->count() > 0)
                    @foreach($tournament->pools as $pool)
                        @php
                            $standings = $pool->calculateStandings();
                        @endphp
                        <div class="card mb-4" style="background: var(--color-bg-secondary); border: 1px solid var(--color-border); border-radius: 12px; overflow: hidden; margin-bottom: 1.5rem;">
                            <div class="card-header" style="background: var(--color-bg-tertiary); border-bottom: 1px solid var(--color-border); padding: 15px 20px;">
                                <h4 style="font-size: 1.15rem; font-weight: 700; color: #fff; margin: 0; display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-list-ol" style="color: var(--color-rugby-green);"></i>
                                    {{ $pool->name }} Standings
                                </h4>
                            </div>
                            <div class="card-body" style="padding: 0;">
                                <div class="table-responsive">
                                    <table class="table" style="width: 100%; margin: 0; color: var(--color-text-secondary); border-collapse: collapse;">
                                        <thead>
                                            <tr style="background: rgba(0,0,0,0.15); border-bottom: 1px solid var(--color-border);">
                                                <th style="padding: 12px 20px; text-align: center; font-weight: 700; width: 60px; color: #fff; border-bottom: none;">Pos</th>
                                                <th style="padding: 12px 20px; text-align: left; font-weight: 700; color: #fff; border-bottom: none;">Team</th>
                                                <th style="padding: 12px 10px; text-align: center; font-weight: 700; width: 60px; color: #fff; border-bottom: none;" title="Played">P</th>
                                                <th style="padding: 12px 10px; text-align: center; font-weight: 700; width: 60px; color: #fff; border-bottom: none;" title="Won">W</th>
                                                <th style="padding: 12px 10px; text-align: center; font-weight: 700; width: 60px; color: #fff; border-bottom: none;" title="Drawn">D</th>
                                                <th style="padding: 12px 10px; text-align: center; font-weight: 700; width: 60px; color: #fff; border-bottom: none;" title="Lost">L</th>
                                                <th style="padding: 12px 10px; text-align: center; font-weight: 700; width: 80px; color: #fff; border-bottom: none;" title="Points For">PF</th>
                                                <th style="padding: 12px 10px; text-align: center; font-weight: 700; width: 80px; color: #fff; border-bottom: none;" title="Points Against">PA</th>
                                                <th style="padding: 12px 10px; text-align: center; font-weight: 700; width: 80px; color: #fff; border-bottom: none;" title="Points Difference">PD</th>
                                                <th style="padding: 12px 20px; text-align: center; font-weight: 700; width: 80px; color: var(--color-rugby-green-light); border-bottom: none;" title="Points (Win=3, Draw=2, Loss=1)">PTS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($standings as $index => $row)
                                                @php
                                                    $isMyTeam = auth()->check() && $row['team'] && $row['team']->manager_id === auth()->id();
                                                    $rank = $index + 1;
                                                    $hasPlayed = $row['played'] > 0;
                                                    $isQualifying = $rank <= 2 && $hasPlayed;
                                                @endphp
                                                <tr style="border-bottom: 1px solid var(--color-border); background: {{ $isMyTeam ? 'rgba(0, 168, 107, 0.08)' : 'transparent' }};">
                                                    <td style="padding: 15px 20px; text-align: center; font-weight: 700; color: {{ $isQualifying ? 'var(--color-rugby-green-light)' : 'var(--color-text-muted)' }}; border-top: none;">
                                                        {{ $rank }}
                                                    </td>
                                                    <td style="padding: 15px 20px; font-weight: 600; color: #fff; border-top: none;">
                                                        <div style="display: flex; align-items: center; gap: 8px;">
                                                            {{ $row['team']->name }}
                                                            @if($isMyTeam)
                                                                <span style="font-size: 0.55rem; font-weight: 800; letter-spacing: 0.5px; background: linear-gradient(135deg, var(--color-rugby-green) 0%, var(--color-rugby-green-dark) 100%); color: #fff; padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">
                                                                    My Team
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td style="padding: 15px 10px; text-align: center; font-weight: 500; border-top: none;">{{ $row['played'] }}</td>
                                                    <td style="padding: 15px 10px; text-align: center; color: var(--color-rugby-green-light); border-top: none;">{{ $row['won'] }}</td>
                                                    <td style="padding: 15px 10px; text-align: center; color: var(--color-text-muted); border-top: none;">{{ $row['drawn'] }}</td>
                                                    <td style="padding: 15px 10px; text-align: center; color: #ef4444; border-top: none;">{{ $row['lost'] }}</td>
                                                    <td style="padding: 15px 10px; text-align: center; border-top: none;">{{ $row['points_for'] }}</td>
                                                    <td style="padding: 15px 10px; text-align: center; border-top: none;">{{ $row['points_against'] }}</td>
                                                    <td style="padding: 15px 10px; text-align: center; font-weight: 600; color: {{ $row['points_difference'] > 0 ? 'var(--color-rugby-green-light)' : ($row['points_difference'] < 0 ? '#ef4444' : 'var(--color-text-muted)') }}; border-top: none;">
                                                        {{ $row['points_difference'] > 0 ? '+' : '' }}{{ $row['points_difference'] }}
                                                    </td>
                                                    <td style="padding: 15px 20px; text-align: center; font-weight: 800; color: var(--color-rugby-green-light); font-size: 1.05rem; border-top: none;">
                                                        {{ $row['points'] }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" style="padding: 30px; text-align: center; color: var(--color-text-muted); border-top: none;">
                                                        No teams assigned to this pool.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="card" style="background: var(--color-bg-secondary); border: 1px solid var(--color-border); border-radius: 12px;">
                        <div class="card-body" style="padding: var(--spacing-3xl); text-align: center;">
                            <i class="fas fa-list-ol fa-3x mb-3 text-muted" style="opacity: 0.5; margin-bottom: var(--spacing-lg); display: block; margin-left: auto; margin-right: auto;"></i>
                            <h3 style="font-weight: 700; color: #fff; margin-bottom: var(--spacing-sm);">No Pools Configured</h3>
                            <p class="text-muted">The tournament organizer has not set up pools for this tournament yet.</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Power Rankings (Elo) Tab -->
            <div class="tab-pane fade {{ $activeTab === 'power-rankings' ? 'show active' : '' }}" id="power-rankings" role="tabpanel" aria-labelledby="power-rankings-tab">
                @php
                    $powerRankings = $tournament->registrations()
                        ->where('status', 'confirmed')
                        ->with('team')
                        ->get()
                        ->pluck('team')
                        ->filter()
                        ->unique('id')
                        ->sortByDesc('rating');
                    $rankNum = 1;

                    // Get completed matches in this tournament with Elo data recorded
                    $completedWithElo = $tournament->fixtures()
                        ->where('status', 'completed')
                        ->whereNotNull('home_elo_before')
                        ->orderBy('updated_at', 'desc')
                        ->take(5)
                        ->get();
                @endphp

                <div class="row">
                    <!-- Left Column: Power Rankings Leaderboard -->
                    <div class="col-lg-6 mb-4">
                        <div class="card" style="background: var(--color-bg-secondary); border: 1px solid var(--color-border); border-radius: 12px; overflow: hidden; margin-bottom: 1.5rem;">
                            <div class="card-header" style="background: var(--color-bg-tertiary); border-bottom: 1px solid var(--color-border); padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                                <h4 style="font-size: 1.15rem; font-weight: 700; color: #fff; margin: 0; display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-bolt" style="color: var(--color-warning);"></i>
                                    Dynamic Power Rankings (Elo)
                                </h4>
                                <span style="font-size: 0.75rem; color: var(--color-text-muted); font-weight: 500;">Sorted by current team rating</span>
                            </div>
                            <div class="card-body" style="padding: 0;">
                                <div class="table-responsive">
                                    <table class="table" style="width: 100%; margin: 0; color: var(--color-text-secondary); border-collapse: collapse;">
                                        <thead>
                                            <tr style="background: rgba(0,0,0,0.15); border-bottom: 1px solid var(--color-border);">
                                                <th style="padding: 12px 20px; text-align: center; font-weight: 700; width: 80px; color: #fff; border-bottom: none;">Rank</th>
                                                <th style="padding: 12px 20px; text-align: left; font-weight: 700; color: #fff; border-bottom: none;">Team</th>
                                                <th style="padding: 12px 20px; text-align: center; font-weight: 700; width: 120px; color: var(--color-warning); border-bottom: none;">Elo Rating</th>
                                                <th style="padding: 12px 20px; text-align: left; font-weight: 700; color: #fff; border-bottom: none;">Form Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($powerRankings as $team)
                                                @php
                                                    $isMyTeam = auth()->check() && $team->manager_id === auth()->id();
                                                @endphp
                                                <tr style="border-bottom: 1px solid var(--color-border); background: {{ $isMyTeam ? 'rgba(0, 168, 107, 0.08)' : 'transparent' }};">
                                                    <td style="padding: 15px 20px; text-align: center; font-weight: 800; border-top: none;">
                                                        @if($rankNum == 1)
                                                            <span style="background: linear-gradient(135deg, #ffd700, #ffa500); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-size: 1.15rem;"><i class="fas fa-crown"></i> 1</span>
                                                        @elseif($rankNum == 2)
                                                            <span style="color: #c0c0c0; font-size: 1.1rem;"><i class="fas fa-medal"></i> 2</span>
                                                        @elseif($rankNum == 3)
                                                            <span style="color: #cd7f32; font-size: 1.05rem;"><i class="fas fa-medal"></i> 3</span>
                                                        @else
                                                            <span style="color: var(--color-text-muted);">{{ $rankNum }}</span>
                                                        @endif
                                                    </td>
                                                    <td style="padding: 15px 20px; font-weight: 600; color: #fff; border-top: none;">
                                                        <div style="display: flex; align-items: center; gap: 10px;">
                                                            <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ $isMyTeam ? 'rgba(0,168,107,0.15)' : 'var(--color-bg-tertiary)' }}; border: 1px solid {{ $isMyTeam ? 'var(--color-rugby-green)' : 'var(--color-border-light)' }}; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700; color: {{ $isMyTeam ? 'var(--color-rugby-green-light)' : 'var(--color-text-secondary)' }}; flex-shrink: 0;">
                                                                {{ strtoupper(substr($team->name, 0, 2)) }}
                                                            </div>
                                                            <span>{{ $team->name }}</span>
                                                            @if($isMyTeam)
                                                                <span style="font-size: 0.55rem; font-weight: 800; letter-spacing: 0.5px; background: linear-gradient(135deg, var(--color-rugby-green) 0%, var(--color-rugby-green-dark) 100%); color: #fff; padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">
                                                                    My Team
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td style="padding: 15px 20px; text-align: center; font-weight: 800; color: var(--color-warning); font-size: 1.15rem; border-top: none; font-family: 'Outfit', sans-serif;">
                                                        {{ $team->rating ?? 1500 }}
                                                    </td>
                                                    <td style="padding: 15px 20px; border-top: none;">
                                                        @php
                                                            $rating = $team->rating ?? 1500;
                                                        @endphp
                                                        @if($rating > 1600)
                                                            <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); font-weight: 700;">Contender 🔥</span>
                                                        @elseif($rating >= 1520)
                                                            <span class="badge" style="background: rgba(0, 168, 107, 0.1); color: var(--color-rugby-green-light); border: 1px solid rgba(0, 168, 107, 0.2); font-weight: 700;">Rising Star 📈</span>
                                                        @elseif($rating <= 1400)
                                                            <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); font-weight: 700;">Underdog 📉</span>
                                                        @else
                                                            <span class="badge" style="background: rgba(148, 163, 184, 0.1); color: var(--color-text-secondary); border: 1px solid rgba(148, 163, 184, 0.2); font-weight: 700;">Stable Form ⚖️</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @php $rankNum++; @endphp
                                            @empty
                                                <tr>
                                                    <td colspan="4" style="padding: 30px; text-align: center; color: var(--color-text-muted); border-top: none;">
                                                        No confirmed registrations for this tournament yet.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Algorithmic Complexity Panel -->
                    <div class="col-lg-6 mb-4">
                        <div class="card" style="background: var(--color-bg-secondary); border: 1px solid var(--color-border); border-radius: 12px; overflow: hidden;">
                            <div class="card-header" style="background: var(--color-bg-tertiary); border-bottom: 1px solid var(--color-border); padding: 15px 20px;">
                                <h4 style="font-size: 1.1rem; font-weight: 700; color: #fff; margin: 0; display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-microchip" style="color: var(--color-rugby-green-light);"></i>
                                    Algorithmic Complexity & Calculations
                                </h4>
                            </div>
                            <div class="card-body" style="padding: 20px;">
                                <!-- Inner Tab Navigation -->
                                <ul class="nav nav-pills mb-4" id="complexityTab" role="tablist" style="border-bottom: 1px solid var(--color-border); padding-bottom: 12px; gap: 5px;">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="math-tab" data-toggle="pill" href="#complexity-math" role="tab" aria-controls="complexity-math" aria-selected="true" style="font-size: 0.85rem; font-weight: 600; padding: 6px 12px; border-radius: 4px; color: var(--color-text-secondary);">
                                            <i class="fas fa-square-root-alt mr-1"></i> Formulas
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="sandbox-tab" data-toggle="pill" href="#complexity-sandbox" role="tab" aria-controls="complexity-sandbox" aria-selected="false" style="font-size: 0.85rem; font-weight: 600; padding: 6px 12px; border-radius: 4px; color: var(--color-text-secondary);">
                                            <i class="fas fa-calculator mr-1"></i> Sandbox Simulator
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="audit-tab" data-toggle="pill" href="#complexity-audit" role="tab" aria-controls="complexity-audit" aria-selected="false" style="font-size: 0.85rem; font-weight: 600; padding: 6px 12px; border-radius: 4px; color: var(--color-text-secondary);">
                                            <i class="fas fa-history mr-1"></i> Live Audit Log
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="complexityTabContent">
                                    <!-- Tab 1: Formulas -->
                                    <div class="tab-pane fade show active" id="complexity-math" role="tabpanel" aria-labelledby="math-tab">
                                        <div style="display: flex; flex-direction: column; gap: var(--spacing-md); font-size: 0.88rem; color: var(--color-text-secondary); line-height: 1.6;">
                                            <p style="margin-bottom: 8px;">Algoritma <strong>Adaptive Rugby Strength & Outcome Predictor</strong> berasaskan formula pemeringkatan Elo yang diubah suai khas untuk mengambil kira margin jaringan perlawanan ragbi:</p>
                                            
                                            <!-- Formula 1: Win expectation -->
                                            <div style="background: var(--color-bg-primary); border: 1px solid var(--color-border-light); border-radius: 8px; padding: 12px; display: flex; flex-direction: column; gap: 4px;">
                                                <span style="font-size: 0.75rem; font-weight: 700; color: var(--color-electric-blue); text-transform: uppercase;">1. Expected Outcome (Jangkaan Kemenangan)</span>
                                                <code style="font-family: monospace; font-size: 0.95rem; color: #fff; margin: 4px 0; display: block;">E_A = 1 / (1 + 10^((R_B - R_A) / 400))</code>
                                                <span style="font-size: 0.75rem; color: var(--color-text-muted);">Di mana R_A dan R_B adalah rating sedia ada sebelum sepak mula. Nilai E_A menggambarkan kebarangkalian menang (0.0 hingga 1.0).</span>
                                            </div>

                                            <!-- Formula 2: Margin modifier -->
                                            <div style="background: var(--color-bg-primary); border: 1px solid var(--color-border-light); border-radius: 8px; padding: 12px; display: flex; flex-direction: column; gap: 4px;">
                                                <span style="font-size: 0.75rem; font-weight: 700; color: var(--color-warning); text-transform: uppercase;">2. Victory Margin Multiplier (Pengganda Margin)</span>
                                                <code style="font-family: monospace; font-size: 0.95rem; color: #fff; margin: 4px 0; display: block;">M = sqrt(Score_Difference + 1)</code>
                                                <span style="font-size: 0.75rem; color: var(--color-text-muted);">Untuk menghalang rating dimanipulasi oleh keputusan tipis, beza jaringan dianalisis bagi memberi ganjaran kepada margin kemenangan dominan.</span>
                                            </div>

                                            <!-- Formula 3: Rating update -->
                                            <div style="background: var(--color-bg-primary); border: 1px solid var(--color-border-light); border-radius: 8px; padding: 12px; display: flex; flex-direction: column; gap: 4px;">
                                                <span style="font-size: 0.75rem; font-weight: 700; color: var(--color-rugby-green-light); text-transform: uppercase;">3. Rating Adjustment (Penyelarasan Rating)</span>
                                                <code style="font-family: monospace; font-size: 0.95rem; color: #fff; margin: 4px 0; display: block;">R'_A = R_A + K * M * (W_A - E_A)</code>
                                                <span style="font-size: 0.75rem; color: var(--color-text-muted);">Sensitiviti pemalar (K-factor) ditetapkan pada 32. Hasil sebenar W_A bernilai 1 (menang), 0.5 (seri), atau 0 (kalah).</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tab 2: Sandbox Calculator -->
                                    <div class="tab-pane fade" id="complexity-sandbox" role="tabpanel" aria-labelledby="sandbox-tab">
                                        <div style="display: flex; flex-direction: column; gap: 15px;">
                                            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin: 0;">Uji algoritma secara interaktif dengan simulasi rating dan skor perlawanan:</p>
                                            
                                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                                <div>
                                                    <label style="font-size: 0.75rem; color: var(--color-text-secondary); font-weight: 600; display: block; margin-bottom: 4px;">Rating Pasukan A</label>
                                                    <input type="number" id="sim-rating-a" class="form-control" value="1500" style="background: var(--color-bg-primary); border-color: var(--color-border); color: #fff;">
                                                </div>
                                                <div>
                                                    <label style="font-size: 0.75rem; color: var(--color-text-secondary); font-weight: 600; display: block; margin-bottom: 4px;">Rating Pasukan B</label>
                                                    <input type="number" id="sim-rating-b" class="form-control" value="1500" style="background: var(--color-bg-primary); border-color: var(--color-border); color: #fff;">
                                                </div>
                                            </div>

                                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                                <div>
                                                    <label style="font-size: 0.75rem; color: var(--color-text-secondary); font-weight: 600; display: block; margin-bottom: 4px;">Skor Pasukan A</label>
                                                    <input type="number" id="sim-score-a" class="form-control" value="24" style="background: var(--color-bg-primary); border-color: var(--color-border); color: #fff;">
                                                </div>
                                                <div>
                                                    <label style="font-size: 0.75rem; color: var(--color-text-secondary); font-weight: 600; display: block; margin-bottom: 4px;">Skor Pasukan B</label>
                                                    <input type="number" id="sim-score-b" class="form-control" value="10" style="background: var(--color-bg-primary); border-color: var(--color-border); color: #fff;">
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-primary btn-block" onclick="runEloSimulation()" style="font-weight: 600;">
                                                <i class="fas fa-play mr-1"></i> Jalankan Simulasi Pengiraan
                                            </button>

                                            <!-- Results section -->
                                            <div id="sim-results" style="display: none; background: var(--color-bg-primary); border: 1px solid var(--color-border-light); border-radius: 8px; padding: 15px; font-family: monospace; font-size: 0.85rem; color: var(--color-text-secondary); flex-direction: column; gap: 8px;">
                                                <div style="border-bottom: 1px dashed var(--color-border); padding-bottom: 6px; margin-bottom: 6px; font-weight: 700; color: #fff; display: flex; align-items: center; gap: 6px;">
                                                    <i class="fas fa-list-ol" style="color: var(--color-warning);"></i>
                                                    LANGKAH PENGIRAAN ALGORITMA:
                                                </div>
                                                <div>1. Jangkaan Menang A (E_A) : <span id="sim-res-ea" style="color: var(--color-electric-blue);"></span></div>
                                                <div>2. Jangkaan Menang B (E_B) : <span id="sim-res-eb" style="color: var(--color-electric-blue);"></span></div>
                                                <div>3. Perbezaan Skor        : <span id="sim-res-diff" style="color: var(--color-warning);"></span></div>
                                                <div>4. Pengganda Margin (M)  : <span id="sim-res-m" style="color: var(--color-warning);"></span></div>
                                                <div style="border-top: 1px dashed var(--color-border); padding-top: 6px; margin-top: 6px; font-weight: 700; color: #fff;">KEMAS KINI RATING:</div>
                                                <div>* Perubahan Mata (ΔR)    : <span id="sim-res-delta" style="color: var(--color-rugby-green-light); font-weight: 700;"></span></div>
                                                <div>* Rating Baharu A        : <span id="sim-res-new-a" style="color: #fff; font-weight: 700;"></span></div>
                                                <div>* Rating Baharu B        : <span id="sim-res-new-b" style="color: #fff; font-weight: 700;"></span></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tab 3: Live Audit Log -->
                                    <div class="tab-pane fade" id="complexity-audit" role="tabpanel" aria-labelledby="audit-tab">
                                        <div class="table-responsive">
                                            <table class="table table-hover" style="font-size: 0.8rem; color: var(--color-text-secondary); width: 100%; border-collapse: collapse; margin: 0;">
                                                <thead>
                                                    <tr style="border-bottom: 1px solid var(--color-border); color: #fff; background: rgba(0,0,0,0.15);">
                                                        <th style="padding: 8px 10px;">Perlawanan</th>
                                                        <th style="padding: 8px 10px; text-align: center;">Skor</th>
                                                        <th style="padding: 8px 10px; text-align: center;">Rating Asal</th>
                                                        <th style="padding: 8px 10px; text-align: center;">Rating Baru</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($completedWithElo as $fix)
                                                        @php
                                                            $changeA = $fix->home_elo_after - $fix->home_elo_before;
                                                            $signA = $changeA >= 0 ? '+' : '';
                                                            $changeB = $fix->away_elo_after - $fix->away_elo_before;
                                                            $signB = $changeB >= 0 ? '+' : '';
                                                        @endphp
                                                        <tr style="border-bottom: 1px solid var(--color-border);">
                                                            <td style="padding: 10px 10px; line-height: 1.3;">
                                                                <span style="font-weight: 600; color: #fff;">{{ $fix->homeTeam->name ?? 'TBD' }}</span>
                                                                <br>
                                                                <span style="color: var(--color-text-muted); font-size: 0.75rem;">vs</span>
                                                                <br>
                                                                <span style="font-weight: 600; color: #fff;">{{ $fix->awayTeam->name ?? 'TBD' }}</span>
                                                            </td>
                                                            <td style="padding: 10px 10px; text-align: center; vertical-align: middle; font-weight: 700; color: #fff;">
                                                                {{ $fix->home_score }} - {{ $fix->away_score }}
                                                            </td>
                                                            <td style="padding: 10px 10px; text-align: center; vertical-align: middle; color: var(--color-text-muted); line-height: 1.4;">
                                                                {{ $fix->home_elo_before }} (A)
                                                                <br>
                                                                {{ $fix->away_elo_before }} (B)
                                                            </td>
                                                            <td style="padding: 10px 10px; text-align: center; vertical-align: middle; line-height: 1.4;">
                                                                <span style="color: {{ $changeA >= 0 ? 'var(--color-rugby-green-light)' : '#ef4444' }}; font-weight: 700;">
                                                                    {{ $fix->home_elo_after }} ({{ $signA }}{{ $changeA }})
                                                                </span>
                                                                <br>
                                                                <span style="color: {{ $changeB >= 0 ? 'var(--color-rugby-green-light)' : '#ef4444' }}; font-weight: 700;">
                                                                    {{ $fix->away_elo_after }} ({{ $signB }}{{ $changeB }})
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" style="padding: 20px; text-align: center; color: var(--color-text-muted);">
                                                                Tiada log pengiraan dalam kejohanan ini lagi. Selesaikan perlawanan dahulu untuk jana log.
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
                    </div>
                </div>
            </div>

            <!-- Pool Composition Tab -->
            <div class="tab-pane fade {{ $activeTab === 'composition' ? 'show active' : '' }}" id="composition" role="tabpanel" aria-labelledby="composition-tab">
                @if($tournament->pools->count() > 0)
                    <div class="row">
                        @foreach($tournament->pools as $pool)
                            <div class="col-md-6 mb-4">
                                <div class="card h-100" style="background: var(--color-bg-secondary); border: 1px solid var(--color-border); border-radius: 12px; overflow: hidden;">
                                    <div class="card-header" style="background: var(--color-bg-tertiary); border-bottom: 1px solid var(--color-border); padding: 12px 20px;">
                                        <h4 style="font-size: 1.05rem; font-weight: 700; color: #fff; margin: 0; display: flex; align-items: center; gap: 8px;">
                                            <i class="fas fa-folder" style="color: var(--color-rugby-green);"></i>
                                            {{ $pool->name }}
                                        </h4>
                                    </div>
                                    <div class="card-body" style="padding: 15px 20px;">
                                        @php
                                            $confirmedRegs = $pool->registrations->where('status', 'confirmed');
                                        @endphp
                                        @if($confirmedRegs->count() > 0)
                                            <ul style="list-style: none; padding: 0; margin: 0;">
                                                @foreach($confirmedRegs as $index => $reg)
                                                    @php
                                                        $isMyTeam = auth()->check() && $reg->team && $reg->team->manager_id === auth()->id();
                                                    @endphp
                                                    <li style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: {{ $index < $confirmedRegs->count() - 1 ? '1px solid var(--color-border)' : 'none' }};">
                                                        <div style="display: flex; align-items: center; gap: 10px; min-width: 0;">
                                                            <div style="width: 28px; height: 28px; border-radius: 50%; background: {{ $isMyTeam ? 'rgba(0,168,107,0.15)' : 'var(--color-bg-tertiary)' }}; border: 1px solid {{ $isMyTeam ? 'var(--color-rugby-green)' : 'var(--color-border-light)' }}; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; color: {{ $isMyTeam ? 'var(--color-rugby-green-light)' : 'var(--color-text-secondary)' }}; flex-shrink: 0;">
                                                                {{ $reg->team ? strtoupper(substr($reg->team->name, 0, 2)) : 'TBD' }}
                                                            </div>
                                                            <span style="font-weight: {{ $isMyTeam ? '700' : '500' }}; color: {{ $isMyTeam ? '#fff' : 'var(--color-text-secondary)' }}; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                                {{ $reg->team->name ?? 'TBD' }}
                                                            </span>
                                                        </div>
                                                        @if($isMyTeam)
                                                            <span style="font-size: 0.55rem; font-weight: 800; letter-spacing: 0.5px; background: linear-gradient(135deg, var(--color-rugby-green) 0%, var(--color-rugby-green-dark) 100%); color: #fff; padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">
                                                                My Team
                                                            </span>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div style="text-align: center; padding: 20px 0; color: var(--color-text-muted);">
                                                <i class="fas fa-users-slash" style="font-size: 1.5rem; margin-bottom: 8px; opacity: 0.5;"></i>
                                                <div style="font-size: 0.85rem;">No teams assigned to this pool yet.</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 40px 20px; background: var(--color-bg-secondary); border-radius: 12px; border: 1px dashed var(--color-border-light);">
                        <i class="fas fa-folder-open fa-3x" style="color: var(--color-text-muted); margin-bottom: 15px; opacity: 0.5;"></i>
                        <h4 style="font-weight: 600; color: #fff; margin-bottom: 6px;">No Pools Configured</h4>
                        <p style="color: var(--color-text-secondary); font-size: 0.9rem;">The tournament organizer has not set up pools for this tournament yet.</p>
                    </div>
                @endif
            </div>
            <!-- Knockouts Tab -->
            <div class="tab-pane fade {{ $activeTab === 'knockouts' ? 'show active' : '' }}" id="knockouts" role="tabpanel" aria-labelledby="knockouts-tab">
                @php
                    $knockoutFixtures = $tournament->fixtures()->whereNull('pool_id')->where('status', '!=', 'draft')->orderBy('start_time', 'asc')->get();
                @endphp

                @if($knockoutFixtures->isNotEmpty())
                    <div class="card" style="background: var(--color-bg-secondary); border: 1px solid var(--color-border); border-radius: 12px; overflow: hidden;">
                        <div class="card-header" style="background: var(--color-bg-tertiary); border-bottom: 1px solid var(--color-border); padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                            <h4 style="font-size: 1.1rem; font-weight: 700; color: #fff; margin: 0; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-sitemap text-warning"></i>
                                Knockout Stage Bracket
                            </h4>
                            <ul class="nav nav-pills" id="bracket-pills" role="tablist" style="border: 1px solid var(--color-border); gap: 0.25rem; background: rgba(0,0,0,0.2); padding: 4px; border-radius: 8px;">
                                <li class="nav-item">
                                    <a class="nav-link active py-1 px-3" id="cup-plate-tab" data-toggle="pill" href="#bracket-cup-plate" role="tab" style="font-size: 0.75rem; border-radius: 6px; color: #fff; font-weight: 600;">🏆 Cup & Plate</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link py-1 px-3" id="bowl-shield-tab" data-toggle="pill" href="#bracket-bowl-shield" role="tab" style="font-size: 0.75rem; border-radius: 6px; color: #fff; font-weight: 600;">🛡️ Bowl & Shield</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body" style="overflow-x: auto; padding: 1.5rem;">
                             @php
                                $findMatch = function($stageName) use ($knockoutFixtures) {
                                    return $knockoutFixtures->first(function($f) use ($stageName) {
                                        return strtolower($f->stage) === strtolower($stageName);
                                    });
                                };

                                $qp1 = $findMatch('Cup/Plate - Quarter-Final 1');
                                $qp2 = $findMatch('Cup/Plate - Quarter-Final 2');
                                $qp3 = $findMatch('Cup/Plate - Quarter-Final 3');
                                $qp4 = $findMatch('Cup/Plate - Quarter-Final 4');

                                $cupSf1 = $findMatch('Cup - Semi-Final 1');
                                $cupSf2 = $findMatch('Cup - Semi-Final 2');
                                $plateSf1 = $findMatch('Plate - Semi-Final 1');
                                $plateSf2 = $findMatch('Plate - Semi-Final 2');

                                $cupFinal = $findMatch('Cup - Final');
                                $plateFinal = $findMatch('Plate - Final');
                             @endphp

                             <div class="tab-content" id="bracket-tab-contents" style="margin-top: 0 !important;">
                                 <!-- Cup & Plate Bracket Pane -->
                                 <div class="tab-pane active" id="bracket-cup-plate" role="tabpanel">
                                     <div class="bracket-container">
                                         <!-- Column 1: Quarter-Finals -->
                                         <div class="bracket-column">
                                             <div class="bracket-column-title">Quarter-Finals</div>
                                             @foreach([['name' => 'Cup/Plate QF 1', 'm' => $qp1], ['name' => 'Cup/Plate QF 2', 'm' => $qp2], ['name' => 'Cup/Plate QF 3', 'm' => $qp3], ['name' => 'Cup/Plate QF 4', 'm' => $qp4]] as $qf)
                                                 @include('admin.tournaments.partials.bracket-match-node', [
                                                     'title' => $qf['name'], 
                                                     'match' => $qf['m'],
                                                     'info' => 'WINNER ➔ CUP SF | LOSER ➔ PLATE SF'
                                                 ])
                                             @endforeach
                                         </div>

                                         <!-- Column 2: Semi-Finals -->
                                         <div class="bracket-column">
                                             <div class="bracket-sub-section">
                                                 <div class="bracket-sub-section-title">🏆 Cup Semi-Finals</div>
                                                 @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Cup SF 1', 'match' => $cupSf1, 'info' => 'WINNER ➔ CUP FINAL'])
                                                 @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Cup SF 2', 'match' => $cupSf2, 'info' => 'WINNER ➔ CUP FINAL'])
                                             </div>
                                             <div class="bracket-sub-section">
                                                 <div class="bracket-sub-section-title">🥈 Plate Semi-Finals</div>
                                                 @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Plate SF 1', 'match' => $plateSf1, 'info' => 'WINNER ➔ PLATE FINAL'])
                                                 @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Plate SF 2', 'match' => $plateSf2, 'info' => 'WINNER ➔ PLATE FINAL'])
                                             </div>
                                         </div>

                                         <!-- Column 3: Finals -->
                                         <div class="bracket-column">
                                             <div class="bracket-sub-section">
                                                 <div class="bracket-sub-section-title">🏆 Cup Final</div>
                                                 @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Cup Final', 'match' => $cupFinal, 'info' => 'CHAMPIONSHIP MATCH'])
                                             </div>
                                             <div class="bracket-sub-section">
                                                 <div class="bracket-sub-section-title">🥈 Plate Final</div>
                                                 @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Plate Final', 'match' => $plateFinal, 'info' => 'CHAMPIONSHIP MATCH'])
                                             </div>
                                         </div>
                                     </div>
                                 </div>

                                 <!-- Bowl & Shield Bracket Pane -->
                                 <div class="tab-pane" id="bracket-bowl-shield" role="tabpanel">
                                     @php
                                         $bsSf1 = $findMatch('Bowl/Shield - Semi-Final 1');
                                         $bsSf2 = $findMatch('Bowl/Shield - Semi-Final 2');

                                         $bowlFinal = $findMatch('Bowl - Final');
                                         $shieldFinal = $findMatch('Shield - Final');
                                     @endphp

                                     <div class="bracket-container" style="justify-content: flex-start; gap: 4rem;">
                                         <!-- Column 1: Semi-Finals -->
                                         <div class="bracket-column">
                                             <div class="bracket-column-title">Semi-Finals</div>
                                             @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Bowl/Shield SF 1', 'match' => $bsSf1, 'info' => 'WINNER ➔ BOWL FINAL | LOSER ➔ SHIELD FINAL'])
                                             @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Bowl/Shield SF 2', 'match' => $bsSf2, 'info' => 'WINNER ➔ BOWL FINAL | LOSER ➔ SHIELD FINAL'])
                                         </div>

                                         <!-- Column 2: Finals -->
                                         <div class="bracket-column">
                                             <div class="bracket-sub-section">
                                                 <div class="bracket-sub-section-title">🥉 Bowl Final</div>
                                                 @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Bowl Final', 'match' => $bowlFinal, 'info' => 'CHAMPIONSHIP MATCH'])
                                             </div>
                                             <div class="bracket-sub-section">
                                                 <div class="bracket-sub-section-title">🛡️ Shield Final</div>
                                                 @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Shield Final', 'match' => $shieldFinal, 'info' => 'CHAMPIONSHIP MATCH'])
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                        </div>
                    </div>
                @else
                    <div style="text-align: center; padding: 40px 20px; background: var(--color-bg-secondary); border-radius: 12px; border: 1px dashed var(--color-border-light);">
                        <i class="fas fa-sitemap fa-3x" style="color: var(--color-text-muted); margin-bottom: 15px; opacity: 0.5;"></i>
                        <h4 style="font-weight: 600; color: #fff; margin-bottom: 6px;">No Knockout Matches</h4>
                        <p style="color: var(--color-text-secondary); font-size: 0.9rem;">Knockout stages have not been scheduled yet. They will appear here once the pool stages conclude.</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body" style="text-align: center; padding: var(--spacing-2xl);">
                <p class="text-muted">No active tournament found.</p>
            </div>
        </div>
    @endif
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
                console.log('Match update detected on Standings page.');
                if (data.fixture && data.fixture.status === 'completed') {
                    console.log('Match completed. Reloading standings to show updated points...');
                    location.reload();
                }
            });
        }
    });

    function runEloSimulation() {
        const rA = parseInt(document.getElementById('sim-rating-a').value) || 1500;
        const rB = parseInt(document.getElementById('sim-rating-b').value) || 1500;
        const sA = parseInt(document.getElementById('sim-score-a').value) || 0;
        const sB = parseInt(document.getElementById('sim-score-b').value) || 0;

        // 1. Expected outcome
        const eA = 1 / (1 + Math.pow(10, (rB - rA) / 400));
        const eB = 1 - eA;

        // 2. Winner status
        let wA = 0.5;
        if (sA > sB) wA = 1;
        else if (sA < sB) wA = 0;

        // 3. Margin multiplier
        const scoreDiff = Math.abs(sA - sB);
        const m = Math.sqrt(scoreDiff + 1);

        // 4. Change calculation
        const k = 32;
        const delta = k * m * (wA - eA);

        const newA = Math.round(rA + delta);
        const newB = Math.round(rB - delta);

        document.getElementById('sim-res-ea').textContent = (eA * 100).toFixed(1) + '%';
        document.getElementById('sim-res-eb').textContent = (eB * 100).toFixed(1) + '%';
        document.getElementById('sim-res-diff').textContent = scoreDiff + ' mata';
        document.getElementById('sim-res-m').textContent = m.toFixed(3);
        
        const sign = delta >= 0 ? '+' : '';
        document.getElementById('sim-res-delta').textContent = sign + Math.round(delta) + ' mata';
        document.getElementById('sim-res-new-a').textContent = newA;
        document.getElementById('sim-res-new-b').textContent = newB;

        document.getElementById('sim-results').style.display = 'flex';
    }
</script>
@endpush