<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DETAILED TEAM CHECK ===\n\n";

// Check all teams
$teams = App\Models\Team::all();
echo "Total teams in database: " . $teams->count() . "\n\n";

foreach ($teams as $team) {
    echo "Team: {$team->name}\n";
    echo "  ID: {$team->id}\n";
    echo "  Tournament ID: " . ($team->tournament_id ?? 'NULL') . "\n";
    if ($team->tournament_id) {
        $tournament = App\Models\Tournament::find($team->tournament_id);
        echo "  Tournament: " . ($tournament ? $tournament->name : 'NOT FOUND') . "\n";
    }
    echo "  Payment Status: " . ($team->payment_status ?? 'NULL') . "\n";
    echo "  Players Count: " . ($team->players ? $team->players->count() : 0) . "\n";
    echo "---\n\n";
}

// Check tournaments with teams
echo "\nTournaments with team counts:\n";
$tournaments = App\Models\Tournament::all();
foreach ($tournaments as $t) {
    $teamCount = App\Models\Team::where('tournament_id', $t->id)->count();
    echo "{$t->name} (ID: {$t->id}): {$teamCount} teams\n";
}
