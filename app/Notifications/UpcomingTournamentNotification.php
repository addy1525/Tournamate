<?php

namespace App\Notifications;

use App\Models\Tournament;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UpcomingTournamentNotification extends Notification
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
            'title' => 'New Tournament Open! 🏆',
            'message' => 'The tournament "' . $this->tournament->name . '" is now open for team registrations.',
            'icon' => 'fas fa-trophy',
            'color' => 'var(--color-electric-blue)',
            'url' => route('manager.browse-tournaments'),
        ];
    }
}
