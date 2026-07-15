<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'fixture_id', 'team_id', 'event_type', 'player_name', 'player_jersey', 'minute', 'points'
    ];

    // Auto-calculate points based on rugby event type
    protected static function booted()
    {
        static::creating(function ($event) {
            $event->points = static::getPointsForType($event->event_type);
        });
    }

    public static function getPointsForType($type)
    {
        switch ($type) {
            case 'try':
                return 5;
            case 'conversion':
                return 2;
            case 'penalty':
            case 'drop_goal':
                return 3;
            default:
                return 0;
        }
    }

    public function fixture()
    {
        return $this->belongsTo(Fixture::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
