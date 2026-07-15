<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n=== TOURNAMENT LOCATION VERIFICATION ===\n\n";
echo str_pad("Tournament", 20) . " | " . str_pad("Status", 12) . " | " . str_pad("Venue", 35) . " | Coordinates\n";
echo str_repeat("-", 110) . "\n";

$tournaments = DB::table('tournaments')->get();

foreach ($tournaments as $t) {
    echo str_pad($t->name, 20) . " | ";
    echo str_pad($t->status, 12) . " | ";
    echo str_pad($t->venue ?? 'N/A', 35) . " | ";
    echo ($t->location_coordinates ?? 'NO COORDINATES') . "\n";
}

echo "\n=== VERIFICATION GUIDE ===\n";
echo "Kuala Lumpur area: ~3.1xxx, 101.6xxx\n";
echo "Perak (MCKK) area: ~4.7xxx, 100.9xxx\n";
echo "\n";
