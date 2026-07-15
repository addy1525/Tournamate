<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$tournaments = App\Models\Tournament::all();

echo "=== ALL TOURNAMENTS IN DATABASE ===\n\n";
foreach ($tournaments as $tournament) {
    echo "ID: {$tournament->id}\n";
    echo "Name: {$tournament->name}\n";
    echo "Status: {$tournament->status}\n";
    echo "Start Date: {$tournament->start_date}\n";
    echo "---\n";
}

$upcomingTournaments = App\Models\Tournament::where('status', 'upcoming')->get();
echo "\n=== UPCOMING TOURNAMENTS (what team managers should see) ===\n\n";
foreach ($upcomingTournaments as $tournament) {
    echo "- {$tournament->name} ({$tournament->start_date})\n";
}
echo "\nTotal upcoming: {$upcomingTournaments->count()}\n";
