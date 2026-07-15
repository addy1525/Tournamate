<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'venue_name',
        'venue',
        'location_coordinates',
        'start_date',
        'end_date',
        'tournament_date',
        'status',
        'description',
        'categories',
        'fee',
        'max_teams',
        'registration_deadline',
    ];

    protected $casts = [
        'start_date'            => 'date',
        'end_date'              => 'date',
        'tournament_date'       => 'date',
        'registration_deadline' => 'datetime',
    ];

    // ─── Status Constants ───────────────────────────────────────────────
    const STATUS_UPCOMING             = 'upcoming';
    const STATUS_ONGOING              = 'ongoing';
    const STATUS_COMPLETED            = 'completed';
    const STATUS_REGISTRATION_CLOSED  = 'registration_closed';
    const STATUS_CANCELLED            = 'cancelled';

    // ─── Relationships ───────────────────────────────────────────────────
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'tournament_team');
    }

    public function registrations()
    {
        return $this->hasMany(TournamentRegistration::class);
    }

    public function pools()
    {
        return $this->hasMany(Pool::class);
    }

    public function fixtures()
    {
        return $this->hasMany(Fixture::class);
    }

    public function liveStreams()
    {
        return $this->hasMany(LiveStream::class);
    }

    // ─── Scopes ─────────────────────────────────────────────────────────
    public function scopeUpcoming($query)
    {
        return $query->where('status', self::STATUS_UPCOMING);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', self::STATUS_UPCOMING);
    }

    // ─── Capacity Helper Methods ─────────────────────────────────────────

    /**
     * Count confirmed + paid registrations (unique teams).
     */
    public function confirmedTeamsCount(): int
    {
        return $this->registrations()
            ->where('status', TournamentRegistration::STATUS_CONFIRMED)
            ->where('payment_status', TournamentRegistration::PAYMENT_PAID)
            ->distinct('team_id')
            ->count('team_id');
    }

    /**
     * Check whether the tournament has reached its team capacity.
     * Returns false if max_teams is not set (unlimited).
     */
    public function isFull(): bool
    {
        if (is_null($this->max_teams)) {
            return false;
        }
        return $this->confirmedTeamsCount() >= $this->max_teams;
    }

    /**
     * Return remaining slots, or null if no limit is set.
     */
    public function spotsRemaining(): ?int
    {
        if (is_null($this->max_teams)) {
            return null;
        }
        return max(0, $this->max_teams - $this->confirmedTeamsCount());
    }

    /**
     * Check whether registration is open based on status, deadline, and capacity.
     */
    public function isRegistrationOpen(): bool
    {
        // Status must be 'upcoming'
        if ($this->status !== self::STATUS_UPCOMING) {
            return false;
        }

        // Check registration deadline
        if ($this->registration_deadline && now()->isAfter($this->registration_deadline)) {
            return false;
        }

        // Check capacity
        if ($this->isFull()) {
            return false;
        }

        return true;
    }

    /**
     * How many days until the registration deadline (null if no deadline).
     */
    public function daysUntilDeadline(): ?int
    {
        if (is_null($this->registration_deadline)) {
            return null;
        }
        return max(0, (int) now()->diffInDays($this->registration_deadline, false));
    }
}