<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewTournamentMail;
use App\Models\User;

class TournamentController extends Controller
{
    /**
     * Display a listing of tournaments.
     */
    public function index()
    {
        $tournaments = \App\Models\Tournament::orderBy('start_date', 'asc')->get();
        
        // Data for Header Widgets & Summary Cards
        $latestSafetyLog = \App\Models\SafetyLog::latest()->first();
        $totalTournaments = $tournaments->count();
        $ongoingTournaments = $tournaments->where('status', 'ongoing')->count();
        $completedTournaments = $tournaments->where('status', 'completed')->count();

        return view('admin.tournaments.index', compact(
            'tournaments', 
            'latestSafetyLog', 
            'totalTournaments', 
            'ongoingTournaments', 
            'completedTournaments'
        ));
    }

    public function create()
    {
        return view('admin.tournaments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'venue_name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:upcoming,ongoing,completed',
            'fee' => 'required|numeric|min:0',
            'categories' => 'nullable|string',
            'max_teams' => 'nullable|integer|min:2',
            'registration_deadline' => 'nullable|date',
        ]);

        $data = $request->all();
        // Populate legacy fields
        $data['venue'] = $request->venue_name;
        $data['tournament_date'] = $request->start_date;

        \App\Models\Tournament::create($data);
        $tournament = \App\Models\Tournament::latest()->first();

        // ── Blast email & in-app notification to all active managers if tournament is upcoming ──
        if (($data['status'] ?? 'upcoming') === 'upcoming') {
            try {
                $managers = User::where('role', 'manager')
                    ->where('status', User::STATUS_ACTIVE)
                    ->get();

                foreach ($managers as $manager) {
                    Mail::to($manager->email)->send(new NewTournamentMail($tournament));
                    $manager->notify(new \App\Notifications\UpcomingTournamentNotification($tournament));
                }
            } catch (\Exception $mailEx) {
                \Illuminate\Support\Facades\Log::error('Failed to send new tournament emails: ' . $mailEx->getMessage());
            }
        }

        return redirect()->route('admin.tournaments.index')->with('success', 'Tournament created successfully. Email notification sent to all managers.');

    }

    public function edit($id)
    {
        $tournament = \App\Models\Tournament::findOrFail($id);
        return view('admin.tournaments.edit', compact('tournament'));
    }

    public function update(Request $request, $id)
    {
        $tournament = \App\Models\Tournament::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'venue_name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:upcoming,ongoing,completed',
            'fee' => 'required|numeric|min:0',
            'categories' => 'nullable|string',
            'max_teams' => 'nullable|integer|min:2',
            'registration_deadline' => 'nullable|date',
        ]);

        $data = $request->all();
        $data['venue'] = $request->venue_name;
        $data['tournament_date'] = $request->start_date;

        $oldStatus = $tournament->status;
        $tournament->update($data);

        // Notify spectators and managers when a tournament starts (ongoing)
        if ($oldStatus !== 'ongoing' && $tournament->status === 'ongoing') {
            try {
                $users = User::whereIn('role', ['spectator', 'manager'])->get();
                foreach ($users as $user) {
                    $user->notify(new \App\Notifications\TournamentStartedNotification($tournament));
                }
            } catch (\Exception $ex) {
                \Illuminate\Support\Facades\Log::error('Failed to send tournament started notifications: ' . $ex->getMessage());
            }
        }

        return redirect()->route('admin.tournaments.index')->with('success', 'Tournament updated successfully.');
    }

    public function destroy($id)
    {
        $tournament = \App\Models\Tournament::findOrFail($id);
        $tournament->delete();
        return redirect()->route('admin.tournaments.index')->with('success', 'Tournament deleted successfully.');
    }

    public function assignTeams($id)
    {
        $tournament = \App\Models\Tournament::findOrFail($id);
        // Only get teams that have a valid, existing manager (excludes orphaned profiles)
        $teams = \App\Models\Team::has('manager')->get();
        return view('admin.tournaments.assign-teams', compact('tournament', 'teams'));
    }

    public function updateTeams(Request $request, $id)
    {
        $tournament = \App\Models\Tournament::findOrFail($id);
        
        // Sync the pivot table (legacy compatibility)
        $teamIds = $request->teams ?? [];
        $tournament->teams()->sync($teamIds);
        
        // Ensure TournamentRegistration exists for each assigned team
        foreach ($teamIds as $teamId) {
            $team = \App\Models\Team::find($teamId);
            if ($team) {
                \App\Models\TournamentRegistration::updateOrCreate(
                    [
                        'tournament_id' => $id,
                        'team_id' => $teamId,
                    ],
                    [
                        'manager_id' => $team->manager_id ?? auth()->id(),
                        'status' => \App\Models\TournamentRegistration::STATUS_CONFIRMED,
                        'payment_status' => \App\Models\TournamentRegistration::PAYMENT_PAID, // Admin assigned teams are assumed paid
                        'amount_paid' => $tournament->fee ?? 0,
                        'registered_at' => now(),
                    ]
                );
            }
        }
        
        // Optional: We might want to remove registrations for teams that were un-assigned
        // But for safety, we'll keep them or let admin delete them manually if needed.

        return redirect()->route('admin.tournaments.index')->with('success', 'Teams assigned successfully.');
    }

    /**
     * Display a listing of teams (Legacy/Utility).
     */
    public function teams()
    {
        $teams = \App\Models\Team::orderBy('created_at', 'desc')->get();
        return view('admin.tournaments.teams', compact('teams'));
    }

    /**
     * Display all registrations for a tournament.
     */
    public function registrations($id)
    {
        $tournament = \App\Models\Tournament::findOrFail($id);
        
        // Get all registrations with team and manager details
        $registrations = \App\Models\TournamentRegistration::with(['team.players', 'manager'])
            ->where('tournament_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate statistics
        $stats = [
            'total' => $registrations->count(),
            'paid' => $registrations->where('payment_status', 'paid')->count(),
            'pending' => $registrations->where('payment_status', 'pending')->count(),
            'total_players' => $registrations->sum(function($reg) {
                return $reg->team->players->count();
            })
        ];
        
        return view('admin.tournaments.registrations', compact('tournament', 'registrations', 'stats'));
    }

    /**
     * Display team roster for admin view.
     */
    public function teamRoster($tournamentId, $teamId)
    {
        $tournament = \App\Models\Tournament::findOrFail($tournamentId);
        $team = \App\Models\Team::with(['players', 'manager'])->findOrFail($teamId);
        
        // Get registration details
        $registration = \App\Models\TournamentRegistration::where('tournament_id', $tournamentId)
            ->where('team_id', $teamId)
            ->first();
        
        return view('admin.tournaments.team-roster', compact('tournament', 'team', 'registration'));
    }

    public function matchManagement($id)
    {
        $tournament = \App\Models\Tournament::with(['pools.fixtures', 'pools.registrations.team', 'fixtures'])->findOrFail($id);
        
        // Get registered teams that are not assigned to any pool yet
        $unassignedRegistrations = \App\Models\TournamentRegistration::with('team')
            ->where('tournament_id', $id)
            ->whereNull('pool_id')
            ->get();
            
        // Get all registrations to assign manually
        $allRegistrations = \App\Models\TournamentRegistration::with('team')->where('tournament_id', $id)->get();

        return view('admin.tournaments.match-management', compact('tournament', 'unassignedRegistrations', 'allRegistrations'));
    }

    public function storePools(Request $request, $id)
    {
        $tournament = \App\Models\Tournament::findOrFail($id);
        $request->validate([
            'pool_name' => 'required|string',
            'team_ids' => 'required|array'
        ]);

        $pool = \App\Models\Pool::create([
            'tournament_id' => $id,
            'name' => $request->pool_name
        ]);

        \App\Models\TournamentRegistration::whereIn('team_id', $request->team_ids)
            ->where('tournament_id', $id)
            ->update(['pool_id' => $pool->id]);

        return redirect()->back()->with('success', 'Pool created and teams assigned.');
    }

    public function generateFixtures(Request $request, $id)
    {
        $tournament = \App\Models\Tournament::with('pools.registrations')->findOrFail($id);
        
        // Handle Custom / Knockout Fixtures
        if ($request->has('is_custom')) {
            $request->validate([
                'custom_category' => 'required|string|max:50',
                'custom_round' => 'required|string|max:50',
                'home_team_id' => 'required|exists:teams,id',
                'away_team_id' => 'required|exists:teams,id|different:home_team_id',
                'custom_start_time' => 'nullable|date',
            ]);

            $stageName = $request->custom_category . ' - ' . $request->custom_round;

            \App\Models\Fixture::create([
                'tournament_id' => $id,
                'pool_id' => null,
                'home_team_id' => $request->home_team_id,
                'away_team_id' => $request->away_team_id,
                'stage' => $stageName,
                'status' => 'draft',
                'start_time' => $request->custom_start_time ? \Carbon\Carbon::parse($request->custom_start_time) : null,
            ]);

            return redirect()->back()->with('success', 'Custom match added as Draft. Please publish when ready.');
        }

        $request->validate([
            'start_datetime' => 'required|date',
            'match_duration' => 'required|integer|min:5',
        ]);

        $currentStartTime = \Carbon\Carbon::parse($request->start_datetime);
        $duration = (int) $request->match_duration;
        
        $poolsMatches = [];
        $maxRounds = 0;

        foreach ($tournament->pools as $pool) {
            $teams = $pool->registrations->pluck('team_id')->toArray();
            $count = count($teams);
            if ($count < 2) continue;
            
            if ($count % 2 != 0) {
                array_push($teams, null);
                $count++;
            }
            
            $halfSize = $count / 2;
            $poolRounds = [];
            
            for ($round = 0; $round < $count - 1; $round++) {
                $roundMatches = [];
                for ($i = 0; $i < $halfSize; $i++) {
                    $home = $teams[$i];
                    $away = $teams[$count - 1 - $i];
                    if ($home !== null && $away !== null) {
                        $roundMatches[] = [
                            'pool_id' => $pool->id,
                            'home' => $home,
                            'away' => $away
                        ];
                    }
                }
                $poolRounds[] = $roundMatches;
                
                $last = array_pop($teams);
                array_splice($teams, 1, 0, [$last]);
            }
            $poolsMatches[$pool->id] = $poolRounds;
            $maxRounds = max($maxRounds, count($poolRounds));
        }

        // Now save alternated round-by-round
        $generatedCount = 0;
        for ($r = 0; $r < $maxRounds; $r++) {
            foreach ($tournament->pools as $pool) {
                if (isset($poolsMatches[$pool->id][$r])) {
                    foreach ($poolsMatches[$pool->id][$r] as $match) {
                        \App\Models\Fixture::create([
                            'tournament_id' => $id,
                            'pool_id' => $match['pool_id'],
                            'home_team_id' => $match['home'],
                            'away_team_id' => $match['away'],
                            'stage' => 'Pool Stage',
                            'status' => 'draft',
                            'start_time' => $currentStartTime->copy(),
                        ]);
                        $currentStartTime->addMinutes($duration);
                        $generatedCount++;
                    }
                }
            }
        }

        return redirect()->back()->with('success', $generatedCount . ' fixtures generated automatically as Draft.');
    }

    public function previewFixtures(Request $request, $id)
    {
        $tournament = \App\Models\Tournament::with('pools.registrations.team')->findOrFail($id);
        
        $request->validate([
            'start_datetime' => 'required|date',
            'match_duration' => 'required|integer|min:5',
        ]);

        $currentStartTime = \Carbon\Carbon::parse($request->start_datetime);
        $duration = (int) $request->match_duration;
        
        $poolsMatches = [];
        $maxRounds = 0;

        foreach ($tournament->pools as $pool) {
            $teams = $pool->registrations->where('status', 'confirmed')->map(function($reg) {
                return [
                    'id' => $reg->team_id,
                    'name' => $reg->team->name ?? 'TBD'
                ];
            })->values()->toArray();
            
            $count = count($teams);
            if ($count < 2) continue;
            
            if ($count % 2 != 0) {
                array_push($teams, null);
                $count++;
            }
            
            $halfSize = $count / 2;
            $poolRounds = [];
            
            for ($round = 0; $round < $count - 1; $round++) {
                $roundMatches = [];
                for ($i = 0; $i < $halfSize; $i++) {
                    $home = $teams[$i];
                    $away = $teams[$count - 1 - $i];
                    if ($home !== null && $away !== null) {
                        $roundMatches[] = [
                            'pool_id' => $pool->id,
                            'pool_name' => $pool->name,
                            'home' => $home,
                            'away' => $away
                        ];
                    }
                }
                $poolRounds[] = $roundMatches;
                
                $last = array_pop($teams);
                array_splice($teams, 1, 0, [$last]);
            }
            $poolsMatches[$pool->id] = $poolRounds;
            $maxRounds = max($maxRounds, count($poolRounds));
        }

        // Alternate round-by-round for preview
        $fixtures = [];
        $matchNumber = 1;
        
        for ($r = 0; $r < $maxRounds; $r++) {
            foreach ($tournament->pools as $pool) {
                if (isset($poolsMatches[$pool->id][$r])) {
                    foreach ($poolsMatches[$pool->id][$r] as $match) {
                        $fixtures[] = [
                            'match_no' => $matchNumber++,
                            'pool_name' => $match['pool_name'],
                            'pool_id' => $match['pool_id'],
                            'home_team_id' => $match['home']['id'],
                            'home_team_name' => $match['home']['name'],
                            'away_team_id' => $match['away']['id'],
                            'away_team_name' => $match['away']['name'],
                            'start_time' => $currentStartTime->format('Y-m-d H:i:s'),
                            'formatted_time' => $currentStartTime->format('d M, h:i A')
                        ];
                        $currentStartTime->addMinutes($duration);
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'fixtures' => $fixtures
        ]);
    }

    public function generateKnockouts(Request $request, $id)
    {
        $tournament = \App\Models\Tournament::with(['pools.registrations'])->findOrFail($id);
        
        $request->validate([
            'start_datetime' => 'required|date',
            'match_duration' => 'required|integer|min:5',
        ]);

        $pools = $tournament->pools;
        $poolCount = $pools->count();

        if (!in_array($poolCount, [2, 3, 4])) {
            return redirect()->back()->with('error', 'Auto-generation supports 2, 3, or 4 pools. Please add custom matches manually for other configurations.');
        }

        // Calculate standings for each pool
        $rankedPools = [];
        foreach ($pools as $pool) {
            $rankedPools[$pool->name] = $pool->calculateStandings();
        }

        $currentStartTime = \Carbon\Carbon::parse($request->start_datetime);
        $duration = (int) $request->match_duration;
        $generatedCount = 0;

        // ─────────────────────────────────────────────────────────────────────
        // 3 Pools — Standard Rugby 7s 12-team format
        //  • Cup  Semi-Finals : Seeds 1-4  (2 matches)
        //  • Plate Semi-Finals: Seeds 5-8  (2 matches)
        //  • Bowl  Final      : Seeds 9-10 (1 match)
        //  • Shield Final     : Seeds 11-12 (1 match)
        // ─────────────────────────────────────────────────────────────────────
        if ($poolCount === 3) {
            $poolNames = $pools->pluck('name')->sort()->values()->toArray();

            // Build a cross-pool seed list.
            // Group teams by their finish position (0-indexed), then within each group
            // rank by tournament points DESC, then points_difference DESC.
            $byPosition = []; // [ position => [ ['team'=>..,'pts'=>..,'pd'=>..], ... ] ]

            foreach ($poolNames as $poolName) {
                $standings = $rankedPools[$poolName] ?? collect();
                foreach ($standings as $pos => $row) {
                    $byPosition[$pos][] = [
                        'team_id' => $row['team']->id,
                        'pts'     => $row['points'],
                        'pd'      => $row['points_difference'],
                    ];
                }
            }

            ksort($byPosition); // sort positions 0,1,2,3

            // Sort within each position group by PTS desc, then PD desc
            $seededTeams = [];
            foreach ($byPosition as $posGroup) {
                usort($posGroup, function ($a, $b) {
                    if ($b['pts'] !== $a['pts']) return $b['pts'] - $a['pts'];
                    return $b['pd']  - $a['pd'];
                });
                foreach ($posGroup as $entry) {
                    $seededTeams[] = $entry['team_id'];
                }
            }

            // Seeds: [0]=1st, [1]=2nd, ... [11]=12th
            $s = function(int $seedIndex) use ($seededTeams) {
                return $seededTeams[$seedIndex] ?? null;
            };

            // ── Cup/Plate Quarter-Finals (Seeds 1 vs 8, 2 vs 7, 3 vs 6, 4 vs 5) ──
            $cupPlateQFs = [
                ['label' => 'Cup/Plate - Quarter-Final 1', 'home' => 0, 'away' => 7],
                ['label' => 'Cup/Plate - Quarter-Final 2', 'home' => 1, 'away' => 6],
                ['label' => 'Cup/Plate - Quarter-Final 3', 'home' => 2, 'away' => 5],
                ['label' => 'Cup/Plate - Quarter-Final 4', 'home' => 3, 'away' => 4],
            ];
            foreach ($cupPlateQFs as $match) {
                \App\Models\Fixture::create([
                    'tournament_id' => $id,
                    'pool_id'       => null,
                    'home_team_id'  => $s($match['home']),
                    'away_team_id'  => $s($match['away']),
                    'stage'         => $match['label'],
                    'status'        => 'draft',
                    'start_time'    => $currentStartTime->copy(),
                ]);
                $currentStartTime->addMinutes($duration);
                $generatedCount++;
            }

            // ── Bowl/Shield Semi-Finals (Seeds 9 vs 12, 10 vs 11) ──
            $bowlShieldSFs = [
                ['label' => 'Bowl/Shield - Semi-Final 1', 'home' => 8, 'away' => 11],
                ['label' => 'Bowl/Shield - Semi-Final 2', 'home' => 9, 'away' => 10],
            ];
            foreach ($bowlShieldSFs as $match) {
                \App\Models\Fixture::create([
                    'tournament_id' => $id,
                    'pool_id'       => null,
                    'home_team_id'  => $s($match['home']),
                    'away_team_id'  => $s($match['away']),
                    'stage'         => $match['label'],
                    'status'        => 'draft',
                    'start_time'    => $currentStartTime->copy(),
                ]);
                $currentStartTime->addMinutes($duration);
                $generatedCount++;
            }

        } elseif ($poolCount === 4) {
            $poolNames = $pools->pluck('name')->sort()->values()->toArray();
            
            $getTeamId = function($poolIndex, $rank) use ($poolNames, $rankedPools) {
                $poolName = $poolNames[$poolIndex] ?? null;
                if (!$poolName) return null;
                $standings = $rankedPools[$poolName] ?? collect();
                return isset($standings[$rank]) ? $standings[$rank]['team']->id : null;
            };

            // Cup/Plate QF
            $cupPairs = [
                ['home' => [0, 0], 'away' => [1, 1]], // 1st Pool A vs 2nd Pool B
                ['home' => [1, 0], 'away' => [0, 1]], // 1st Pool B vs 2nd Pool A
                ['home' => [2, 0], 'away' => [3, 1]], // 1st Pool C vs 2nd Pool D
                ['home' => [3, 0], 'away' => [2, 1]], // 1st Pool D vs 2nd Pool C
            ];

            // Bowl/Shield QF
            $bowlPairs = [
                ['home' => [0, 2], 'away' => [1, 3]], // 3rd Pool A vs 4th Pool B
                ['home' => [1, 2], 'away' => [0, 3]], // 3rd Pool B vs 4th Pool A
                ['home' => [2, 2], 'away' => [3, 3]], // 3rd Pool C vs 4th Pool D
                ['home' => [3, 2], 'away' => [2, 3]], // 3rd Pool D vs 4th Pool C
            ];

            foreach ($cupPairs as $index => $pair) {
                $homeTeamId = $getTeamId($pair['home'][0], $pair['home'][1]);
                $awayTeamId = $getTeamId($pair['away'][0], $pair['away'][1]);
                
                \App\Models\Fixture::create([
                    'tournament_id' => $id,
                    'pool_id'       => null,
                    'home_team_id'  => $homeTeamId,
                    'away_team_id'  => $awayTeamId,
                    'stage'         => 'Cup/Plate - Quarter-Final ' . ($index + 1),
                    'status'        => 'draft',
                    'start_time'    => $currentStartTime->copy(),
                ]);
                $currentStartTime->addMinutes($duration);
                $generatedCount++;
            }

            foreach ($bowlPairs as $index => $pair) {
                $homeTeamId = $getTeamId($pair['home'][0], $pair['home'][1]);
                $awayTeamId = $getTeamId($pair['away'][0], $pair['away'][1]);
                
                \App\Models\Fixture::create([
                    'tournament_id' => $id,
                    'pool_id'       => null,
                    'home_team_id'  => $homeTeamId,
                    'away_team_id'  => $awayTeamId,
                    'stage'         => 'Bowl/Shield - Quarter-Final ' . ($index + 1),
                    'status'        => 'draft',
                    'start_time'    => $currentStartTime->copy(),
                ]);
                $currentStartTime->addMinutes($duration);
                $generatedCount++;
            }

        } elseif ($poolCount === 2) {
            $poolNames = $pools->pluck('name')->sort()->values()->toArray();
            
            $getTeamId = function($poolIndex, $rank) use ($poolNames, $rankedPools) {
                $poolName = $poolNames[$poolIndex] ?? null;
                if (!$poolName) return null;
                $standings = $rankedPools[$poolName] ?? collect();
                return isset($standings[$rank]) ? $standings[$rank]['team']->id : null;
            };

            $cupPairs = [
                ['home' => [0, 0], 'away' => [1, 1]],
                ['home' => [1, 0], 'away' => [0, 1]],
            ];

            $bowlPairs = [
                ['home' => [0, 2], 'away' => [1, 3]],
                ['home' => [1, 2], 'away' => [0, 3]],
            ];

            foreach ($cupPairs as $index => $pair) {
                $homeTeamId = $getTeamId($pair['home'][0], $pair['home'][1]);
                $awayTeamId = $getTeamId($pair['away'][0], $pair['away'][1]);
                
                \App\Models\Fixture::create([
                    'tournament_id' => $id,
                    'pool_id'       => null,
                    'home_team_id'  => $homeTeamId,
                    'away_team_id'  => $awayTeamId,
                    'stage'         => 'Cup - Semi-Final ' . ($index + 1),
                    'status'        => 'draft',
                    'start_time'    => $currentStartTime->copy(),
                ]);
                $currentStartTime->addMinutes($duration);
                $generatedCount++;
            }

            foreach ($bowlPairs as $index => $pair) {
                $homeTeamId = $getTeamId($pair['home'][0], $pair['home'][1]);
                $awayTeamId = $getTeamId($pair['away'][0], $pair['away'][1]);
                
                \App\Models\Fixture::create([
                    'tournament_id' => $id,
                    'pool_id'       => null,
                    'home_team_id'  => $homeTeamId,
                    'away_team_id'  => $awayTeamId,
                    'stage'         => 'Bowl - Semi-Final ' . ($index + 1),
                    'status'        => 'draft',
                    'start_time'    => $currentStartTime->copy(),
                ]);
                $currentStartTime->addMinutes($duration);
                $generatedCount++;
            }
        }

        return redirect()->back()->with('success', $generatedCount . ' knockout fixtures generated as Draft. Please review and publish when ready.');
    }



    /**
     * Auto-generate pools with random team draw (Malaysian rugby 7s format).
     * Remainder teams are distributed front-to-back (Pool A gets extras first).
     */
    public function autoGeneratePools(Request $request, $id)
    {
        $tournament = \App\Models\Tournament::with('pools')->findOrFail($id);

        // Block if pools already exist
        if ($tournament->pools->isNotEmpty()) {
            return redirect()->back()->with('error', 'Pools already exist. Please clear all existing pools before running Auto Draw again.');
        }

        $request->validate([
            'num_pools'      => 'required|integer|min:2|max:8',
            'teams_per_pool' => 'required|integer|min:2|max:16',
        ]);

        $numPools   = (int) $request->num_pools;
        $perPool    = (int) $request->teams_per_pool;

        // Get all unassigned confirmed registrations, shuffle for random draw
        $registrations = \App\Models\TournamentRegistration::where('tournament_id', $id)
            ->whereNull('pool_id')
            ->get()
            ->shuffle()
            ->values();

        $totalTeams = $registrations->count();

        if ($totalTeams < $numPools * 2) {
            return redirect()->back()->with('error', 'Not enough teams to fill ' . $numPools . ' pools. A minimum of ' . ($numPools * 2) . ' teams is required.');
        }

        // Pool naming: A, B, C, D...
        $poolLabels = range('A', 'Z');

        // Calculate team slots per pool (remainder goes front-to-back, Pool A gets extra first)
        $remainder  = $totalTeams % $numPools;
        $teamIndex  = 0;

        for ($i = 0; $i < $numPools; $i++) {
            $thisPoolSize = $perPool + ($i < $remainder ? 1 : 0);

            $pool = \App\Models\Pool::create([
                'tournament_id' => $id,
                'name'          => 'Pool ' . $poolLabels[$i],
            ]);

            // Assign the next chunk of teams to this pool
            $chunk = $registrations->slice($teamIndex, $thisPoolSize)->pluck('id');
            \App\Models\TournamentRegistration::whereIn('id', $chunk)
                ->update(['pool_id' => $pool->id]);

            $teamIndex += $thisPoolSize;

            // Stop if no more teams
            if ($teamIndex >= $totalTeams) break;
        }

        $poolsCreated = min($numPools, (int) ceil($totalTeams / max($perPool, 1)));
        return redirect()->back()->with('success', '🎲 Auto Pool Draw complete! ' . $poolsCreated . ' pools created and ' . $totalTeams . ' teams randomly assigned.');
    }

    /**
     * Clear all pools and unassign all teams for a tournament.
     */
    public function clearPools(Request $request, $id)
    {
        $tournament = \App\Models\Tournament::findOrFail($id);

        // Check if any pool has fixtures — warn before clearing
        $hasFixtures = \App\Models\Fixture::where('tournament_id', $id)
            ->where('stage', 'Pool Stage')
            ->exists();

        if ($hasFixtures && !$request->has('force')) {
            return redirect()->back()->with('error', 'Pool Stage fixtures have already been generated. Please clear fixtures first before resetting pools.');
        }

        // Unassign all registrations from pools
        \App\Models\TournamentRegistration::where('tournament_id', $id)
            ->update(['pool_id' => null]);

        // Delete all pools for this tournament
        \App\Models\Pool::where('tournament_id', $id)->delete();

        return redirect()->back()->with('success', 'All pools have been reset. Teams are now available for reassignment.');
    }

    public function clearFixtures(Request $request, $id)
    {
        $tournament = \App\Models\Tournament::findOrFail($id);
        
        if ($request->query('type') === 'knockout') {
            \App\Models\Fixture::where('tournament_id', $id)
                ->where('stage', '!=', 'Pool Stage')
                ->delete();
            return redirect()->back()->with('success', 'Knockout fixtures have been cleared.');
        }
        
        // Delete all fixtures for this tournament
        \App\Models\Fixture::where('tournament_id', $id)->delete();
        
        return redirect()->back()->with('success', 'All fixtures have been cleared.');
    }

    public function updateFixture(Request $request, $id, $fixtureId)
    {
        $fixture = \App\Models\Fixture::where('tournament_id', $id)->findOrFail($fixtureId);
        
        $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'status' => 'required|in:draft,scheduled,ongoing,completed',
            'home_score' => 'nullable|integer|min:0',
            'away_score' => 'nullable|integer|min:0',
            'start_time' => 'nullable|date',
        ]);

        $fixture->update([
            'home_team_id' => $request->home_team_id,
            'away_team_id' => $request->away_team_id,
            'status' => $request->status,
            'home_score' => $request->home_score,
            'away_score' => $request->away_score,
            'start_time' => $request->start_time,
        ]);

        return redirect()->back()->with('success', 'Fixture updated successfully.');
    }

    public function deleteFixture($id, $fixtureId)
    {
        $fixture = \App\Models\Fixture::where('tournament_id', $id)->findOrFail($fixtureId);
        $fixture->delete();
        
        return redirect()->back()->with('success', 'Fixture deleted successfully.');
    }

    public function publishFixtures($id)
    {
        $tournament = \App\Models\Tournament::findOrFail($id);
        
        \App\Models\Fixture::where('tournament_id', $id)
            ->where('status', 'draft')
            ->update(['status' => 'scheduled']);

        // Notify managers and referees
        try {
            $registrations = \App\Models\TournamentRegistration::where('tournament_id', $id)
                ->whereNotNull('manager_id')
                ->get();
            $managerIds = $registrations->pluck('manager_id')->unique()->toArray();
            
            $managers = User::whereIn('id', $managerIds)->get();
            foreach ($managers as $manager) {
                $manager->notify(new \App\Notifications\FixturesPublishedNotification($tournament));
            }

            $referees = User::where('role', 'referee')->get();
            foreach ($referees as $referee) {
                $referee->notify(new \App\Notifications\FixturesPublishedNotification($tournament));
            }
        } catch (\Exception $ex) {
            \Illuminate\Support\Facades\Log::error('Failed to send fixtures published notifications: ' . $ex->getMessage());
        }
            
        return redirect()->back()->with('success', 'Fixtures have been published and are now visible to everyone.');
    }

    /**
     * Verify and update manual payment status for a registration
     */
    public function verifyPayment(Request $request, $tournamentId, $registrationId)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        $tournament = \App\Models\Tournament::findOrFail($tournamentId);
        $registration = \App\Models\TournamentRegistration::where('tournament_id', $tournamentId)
            ->findOrFail($registrationId);

        if ($request->action === 'approve') {
            $fee = $tournament->fee ?? 250;

            $registration->update([
                'status' => \App\Models\TournamentRegistration::STATUS_CONFIRMED,
                'payment_status' => \App\Models\TournamentRegistration::PAYMENT_PAID,
                'amount_paid' => $fee,
                'registered_at' => now(),
            ]);

            if ($registration->team) {
                $registration->team->update([
                    'payment_status' => \App\Models\Team::PAYMENT_STATUS_PAID,
                    'amount_paid' => $fee,
                ]);
            }

            return redirect()->back()->with('success', "Payment for team '{$registration->team->name}' has been approved and confirmed!");
        } else {
            $registration->update([
                'payment_status' => \App\Models\TournamentRegistration::PAYMENT_FAILED,
                'receipt_path' => null, // Clear the invalid receipt so they can re-upload
            ]);

            if ($registration->team) {
                $registration->team->update([
                    'payment_status' => \App\Models\Team::PAYMENT_STATUS_UNPAID,
                ]);
            }

            // Send notification to the manager
            try {
                if ($registration->manager) {
                    $registration->manager->notify(new \App\Notifications\PaymentRejectedNotification($tournament));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to notify manager of payment rejection: ' . $e->getMessage());
            }

            return redirect()->back()->with('warning', "Payment for team '{$registration->team->name}' has been rejected. The manager has been notified.");
        }
    }
}
