<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\ManagerStatusMail;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,referee,spectator',
            'status' => 'required|in:active,pending,inactive',
            'phone_number' => 'nullable|string|max:20',
        ]);

        // Hash password
        $validated['password'] = bcrypt($validated['password']);

        // Create user
        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,referee,spectator',
            'status' => 'required|in:active,pending,inactive',
            'phone_number' => 'nullable|string|max:20',
        ]);

        // Only update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Update user
        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    public function approve($id)
    {
        $manager = User::findOrFail($id);

        // Check if user is a manager
        if ($manager->role !== 'manager') {
            return redirect()->back()->with('error', 'Invalid user type.');
        }

        // Check if already approved
        if (($manager->status ?? 'active') === 'active') {
            return redirect()->back()->with('info', 'Manager is already approved.');
        }

        // Update status to active
        $manager->status = 'active';
        $manager->save();

        // Send status email via Brevo SMTP & In-app Notification
        try {
            Mail::to($manager->email)->send(new ManagerStatusMail($manager, 'approved'));
            $manager->notify(new \App\Notifications\ManagerApprovedNotification($manager));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send manager approved email/notification: ' . $e->getMessage());
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
        $manager = User::findOrFail($id);

        if ($manager->role !== 'manager') {
            return redirect()->back()->with('error', 'Invalid user type.');
        }

        $manager->status = 'inactive';
        $manager->save();

        // Send status email via Brevo SMTP
        try {
            Mail::to($manager->email)->send(new ManagerStatusMail($manager, 'rejected'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send manager rejected email: ' . $e->getMessage());
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
