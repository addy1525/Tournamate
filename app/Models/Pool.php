<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pool extends Model
{
    use HasFactory;

    protected $fillable = ['tournament_id', 'name'];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function fixtures()
    {
        return $this->hasMany(Fixture::class);
    }

    public function registrations()
    {
        return $this->hasMany(TournamentRegistration::class, 'pool_id');
    }

    public function calculateStandings()
    {
        $registrations = $this->registrations()->with('team')->get();
        
        $standings = [];
        foreach ($registrations as $reg) {
            if (!$reg->team) continue;
            $standings[$reg->team_id] = [
                'team' => $reg->team,
                'played' => 0,
                'won' => 0,
                'drawn' => 0,
                'lost' => 0,
                'points_for' => 0,
                'points_against' => 0,
                'points_difference' => 0,
                'points' => 0,
            ];
        }
        
        $fixtures = $this->fixtures()->where('status', 'completed')->get();
        
        foreach ($fixtures as $fixture) {
            $homeId = $fixture->home_team_id;
            $awayId = $fixture->away_team_id;
            
            if (!isset($standings[$homeId]) || !isset($standings[$awayId])) {
                continue;
            }
            
            $homeScore = $fixture->home_score ?? 0;
            $awayScore = $fixture->away_score ?? 0;
            
            $standings[$homeId]['played']++;
            $standings[$awayId]['played']++;
            
            $standings[$homeId]['points_for'] += $homeScore;
            $standings[$homeId]['points_against'] += $awayScore;
            
            $standings[$awayId]['points_for'] += $awayScore;
            $standings[$awayId]['points_against'] += $homeScore;
            
            if ($homeScore > $awayScore) {
                $standings[$homeId]['won']++;
                $standings[$homeId]['points'] += 3;
                
                $standings[$awayId]['lost']++;
                $standings[$awayId]['points'] += 1;
            } elseif ($homeScore < $awayScore) {
                $standings[$awayId]['won']++;
                $standings[$awayId]['points'] += 3;
                
                $standings[$homeId]['lost']++;
                $standings[$homeId]['points'] += 1;
            } else {
                $standings[$homeId]['drawn']++;
                $standings[$homeId]['points'] += 2;
                
                $standings[$awayId]['drawn']++;
                $standings[$awayId]['points'] += 2;
            }
        }
        
        $standingsCollection = collect($standings)->map(function ($stats) {
            $stats['points_difference'] = $stats['points_for'] - $stats['points_against'];
            return $stats;
        });
        
        return $standingsCollection->sort(function ($a, $b) {
            if ($b['points'] !== $a['points']) {
                return $b['points'] <=> $a['points'];
            }
            if ($b['points_difference'] !== $a['points_difference']) {
                return $b['points_difference'] <=> $a['points_difference'];
            }
            return $b['points_for'] <=> $a['points_for'];
        })->values();
    }
}
