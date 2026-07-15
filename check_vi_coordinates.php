<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CHECKING VI 10s TOURNAMENT COORDINATES ===\n\n";

$tournament = App\Models\Tournament::where('name', 'LIKE', '%VI%')->first();

if ($tournament) {
    echo "Tournament ID: {$tournament->id}\n";
    echo "Name: {$tournament->name}\n";
    echo "Venue: {$tournament->venue_name}\n";
    echo "Current Coordinates: " . ($tournament->location_coordinates ?? 'NULL') . "\n\n";
    
    echo "ISSUE: The coordinates are likely wrong or NULL.\n";
    echo "Victoria Institution is located at approximately:\n";
    echo "  Latitude: 3.1491\n";
    echo "  Longitude: 101.7023\n";
    echo "  Correct coordinates: 3.1491, 101.7023\n";
} else {
    echo "VI tournament not found!\n";
}
