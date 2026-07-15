<?php

namespace App\Mail;

use App\Models\TournamentRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public TournamentRegistration $registration;

    public function __construct(TournamentRegistration $registration)
    {
        $this->registration = $registration;
    }

    public function build(): self
    {
        return $this
            ->subject('✅ Payment Confirmed – ' . $this->registration->tournament->name . ' · Tournamate')
            ->view('emails.payment-receipt');
    }
}
