<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ManagerStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $manager;
    public $status;

    /**
     * Create a new message instance.
     */
    public function __construct(User $manager, $status)
    {
        $this->manager = $manager;
        $this->status = $status; // 'approved' or 'rejected'
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = $this->status === 'approved' 
            ? 'Account Approved - Welcome to Tournamate' 
            : 'Account Status Update - Tournamate';

        return $this->subject($subject)
                    ->view('emails.manager-status');
    }
}
