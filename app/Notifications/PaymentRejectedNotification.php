<?php

namespace App\Notifications;

use App\Models\Tournament;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentRejectedNotification extends Notification
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
            'title' => '❌ Payment Rejected',
            'message' => "Your manual payment receipt for '{$this->tournament->name}' was rejected. Please upload a valid receipt.",
            'icon' => 'fas fa-times-circle',
            'color' => '#ef4444',
            'url' => route('manager.my-applications'),
        ];
    }
}
