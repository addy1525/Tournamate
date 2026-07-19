<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Team;
use App\Models\Player;
use App\Models\TournamentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class TournamentRegistrationController extends Controller
{
    /**
     * Show the registration wizard for a tournament
     */
    public function create($tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);

        // Check if tournament is open for registration (status + deadline + capacity)
        if (!$tournament->isRegistrationOpen()) {
            $reason = 'This tournament is not open for registration.';
            if ($tournament->isFull()) {
                $reason = 'Sorry, this tournament is already full.';
            } elseif ($tournament->status === Tournament::STATUS_REGISTRATION_CLOSED) {
                $reason = 'Registration for this tournament has been closed.';
            } elseif ($tournament->status === Tournament::STATUS_CANCELLED) {
                $reason = 'This tournament has been cancelled.';
            } elseif ($tournament->registration_deadline && now()->isAfter($tournament->registration_deadline)) {
                $reason = 'The registration deadline for this tournament has passed.';
            }
            return redirect()->route('manager.browse-tournaments')
                ->with('error', $reason);
        }

        // One manager = one team rule
        $manager  = Auth::user();
        $myTeam   = Team::where('manager_id', $manager->id)->first();

        return view('manager.tournaments.register', compact('tournament', 'myTeam'));
    }

    /**
     * Store team and players data (Phase 1 & 2)
     */
    public function store(Request $request, $tournamentId)
    {
        $validator = Validator::make($request->all(), [
            'team_name' => 'required|string|max:255',
            'head_coach' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'team_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'players' => 'nullable|array',
            'players.*.name' => 'nullable|string|max:255',
            'players.*.position' => 'nullable|in:forward,back',
            'registered_category' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $tournament = Tournament::findOrFail($tournamentId);
            $manager   = Auth::user();

            // ── Guard 1: Registration still open? (race-condition safe) ──────
            if (!$tournament->isRegistrationOpen()) {
                return response()->json([
                    'success' => false,
                    'message' => $tournament->isFull()
                        ? 'Sorry, this tournament just became full. Please try another tournament.'
                        : 'Registration for this tournament is no longer open.',
                ], 409);
            }

            // ── One Manager = One Team rule ───────────────────────────────────
            $team = Team::where('manager_id', $manager->id)->first();

            if ($team) {
                // Update existing team details
                $team->update([
                    'name'           => $request->team_name,
                    'manager_name'   => $request->head_coach,
                    'phone_number'   => $request->phone_number,
                    'payment_status' => $team->payment_status ?? Team::PAYMENT_STATUS_UNPAID,
                ]);
            } else {
                // Create brand-new team for this manager
                $team = Team::create([
                    'name'           => $request->team_name,
                    'manager_id'     => $manager->id,
                    'manager_name'   => $request->head_coach,
                    'phone_number'   => $request->phone_number,
                    'payment_status' => Team::PAYMENT_STATUS_UNPAID,
                ]);
            }

            // Handle team logo upload
            if ($request->hasFile('team_logo')) {
                $logoPath = $request->file('team_logo')->store('team-logos', 'public');
                $team->logo = $logoPath;
                $team->save();
            }

            // ── Guard 2: Duplicate category check ────────────────────────────
            $category  = $request->registered_category;
            $duplicate = TournamentRegistration::where('tournament_id', $tournament->id)
                ->where('team_id', $team->id)
                ->where('registered_category', $category)
                ->whereIn('status', [
                    TournamentRegistration::STATUS_REGISTERING,
                    TournamentRegistration::STATUS_CONFIRMED,
                ])
                ->exists();

            if ($duplicate) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Your team is already registered for the '{$category}' category in this tournament.",
                ], 409);
            }

            // ── Create new registration for this category ─────────────────────
            $registration = TournamentRegistration::create([
                'tournament_id'     => $tournament->id,
                'team_id'           => $team->id,
                'manager_id'        => $manager->id,
                'registered_category' => $category,
                'status'            => TournamentRegistration::STATUS_REGISTERING,
                'payment_status'    => TournamentRegistration::PAYMENT_PENDING,
                'amount_paid'       => 0,
            ]);

            DB::commit();

            return response()->json([
                'success'         => true,
                'registration_id' => $registration->id,
                'team_id'         => $team->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create registration: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create Stripe Checkout Session
     */
    public function createCheckoutSession(Request $request, $tournamentId)
    {
        try {
            $tournament = Tournament::findOrFail($tournamentId);
            $registrationId = $request->registration_id;
            
            $registration = TournamentRegistration::findOrFail($registrationId);

            // Validate tournament fee
            $fee = $tournament->fee ?? 250; // Default to RM 250 if not set
            if ($fee < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tournament fee is below minimum charge amount (RM 2.00)'
                ], 400);
            }

            // Set Stripe API key
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            // Create Checkout Session supporting Cards and FPX
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card', 'fpx'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'myr',
                        'product_data' => [
                            'name' => 'Tournament Registration Fee: ' . $tournament->name,
                            'description' => 'Registration fee for team: ' . $registration->team->name,
                        ],
                        'unit_amount' => $fee * 100, // Cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('manager.registrations.show', $registration->id) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('manager.tournaments.register', $tournament->id),
                'metadata' => [
                    'tournament_id' => $tournament->id,
                    'registration_id' => $registration->id,
                    'team_id' => $registration->team_id,
                ],
            ]);

            // Save Stripe Session ID for tracking
            $registration->update([
                'payment_intent_id' => $session->id,
            ]);

            return response()->json([
                'success' => true,
                'id' => $session->id,
                'url' => $session->url,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create checkout session: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show registration ticket (confirmation page)
     */
    public function show(Request $request, $registrationId)
    {
        $registration = TournamentRegistration::with(['tournament', 'team.players', 'manager'])
            ->findOrFail($registrationId);

        // Verify the registration belongs to the current user
        if ($registration->manager_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this registration.');
        }

        // Fallback: If Stripe session_id is in query and registration is still pending, verify with Stripe API.
        // This ensures local testing works instantly without requiring a webhook tunnel.
        $sessionId = $request->query('session_id');
        if ($sessionId && $registration->payment_status !== TournamentRegistration::PAYMENT_PAID) {
            try {
                \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                $session = \Stripe\Checkout\Session::retrieve($sessionId);

                if ($session->payment_status === 'paid') {
                    $amountPaid = $session->amount_total / 100; // Convert cents to MYR

                    $registration->update([
                        'status' => TournamentRegistration::STATUS_CONFIRMED,
                        'payment_status' => TournamentRegistration::PAYMENT_PAID,
                        'amount_paid' => $amountPaid,
                        'registered_at' => now(),
                    ]);

                    if ($registration->team) {
                        $registration->team->update([
                            'payment_status' => Team::PAYMENT_STATUS_PAID,
                            'amount_paid' => $amountPaid,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Stripe Checkout Session verification fallback failed: ' . $e->getMessage());
            }
        }

        return view('manager.registrations.ticket', compact('registration'));
    }

    /**
     * Upload offline bank transfer receipt for registration
     */
    public function uploadReceipt(Request $request, $id)
    {
        $request->validate([
            'receipt' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120', // 5MB max
        ]);

        $registration = TournamentRegistration::findOrFail($id);

        if ($registration->manager_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $path = $request->file('receipt')->store('registrations/receipts', 'public');
            
            $registration->update([
                'receipt_path' => $path,
            ]);

            // Notify all admin users
            try {
                $admins = \App\Models\User::where('role', \App\Models\User::ROLE_ADMIN ?? 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new \App\Notifications\PaymentReceiptUploadedNotification($registration));
                }
            } catch (\Exception $notifEx) {
                \Log::error('Failed to notify admins of uploaded receipt: ' . $notifEx->getMessage());
            }

            return redirect()->back()->with('success', 'Receipt uploaded successfully! The organizer/admin will verify your payment shortly.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to upload receipt: ' . $e->getMessage());
        }
    }
}
