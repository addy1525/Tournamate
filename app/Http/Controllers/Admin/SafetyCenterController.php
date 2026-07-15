<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\WeatherApiService;
use Illuminate\Http\Request;

class SafetyCenterController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherApiService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * Display the safety center dashboard.
     */
    public function index(Request $request)
    {
        // Load all ongoing & upcoming tournaments for the dropdown
        $tournaments = \App\Models\Tournament::whereIn('status', ['ongoing', 'upcoming'])
            ->orderBy('start_date', 'asc')
            ->get();

        // Get selected tournament
        $selectedTournamentId = $request->query('tournament_id');
        
        if ($selectedTournamentId) {
            $selectedTournament = \App\Models\Tournament::find($selectedTournamentId);
        } else {
            // Default to the first ongoing or upcoming tournament
            $selectedTournament = $tournaments->first();
        }

        $latestLog = null;
        $logs = collect();

        if ($selectedTournament) {
            $latestLog = \App\Models\SafetyLog::where('tournament_id', $selectedTournament->id)
                ->latest()
                ->first();
            
            $logs = \App\Models\SafetyLog::where('tournament_id', $selectedTournament->id)
                ->latest()
                ->limit(10)
                ->get();
        }

        return view('admin.safety.index', compact('latestLog', 'logs', 'tournaments', 'selectedTournament'));
    }

    /**
     * Manually refresh weather data from Tomorrow.io API
     */
    public function refreshWeatherData(Request $request)
    {
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
        ]);

        $tournament = \App\Models\Tournament::findOrFail($request->tournament_id);

        try {
            $latitude = null;
            $longitude = null;
            $locationName = $tournament->venue ?? $tournament->name;

            if ($tournament->location_coordinates) {
                $coords = explode(',', $tournament->location_coordinates);
                if (count($coords) === 2) {
                    $latitude = trim($coords[0]);
                    $longitude = trim($coords[1]);
                }
            }

            // Fallback if coordinates are invalid or missing
            if ($latitude === null || $longitude === null) {
                $latitude = 3.1390;
                $longitude = 101.6869;
                $locationName = 'Default (Kuala Lumpur)';
            }

            // Fetch weather data
            $weatherData = $this->weatherService->fetchWeatherData($latitude, $longitude);

            if ($weatherData === null) {
                return redirect()->back()->with('error', 'Failed to fetch weather data. Please check your API key and try again.');
            }

            // Link tournament_id
            $weatherData['tournament_id'] = $tournament->id;

            // Save to database
            $log = \App\Models\SafetyLog::create($weatherData);

            // Broadcast the real-time weather update event
            try {
                broadcast(new \App\Events\SafetyAlertBroadcast($log->load('tournament')));
            } catch (\Exception $e) {
                \Log::error('Failed to broadcast safety alert: ' . $e->getMessage());
            }

            return redirect()->route('admin.safety.index', ['tournament_id' => $tournament->id])
                ->with('success', "Weather data updated successfully for {$locationName}!");
        } catch (\Exception $e) {
            \Log::error('Error refreshing weather data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while fetching weather data.');
        }
    }

    /**
     * Store manual safety log entry
     */
    public function store(Request $request)
    {
        $request->validate([
            'tournament_id'  => 'required|exists:tournaments,id',
            'wbgt'           => 'required|numeric',
            'lightning_risk' => 'required|numeric',
        ]);

        $alertLevel = \App\Models\SafetyLog::getAlertLevel(
            $request->wbgt,
            $request->lightning_risk
        );

        $log = \App\Models\SafetyLog::create([
            'tournament_id'  => $request->tournament_id,
            'temperature'    => $request->temperature,
            'humidity'       => $request->humidity,
            'wind_speed'     => $request->wind_speed,
            'wbgt'           => $request->wbgt,
            'lightning_risk' => $request->lightning_risk,
            'alert_level'    => $alertLevel,
            'notes'          => $request->notes,
        ]);

        // Broadcast the real-time weather update event
        try {
            broadcast(new \App\Events\SafetyAlertBroadcast($log->load('tournament')));
        } catch (\Exception $e) {
            \Log::error('Failed to broadcast manual safety alert: ' . $e->getMessage());
        }

        return redirect()->route('admin.safety.index', ['tournament_id' => $request->tournament_id])
            ->with('success', 'Safety log added successfully.');
    }

    public function history(Request $request)
    {
        $tournaments = \App\Models\Tournament::whereIn('status', ['ongoing', 'upcoming'])
            ->orderBy('start_date', 'asc')
            ->get();

        $selectedTournamentId = $request->query('tournament_id');
        
        $query = \App\Models\SafetyLog::latest();
        if ($selectedTournamentId) {
            $query->where('tournament_id', $selectedTournamentId);
        }

        $logs = $query->paginate(20);
        return view('admin.safety.history', compact('logs', 'tournaments', 'selectedTournamentId'));
    }
}
