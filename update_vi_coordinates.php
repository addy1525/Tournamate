<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== UPDATING VI 10s COORDINATES ===\n\n";

$tournament = App\Models\Tournament::where('name', 'LIKE', '%VI%')->first();

if ($tournament) {
    echo "Before update:\n";
    echo "  Name: {$tournament->name}\n";
    echo "  Venue: {$tournament->venue_name}\n";
    echo "  Coordinates: " . ($tournament->location_coordinates ?? 'NULL') . "\n\n";
    
    // Update with correct Victoria Institution coordinates
    $tournament->location_coordinates = '3.1491, 101.7023';
    $tournament->save();
    
    echo "After update:\n";
    echo "  Name: {$tournament->name}\n";
    echo "  Venue: {$tournament->venue_name}\n";
    echo "  Coordinates: {$tournament->location_coordinates}\n\n";
    
    echo "✅ Coordinates updated successfully!\n";
    echo "   Victoria Institution Field is now at the correct location.\n";
} else {
    echo "❌ VI tournament not found!\n";
}
