<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fixture;
use Carbon\Carbon;
use App\Events\ScoreUpdated;
use App\Models\MatchEvent;

class RefereeController extends Controller
{
    public function console(Request $request)
    {
        // Include scheduled, in_progress, AND recently completed (last 24h) so
        // the referee can still see the match after saving the final score.
        $fixtures = Fixture::with(['homeTeam', 'awayTeam', 'tournament', 'pool', 'matchEvents' => function($q) {
            $q->orderBy('minute', 'desc')->orderBy('created_at', 'desc');
        }])
            ->where(function ($q) {
                $q->whereIn('status', ['scheduled', 'in_progress'])
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'completed')
                         ->where('updated_at', '>=', now()->subHours(24));
                  });
            })
            ->orderByRaw("FIELD(status, 'in_progress', 'scheduled', 'completed')")
            ->orderBy('start_time', 'asc')
            ->get();

        $latestSafetyLogs = \App\Models\SafetyLog::latest()->get()->unique('tournament_id')->keyBy('tournament_id');

        // Auto-select fixture from query string (set after a save redirect)
        $selectedFixtureId = $request->query('fixture_id');

        return view('referee.console', compact('fixtures', 'latestSafetyLogs', 'selectedFixtureId'));
    }

    public function updateScore(Request $request, $id)
    {
        $fixture = Fixture::findOrFail($id);
        $request->validate([
            'home_score' => 'nullable|integer|min:0',
            'away_score' => 'nullable|integer|min:0',
            'status'     => 'required|in:scheduled,in_progress,completed',
        ]);

        $oldStatus = $fixture->status;

        $fixture->update($request->only(['home_score', 'away_score', 'status']));

        // If status changed to completed, update Elo ratings safely
        if ($fixture->status === 'completed' && $oldStatus !== 'completed') {
            try {
                $this->updateEloRating($fixture);
            } catch (\Throwable $eloError) {
                \Log::error('Failed to update Elo ratings: ' . $eloError->getMessage());
            }
        }

        // Broadcast the real-time score update event
        try {
            broadcast(new ScoreUpdated($fixture))->toOthers();
        } catch (\Exception $e) {
            // Log warning or exception, fallback gracefully
            report($e);
        }

        return redirect()->route('referee.console', ['fixture_id' => $fixture->id])
                         ->with('success', 'Score saved for ' . ($fixture->homeTeam->name ?? 'Home') . ' vs ' . ($fixture->awayTeam->name ?? 'Away') . '!');
    }

    public function addEvent(Request $request, $id)
    {
        $fixture = Fixture::findOrFail($id);
        $request->validate([
            'event_type'    => 'required|in:try,conversion,penalty,drop_goal,yellow_card,red_card,info',
            'team_id'       => 'nullable|exists:teams,id',
            'player_name'   => 'nullable|string|max:255',
            'player_jersey' => 'nullable|integer|min:1|max:99',
            'minute'        => 'required|integer|min:0|max:120',
        ]);

        // Create the match event (points auto-calculated in booting)
        $event = MatchEvent::create([
            'fixture_id'    => $fixture->id,
            'team_id'       => $request->team_id,
            'event_type'    => $request->event_type,
            'player_name'   => $request->player_name,
            'player_jersey' => $request->player_jersey,
            'minute'        => $request->minute,
        ]);

        // Update fixture score if event has points
        if ($event->points > 0 && $request->team_id) {
            if ($request->team_id == $fixture->home_team_id) {
                $fixture->increment('home_score', $event->points);
            } elseif ($request->team_id == $fixture->away_team_id) {
                $fixture->increment('away_score', $event->points);
            }
        }

        // Broadcast the update
        try {
            broadcast(new ScoreUpdated($fixture))->toOthers();
        } catch (\Exception $e) {
            report($e);
        }

        return redirect()->route('referee.console', ['fixture_id' => $fixture->id])
                         ->with('success', 'Event recorded successfully!');
    }

    public function deleteEvent($eventId)
    {
        $event = MatchEvent::findOrFail($eventId);
        $fixture = $event->fixture;
        
        $event->delete();

        // Recalculate score from remaining events
        $homeEventsScore = $fixture->matchEvents()->where('team_id', $fixture->home_team_id)->sum('points');
        $awayEventsScore = $fixture->matchEvents()->where('team_id', $fixture->away_team_id)->sum('points');

        $fixture->update([
            'home_score' => $homeEventsScore,
            'away_score' => $awayEventsScore
        ]);

        // Broadcast the update
        try {
            broadcast(new ScoreUpdated($fixture))->toOthers();
        } catch (\Exception $e) {
            report($e);
        }

        return redirect()->route('referee.console', ['fixture_id' => $fixture->id])
                         ->with('success', 'Event removed and scores recalculated!');
    }

    public function assignments()
    {
        // Show all scheduled/upcoming fixtures ordered by time
        $upcoming = Fixture::with(['homeTeam', 'awayTeam', 'tournament', 'pool'])
            ->where('status', 'scheduled')
            ->orderBy('start_time', 'asc')
            ->get();

        $today = Fixture::with(['homeTeam', 'awayTeam', 'tournament', 'pool'])
            ->where('status', 'scheduled')
            ->whereDate('start_time', Carbon::today())
            ->orderBy('start_time', 'asc')
            ->get();

        return view('referee.assignments', compact('upcoming', 'today'));
    }

    public function history()
    {
        $completed = Fixture::with(['homeTeam', 'awayTeam', 'tournament', 'pool'])
            ->where('status', 'completed')
            ->orderBy('start_time', 'desc')
            ->get();

        $totalMatches   = $completed->count();
        $totalTournaments = $completed->pluck('tournament_id')->unique()->count();

        return view('referee.history', compact('completed', 'totalMatches', 'totalTournaments'));
    }

    public function safety(Request $request)
    {
        // Load ongoing and upcoming tournaments
        $tournaments = \App\Models\Tournament::whereIn('status', ['ongoing', 'upcoming'])
            ->orderBy('start_date', 'asc')
            ->get();

        // Get selected tournament ID
        $selectedTournamentId = $request->query('tournament_id');
        
        if ($selectedTournamentId) {
            $selectedTournament = \App\Models\Tournament::find($selectedTournamentId);
        } else {
            $selectedTournament = $tournaments->first();
        }

        $latestLog = null;
        if ($selectedTournament) {
            $latestLog = \App\Models\SafetyLog::where('tournament_id', $selectedTournament->id)
                ->latest()
                ->first();
        }

        return view('referee.safety', compact('latestLog', 'tournaments', 'selectedTournament'));
    }

    private function updateEloRating($fixture)
    {
        $homeTeam = $fixture->homeTeam;
        $awayTeam = $fixture->awayTeam;

        if (!$homeTeam || !$awayTeam) {
            return;
        }

        $rA = $homeTeam->rating ?? 1500;
        $rB = $awayTeam->rating ?? 1500;

        // 1. Calculate Expected Outcome (E)
        $eA = 1 / (1 + pow(10, ($rB - $rA) / 400));
        $eB = 1 - $eA;

        // 2. Determine Actual Outcome (W)
        $scoreDiff = abs((int)$fixture->home_score - (int)$fixture->away_score);
        if ($fixture->home_score > $fixture->away_score) {
            $wA = 1;
            $wB = 0;
        } elseif ($fixture->home_score < $fixture->away_score) {
            $wA = 0;
            $wB = 1;
        } else {
            $wA = 0.5;
            $wB = 0.5;
        }

        // 3. Margin of Victory Multiplier (M)
        // M = sqrt(Score Difference + 1)
        $multiplier = sqrt($scoreDiff + 1);

        // 4. Calculate change (K-factor = 32)
        $kFactor = 32;
        $newRatingA = $rA + ($kFactor * $multiplier * ($wA - $eA));
        $newRatingB = $rB + ($kFactor * $multiplier * ($wB - $eB));
        // 5. Update team ratings in the database safely
        try {
            $homeTeam->update(['rating' => (int) round($newRatingA)]);
            $awayTeam->update(['rating' => (int) round($newRatingB)]);

            // 6. Record history in the fixture
            $fixture->update([
                'home_elo_before' => (int) $rA,
                'away_elo_before' => (int) $rB,
                'home_elo_after'  => (int) round($newRatingA),
                'away_elo_after'  => (int) round($newRatingB),
            ]);
        } catch (\Throwable $ex) {
            \Log::error('Elo database update error: ' . $ex->getMessage());
        }
    }
}
