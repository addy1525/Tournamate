<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING TOURNAMENT DETECTION LOGIC ===\n\n";

// Test 1: Check for active tournament
echo "Test 1: Looking for active tournament...\n";
$activeTournament = App\Models\Tournament::where('status', 'ongoing')->first();
if ($activeTournament) {
    echo "✓ Found active tournament: {$activeTournament->name}\n";
    echo "  Coordinates: {$activeTournament->location_coordinates}\n";
} else {
    echo "✗ No active tournament found\n";
}

echo "\n";

// Test 2: Check for upcoming tournament
echo "Test 2: Looking for upcoming tournament...\n";
$upcomingTournament = App\Models\Tournament::where('status', 'upcoming')
    ->orderBy('start_date', 'asc')
    ->first();
if ($upcomingTournament) {
    echo "✓ Found upcoming tournament: {$upcomingTournament->name}\n";
    echo "  Coordinates: {$upcomingTournament->location_coordinates}\n";
    echo "  Venue: {$upcomingTournament->venue}\n";
} else {
    echo "✗ No upcoming tournament found\n";
}

echo "\n";

// Test 3: Simulate detection logic
echo "Test 3: Simulating detection logic...\n";
$tournament = App\Models\Tournament::where('status', 'ongoing')
    ->orWhere(function($query) {
        $query->where('status', 'upcoming')
              ->orderBy('start_date', 'asc');
    })
    ->first();

if ($tournament && $tournament->location_coordinates) {
    $coords = explode(',', $tournament->location_coordinates);
    if (count($coords) === 2) {
        $lat = trim($coords[0]);
        $lon = trim($coords[1]);
        $locationName = $tournament->venue ?? $tournament->name;
        echo "✓ Will use tournament location\n";
        echo "  Tournament: {$tournament->name}\n";
        echo "  Location: {$locationName}\n";
        echo "  Coordinates: {$lat}, {$lon}\n";
    } else {
        echo "✗ Invalid coordinates format\n";
    }
} else {
    echo "✓ Will use fallback location\n";
    echo "  Location: Kuala Lumpur\n";
    echo "  Coordinates: 3.1390, 101.6869\n";
}

echo "\n=== TEST COMPLETE ===\n";
