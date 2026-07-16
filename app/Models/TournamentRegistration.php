<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentRegistration extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::saved(function ($registration) {
            $wasJustPaid = $registration->wasRecentlyCreated && $registration->payment_status === self::PAYMENT_PAID;
            $didChangeToPaid = !$registration->wasRecentlyCreated && $registration->isDirty('payment_status') && $registration->payment_status === self::PAYMENT_PAID;

            if ($wasJustPaid || $didChangeToPaid) {
                try {
                    if ($registration->manager) {
                        // 1. Send database/in-app notification
                        $registration->manager->notify(new \App\Notifications\PaymentSuccessfulNotification($registration));

                        // 2. Send email receipt
                        if ($registration->manager->email) {
                            \Illuminate\Support\Facades\Mail::to($registration->manager->email)
                                ->send(new \App\Mail\PaymentReceiptMail($registration));
                        }
                    }
                } catch (\Exception $ex) {
                    \Log::error('Failed to send payment success notification/email from registration saved event: ' . $ex->getMessage());
                }
            }
        });
    }

    protected $fillable = [
        'tournament_id',
        'team_id',
        'pool_id',
        'registered_category',
        'manager_id',
        'status',
        'payment_intent_id',
        'payment_status',
        'amount_paid',
        'registered_at',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'registered_at' => 'datetime',
    ];

    const STATUS_REGISTERING = 'registering';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_FAILED = 'failed';

    /**
     * Get the tournament for this registration.
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get the team for this registration.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the manager (user) for this registration.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function pool()
    {
        return $this->belongsTo(Pool::class, 'pool_id');
    }
}
