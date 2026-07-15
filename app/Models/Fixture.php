<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id', 'pool_id', 'home_team_id', 'away_team_id',
        'home_score', 'away_score', 'start_time', 'field_name', 'stage', 'status',
        'home_elo_before', 'away_elo_before', 'home_elo_after', 'away_elo_after'
    ];

    protected $casts = [
        'start_time' => 'datetime',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function pool()
    {
        return $this->belongsTo(Pool::class);
    }

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function matchEvents()
    {
        return $this->hasMany(MatchEvent::class);
    }
}
