<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
    public function index()
    {
        $manager = Auth::user();
        
        // Get all open tournaments
        $openTournaments = Tournament::where('status', Tournament::STATUS_UPCOMING)
            ->orderByRaw('COALESCE(start_date, tournament_date) ASC')
            ->get();
        
        // Get manager's teams and statistics
        $myTeams = Team::where('manager_id', $manager->id)->get();
        $totalPlayers = 0;
        $pendingPayments = 0;
        
        foreach ($myTeams as $team) {
            $totalPlayers += $team->players()->count();
            if ($team->payment_status === Team::PAYMENT_STATUS_UNPAID) {
                $pendingPayments++;
            }
        }

        // Get live & scheduled fixtures involving manager's teams
        $managerTeamIds = $myTeams->pluck('id');
        $fixtures = collect();
        if ($managerTeamIds->isNotEmpty()) {
            $fixtures = \App\Models\Fixture::with(['tournament', 'pool', 'homeTeam', 'awayTeam'])
                ->where(function ($query) use ($managerTeamIds) {
                    $query->whereIn('home_team_id', $managerTeamIds)
                          ->orWhereIn('away_team_id', $managerTeamIds);
                })
                ->whereIn('status', ['scheduled', 'in_progress'])
                ->orderBy('start_time', 'asc')
                ->get();
        }
        
        return view('manager.dashboard', compact('openTournaments', 'myTeams', 'totalPlayers', 'pendingPayments', 'fixtures'));
    }

    public function browseTournaments()
    {
        $manager = Auth::user();

        // Load tournaments that are upcoming OR registration_closed (show full ones too)
        $tournaments = Tournament::whereIn('status', [
                Tournament::STATUS_UPCOMING,
                Tournament::STATUS_REGISTRATION_CLOSED,
            ])
            ->withCount([
                'registrations as confirmed_count' => function ($q) {
                    $q->where('status', \App\Models\TournamentRegistration::STATUS_CONFIRMED)
                      ->where('payment_status', \App\Models\TournamentRegistration::PAYMENT_PAID)
                      ->distinct('team_id');
                },
            ])
            ->orderByRaw('COALESCE(start_date, tournament_date) ASC')
            ->get();

        // Get this manager's team and which tournaments they've already registered for
        $myTeam = \App\Models\Team::where('manager_id', $manager->id)->first();

        $myRegistrations = $myTeam
            ? \App\Models\TournamentRegistration::where('team_id', $myTeam->id)
                ->whereIn('status', [
                    \App\Models\TournamentRegistration::STATUS_REGISTERING,
                    \App\Models\TournamentRegistration::STATUS_CONFIRMED,
                ])
                ->get()
                ->groupBy('tournament_id')
            : collect();

        return view('manager.browse-tournaments', compact('tournaments', 'myTeam', 'myRegistrations'));
    }

    public function myApplications()
    {
        $manager = Auth::user();
        
        // Get all registrations for this manager with tournament and team details
        $registrations = \App\Models\TournamentRegistration::with(['tournament', 'team'])
            ->where('manager_id', $manager->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('manager.my-applications', compact('registrations'));
    }

    public function paymentHistory()
    {
        $manager = Auth::user();
        
        // Get only paid registrations for payment history
        $payments = \App\Models\TournamentRegistration::with(['tournament', 'team'])
            ->where('manager_id', $manager->id)
            ->where('payment_status', \App\Models\TournamentRegistration::PAYMENT_PAID)
            ->orderBy('registered_at', 'desc')
            ->get();
        
        return view('manager.payment-history', compact('payments'));
    }

    public function schedule()
    {
        $manager = Auth::user();
        
        // Get all teams managed by this manager
        $managerTeams = \App\Models\Team::where('manager_id', $manager->id)->get();
        $managerTeamIds = $managerTeams->pluck('id');
        
        // Get all fixtures involving these teams, ordered by start time
        $fixtures = \App\Models\Fixture::with(['tournament', 'pool', 'homeTeam', 'awayTeam'])
            ->where(function ($query) use ($managerTeamIds) {
                $query->whereIn('home_team_id', $managerTeamIds)
                      ->orWhereIn('away_team_id', $managerTeamIds);
            })
            ->where('status', '!=', 'draft')
            ->orderBy('start_time', 'asc')
            ->get();

        // Get all tournaments where manager's teams are registered (for pool composition)
        $registeredTournamentIds = \App\Models\TournamentRegistration::whereIn('team_id', $managerTeamIds)
            ->pluck('tournament_id')
            ->unique();

        $tournamentPools = \App\Models\Tournament::with(['pools.registrations.team'])
            ->whereIn('id', $registeredTournamentIds)
            ->get();
            
        return view('manager.schedule', compact('fixtures', 'managerTeamIds', 'managerTeams', 'tournamentPools'));
    }

    public function teamDetail($id)
    {
        $manager = Auth::user();
        
        // Get team with relationships
        $team = Team::with(['players', 'manager'])
            ->where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();
        
        // Get tournament registration for this team
        $registration = \App\Models\TournamentRegistration::with('tournament')
            ->where('team_id', $id)
            ->where('manager_id', $manager->id)
            ->first();
        
        // Get max player limit (default 23 for rugby 7s)
        $maxPlayers = $registration && $registration->tournament 
            ? ($registration->tournament->max_players_per_team ?? 23) 
            : 23;

        // Get fixtures involving this team
        $fixtures = \App\Models\Fixture::with(['tournament', 'pool', 'homeTeam', 'awayTeam'])
            ->where(function ($query) use ($id) {
                $query->where('home_team_id', $id)
                      ->orWhere('away_team_id', $id);
            })
            ->where('status', '!=', 'draft')
            ->orderBy('start_time', 'asc')
            ->get();
        
        return view('manager.teams.detail', compact('team', 'registration', 'maxPlayers', 'fixtures'));
    }

    public function updateTeamInfo(Request $request, $id)
    {
        $manager = Auth::user();
        
        $team = Team::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();
        
        $request->validate([
            'head_coach' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('team-logos', 'public');
            $team->logo = $logoPath;
        }
        
        $team->head_coach = $request->head_coach;
        $team->save();
        
        return redirect()->route('manager.teams.detail', $id)
            ->with('success', 'Team information updated successfully!');
    }

    public function addPlayer(Request $request, $id)
    {
        $manager = Auth::user();
        
        $team = Team::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();
        
        // Check max players limit
        $registration = \App\Models\TournamentRegistration::with('tournament')
            ->where('team_id', $id)
            ->first();
        
        $maxPlayers = $registration && $registration->tournament 
            ? ($registration->tournament->max_players_per_team ?? 23) 
            : 23;
        
        if ($team->players()->count() >= $maxPlayers) {
            return response()->json([
                'success' => false,
                'message' => 'Maximum player limit reached!'
            ], 422);
        }
        
        $request->validate([
            'jersey_number' => 'required|integer|min:1|max:99',
            'name' => 'required|string|max:255',
            'position' => 'required|in:forward,back',
            'ic_number' => 'required|string|max:50',
        ]);
        
        // Check duplicate jersey number
        $existingPlayer = $team->players()->where('jersey_number', $request->jersey_number)->first();
        if ($existingPlayer) {
            return response()->json([
                'success' => false,
                'message' => 'Jersey number already assigned!'
            ], 422);
        }
        
        $player = new \App\Models\Player();
        $player->team_id = $team->id;
        $player->jersey_number = $request->jersey_number;
        $player->name = $request->name;
        $player->position = $request->position;
        $player->ic_number = $request->ic_number;
        $player->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Player added successfully!',
            'player' => $player
        ]);
    }

    public function removePlayer($id, $playerId)
    {
        $manager = Auth::user();
        
        $team = Team::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();
        
        $player = \App\Models\Player::where('id', $playerId)
            ->where('team_id', $team->id)
            ->firstOrFail();
        
        $player->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Player removed successfully!'
        ]);
    }
}
