<?php

namespace App\Console\Commands;

use App\Services\WeatherApiService;
use App\Models\SafetyLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchWeatherData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch real-time weather data from Tomorrow.io API and save to database';

    protected $weatherService;

    /**
     * Create a new command instance.
     */
    public function __construct(WeatherApiService $weatherService)
    {
        parent::__construct();
        $this->weatherService = $weatherService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching weather data from Tomorrow.io API...');

        try {
            $weatherData = $this->weatherService->fetchWeatherData();

            if ($weatherData === null) {
                $this->error('Failed to fetch weather data. Check API key and logs.');
                Log::error('Weather data fetch failed - API returned null');
                return 1;
            }

            // Save to database
            $log = SafetyLog::create($weatherData);

            $this->info('Weather data saved successfully!');
            $this->line("Temperature: {$log->temperature}°C");
            $this->line("Humidity: {$log->humidity}%");
            $this->line("Wind Speed: {$log->wind_speed} km/h");
            $this->line("WBGT: {$log->wbgt}°C");
            $this->line("Lightning Risk: {$log->lightning_risk} km");
            $this->line("Alert Level: {$log->alert_level}");

            return 0;
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            Log::error('Weather fetch command error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 1;
        }
    }
}
