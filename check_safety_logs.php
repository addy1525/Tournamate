<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "All Safety Logs in Database:\n";
echo "============================\n\n";

$logs = \App\Models\SafetyLog::orderBy('created_at', 'desc')->get();

foreach ($logs as $index => $log) {
    echo ($index + 1) . ". ID: {$log->id}\n";
    echo "   Temperature: {$log->temperature}°C\n";
    echo "   Humidity: {$log->humidity}%\n";
    echo "   WBGT: {$log->wbgt}°C\n";
    echo "   Lightning: {$log->lightning_risk} km\n";
    echo "   Alert: {$log->alert_level}\n";
    echo "   Created: {$log->created_at}\n";
    echo "   ---\n";
}

echo "\nTotal Records: " . $logs->count() . "\n";
echo "\nLatest Record (what should be shown):\n";
$latest = \App\Models\SafetyLog::latest()->first();
if ($latest) {
    echo "WBGT: {$latest->wbgt}°C\n";
    echo "Lightning: {$latest->lightning_risk} km\n";
}
