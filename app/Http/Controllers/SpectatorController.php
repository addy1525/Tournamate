<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpectatorController extends Controller
{
    public function index(Request $request)
    {
        // Get all configured tournaments for the selector
        $tournaments = \App\Models\Tournament::where(function($query) {
            $query->has('pools')
                  ->orWhereHas('fixtures', function($q) {
                      $q->where('status', '!=', 'draft');
                  })
                  ->orWhereIn('status', ['live', 'completed']);
        })->orderBy('start_date', 'asc')->get();
        
        // Get selected tournament or default to first
        $selectedId = $request->query('tournament_id');
        if ($selectedId) {
            $tournament = \App\Models\Tournament::find($selectedId);
        } else {
            $tournament = \App\Models\Tournament::where(function($query) {
                $query->has('pools')
                      ->orWhereHas('fixtures', function($q) {
                          $q->where('status', '!=', 'draft');
                      })
                      ->orWhereIn('status', ['live', 'completed']);
            })->orderBy('start_date', 'asc')->first();
        }
        
        return view('spectator.dashboard', compact('tournament', 'tournaments'));
    }

    public function standings(Request $request)
    {
        $tournaments = \App\Models\Tournament::where(function($query) {
            $query->has('pools')
                  ->orWhereHas('fixtures', function($q) {
                      $q->where('status', '!=', 'draft');
                  })
                  ->orWhereIn('status', ['live', 'completed']);
        })->orderBy('start_date', 'asc')->get();
        
        $selectedId = $request->query('tournament_id');
        if ($selectedId) {
            $tournament = \App\Models\Tournament::with(['pools.registrations.team', 'registrations.team'])->find($selectedId);
        } else {
            $tournament = \App\Models\Tournament::with(['pools.registrations.team', 'registrations.team'])
                ->where(function($query) {
                    $query->has('pools')
                          ->orWhereHas('fixtures', function($q) {
                              $q->where('status', '!=', 'draft');
                          })
                          ->orWhereIn('status', ['live', 'completed']);
                })->orderBy('start_date', 'asc')->first();
        }
        return view('spectator.standings', compact('tournament', 'tournaments'));
    }

    public function schedule(Request $request)
    {
        $tournaments = \App\Models\Tournament::where(function($query) {
            $query->has('pools')
                  ->orWhereHas('fixtures', function($q) {
                      $q->where('status', '!=', 'draft');
                  })
                  ->orWhereIn('status', ['live', 'completed']);
        })->orderBy('start_date', 'asc')->get();
        
        $selectedId = $request->query('tournament_id');
        if ($selectedId) {
            $tournament = \App\Models\Tournament::find($selectedId);
        } else {
            $tournament = \App\Models\Tournament::where(function($query) {
                $query->has('pools')
                      ->orWhereHas('fixtures', function($q) {
                          $q->where('status', '!=', 'draft');
                      })
                      ->orWhereIn('status', ['live', 'completed']);
            })->orderBy('start_date', 'asc')->first();
        }
        return view('spectator.schedule', compact('tournament', 'tournaments'));
    }

    public function info(Request $request)
    {
        $tournaments = \App\Models\Tournament::where(function($query) {
            $query->has('pools')
                  ->orWhereHas('fixtures', function($q) {
                      $q->where('status', '!=', 'draft');
                  })
                  ->orWhereIn('status', ['live', 'completed']);
        })->orderBy('start_date', 'asc')->get();
        
        $selectedId = $request->query('tournament_id');
        if ($selectedId) {
            $tournament = \App\Models\Tournament::find($selectedId);
        } else {
            $tournament = \App\Models\Tournament::where(function($query) {
                $query->has('pools')
                      ->orWhereHas('fixtures', function($q) {
                          $q->where('status', '!=', 'draft');
                      })
                      ->orWhereIn('status', ['live', 'completed']);
            })->orderBy('start_date', 'asc')->first();
        }
        return view('spectator.info', compact('tournament', 'tournaments'));
    }

    public function liveStream(Request $request)
    {
        $tournaments = \App\Models\Tournament::where(function($query) {
            $query->has('pools')
                  ->orWhereHas('fixtures', function($q) {
                      $q->where('status', '!=', 'draft');
                  })
                  ->orWhereIn('status', ['live', 'completed']);
        })->orderBy('start_date', 'asc')->get();
        
        $selectedId = $request->query('tournament_id');
        if ($selectedId) {
            $tournament = \App\Models\Tournament::find($selectedId);
        } else {
            $tournament = \App\Models\Tournament::where(function($query) {
                $query->has('pools')
                      ->orWhereHas('fixtures', function($q) {
                          $q->where('status', '!=', 'draft');
                      })
                      ->orWhereIn('status', ['live', 'completed']);
            })->orderBy('start_date', 'asc')->first();
        }
        return view('spectator.live-stream', compact('tournament', 'tournaments'));
    }
}
