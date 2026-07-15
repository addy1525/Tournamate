<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING BROWSE TOURNAMENTS QUERY ===\n\n";

// This is the exact query from ManagerController::browseTournaments()
$openTournaments = App\Models\Tournament::where('status', App\Models\Tournament::STATUS_UPCOMING)
    ->orderByRaw('COALESCE(start_date, tournament_date) ASC')
    ->get();

echo "Number of tournaments found: {$openTournaments->count()}\n\n";

foreach ($openTournaments as $tournament) {
    echo "✓ {$tournament->name}\n";
    echo "  Date: {$tournament->start_date->format('M d, Y')}\n";
    echo "  Venue: {$tournament->venue_name}\n";
    echo "  Status: {$tournament->status}\n";
    echo "  Fee: RM " . number_format($tournament->fee ?? 250, 2) . "\n";
    echo "\n";
}

echo "These tournaments should ALL appear in the Browse Tournaments page.\n";
