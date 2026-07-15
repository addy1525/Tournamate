<?php

namespace App\Notifications;

use App\Models\SafetyLog;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SafetyWarningNotification extends Notification
{
    use Queueable;

    protected SafetyLog $safetyLog;

    public function __construct(SafetyLog $safetyLog)
    {
        $this->safetyLog = $safetyLog;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $status = $this->safetyLog->status ?? 'warning';
        $icon = $status === 'danger' ? 'fas fa-exclamation-triangle' : 'fas fa-cloud-sun-rain';
        $color = $status === 'danger' ? '#ef4444' : '#f59e0b';
        $title = $status === 'danger' ? '⚠️ CRITICAL Safety Alert' : '⚠️ Weather Alert';

        return [
            'title' => $title,
            'message' => 'Safety update: ' . ($this->safetyLog->remarks ?? 'Weather conditions updated. Please check the safety logs.'),
            'icon' => $icon,
            'color' => $color,
            'url' => route('operations.index'), // Will redirect to safety/operations view
        ];
    }
}
