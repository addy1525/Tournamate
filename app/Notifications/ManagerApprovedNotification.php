<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ManagerApprovedNotification extends Notification
{
    use Queueable;

    protected User $manager;

    public function __construct(User $manager)
    {
        $this->manager = $manager;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Account Approved 🏉',
            'message' => 'Your team manager account has been approved by the Administrator. You can now register teams for tournaments.',
            'icon' => 'fas fa-user-check',
            'color' => 'var(--color-rugby-green)',
            'url' => route('manager.dashboard'),
        ];
    }
}
