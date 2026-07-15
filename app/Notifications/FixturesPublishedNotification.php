<?php

namespace App\Notifications;

use App\Models\Tournament;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FixturesPublishedNotification extends Notification
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
            'title' => 'Fixtures Published 📅',
            'message' => 'Match schedules and fixtures for "' . $this->tournament->name . '" are now published. Review your matches.',
            'icon' => 'fas fa-calendar-alt',
            'color' => '#f59e0b', // Amber/Orange
            'url' => route('shared.brackets', ['tournament_id' => $this->tournament->id]),
        ];
    }
}
