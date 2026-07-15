<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Mail\PaymentStatusMail;
use Illuminate\Support\Facades\Mail;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with('manager')->orderBy('created_at', 'desc')->get();
        return view('admin.teams.index', compact('teams'));
    }

    public function create()
    {
        $managers = User::where('role', User::ROLE_MANAGER)->get();
        return view('admin.teams.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'manager_id' => 'required|exists:users,id',
            'phone_number' => 'required|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('teams/logos', 'public');
            $data['logo'] = $path;
        }

        // Fetch manager name for legacy support
        $manager = User::find($request->manager_id);
        $data['manager_name'] = $manager->name;

        Team::create($data);

        return redirect()->route('admin.teams.index')->with('success', 'Team created successfully.');
    }

    public function edit($id)
    {
        $team = Team::findOrFail($id);
        $managers = User::where('role', User::ROLE_MANAGER)->get();
        return view('admin.teams.edit', compact('team', 'managers'));
    }

    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);
        $oldStatus = $team->payment_status;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'manager_id' => 'required|exists:users,id',
            'phone_number' => 'required|string',
            'logo' => 'nullable|image|max:2048',
            'payment_status' => 'in:unpaid,partial,paid',
            'amount_paid' => 'numeric|min:0',
        ]);

        $data = $request->except('logo', 'receipt');

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('teams/logos', 'public');
            $data['logo'] = $path;
        }

        $manager = User::find($request->manager_id);
        $data['manager_name'] = $manager->name;

        $team->update($data);

        // Send status email to manager if payment status changed
        if (isset($data['payment_status']) && $oldStatus !== $data['payment_status'] && $team->manager) {
            try {
                Mail::to($team->manager->email)->send(new PaymentStatusMail($team));
            } catch (\Exception $e) {
                report($e);
            }
        }

        return redirect()->route('admin.teams.index')->with('success', 'Team updated successfully.');
    }

    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        $team->delete();
        return redirect()->route('admin.teams.index')->with('success', 'Team deleted successfully.');
    }

    public function uploadReceipt(Request $request, $id)
    {
        $request->validate([
            'receipt' => 'required|file|max:5120', // 5MB max
        ]);

        $team = Team::findOrFail($id);
        
        $path = $request->file('receipt')->store('teams/receipts', 'public');
        $team->update(['receipt_path' => $path]);

        return redirect()->back()->with('success', 'Receipt uploaded successfully.');
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:unpaid,partial,paid',
            'amount_paid' => 'nullable|numeric|min:0',
        ]);

        $team = Team::findOrFail($id);
        $oldStatus = $team->payment_status;

        $team->update([
            'payment_status' => $request->payment_status,
            'amount_paid' => $request->amount_paid ?? $team->amount_paid
        ]);

        // Send status email to manager if payment status changed
        if ($oldStatus !== $request->payment_status && $team->manager) {
            try {
                Mail::to($team->manager->email)->send(new PaymentStatusMail($team));
            } catch (\Exception $e) {
                report($e);
            }
        }

        return redirect()->back()->with('success', 'Payment status updated.');
    }
}
