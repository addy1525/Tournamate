<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with dynamic data.
     */
    public function index()
    {
        // Participation Overview - Count of active teams (registered for tournaments)
        $activeTeamsCount = \App\Models\Team::has('registrations')->count();
        
        // Count of managers
        $managersCount = \App\Models\User::where('role', \App\Models\User::ROLE_MANAGER)->count();
        
        // Count of pending managers
        $pendingManagersCount = \App\Models\User::where('role', \App\Models\User::ROLE_MANAGER)
            ->where('status', \App\Models\User::STATUS_PENDING)
            ->count();
        
        // Count of tournaments
        $tournamentsCount = \App\Models\Tournament::count();
        
        // Count of referees
        $refereesCount = \App\Models\User::where('role', \App\Models\User::ROLE_REFEREE)->count();
        
        // Count of spectators
        $spectatorsCount = \App\Models\User::where('role', \App\Models\User::ROLE_SPECTATOR)->count();
        
        // Financial Summary (only from teams registered for tournaments)
        $totalRevenue = \App\Models\Team::has('registrations')->sum('amount_paid');
        $paidTeamsCount = \App\Models\Team::has('registrations')->where('payment_status', 'paid')->count();
        $partialTeamsCount = \App\Models\Team::has('registrations')->where('payment_status', 'partial')->count();
        $unpaidTeamsCount = \App\Models\Team::has('registrations')->where('payment_status', 'unpaid')->count();

        // Safety Widget Data
        $latestSafetyLog = \App\Models\SafetyLog::latest()->first();

        // Recent Activity Log - Latest 5 entries
        $recentActivities = \App\Models\Activity::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Latest Tournaments (Fix: was missing in view compact)
        $latestTournaments = \App\Models\Tournament::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Top ELO Rugby Teams (Leaderboard) - only show registered teams
        $topEloTeams = \App\Models\Team::has('registrations')
            ->orderBy('rating', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'activeTeamsCount',
            'managersCount',
            'pendingManagersCount',
            'tournamentsCount',
            'refereesCount',
            'spectatorsCount',
            'recentActivities',
            'totalRevenue',
            'paidTeamsCount',
            'partialTeamsCount',
            'unpaidTeamsCount',
            'latestSafetyLog',
            'latestTournaments',
            'topEloTeams'
        ));
    }
}
