<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Webhook;
use App\Models\TournamentRegistration;
use App\Models\Team;
use App\Mail\PaymentReceiptMail;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    /**
     * Handle incoming Stripe webhook requests.
     */
    public function handleWebhook(Request $request)
    {
        $payload        = $request->getContent();
        $sigHeader      = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            if ($endpointSecret) {
                $event = Webhook::constructEvent(
                    $payload, $sigHeader, $endpointSecret
                );
            } else {
                // Bypass signature check if not configured (useful for local development)
                $event = \Stripe\Event::constructFrom(
                    json_decode($payload, true)
                );
            }
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe Webhook error: Invalid payload ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe Webhook error: Invalid signature ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Process successful checkout session completion
        if ($event->type === 'checkout.session.completed') {
            $session        = $event->data->object;
            $registrationId = $session->metadata->registration_id ?? null;

            if ($registrationId) {
                $registration = TournamentRegistration::with(['team', 'tournament', 'manager'])
                    ->find($registrationId);

                if ($registration) {
                    $amountPaid = $session->amount_total / 100; // Stripe price in cents

                    $registration->update([
                        'status'            => TournamentRegistration::STATUS_CONFIRMED,
                        'payment_status'    => TournamentRegistration::PAYMENT_PAID,
                        'amount_paid'       => $amountPaid,
                        'payment_intent_id' => $session->id,
                        'registered_at'     => now(),
                    ]);

                    if ($registration->team) {
                        $registration->team->update([
                            'payment_status' => Team::PAYMENT_STATUS_PAID,
                            'amount_paid'    => $amountPaid,
                        ]);
                    }

                    Log::info("Stripe Webhook: Registration #{$registrationId} updated to CONFIRMED & PAID.");
                } else {
                    Log::error("Stripe Webhook: Registration #{$registrationId} not found in database.");
                }
            } else {
                Log::error("Stripe Webhook: Session metadata is missing registration_id.");
            }
        }

        return response()->json(['status' => 'success']);
    }
}

