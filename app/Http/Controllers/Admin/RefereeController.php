<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\RefereeCreatedMail;
use Illuminate\Support\Facades\Mail;

class RefereeController extends Controller
{
    /**
     * Display a listing of referees.
     */
    public function index()
    {
        $referees = \App\Models\User::where('role', \App\Models\User::ROLE_REFEREE)->get();
        return view('admin.referees.index', compact('referees'));
    }

    /**
     * Show the form for creating a new referee.
     */
    public function create()
    {
        return view('admin.referees.create');
    }

    /**
     * Store a newly created referee in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role' => \App\Models\User::ROLE_REFEREE,
            'status' => \App\Models\User::STATUS_ACTIVE,
        ]);

        // Send confirmation email via Brevo SMTP
        try {
            Mail::to($validated['email'])->send(new RefereeCreatedMail(
                $validated['name'], 
                $validated['email'], 
                $validated['password']
            ));
        } catch (\Exception $e) {
            // Log the error or fail gracefully so it doesn't block redirection
            report($e);
        }

        return redirect()->route('admin.referees.index')
            ->with('success', 'Referee account created successfully!');
    }

    /**
     * Remove the specified referee from storage.
     */
    public function destroy($id)
    {
        $referee = \App\Models\User::findOrFail($id);
        
        if (!$referee->isReferee()) {
            return redirect()->back()->with('error', 'Invalid user type.');
        }

        $referee->delete();

        return redirect()->route('admin.referees.index')
            ->with('success', 'Referee account deleted successfully!');
    }
}
