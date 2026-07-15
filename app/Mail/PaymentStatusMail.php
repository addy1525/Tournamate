<?php

namespace App\Mail;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $team;

    /**
     * Create a new message instance.
     */
    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $statusLabel = strtoupper($this->team->payment_status);
        return $this->subject("Payment Status Updated [{$statusLabel}] - {$this->team->name}")
                    ->view('emails.payment-status');
    }
}
