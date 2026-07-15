<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveStream;
use App\Models\Tournament;
use App\Models\Fixture;
use Illuminate\Http\Request;

class LiveStreamController extends Controller
{
    /**
     * Senarai semua stream konfigurasi
     */
    public function index(Request $request)
    {
        $tournaments = Tournament::orderBy('start_date', 'desc')->get();

        // Filter by tournament if selected
        $selectedTournamentId = $request->query('tournament_id');
        $query = LiveStream::with(['tournament', 'fixture.homeTeam', 'fixture.awayTeam'])
            ->orderBy('status', 'asc') // live dulu, then scheduled, then offline
            ->orderBy('field_name', 'asc');

        if ($selectedTournamentId) {
            $query->where('tournament_id', $selectedTournamentId);
            $selectedTournament = Tournament::find($selectedTournamentId);
        } else {
            $selectedTournament = $tournaments->first();
            if ($selectedTournament) {
                $query->where('tournament_id', $selectedTournament->id);
            }
        }

        $streams = $query->get();

        // Get fixtures for the selected tournament (for dropdown)
        $fixtures = $selectedTournament
            ? Fixture::with(['homeTeam', 'awayTeam'])
                ->where('tournament_id', $selectedTournament->id)
                ->whereIn('status', ['scheduled', 'in_progress'])
                ->orderBy('start_time')
                ->get()
            : collect();

        // Stats
        $liveCount    = $streams->where('status', 'live')->count();
        $totalViewers = $streams->where('status', 'live')->sum('viewers');

        return view('admin.media.livestreams', compact(
            'streams', 'tournaments', 'selectedTournament',
            'fixtures', 'liveCount', 'totalViewers'
        ));
    }

    /**
     * Simpan stream baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'fixture_id'    => 'nullable|exists:fixtures,id',
            'field_name'    => 'required|string|max:100',
            'title'         => 'nullable|string|max:255',
            'provider'      => 'required|in:youtube,twitch,custom',
            'video_id'      => 'required|string|max:255',
            'stream_url'    => 'nullable|url',
            'status'        => 'required|in:live,offline,scheduled',
        ]);

        LiveStream::create($request->only([
            'tournament_id', 'fixture_id', 'field_name', 'title',
            'provider', 'video_id', 'stream_url', 'status',
        ]));

        return redirect()
            ->route('admin.live-stream.index', ['tournament_id' => $request->tournament_id])
            ->with('success', "Stream untuk '{$request->field_name}' berjaya ditambah!");
    }

    /**
     * Kemas kini status / video ID stream
     */
    public function update(Request $request, $id)
    {
        $stream = LiveStream::findOrFail($id);

        $request->validate([
            'field_name' => 'required|string|max:100',
            'title'      => 'nullable|string|max:255',
            'provider'   => 'required|in:youtube,twitch,custom',
            'video_id'   => 'required|string|max:255',
            'stream_url' => 'nullable|url',
            'status'     => 'required|in:live,offline,scheduled',
            'fixture_id' => 'nullable|exists:fixtures,id',
        ]);

        $stream->update($request->only([
            'field_name', 'title', 'provider', 'video_id',
            'stream_url', 'status', 'fixture_id',
        ]));

        return redirect()
            ->route('admin.live-stream.index', ['tournament_id' => $stream->tournament_id])
            ->with('success', "Stream '{$stream->field_name}' berjaya dikemas kini!");
    }

    /**
     * Toggle status live/offline cepat (AJAX-friendly)
     */
    public function toggleStatus(Request $request, $id)
    {
        $stream = LiveStream::findOrFail($id);
        $stream->status = ($stream->status === 'live') ? 'offline' : 'live';
        $stream->save();

        return response()->json([
            'success' => true,
            'status'  => $stream->status,
            'message' => "Stream '{$stream->field_name}' kini " . strtoupper($stream->status),
        ]);
    }

    /**
     * Padam stream
     */
    public function destroy($id)
    {
        $stream = LiveStream::findOrFail($id);
        $tournamentId = $stream->tournament_id;
        $name = $stream->field_name;
        $stream->delete();

        return redirect()
            ->route('admin.live-stream.index', ['tournament_id' => $tournamentId])
            ->with('success', "Stream '{$name}' berjaya dipadam.");
    }
}
