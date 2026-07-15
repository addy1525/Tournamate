<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Tomorrow.io API Integration\n";
echo "====================================\n\n";

$apiKey = config('services.tomorrow_io.api_key');
$lat = config('services.tomorrow_io.latitude');
$lon = config('services.tomorrow_io.longitude');

echo "API Key: " . ($apiKey ? substr($apiKey, 0, 10) . '...' : 'NOT SET') . "\n";
echo "Latitude: $lat\n";
echo "Longitude: $lon\n\n";

if (!$apiKey) {
    echo "ERROR: API key not configured!\n";
    exit(1);
}

echo "Fetching weather data...\n";

$url = "https://api.tomorrow.io/v4/timelines?" . http_build_query([
    'apikey' => $apiKey,
    'location' => "$lat,$lon",
    'fields' => 'temperature,humidity,windSpeed,lightningStrikeLastHour',
    'timesteps' => '1h',
    'units' => 'metric',
]);

echo "URL: " . str_replace($apiKey, 'HIDDEN', $url) . "\n\n";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Status: $httpCode\n";

if ($httpCode !== 200) {
    echo "ERROR Response:\n";
    echo $response . "\n";
    exit(1);
}

$data = json_decode($response, true);

if (isset($data['data']['timelines'][0]['intervals'][0]['values'])) {
    $values = $data['data']['timelines'][0]['intervals'][0]['values'];
    echo "\n✅ SUCCESS! Weather Data Retrieved:\n";
    echo "Temperature: " . ($values['temperature'] ?? 'N/A') . "°C\n";
    echo "Humidity: " . ($values['humidity'] ?? 'N/A') . "%\n";
    echo "Wind Speed: " . ($values['windSpeed'] ?? 'N/A') . " km/h\n";
    echo "Lightning Strikes (last hour): " . ($values['lightningStrikeLastHour'] ?? 0) . "\n";
    
    // Now save to database
    echo "\nSaving to database...\n";
    $service = app(\App\Services\WeatherApiService::class);
    $weatherData = $service->fetchWeatherData();
    
    if ($weatherData) {
        $log = \App\Models\SafetyLog::create($weatherData);
        echo "✅ Saved to database! ID: {$log->id}\n";
        echo "WBGT: {$log->wbgt}°C\n";
        echo "Alert Level: {$log->alert_level}\n";
    } else {
        echo "❌ Failed to save to database\n";
    }
} else {
    echo "ERROR: Unexpected API response format\n";
    echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
}
