<?php

namespace App\Notifications;

use App\Models\TournamentRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentSuccessfulNotification extends Notification
{
    use Queueable;

    protected TournamentRegistration $registration;

    public function __construct(TournamentRegistration $registration)
    {
        $this->registration = $registration;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $amount = number_format($this->registration->amount_paid, 2);
        $teamName = $this->registration->team->name ?? 'your team';
        $tournamentName = $this->registration->tournament->name ?? 'Tournament';

        return [
            'title' => 'Payment Successful! 💳',
            'message' => 'Payment of RM ' . $amount . ' for "' . $teamName . '" in "' . $tournamentName . '" has been verified.',
            'icon' => 'fas fa-credit-card',
            'color' => 'var(--color-rugby-green)',
            'url' => route('manager.my-applications'),
        ];
    }
}
