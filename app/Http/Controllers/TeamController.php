<?php

namespace App\Http\Controllers;

use App\Models\Team; // Guna Model Team
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        if (auth()->user() && auth()->user()->role === \App\Models\User::ROLE_ADMIN) {
            $teams = Team::all();
        } else {
            $teams = Team::where('manager_id', auth()->id())->get();
        }
        return view('teams.index', compact('teams'));
    }

    public function create()
    {
        return view('teams.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'manager_name' => 'required',
            'phone_number' => 'required',
        ]);

        $data = $request->all();
        $data['manager_id'] = auth()->id();
        
        Team::create($data);

        return redirect()->route('teams.index')
                         ->with('success','Team created successfully.');
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