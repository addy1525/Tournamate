@extends('layouts.dashboard')

@section('title', 'Live Stream Management')
@section('page-title', 'Live Stream Center')

@push('styles')
<style>
    /* ===== LIVE STREAM ADMIN UI ===== */
    .stream-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .live-pulse {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .live-pulse::before {
        content: '';
        width: 8px;
        height: 8px;
        background: #ef4444;
        border-radius: 50%;
        animation: pulse-red 1.5s ease-in-out infinite;
        flex-shrink: 0;
    }

    @keyframes pulse-red {
        0%, 100% { opacity: 1; transform: scale(1); box-shadow: 0 0 0 0 rgba(239,68,68,0.5); }
        50% { opacity: 0.8; transform: scale(1.2); box-shadow: 0 0 0 6px rgba(239,68,68,0); }
    }

    /* Stream Card */
    .stream-card {
        background: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.2s ease;
        position: relative;
    }

    .stream-card:hover {
        border-color: var(--color-border-light);
        box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    }

    .stream-card.is-live {
        border-color: rgba(239, 68, 68, 0.5);
        box-shadow: 0 0 20px rgba(239, 68, 68, 0.1);
    }

    .stream-card.is-scheduled {
        border-color: rgba(0, 212, 255, 0.3);
    }

    .stream-thumbnail {
        position: relative;
        width: 100%;
        padding-top: 56.25%; /* 16:9 */
        background: #0a1628;
        overflow: hidden;
    }

    .stream-thumbnail img {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        object-fit: cover;
        opacity: 0.8;
    }

    .stream-thumbnail .thumbnail-placeholder {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: var(--color-text-muted);
    }

    .stream-status-badge {
        position: absolute;
        top: 10px; left: 10px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .status-live { background: rgba(239,68,68,0.9); color: white; }
    .status-offline { background: rgba(100,116,139,0.9); color: white; }
    .status-scheduled { background: rgba(0,212,255,0.9); color: #0a1628; }

    .stream-info {
        padding: 1rem;
    }

    .stream-field {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--color-rugby-green);
        margin-bottom: 4px;
    }

    .stream-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--color-text-primary);
        margin-bottom: 6px;
    }

    .stream-provider-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.65rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 4px;
        background: var(--color-bg-tertiary);
        color: var(--color-text-secondary);
    }

    .stream-actions {
        display: flex;
        gap: 6px;
        padding: 0.75rem 1rem;
        border-top: 1px solid var(--color-border);
        background: var(--color-bg-tertiary);
    }

    /* Toggle Switch */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 26px;
        flex-shrink: 0;
    }

    .toggle-switch input { display: none; }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background: #334155;
        border-radius: 13px;
        transition: 0.3s;
    }

    .toggle-slider::before {
        position: absolute;
        content: '';
        width: 20px; height: 20px;
        left: 3px; bottom: 3px;
        background: white;
        border-radius: 50%;
        transition: 0.3s;
    }

    input:checked + .toggle-slider { background: #ef4444; }
    input:checked + .toggle-slider::before { transform: translateX(22px); }

    /* Add Stream Form */
    .add-stream-panel {
        background: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: 12px;
        overflow: hidden;
    }

    .panel-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--color-border);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .panel-header i { color: var(--color-rugby-green); }

    .panel-body { padding: 1.25rem; }

    /* Edit Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.open { display: flex; }

    .modal-box {
        background: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: 16px;
        width: 100%;
        max-width: 560px;
        margin: 1rem;
        overflow: hidden;
        animation: modalIn 0.2s ease;
    }

    @keyframes modalIn {
        from { opacity: 0; transform: translateY(-20px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .modal-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--color-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-body { padding: 1.25rem; }

    .modal-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid var(--color-border);
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }

    /* Stats Bar */
    .stats-bar {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .stat-chip {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }

    .stat-chip .num {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--color-text-primary);
    }

    .stat-chip .label {
        color: var(--color-text-tertiary);
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Provider icons */
    .icon-yt { color: #ff0000; }
    .icon-tw { color: #9146ff; }
    .icon-cu { color: var(--color-electric-blue); }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--color-text-muted);
    }

    .empty-state i { font-size: 3rem; opacity: 0.3; margin-bottom: 1rem; }
</style>
@endpush

@section('content')

{{-- ===== PAGE HEADER ===== --}}
<div class="stream-header">
    <div>
        <p style="color: var(--color-text-tertiary); font-size: 0.95rem; margin: 0;">
            Manage and monitor all live tournament streams.
        </p>
    </div>

    {{-- Stats Bar --}}
    <div class="stats-bar">
        <div class="stat-chip">
            <div>
                <div class="num" style="color: #ef4444;">{{ $liveCount }}</div>
                <div class="label">Live Now</div>
            </div>
        </div>
        <div class="stat-chip">
            <div>
                <div class="num" style="color: var(--color-rugby-green);">{{ number_format($totalViewers) }}</div>
                <div class="label">Total Viewers</div>
            </div>
        </div>
        <div class="stat-chip">
            <div>
                <div class="num">{{ $streams->count() }}</div>
                <div class="label">Streams Configured</div>
            </div>
        </div>
    </div>
</div>

{{-- Tournament Selector --}}
@if($tournaments->count() > 1)
<div class="card mb-lg" style="margin-bottom: 1.5rem;">
    <div class="card-body" style="padding: 1rem 1.25rem; display: flex; align-items: center; gap: 1rem;">
        <i class="fas fa-trophy" style="color: var(--color-rugby-green);"></i>
        <div style="flex: 1;">
            <label style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--color-text-muted); display: block; margin-bottom: 4px;">Select Tournament</label>
            <select class="form-select" style="max-width: 400px;" onchange="window.location.href='{{ route('admin.live-stream.index') }}?tournament_id=' + this.value">
                @foreach($tournaments as $t)
                    <option value="{{ $t->id }}" {{ $selectedTournament && $selectedTournament->id == $t->id ? 'selected' : '' }}>
                        {{ $t->name }}
                        @if($t->start_date) — {{ $t->start_date->format('d M Y') }} @endif
                    </option>
                @endforeach
            </select>
        </div>
        @if($selectedTournament)
            <span class="badge badge-{{ $selectedTournament->status === 'ongoing' ? 'success' : ($selectedTournament->status === 'upcoming' ? 'info' : 'secondary') }}">
                {{ strtoupper($selectedTournament->status ?? 'N/A') }}
            </span>
        @endif
    </div>
</div>
@endif

<div class="row">

    {{-- ===== LEFT: Stream List ===== --}}
    <div class="col-lg-8">

        {{-- Stream Cards Grid --}}
        @if($streams->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                @foreach($streams as $stream)
                    <div class="stream-card {{ $stream->status === 'live' ? 'is-live' : ($stream->status === 'scheduled' ? 'is-scheduled' : '') }}" id="card-{{ $stream->id }}">

                        {{-- Thumbnail --}}
                        <div class="stream-thumbnail">
                            @if($stream->provider === 'youtube' && $stream->thumbnail_url)
                                <img src="{{ $stream->thumbnail_url }}" alt="{{ $stream->field_name }}"
                                     onerror="this.style.display='none'">
                            @endif
                            <div class="thumbnail-placeholder">
                                <i class="fas fa-{{ $stream->provider === 'youtube' ? 'youtube' : ($stream->provider === 'twitch' ? 'twitch' : 'video') }}"
                                   style="font-size: 2.5rem; opacity: 0.3; color: white;"></i>
                            </div>

                            {{-- Status Badge --}}
                            <div class="stream-status-badge status-{{ $stream->status }}" id="badge-{{ $stream->id }}">
                                @if($stream->status === 'live')
                                    <span class="live-pulse">LIVE</span>
                                @elseif($stream->status === 'scheduled')
                                    📅 SCHEDULED
                                @else
                                    ⏸ OFFLINE
                                @endif
                            </div>
                        </div>

                        {{-- Stream Info --}}
                        <div class="stream-info">
                            <div class="stream-field">
                                <i class="fas fa-map-marker-alt"></i> {{ $stream->field_name }}
                            </div>
                            <div class="stream-title">
                                {{ $stream->title ?: ($stream->fixture ? ($stream->fixture->homeTeam->name ?? 'TBD') . ' vs ' . ($stream->fixture->awayTeam->name ?? 'TBD') : 'General Stream') }}
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                <span class="stream-provider-badge">
                                    @if($stream->provider === 'youtube')
                                        <i class="fab fa-youtube icon-yt"></i> YouTube
                                    @elseif($stream->provider === 'twitch')
                                        <i class="fab fa-twitch icon-tw"></i> Twitch
                                    @else
                                        <i class="fas fa-link icon-cu"></i> Custom
                                    @endif
                                </span>
                                <span style="font-size: 0.7rem; color: var(--color-text-muted); font-family: monospace;">{{ Str::limit($stream->video_id, 20) }}</span>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="stream-actions">
                            {{-- Toggle Live/Offline --}}
                            <label class="toggle-switch" title="{{ $stream->status === 'live' ? 'Click to set Offline' : 'Click to set Live' }}">
                                <input type="checkbox"
                                    {{ $stream->status === 'live' ? 'checked' : '' }}
                                    onchange="toggleStream({{ $stream->id }}, this)">
                                <span class="toggle-slider"></span>
                            </label>
                            <span style="font-size: 0.75rem; color: var(--color-text-secondary); margin: auto 0;" id="toggle-label-{{ $stream->id }}">
                                {{ $stream->status === 'live' ? 'LIVE' : strtoupper($stream->status) }}
                            </span>

                            <div style="margin-left: auto; display: flex; gap: 0.4rem;">
                                {{-- Preview --}}
                                <a href="{{ $stream->watch_url }}" target="_blank"
                                   class="btn btn-sm btn-secondary" title="Preview stream">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                {{-- Edit --}}
                                <button class="btn btn-sm btn-outline" title="Edit"
                                    onclick="openEditModal({{ $stream->id }}, '{{ addslashes($stream->field_name) }}', '{{ addslashes($stream->title ?? '') }}', '{{ $stream->provider }}', '{{ addslashes($stream->video_id) }}', '{{ addslashes($stream->stream_url ?? '') }}', '{{ $stream->status }}', '{{ $stream->fixture_id ?? '' }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                {{-- Delete --}}
                                <form action="{{ route('admin.live-stream.destroy', $stream->id) }}" method="POST" style="margin: 0;"
                                    onsubmit="return confirm('Delete stream {{ addslashes($stream->field_name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="add-stream-panel" style="margin-bottom: 1.5rem;">
                <div class="empty-state">
                    <i class="fas fa-broadcast-tower"></i>
                    <h3 style="font-size: 1.1rem; font-weight: 600; color: var(--color-text-secondary);">No Streams Configured</h3>
                    <p style="font-size: 0.85rem; max-width: 400px; margin: 0.5rem auto;">
                        Add the first stream for this tournament using the form on the right.
                    </p>
                </div>
            </div>
        @endif

        {{-- ===== LIVE PREVIEW ===== --}}
        @if($streams->where('status', 'live')->count() > 0)
            @php $liveStream = $streams->where('status', 'live')->first(); @endphp
            <div class="add-stream-panel">
                <div class="panel-header">
                    <i class="fas fa-desktop"></i>
                    <div>
                        <div style="font-weight: 700; font-size: 0.9rem;">Live Preview</div>
                        <div style="font-size: 0.75rem; color: var(--color-text-tertiary);">{{ $liveStream->field_name }}</div>
                    </div>
                    <span style="margin-left: auto;" class="live-pulse" style="color: #ef4444;">LIVE</span>
                </div>
                <div class="panel-body" style="padding: 0;">
                    <div style="position: relative; padding-top: 56.25%; background: black;">
                        <iframe
                            style="position: absolute; top:0; left:0; width:100%; height:100%; border:none;"
                            src="{{ $liveStream->embed_url }}"
                            allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                        </iframe>
                    </div>
                </div>
                <div style="padding: 0.75rem 1.25rem; border-top: 1px solid var(--color-border); display: flex; align-items: center; justify-content: space-between;">
                    <span style="font-size: 0.8rem; color: var(--color-text-tertiary);">
                        <i class="fas fa-signal" style="color: var(--color-rugby-green);"></i>
                        Active stream • {{ ucfirst($liveStream->provider) }}
                    </span>
                    <label class="form-check-label" style="font-size: 0.8rem; color: var(--color-text-secondary); display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" checked style="accent-color: var(--color-rugby-green);">
                        Score Overlay
                    </label>
                </div>
            </div>
        @endif

    </div>{{-- end col-lg-8 --}}

    {{-- ===== RIGHT: Add Stream Form ===== --}}
    <div class="col-lg-4">
        <div class="add-stream-panel">
            <div class="panel-header">
                <i class="fas fa-plus-circle"></i>
                <div style="font-weight: 700; font-size: 0.9rem;">Add New Stream</div>
            </div>
            <div class="panel-body">
                <form action="{{ route('admin.live-stream.store') }}" method="POST" id="addStreamForm">
                    @csrf

                    {{-- Tournament (hidden atau readonly) --}}
                    <input type="hidden" name="tournament_id"
                        value="{{ $selectedTournament?->id ?? ($tournaments->first()?->id ?? '') }}">

                    {{-- Field Name --}}
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label class="form-label">Field Name <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="field_name" class="form-input"
                            placeholder="e.g. Field A, Main Field" required>
                    </div>

                    {{-- Title (Optional) --}}
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label class="form-label">Stream Title <span style="font-size: 0.75rem; color: var(--color-text-muted);">(Optional)</span></label>
                        <input type="text" name="title" class="form-input"
                            placeholder="e.g. Semi Final - Field A">
                    </div>

                    {{-- Link to Fixture --}}
                    @if($fixtures->count() > 0)
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label class="form-label">Link to Match <span style="font-size: 0.75rem; color: var(--color-text-muted);">(Optional)</span></label>
                        <select name="fixture_id" class="form-select">
                            <option value="">— Select Match —</option>
                            @foreach($fixtures as $fixture)
                                <option value="{{ $fixture->id }}">
                                    {{ $fixture->homeTeam->name ?? 'TBD' }} vs {{ $fixture->awayTeam->name ?? 'TBD' }}
                                    @if($fixture->start_time) ({{ $fixture->start_time->format('H:i') }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @else
                        <input type="hidden" name="fixture_id" value="">
                    @endif

                    {{-- Provider --}}
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label class="form-label">Streaming Platform <span style="color: #ef4444;">*</span></label>
                        <select name="provider" class="form-select" id="providerSelect" onchange="updateVideoIdHint()">
                            <option value="youtube">📺 YouTube Live</option>
                            <option value="twitch">🟣 Twitch</option>
                            <option value="custom">🔗 Custom URL</option>
                        </select>
                    </div>

                    {{-- Video ID / Channel --}}
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label class="form-label" id="videoIdLabel">YouTube Video ID <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="video_id" class="form-input" id="videoIdInput"
                            placeholder="e.g. dQw4w9WgXcQ" required>
                        <div id="videoIdHint" style="font-size: 0.72rem; color: var(--color-text-muted); margin-top: 4px;">
                            💡 Extract from YouTube URL: youtube.com/watch?v=<strong>VIDEO_ID</strong>
                        </div>
                    </div>

                    {{-- Custom URL (hanya untuk 'custom') --}}
                    <div class="form-group" id="customUrlGroup" style="margin-bottom: 1rem; display: none;">
                        <label class="form-label">Custom Stream URL</label>
                        <input type="url" name="stream_url" class="form-input" placeholder="https://...">
                    </div>

                    {{-- Status --}}
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label">Initial Status</label>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 0.85rem;">
                                <input type="radio" name="status" value="offline" checked style="accent-color: var(--color-rugby-green);"> Offline
                            </label>
                            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 0.85rem;">
                                <input type="radio" name="status" value="scheduled" style="accent-color: var(--color-electric-blue);"> Scheduled
                            </label>
                            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 0.85rem;">
                                <input type="radio" name="status" value="live" style="accent-color: #ef4444;"> 🔴 Live
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-broadcast-tower"></i> Add Stream
                    </button>
                </form>
            </div>
        </div>

        {{-- Guide Card --}}
        <div class="add-stream-panel" style="margin-top: 1rem;">
            <div class="panel-header">
                <i class="fas fa-question-circle"></i>
                <div style="font-weight: 700; font-size: 0.9rem;">How to Get Video ID</div>
            </div>
            <div class="panel-body">
                <div style="font-size: 0.8rem; color: var(--color-text-secondary); line-height: 1.7;">
                    <p style="margin: 0 0 0.75rem; font-weight: 600; color: var(--color-text-primary);">YouTube Live:</p>
                    <ol style="margin: 0 0 1rem; padding-left: 1.2rem;">
                        <li>Open YouTube Live stream</li>
                        <li>Copy from URL: <code style="background: var(--color-bg-tertiary); padding: 1px 5px; border-radius: 3px; font-size: 0.75rem;">watch?v=<strong>ABC123</strong></code></li>
                        <li>Enter <strong>ABC123</strong> only</li>
                    </ol>
                    <p style="margin: 0 0 0.75rem; font-weight: 600; color: var(--color-text-primary);">Twitch:</p>
                    <ol style="margin: 0; padding-left: 1.2rem;">
                        <li>Copy channel name only</li>
                        <li>Example: <code style="background: var(--color-bg-tertiary); padding: 1px 5px; border-radius: 3px; font-size: 0.75rem;">rugbylive7s</code></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

</div>{{-- end row --}}

{{-- ===== EDIT MODAL ===== --}}
<div class="modal-overlay" id="editModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3 style="font-size: 1rem; font-weight: 700; margin: 0;">
                <i class="fas fa-edit" style="color: var(--color-rugby-green); margin-right: 0.5rem;"></i>
                Edit Stream
            </h3>
            <button onclick="closeEditModal()" style="background: none; border: none; color: var(--color-text-tertiary); cursor: pointer; font-size: 1.2rem;">✕</button>
        </div>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="modal-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Field Name</label>
                        <input type="text" name="field_name" id="edit_field_name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" id="edit_status" class="form-select">
                            <option value="offline">Offline</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="live">🔴 Live</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Stream Title</label>
                    <input type="text" name="title" id="edit_title" class="form-input">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Platform</label>
                        <select name="provider" id="edit_provider" class="form-select">
                            <option value="youtube">YouTube</option>
                            <option value="twitch">Twitch</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Video ID / Channel</label>
                        <input type="text" name="video_id" id="edit_video_id" class="form-input" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Custom URL <span style="font-size: 0.75rem; color: var(--color-text-muted);">(Optional)</span></label>
                    <input type="url" name="stream_url" id="edit_stream_url" class="form-input" placeholder="https://...">
                </div>
                <input type="hidden" name="fixture_id" id="edit_fixture_id">
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeEditModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ===== Provider hint update =====
function updateVideoIdHint() {
    const provider = document.getElementById('providerSelect').value;
    const label  = document.getElementById('videoIdLabel');
    const hint   = document.getElementById('videoIdHint');
    const input  = document.getElementById('videoIdInput');
    const custom = document.getElementById('customUrlGroup');

    if (provider === 'youtube') {
        label.textContent = 'YouTube Video ID *';
        hint.innerHTML = '💡 From URL: youtube.com/watch?v=<strong>VIDEO_ID</strong>';
        input.placeholder = 'e.g. dQw4w9WgXcQ';
        custom.style.display = 'none';
    } else if (provider === 'twitch') {
        label.textContent = 'Twitch Channel Name *';
        hint.innerHTML = '💡 Channel name only, e.g. <strong>rugbylive7s</strong>';
        input.placeholder = 'e.g. rugbylive7s';
        custom.style.display = 'none';
    } else {
        label.textContent = 'Stream Identifier *';
        hint.textContent = '💡 Enter identifier for your custom embed URL';
        input.placeholder = 'stream-key';
        custom.style.display = 'block';
    }
}

// ===== Toggle Live/Offline via AJAX =====
function toggleStream(streamId, checkbox) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const label = document.getElementById('toggle-label-' + streamId);
    const card  = document.getElementById('card-' + streamId);
    const badge = document.getElementById('badge-' + streamId);

    checkbox.disabled = true;

    fetch(`/admin/live-streams/${streamId}/toggle`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const isLive = data.status === 'live';

            // Update label
            label.textContent = data.status.toUpperCase();

            // Update card class
            card.classList.toggle('is-live', isLive);

            // Update badge
            badge.className = 'stream-status-badge status-' + data.status;
            badge.innerHTML = isLive
                ? '<span class="live-pulse">LIVE</span>'
                : (data.status === 'scheduled' ? '📅 SCHEDULED' : '⏸ OFFLINE');

            // Reload page after short delay to refresh preview
            setTimeout(() => location.reload(), 800);
        }
    })
    .catch(() => { location.reload(); })
    .finally(() => { checkbox.disabled = false; });
}

// ===== Edit Modal =====
function openEditModal(id, fieldName, title, provider, videoId, streamUrl, status, fixtureId) {
    document.getElementById('editForm').action = `/admin/live-streams/${id}`;
    document.getElementById('edit_field_name').value = fieldName;
    document.getElementById('edit_title').value       = title;
    document.getElementById('edit_provider').value    = provider;
    document.getElementById('edit_video_id').value    = videoId;
    document.getElementById('edit_stream_url').value  = streamUrl;
    document.getElementById('edit_status').value      = status;
    document.getElementById('edit_fixture_id').value  = fixtureId;
    document.getElementById('editModal').classList.add('open');
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('open');
}

// Close modal on overlay click
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

// ===== ESC to close modal =====
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeEditModal();
});
</script>
@endpush