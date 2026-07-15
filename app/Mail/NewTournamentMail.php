<?php

namespace App\Mail;

use App\Models\Tournament;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewTournamentMail extends Mailable
{
    use Queueable, SerializesModels;

    public Tournament $tournament;

    public function __construct(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    public function build(): self
    {
        return $this
            ->subject('🏉 New Tournament Open – ' . $this->tournament->name . ' · Tournamate')
            ->view('emails.new-tournament');
    }
}
