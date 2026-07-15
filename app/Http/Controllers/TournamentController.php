<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::all();
        return view('tournaments.index', compact('tournaments'));
    }

    public function create()
    {
        return view('tournaments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'tournament_date' => 'required|date',
            'venue' => 'required',
        ]);

        Tournament::create($request->all());

        return redirect()->route('tournaments.index')
                         ->with('success','Tournament created successfully.');
    }

    public function show(Tournament $tournament)
    {
        return view('tournaments.show', compact('tournament'));
    }

    public function edit(Tournament $tournament)
    {
        return view('tournaments.edit', compact('tournament'));
    }

    public function update(Request $request, Tournament $tournament)
    {
        $request->validate([
            'name' => 'required',
            'tournament_date' => 'required|date',
            'venue' => 'required',
        ]);

        $tournament->update($request->all());

        return redirect()->route('tournaments.index')
                         ->with('success','Tournament updated successfully');
    }

    public function destroy(Tournament $tournament)
    {
        $tournament->delete();
        return redirect()->route('tournaments.index')
                         ->with('success','Tournament deleted successfully');
    }
}