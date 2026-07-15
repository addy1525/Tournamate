<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\ManagerStatusMail;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Display a listing of managers.
     */
    public function index()
    {
        // Fetch all users with 'manager' role
        $managers = \App\Models\User::where('role', \App\Models\User::ROLE_MANAGER)
             ->orderBy('created_at', 'desc')
             ->get();

        return view('admin.users.index', compact('managers'));
    }

    /**
     * Approve a pending manager.
     */
    public function approve($id)
    {
        $manager = \App\Models\User::findOrFail($id);

        // Check if user is a manager
        if (!$manager->isManager()) {
            return redirect()->back()->with('error', 'Invalid user type.');
        }

        // Check if already approved
        if ($manager->isActive()) {
            return redirect()->back()->with('info', 'Manager is already approved.');
        }

        // Update status to active
        $manager->status = \App\Models\User::STATUS_ACTIVE;
        $manager->save();

        // Send status email via Brevo SMTP & In-app Notification
        try {
            Mail::to($manager->email)->send(new ManagerStatusMail($manager, 'approved'));
            $manager->notify(new \App\Notifications\ManagerApprovedNotification($manager));
        } catch (\Exception $e) {
            report($e);
        }

        // Log activity
        \App\Models\Activity::create([
            'user_id' => auth()->id(),
            'action' => 'Manager Approved',
            'description' => "Approved manager: {$manager->name} ({$manager->email})",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->back()->with('success', 'Manager approved successfully!');
    }

    /**
     * Reject/Deactivate a manager.
     */
    public function reject($id)
    {
        $manager = \App\Models\User::findOrFail($id);

        if (!$manager->isManager()) {
            return redirect()->back()->with('error', 'Invalid user type.');
        }

        $manager->status = \App\Models\User::STATUS_INACTIVE;
        $manager->save();

        // Send status email via Brevo SMTP
        try {
            Mail::to($manager->email)->send(new ManagerStatusMail($manager, 'rejected'));
        } catch (\Exception $e) {
            report($e);
        }

        // Log activity
        \App\Models\Activity::create([
            'user_id' => auth()->id(),
            'action' => 'Manager Rejected',
            'description' => "Rejected/Deactivated manager: {$manager->name} ({$manager->email})",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->back()->with('success', 'Manager has been deactivated.');
    }
}
