<?php

namespace App\Http\Controllers;

use App\Models\Team; // Guna Model Team
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        if (auth()->user() && auth()->user()->role === \App\Models\User::ROLE_ADMIN) {
            // For Admin: Get all tournament registrations with relations
            $registrations = \App\Models\TournamentRegistration::with(['team', 'tournament', 'manager'])
                ->orderBy('created_at', 'desc')
                ->get();
            return view('teams.index', compact('registrations'));
        } else {
            // For Manager: Get their team profiles
            $teams = Team::where('manager_id', auth()->id())->get();
            return view('teams.index', compact('teams'));
        }
    }

    public function create()
    {
        $managers = \App\Models\User::where('role', \App\Models\User::ROLE_MANAGER)->get();
        $tournaments = \App\Models\Tournament::whereIn('status', [
            \App\Models\Tournament::STATUS_UPCOMING,
            \App\Models\Tournament::STATUS_ONGOING
        ])->get();
        
        if ($tournaments->isEmpty()) {
            $tournaments = \App\Models\Tournament::all();
        }

        return view('teams.create', compact('managers', 'tournaments'));
    }

    public function store(Request $request)
    {
        $isAdmin = auth()->user() && auth()->user()->role === \App\Models\User::ROLE_ADMIN;

        if ($isAdmin) {
            $request->validate([
                'name' => 'required|string|max:255',
                'manager_id' => 'required|exists:users,id',
                'phone_number' => 'required|string',
                'tournament_id' => 'required|exists:tournaments,id',
                'registered_category' => 'required|string|max:255',
                'payment_status' => 'required|in:pending,paid',
                'amount_paid' => 'nullable|numeric|min:0',
            ]);

            $manager = \App\Models\User::findOrFail($request->manager_id);
            $tournament = \App\Models\Tournament::findOrFail($request->tournament_id);
            $fee = $tournament->fee ?? 250;

            $isPaid = $request->payment_status === 'paid';
            $amountPaid = $isPaid ? ($request->amount_paid ?? $fee) : ($request->amount_paid ?? 0);

            // Create team record
            $team = Team::create([
                'name' => $request->name,
                'manager_id' => $manager->id,
                'manager_name' => $manager->name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'payment_status' => $isPaid ? Team::PAYMENT_STATUS_PAID : Team::PAYMENT_STATUS_UNPAID,
                'amount_paid' => $amountPaid,
            ]);

            // Register team into selected tournament
            \App\Models\TournamentRegistration::create([
                'tournament_id' => $tournament->id,
                'team_id' => $team->id,
                'manager_id' => $manager->id,
                'registered_category' => $request->registered_category,
                'status' => $isPaid ? \App\Models\TournamentRegistration::STATUS_CONFIRMED : \App\Models\TournamentRegistration::STATUS_REGISTERING,
                'payment_status' => $isPaid ? \App\Models\TournamentRegistration::PAYMENT_PAID : \App\Models\TournamentRegistration::PAYMENT_PENDING,
                'amount_paid' => $amountPaid,
                'registered_at' => now(),
            ]);

            return redirect()->route('teams.index')
                             ->with('success', "Team '{$team->name}' created and registered for {$tournament->name} successfully.");
        } else {
            $request->validate([
                'name' => 'required|string|max:255',
                'manager_name' => 'required|string|max:255',
                'phone_number' => 'required|string',
            ]);

            $data = $request->all();
            $data['manager_id'] = auth()->id();
            
            Team::create($data);

            return redirect()->route('teams.index')
                             ->with('success', 'Team created successfully.');
        }
    }

    public function show(Team $team)
    {
        return view('teams.show', compact('team'));
    }

    public function edit(Team $team)
    {
        return view('teams.edit', compact('team'));
    }

    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required',
            'manager_name' => 'required',
            'phone_number' => 'required',
        ]);

        $team->update($request->all());

        return redirect()->route('teams.index')
                         ->with('success','Team updated successfully');
    }

    public function destroy(Team $team)
    {
        $team->delete();

        return redirect()->route('teams.index')
                         ->with('success','Team deleted successfully');
    }
}