<div class="bracket-match-node">
    <div class="bracket-match-header">
        <span>{{ $title }}</span>
        @if($match && $match->start_time)
            <span>{{ \Carbon\Carbon::parse($match->start_time)->format('h:i A') }}</span>
        @else
            <span>TBD</span>
        @endif
    </div>
    @if($match)
        @php
            $isCompleted = $match->status === 'completed';
            $homeWinner = $isCompleted && $match->home_score > $match->away_score;
            $awayWinner = $isCompleted && $match->away_score > $match->home_score;
        @endphp
        <div class="bracket-match-team {{ $homeWinner ? 'winner' : '' }}">
            <span>{{ $match->homeTeam->name ?? 'TBD' }}</span>
            <span class="score">{{ $match->home_score ?? '-' }}</span>
        </div>
        <div class="bracket-match-team {{ $awayWinner ? 'winner' : '' }}">
            <span>{{ $match->awayTeam->name ?? 'TBD' }}</span>
            <span class="score">{{ $match->away_score ?? '-' }}</span>
        </div>
    @else
        <div class="bracket-match-team">
            <span class="text-muted" style="font-style: italic; opacity: 0.5;">To Be Decided</span>
            <span class="score">-</span>
        </div>
        <div class="bracket-match-team">
            <span class="text-muted" style="font-style: italic; opacity: 0.5;">To Be Decided</span>
            <span class="score">-</span>
        </div>
    @endif
    @if(isset($info) && $info)
        <div style="background: rgba(255,255,255,0.02); border-top: 1px dashed var(--glass-border); padding: 4px 10px; font-size: 0.62rem; color: var(--zinc-500); text-align: center; font-weight: 600; letter-spacing: 0.3px;">
            {{ $info }}
        </div>
    @endif
</div>
