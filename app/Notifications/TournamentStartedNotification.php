<?php

namespace App\Notifications;

use App\Models\Tournament;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TournamentStartedNotification extends Notification
{
    use Queueable;

    protected Tournament $tournament;

    public function __construct(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Tournament is LIVE! ⚡',
            'message' => 'The "' . $this->tournament->name . '" tournament has officially started! Check out live brackets and matches.',
            'icon' => 'fas fa-broadcast-tower',
            'color' => '#ef4444', // Red
            'url' => route('shared.brackets', ['tournament_id' => $this->tournament->id]),
        ];
    }
}
