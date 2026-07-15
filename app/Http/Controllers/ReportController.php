<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // Participation Overview
        $activeTeamsCount = \App\Models\Team::count();
        
        // Financial Summary
        $totalRevenue = \App\Models\Team::sum('amount_paid');
        
        // Recent Activity Log
        // Assuming Activity model exists as seen in AdminDashboardController
        $recentActivities = \App\Models\Activity::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('reports.index', compact('activeTeamsCount', 'totalRevenue', 'recentActivities'));
    }
}
