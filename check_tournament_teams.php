<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CHECKING TOURNAMENT-TEAM RELATIONSHIP ===\n\n";

// Check via many-to-many relationship
$tournaments = App\Models\Tournament::with('teams')->get();

echo "Tournaments and their registered teams:\n";
foreach ($tournaments as $t) {
    $teamCount = $t->teams->count();
    echo "\n{$t->name} (ID: {$t->id}): {$teamCount} teams\n";
    if ($teamCount > 0) {
        foreach ($t->teams as $team) {
            echo "  - {$team->name} ({$team->payment_status})\n";
        }
    }
}

echo "\n\n=== ALL TEAMS ===\n";
$teams = App\Models\Team::with('tournaments')->get();
foreach ($teams as $team) {
    $tournamentsCount = $team->tournaments->count();
    echo "\nTeam: {$team->name}\n";
    echo "  Registered to {$tournamentsCount} tournament(s)\n";
    if ($tournamentsCount > 0) {
        foreach ($team->tournaments as $t) {
            echo "  - {$t->name}\n";
        }
    }
}
