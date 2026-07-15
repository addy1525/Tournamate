<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\SafetyLog;

class WelcomeController extends Controller
{
    /**
     * Show the application landing page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all ongoing and upcoming tournaments
        $tournaments = Tournament::whereIn('status', ['ongoing', 'upcoming'])
            ->orderBy('start_date', 'asc')
            ->get();

        // Get the latest weather safety log
        $safetyLog = SafetyLog::latest()->first();

        return view('welcome', compact('tournaments', 'safetyLog'));
    }
}
