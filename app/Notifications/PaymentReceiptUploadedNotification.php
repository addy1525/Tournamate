<?php

namespace App\Notifications;

use App\Models\TournamentRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentReceiptUploadedNotification extends Notification
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
        $teamName = $this->registration->team->name ?? 'A Team';
        $tournamentName = $this->registration->tournament->name ?? 'Tournament';
        
        return [
            'title' => '📥 Payment Receipt Uploaded',
            'message' => "{$teamName} has uploaded a payment receipt for {$tournamentName}. Please verify it.",
            'icon' => 'fas fa-file-invoice-dollar',
            'color' => '#3b82f6',
            'url' => route('admin.tournaments.registrations', $this->registration->tournament_id),
        ];
    }
}
