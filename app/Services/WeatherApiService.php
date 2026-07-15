<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherApiService
{
    protected $apiKey;
    protected $latitude;
    protected $longitude;
    protected $baseUrl = 'https://api.tomorrow.io/v4/timelines';

    public function __construct()
    {
        $this->apiKey = config('services.tomorrow_io.api_key');
        $this->latitude = config('services.tomorrow_io.latitude', 14.5995); // Default: Manila
        $this->longitude = config('services.tomorrow_io.longitude', 120.9842);
    }

    /**
     * Fetch real-time weather data from Tomorrow.io API
     *
     * @param float|null $latitude Optional latitude override
     * @param float|null $longitude Optional longitude override
     * @return array|null
     */
    public function fetchWeatherData($latitude = null, $longitude = null)
    {
        try {
            // Use provided coordinates or fallback to config defaults
            $lat = $latitude ?? $this->latitude;
            $lon = $longitude ?? $this->longitude;

            Log::info('Starting weather data fetch', [
                'api_key_set' => !empty($this->apiKey),
                'latitude' => $lat,
                'longitude' => $lon,
                'using_override' => $latitude !== null && $longitude !== null,
            ]);

            $params = [
                'apikey' => $this->apiKey,
                'location' => "{$lat},{$lon}",
                'fields' => 'temperature,humidity,windSpeed,weatherCode',
                'timesteps' => '1h',
                'units' => 'metric',
            ];

            Log::info('API Request params', ['params' => array_merge($params, ['apikey' => 'HIDDEN'])]);

            // Disable SSL verification for development (Windows SSL cert issue)
            $response = Http::withOptions([
                'verify' => false, // Disable SSL verification
            ])->timeout(10)->get($this->baseUrl, $params);

            Log::info('API Response received', [
                'status' => $response->status(),
                'successful' => $response->successful(),
            ]);

            if ($response->failed()) {
                Log::error('Tomorrow.io API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            $data = $response->json();

            Log::info('API Response parsed', [
                'has_data' => isset($data['data']),
                'has_timelines' => isset($data['data']['timelines']),
            ]);

            // Extract the current values from the API response
            $values = $data['data']['timelines'][0]['intervals'][0]['values'] ?? null;

            if (!$values) {
                Log::error('Tomorrow.io API returned unexpected format', ['data' => $data]);
                return null;
            }

            $temperature = $values['temperature'] ?? null;
            $humidity = $values['humidity'] ?? null;
            $windSpeed = $values['windSpeed'] ?? null;
            $weatherCode = $values['weatherCode'] ?? null;
            
            // Tomorrow.io codes: 4200 (Light Thunderstorm), 4001 (Thunderstorm), 4201 (Heavy Thunderstorm)
            // If thunderstorm code is returned, simulate lightning strikes to trigger danger levels
            $lightningStrikes = 0;
            if ($weatherCode === 4200 || $weatherCode === 4001 || $weatherCode === 4201) {
                $lightningStrikes = 7; // Estimated at 10km (Danger zone)
            }

            Log::info('Weather values extracted', [
                'temperature' => $temperature,
                'humidity' => $humidity,
                'wind_speed' => $windSpeed,
                'weather_code' => $weatherCode,
                'lightning_strikes' => $lightningStrikes,
            ]);

            // Calculate WBGT
            $wbgt = $this->calculateWBGT($temperature, $humidity);

            // Determine lightning risk (distance estimation based on strikes)
            $lightningRisk = $this->estimateLightningDistance($lightningStrikes);

            // Determine alert level
            $alertLevel = $this->determineAlertLevel($wbgt, $lightningRisk);

            return [
                'temperature' => $temperature,
                'humidity' => $humidity,
                'wind_speed' => $windSpeed,
                'wbgt' => $wbgt,
                'lightning_risk' => $lightningRisk,
                'alert_level' => $alertLevel,
            ];

        } catch (\Exception $e) {
            Log::error('Exception while fetching weather data', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Calculate WBGT (Wet Bulb Globe Temperature) index
     * Simplified formula: WBGT ≈ 0.567 × T + 0.393 × e + 3.94
     * where e = vapor pressure derived from humidity
     *
     * @param float|null $temperature Temperature in Celsius
     * @param float|null $humidity Humidity percentage
     * @return float|null
     */
    public function calculateWBGT($temperature, $humidity)
    {
        if ($temperature === null || $humidity === null) {
            return null;
        }

        // Simplified WBGT calculation
        // For outdoor conditions with solar radiation, a more accurate formula would be:
        // WBGT = 0.7 × Twb + 0.2 × Tg + 0.1 × Ta
        // But for simplicity, we'll use an approximation based on temperature and humidity

        // Calculate vapor pressure (e) in hPa
        $e = ($humidity / 100) * 6.112 * exp((17.67 * $temperature) / ($temperature + 243.5));

        // Simplified WBGT formula
        $wbgt = (0.567 * $temperature) + (0.393 * $e) + 3.94;

        return round($wbgt, 2);
    }

    /**
     * Estimate lightning distance based on strike count
     * This is a simplified estimation - in production, you'd use actual lightning distance data
     *
     * @param int $strikeCount Number of lightning strikes in the last hour
     * @return float Distance in km
     */
    protected function estimateLightningDistance($strikeCount)
    {
        if ($strikeCount === 0) {
            return 50.0; // No strikes = safe distance
        } elseif ($strikeCount >= 10) {
            return 5.0; // Many strikes = very close
        } elseif ($strikeCount >= 5) {
            return 10.0; // Several strikes = close
        } elseif ($strikeCount >= 2) {
            return 15.0; // Few strikes = moderate distance
        } else {
            return 25.0; // Single strike = far
        }
    }

    /**
     * Determine alert level based on WBGT and lightning risk
     *
     * @param float|null $wbgt WBGT index
     * @param float|null $lightningRisk Lightning distance in km
     * @return string Alert level: safe, caution, warning, danger
     */
    protected function determineAlertLevel($wbgt, $lightningRisk)
    {
        // Use the SafetyLog model's static method for consistency
        return \App\Models\SafetyLog::getAlertLevel($wbgt ?? 0, $lightningRisk ?? 50);
    }

    /**
     * Get a fallback/dummy data set for testing or when API is unavailable
     *
     * @return array
     */
    public function getFallbackData()
    {
        return [
            'temperature' => 28.5,
            'humidity' => 65.0,
            'wind_speed' => 12.3,
            'wbgt' => 26.8,
            'lightning_risk' => 50.0,
            'alert_level' => 'safe',
        ];
    }
}
