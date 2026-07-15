<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CHECKING ALL TOURNAMENT COORDINATES ===\n\n";

$tournaments = App\Models\Tournament::orderBy('start_date', 'asc')->get();

foreach ($tournaments as $t) {
    echo "ID: {$t->id}\n";
    echo "Name: {$t->name}\n";
    echo "Venue: {$t->venue_name}\n";
    echo "Coordinates: " . ($t->location_coordinates ?? 'NULL') . "\n";
    echo "---\n\n";
}
