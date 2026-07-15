<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ADMIN DASHBOARD DATA ===\n\n";
echo "Tournaments: " . App\Models\Tournament::count() . "\n";
echo "Teams: " . App\Models\Team::count() . "\n";
echo "Managers: " . App\Models\User::where('role', 'manager')->count() . "\n\n";

echo "Teams by Tournament:\n";
$tournaments = App\Models\Tournament::with('teams')->get();
foreach ($tournaments as $t) {
    $teamCount = $t->teams ? $t->teams->count() : 0;
    echo "  {$t->name}: {$teamCount} teams\n";
}
