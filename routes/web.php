<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ✅ IMPORT CONTROLLER BARU
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\TeamController;       // Untuk Manage Team
use App\Http\Controllers\TournamentController; // Untuk Manage Tournament
use App\Http\Controllers\UserController;       // Untuk Manage Users
use App\Http\Controllers\OperationsController; // Untuk Operations
use App\Http\Controllers\MediaController;      // Untuk Media
use App\Http\Controllers\ReportController;     // Untuk Reports
use App\Http\Controllers\SettingsController;   // Untuk Settings
use App\Http\Controllers\RefereeController;    // Untuk Referee
use App\Http\Controllers\ManagerController;    // Untuk Manager
use App\Http\Controllers\SpectatorController;  // Untuk Spectator

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');


// Role-Specific Login Pages
Route::get('/login/admin', function () {
    return view('auth.login-admin');
})->name('login.admin');

Route::get('/login/manager', function () {
    return view('auth.login-manager');
})->name('login.manager');

Route::get('/login/referee', function () {
    return view('auth.login-referee');
})->name('login.referee');

Route::get('/login/spectator', function () {
    return view('auth.login-spectator');
})->name('login.spectator');

// Route untuk Login/Register/Logout
Auth::routes();

// Route untuk Dashboard
Route::get('/home', [HomeController::class, 'index'])->name('home');

// ✅ GROUP ROUTE (Wajib Login baru boleh akses)
Route::middleware(['auth'])->group(function () {

    // Route untuk module Team
    Route::resource('teams', TeamController::class);

    // Route untuk module Tournament
    Route::resource('tournaments', TournamentController::class);

    // Route untuk User Management
    Route::resource('users', UserController::class);
    
    // Manager Approval Routes (for /users page)
    Route::post('/users/{id}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{id}/reject', [UserController::class, 'reject'])->name('users.reject');

    // Route untuk Operations Dashboard
    Route::get('/operations', [OperationsController::class, 'index'])->name('operations.index');

    // Route untuk Media Dashboard
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');

    // Route untuk Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Route untuk Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');

    // Notifications Module
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

});

// ============================================
// ROLE-BASED ROUTES WITH MIDDLEWARE PROTECTION
// ============================================

// Pending Approval Page (for managers awaiting approval)
Route::get('/pending-approval', function () {
    return view('auth.pending-approval');
})->middleware('auth')->name('pending.approval');

// Admin Routes - Only accessible by Admin role
Route::prefix('admin')->name('admin.')->middleware(['auth', App\Http\Middleware\AdminMiddleware::class])->group(function () {
    // Dashboard
    Route::get('/debug', function() { return 'DEBUG OK - Admin Middleware Works'; });
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    
    // User Management (Managers)
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/approve', [App\Http\Controllers\Admin\UserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{id}/reject', [App\Http\Controllers\Admin\UserController::class, 'reject'])->name('users.reject');
    
    // Referee Management
    Route::get('/referees', [App\Http\Controllers\Admin\RefereeController::class, 'index'])->name('referees.index');
    Route::get('/referees/create', [App\Http\Controllers\Admin\RefereeController::class, 'create'])->name('referees.create');
    Route::post('/referees', [App\Http\Controllers\Admin\RefereeController::class, 'store'])->name('referees.store');
    Route::delete('/referees/{id}', [App\Http\Controllers\Admin\RefereeController::class, 'destroy'])->name('referees.destroy');
    
    // Tournament Management
    Route::get('/tournaments', [App\Http\Controllers\Admin\TournamentController::class, 'index'])->name('tournaments.index');
    Route::get('/tournaments/create', [App\Http\Controllers\Admin\TournamentController::class, 'create'])->name('tournaments.create');
    Route::post('/tournaments', [App\Http\Controllers\Admin\TournamentController::class, 'store'])->name('tournaments.store');
    Route::get('/tournaments/{id}/edit', [App\Http\Controllers\Admin\TournamentController::class, 'edit'])->name('tournaments.edit');
    Route::put('/tournaments/{id}', [App\Http\Controllers\Admin\TournamentController::class, 'update'])->name('tournaments.update');
    Route::delete('/tournaments/{id}', [App\Http\Controllers\Admin\TournamentController::class, 'destroy'])->name('tournaments.destroy');
    Route::get('/tournaments/{id}/assign-teams', [App\Http\Controllers\Admin\TournamentController::class, 'assignTeams'])->name('tournaments.assignTeams');
    Route::post('/tournaments/{id}/teams', [App\Http\Controllers\Admin\TournamentController::class, 'updateTeams'])->name('tournaments.updateTeams');
    Route::get('/tournaments/{id}/matches', [App\Http\Controllers\Admin\TournamentController::class, 'matchManagement'])->name('tournaments.matches');
    Route::post('/tournaments/{id}/pools', [App\Http\Controllers\Admin\TournamentController::class, 'storePools'])->name('tournaments.storePools');
    Route::post('/tournaments/{id}/auto-pools', [App\Http\Controllers\Admin\TournamentController::class, 'autoGeneratePools'])->name('tournaments.autoPools');
    Route::post('/tournaments/{id}/clear-pools', [App\Http\Controllers\Admin\TournamentController::class, 'clearPools'])->name('tournaments.clearPools');
    Route::post('/tournaments/{id}/generate-fixtures', [App\Http\Controllers\Admin\TournamentController::class, 'generateFixtures'])->name('tournaments.generateFixtures');
    Route::get('/tournaments/{id}/preview-fixtures', [App\Http\Controllers\Admin\TournamentController::class, 'previewFixtures'])->name('tournaments.previewFixtures');
    Route::post('/tournaments/{id}/generate-knockouts', [App\Http\Controllers\Admin\TournamentController::class, 'generateKnockouts'])->name('tournaments.generateKnockouts');
    Route::post('/tournaments/{id}/publish-fixtures', [App\Http\Controllers\Admin\TournamentController::class, 'publishFixtures'])->name('tournaments.publishFixtures');
    Route::post('/tournaments/{id}/clear-fixtures', [App\Http\Controllers\Admin\TournamentController::class, 'clearFixtures'])->name('tournaments.clearFixtures');
    Route::delete('/tournaments/{id}/fixtures/{fixtureId}', [App\Http\Controllers\Admin\TournamentController::class, 'deleteFixture'])->name('tournaments.deleteFixture');
    Route::put('/tournaments/{id}/fixtures/{fixtureId}', [App\Http\Controllers\Admin\TournamentController::class, 'updateFixture'])->name('tournaments.updateFixture');
    Route::get('/tournaments/{id}/registrations', [App\Http\Controllers\Admin\TournamentController::class, 'registrations'])->name('tournaments.registrations');
    Route::post('/tournaments/{tournamentId}/registrations/{registrationId}/verify-payment', [App\Http\Controllers\Admin\TournamentController::class, 'verifyPayment'])->name('tournaments.registrations.verify-payment');
    Route::get('/tournaments/{tournamentId}/teams/{teamId}', [App\Http\Controllers\Admin\TournamentController::class, 'teamRoster'])->name('tournaments.teams.roster');
    Route::get('/teams', [App\Http\Controllers\Admin\TournamentController::class, 'teams'])->name('teams.index'); // Legacy view

    // Team Management (New Module)
    Route::get('/manage-teams', [App\Http\Controllers\Admin\TeamController::class, 'index'])->name('manage-teams.index');
    Route::get('/manage-teams/create', [App\Http\Controllers\Admin\TeamController::class, 'create'])->name('manage-teams.create');
    Route::post('/manage-teams', [App\Http\Controllers\Admin\TeamController::class, 'store'])->name('manage-teams.store');
    Route::get('/manage-teams/{id}/edit', [App\Http\Controllers\Admin\TeamController::class, 'edit'])->name('manage-teams.edit');
    Route::put('/manage-teams/{id}', [App\Http\Controllers\Admin\TeamController::class, 'update'])->name('manage-teams.update');
    Route::delete('/manage-teams/{id}', [App\Http\Controllers\Admin\TeamController::class, 'destroy'])->name('manage-teams.destroy');
    Route::post('/manage-teams/{id}/receipt', [App\Http\Controllers\Admin\TeamController::class, 'uploadReceipt'])->name('manage-teams.uploadReceipt');
    Route::post('/manage-teams/{id}/payment', [App\Http\Controllers\Admin\TeamController::class, 'updatePaymentStatus'])->name('manage-teams.updatePayment');
    
    // Safety Center
    Route::get('/safety', [App\Http\Controllers\Admin\SafetyCenterController::class, 'index'])->name('safety.index');
    Route::post('/safety/logs', [App\Http\Controllers\Admin\SafetyCenterController::class, 'store'])->name('safety.store');
    Route::post('/safety/refresh', [App\Http\Controllers\Admin\SafetyCenterController::class, 'refreshWeatherData'])->name('safety.refresh');
    Route::get('/safety/history', [App\Http\Controllers\Admin\SafetyCenterController::class, 'history'])->name('safety.history');
    
    // Manager Approval (Legacy - keeping for compatibility)
    Route::get('/managers/pending', function () {
        $managers = \App\Models\User::where('role', \App\Models\User::ROLE_MANAGER)
            ->where('status', \App\Models\User::STATUS_PENDING)
            ->get();
        return view('admin.managers.pending', compact('managers'));
    })->name('managers.pending');
    
    Route::post('/managers/{id}/approve', function ($id) {
        $manager = \App\Models\User::findOrFail($id);
        $manager->status = \App\Models\User::STATUS_ACTIVE;
        $manager->save();

        // Send status email via Brevo SMTP
        try {
            \Illuminate\Support\Facades\Mail::to($manager->email)->send(new \App\Mail\ManagerStatusMail($manager, 'approved'));
        } catch (\Exception $e) {
            report($e);
        }

        return redirect()->back()->with('success', 'Manager approved successfully!');
    })->name('managers.approve');

    // Live Stream Management
    Route::get('/live-streams', [App\Http\Controllers\Admin\LiveStreamController::class, 'index'])->name('live-stream.index');
    Route::post('/live-streams', [App\Http\Controllers\Admin\LiveStreamController::class, 'store'])->name('live-stream.store');
    Route::put('/live-streams/{id}', [App\Http\Controllers\Admin\LiveStreamController::class, 'update'])->name('live-stream.update');
    Route::delete('/live-streams/{id}', [App\Http\Controllers\Admin\LiveStreamController::class, 'destroy'])->name('live-stream.destroy');
    Route::patch('/live-streams/{id}/toggle', [App\Http\Controllers\Admin\LiveStreamController::class, 'toggleStatus'])->name('live-stream.toggle');
});

// Manager Routes - Only accessible by Manager role with active status
Route::prefix('manager')->name('manager.')->middleware(['auth', App\Http\Middleware\ManagerMiddleware::class])->group(function () {
    Route::get('/dashboard', [ManagerController::class, 'index'])->name('dashboard');
    Route::get('/browse-tournaments', [ManagerController::class, 'browseTournaments'])->name('browse-tournaments');
    Route::get('/my-applications', [ManagerController::class, 'myApplications'])->name('my-applications');
    Route::get('/payment-history', [ManagerController::class, 'paymentHistory'])->name('payment-history');
    Route::get('/schedule', [ManagerController::class, 'schedule'])->name('schedule');
    
    // Team Management Routes
    Route::get('/teams/{id}', [ManagerController::class, 'teamDetail'])->name('teams.detail');
    Route::post('/teams/{id}/update-info', [ManagerController::class, 'updateTeamInfo'])->name('teams.update-info');
    Route::post('/teams/{id}/add-player', [ManagerController::class, 'addPlayer'])->name('teams.add-player');
    Route::delete('/teams/{id}/players/{playerId}', [ManagerController::class, 'removePlayer'])->name('teams.remove-player');
    
    // Tournament Registration Routes
    Route::get('/tournaments/{id}/register', [App\Http\Controllers\TournamentRegistrationController::class, 'create'])->name('tournaments.register');
    Route::post('/tournaments/{id}/register', [App\Http\Controllers\TournamentRegistrationController::class, 'store'])->name('tournaments.register.store');
    Route::post('/tournaments/{id}/checkout-session', [App\Http\Controllers\TournamentRegistrationController::class, 'createCheckoutSession'])->name('tournaments.checkout-session');
    Route::get('/registrations/{id}', [App\Http\Controllers\TournamentRegistrationController::class, 'show'])->name('registrations.show');
    Route::post('/registrations/{id}/upload-receipt', [App\Http\Controllers\TournamentRegistrationController::class, 'uploadReceipt'])->name('registrations.upload-receipt');
});

// Referee Routes - Only accessible by Referee role
Route::prefix('referee')->name('referee.')->middleware(['auth', App\Http\Middleware\RefereeMiddleware::class])->group(function () {
    Route::get('/console', [RefereeController::class, 'console'])->name('console');
    Route::post('/console/score/{id}', [RefereeController::class, 'updateScore'])->name('score.update');
    Route::post('/console/event/{id}', [RefereeController::class, 'addEvent'])->name('event.add');
    Route::delete('/console/event/{eventId}', [RefereeController::class, 'deleteEvent'])->name('event.delete');
    Route::get('/assignments', [RefereeController::class, 'assignments'])->name('assignments');
    Route::get('/history', [RefereeController::class, 'history'])->name('history');
    Route::get('/safety', [RefereeController::class, 'safety'])->name('safety');
});

// Spectator Routes - Only accessible by Spectator role
Route::prefix('spectator')->name('spectator.')->middleware(['auth', App\Http\Middleware\SpectatorMiddleware::class])->group(function () {
    Route::get('/dashboard', [SpectatorController::class, 'index'])->name('dashboard');
    Route::get('/standings', [SpectatorController::class, 'standings'])->name('standings');
    Route::get('/schedule', [SpectatorController::class, 'schedule'])->name('schedule');
    Route::get('/info', [SpectatorController::class, 'info'])->name('info');
    Route::get('/live-stream', function (Illuminate\Http\Request $request) {
        $tournaments = App\Models\Tournament::orderBy('start_date', 'asc')->get();
        $selectedId = $request->query('tournament_id');
        if ($selectedId) {
            $tournament = App\Models\Tournament::find($selectedId);
        } else {
            $tournament = App\Models\Tournament::orderBy('start_date', 'asc')->first();
        }
        return view('spectator.live-stream', compact('tournament', 'tournaments'));
    })->name('live-stream');
});

// Shared Routes (accessible by multiple roles)
Route::prefix('shared')->name('shared.')->middleware(['auth'])->group(function () {
    Route::get('/brackets', function (Illuminate\Http\Request $request) {
        $tournaments = \App\Models\Tournament::where(function($query) {
            $query->has('pools')
                  ->orWhereHas('fixtures', function($q) {
                      $q->where('status', '!=', 'draft');
                  })
                  ->orWhereIn('status', ['live', 'completed']);
        })->orderBy('start_date', 'desc')->get();
        
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
            })->orderBy('start_date', 'desc')->first();
        }
        return view('shared.brackets', compact('tournament', 'tournaments'));
    })->name('brackets');

    Route::get('/venue-map', function (Illuminate\Http\Request $request) {
        $tournaments = App\Models\Tournament::where(function($query) {
            $query->has('pools')
                  ->orWhereHas('fixtures', function($q) {
                      $q->where('status', '!=', 'draft');
                  })
                  ->orWhereIn('status', ['live', 'completed']);
        })->orderBy('start_date', 'asc')->get();
        
        $selectedId = $request->query('tournament_id');
        if ($selectedId) {
            $tournament = App\Models\Tournament::find($selectedId);
        } else {
            $tournament = App\Models\Tournament::where(function($query) {
                $query->has('pools')
                      ->orWhereHas('fixtures', function($q) {
                          $q->where('status', '!=', 'draft');
                      })
                      ->orWhereIn('status', ['live', 'completed']);
            })->orderBy('start_date', 'asc')->first();
        }
        return view('shared.venue-map', compact('tournament', 'tournaments'));
    })->name('venue-map');

    // Live Stream - boleh diakses semua peranan (Manager, Referee, Spectator, Admin)
    Route::get('/live-stream', function (Illuminate\Http\Request $request) {
        $tournaments = App\Models\Tournament::where(function($query) {
            $query->has('pools')
                  ->orWhereHas('fixtures', function($q) {
                      $q->where('status', '!=', 'draft');
                  })
                  ->orWhereIn('status', ['live', 'completed']);
        })->orderBy('start_date', 'asc')->get();
        
        $selectedId = $request->query('tournament_id');
        if ($selectedId) {
            $tournament = App\Models\Tournament::with('liveStreams')->find($selectedId);
        } else {
            $tournament = App\Models\Tournament::with('liveStreams')->where(function($query) {
                $query->has('pools')
                      ->orWhereHas('fixtures', function($q) {
                          $q->where('status', '!=', 'draft');
                      })
                      ->orWhereIn('status', ['live', 'completed']);
            })->orderBy('start_date', 'asc')->first();
        }
        return view('spectator.live-stream', compact('tournament', 'tournaments'));
    })->name('live-stream');

    // Schedule - boleh diakses semua peranan
    Route::get('/schedule', [App\Http\Controllers\SpectatorController::class, 'schedule'])->name('schedule');

    // Standings - boleh diakses semua peranan
    Route::get('/standings', [App\Http\Controllers\SpectatorController::class, 'standings'])->name('standings');

    // Info - boleh diakses semua peranan
    Route::get('/info', [App\Http\Controllers\SpectatorController::class, 'info'])->name('info');
});

// Stripe Webhook Endpoint (Exempt from CSRF & Auth Middleware)
Route::post('/stripe/webhook', [App\Http\Controllers\StripeWebhookController::class, 'handleWebhook'])->name('stripe.webhook');