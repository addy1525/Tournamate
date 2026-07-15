<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = auth()->user();

        // Redirect to role-specific dashboard routes to ensure controllers are hit
        switch ($user->role ?? 'spectator') {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'manager':
                return redirect()->route('manager.dashboard');
            case 'referee':
                return redirect()->route('referee.console');
            case 'spectator':
            default:
                return redirect()->route('spectator.dashboard');
        }
    }
}
