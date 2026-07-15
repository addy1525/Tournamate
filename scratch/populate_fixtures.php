<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tournament;
use App\Models\Fixture;
use Carbon\Carbon;

$tournament = Tournament::where('name', 'like', '%MCKK%')->first();
if (!$tournament) {
    echo "Tournament not found!\n";
    exit(1);
}

echo "Found tournament: " . $tournament->name . " (ID: " . $tournament->id . ")\n";

// Clear existing fixtures
Fixture::where('tournament_id', $tournament->id)->delete();
echo "Cleared existing fixtures.\n";

$pools = $tournament->pools()->with('registrations')->get();
$currentStartTime = Carbon::parse($tournament->start_date)->setHour(8)->setMinute(0);
$duration = 15;
$generatedCount = 0;

$rugbyScores = [
    [14, 7], [21, 12], [5, 0], [10, 10], [24, 7], [17, 12], [28, 14], [12, 5],
    [7, 14], [12, 21], [0, 5], [7, 24], [12, 17], [14, 28], [5, 12], [14, 14]
];

foreach ($pools as $pool) {
    $teams = $pool->registrations->pluck('team_id')->toArray();
    $count = count($teams);
    
    if ($count < 2) continue;
    
    if ($count % 2 != 0) {
        array_push($teams, null);
        $count++;
    }
    
    $halfSize = $count / 2;
    for ($round = 0; $round < $count - 1; $round++) {
        for ($i = 0; $i < $halfSize; $i++) {
            $home = $teams[$i];
            $away = $teams[$count - 1 - $i];
            
            if ($home !== null && $away !== null) {
                $score = $rugbyScores[array_rand($rugbyScores)];
                
                Fixture::create([
                    'tournament_id' => $tournament->id,
                    'pool_id' => $pool->id,
                    'home_team_id' => $home,
                    'away_team_id' => $away,
                    'stage' => 'Pool Stage',
                    'status' => 'completed',
                    'home_score' => $score[0],
                    'away_score' => $score[1],
                    'start_time' => $currentStartTime->copy(),
                ]);
                
                $currentStartTime->addMinutes($duration);
                $generatedCount++;
            }
        }
        
        $last = array_pop($teams);
        array_splice($teams, 1, 0, [$last]);
    }
}

echo "Successfully generated and scored " . $generatedCount . " Pool Stage fixtures!\n";
