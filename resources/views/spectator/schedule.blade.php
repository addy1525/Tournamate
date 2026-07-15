@extends('layouts.dashboard')

@section('title', 'Match Schedule')
@section('page-title', 'Match Schedule')

@section('content')
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

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Match Fixtures</h3>
            @if($tournament)
                <p class="card-subtitle">{{ $tournament->name }}</p>
            @endif
        </div>
        <div class="card-body">
            @if(isset($tournament) && $tournament)
                @if($tournament->fixtures()->where('status', '!=', 'draft')->count() == 0)
                <!-- Empty State for Schedule -->
                <div style="text-align: center; padding: var(--spacing-3xl);">
                    <div
                        style="width: 80px; height: 80px; background: var(--color-bg-tertiary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-lg);">
                        <i class="fas fa-calendar-alt" style="font-size: 2rem; color: var(--color-text-muted);"></i>
                    </div>
                    <h3 style="font-size: var(--font-size-xl); font-weight: 700; margin-bottom: var(--spacing-sm);">
                        Schedule Not Released
                    </h3>
                    <p style="color: var(--color-text-secondary); max-width: 450px; margin: 0 auto var(--spacing-xl); line-height: 1.6;">
                        The match schedule for <strong>{{ $tournament->name }}</strong> has not been published yet.
                    </p>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover" style="color: var(--color-text-primary);">
                        <thead>
                            <tr>
                                <th style="border-bottom-color: var(--color-border);">Time</th>
                                <th style="border-bottom-color: var(--color-border);">Stage / Pool</th>
                                <th style="border-bottom-color: var(--color-border);">Match</th>
                                <th style="border-bottom-color: var(--color-border);">Score</th>
                                <th style="border-bottom-color: var(--color-border);">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tournament->fixtures()->where('status', '!=', 'draft')->orderBy('start_time', 'asc')->get() as $fixture)
                                <tr data-fixture-id="{{ $fixture->id }}">
                                    <td style="border-top-color: var(--color-border);">{{ $fixture->start_time ? $fixture->start_time->format('h:i A') : 'TBD' }}</td>
                                    <td style="border-top-color: var(--color-border);">
                                        <span class="badge badge-info">{{ $fixture->pool ? $fixture->pool->name : $fixture->stage }}</span>
                                    </td>
                                    <td style="border-top-color: var(--color-border);">
                                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                            <span style="font-weight: 700;">{{ $fixture->homeTeam->name ?? 'TBD' }}</span>
                                            @if($fixture->status == 'scheduled' && $fixture->homeTeam && $fixture->awayTeam)
                                                @php
                                                    $rA = $fixture->homeTeam->rating ?? 1500;
                                                    $rB = $fixture->awayTeam->rating ?? 1500;
                                                    $probA = round((1 / (1 + pow(10, ($rB - $rA) / 400))) * 100);
                                                    $probB = 100 - $probA;
                                                @endphp
                                                <span class="badge" style="background: rgba(0, 168, 107, 0.12); color: var(--color-rugby-green-light); font-size: 0.72rem; padding: 2px 6px; font-weight: 700; border: 1px solid rgba(0, 168, 107, 0.25);" title="Elo Win Probability: {{ $probA }}%">{{ $probA }}%</span>
                                            @endif
                                            <span class="text-muted mx-1" style="font-size: 0.85rem; font-weight: 500;">vs</span>
                                            <span style="font-weight: 700;">{{ $fixture->awayTeam->name ?? 'TBD' }}</span>
                                            @if($fixture->status == 'scheduled' && $fixture->homeTeam && $fixture->awayTeam)
                                                <span class="badge" style="background: rgba(0, 212, 255, 0.12); color: var(--color-electric-blue); font-size: 0.72rem; padding: 2px 6px; font-weight: 700; border: 1px solid rgba(0, 212, 255, 0.25);" title="Elo Win Probability: {{ $probB }}%">{{ $probB }}%</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="border-top-color: var(--color-border); font-weight: bold; font-size: 1.1em;">
                                        <span class="score-display" style="{{ $fixture->status == 'scheduled' ? 'display: none;' : '' }}">
                                            <span class="home-score">{{ $fixture->home_score ?? 0 }}</span> - <span class="away-score">{{ $fixture->away_score ?? 0 }}</span>
                                        </span>
                                        <span class="score-placeholder" style="{{ $fixture->status == 'scheduled' ? '' : 'display: none;' }}">-</span>
                                    </td>
                                    <td style="border-top-color: var(--color-border);">
                                        @if($fixture->status == 'completed')
                                            <span class="badge badge-success status-badge">COMPLETED</span>
                                        @elseif($fixture->status == 'in_progress')
                                            <span class="badge badge-warning status-badge">IN PROGRESS</span>
                                        @else
                                            <span class="badge badge-secondary status-badge">SCHEDULED</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

            @else
                <div style="text-align: center; padding: var(--spacing-2xl);">
                    <p class="text-muted">No active tournament found.</p>
                </div>
            @endif
        </div>
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
                console.log('Real-time score update received on Spectator Schedule page:', data);
                const fixture = data.fixture;
                if (!fixture) return;

                const fixtureRow = document.querySelector(`[data-fixture-id="${fixture.id}"]`);
                if (fixtureRow) {
                    const homeScoreEl = fixtureRow.querySelector('.home-score');
                    const awayScoreEl = fixtureRow.querySelector('.away-score');
                    const scoreDisplay = fixtureRow.querySelector('.score-display');
                    const scorePlaceholder = fixtureRow.querySelector('.score-placeholder');
                    const statusBadge = fixtureRow.querySelector('.status-badge');
                    
                    let rowChanged = false;
                    
                    if (homeScoreEl && awayScoreEl) {
                        const oldHome = parseInt(homeScoreEl.textContent);
                        const oldAway = parseInt(awayScoreEl.textContent);
                        
                        if (oldHome !== parseInt(fixture.home_score) || oldAway !== parseInt(fixture.away_score)) {
                            homeScoreEl.textContent = fixture.home_score ?? 0;
                            awayScoreEl.textContent = fixture.away_score ?? 0;
                            rowChanged = true;
                        }
                    }

                    // Toggle score vs placeholder depending on status
                    if (fixture.status !== 'scheduled') {
                        if (scoreDisplay && scoreDisplay.style.display === 'none') {
                            scoreDisplay.style.display = 'inline';
                            rowChanged = true;
                        }
                        if (scorePlaceholder) scorePlaceholder.style.display = 'none';
                    } else {
                        if (scoreDisplay) scoreDisplay.style.display = 'none';
                        if (scorePlaceholder) scorePlaceholder.style.display = 'inline';
                    }

                    // Update status badge class and text
                    if (statusBadge) {
                        const newClass = fixture.status === 'completed' ? 'badge-success' : (fixture.status === 'in_progress' ? 'badge-warning' : 'badge-secondary');
                        const newText = fixture.status === 'completed' ? 'COMPLETED' : (fixture.status === 'in_progress' ? 'IN PROGRESS' : 'SCHEDULED');
                        
                        if (!statusBadge.classList.contains(newClass)) {
                            statusBadge.className = `badge ${newClass} status-badge`;
                            statusBadge.textContent = newText;
                            rowChanged = true;
                        }
                    }

                    // Flash row
                    if (rowChanged) {
                        fixtureRow.style.transition = 'none';
                        fixtureRow.style.backgroundColor = 'rgba(20, 184, 166, 0.25)';
                        
                        setTimeout(() => {
                            fixtureRow.style.transition = 'all 1s ease';
                            fixtureRow.style.backgroundColor = '';
                        }, 100);
                    }
                }
            });
        }
    });
</script>
@endpush