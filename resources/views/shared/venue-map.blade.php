@extends('layouts.dashboard')

@section('title', 'Venue Map')
@section('page-title', 'Venue Map & Logistics')

@section('content')
    <div class="page-header" style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:1.5rem;">
        <a href="javascript:history.back()"
           style="display:inline-flex;align-items:center;gap:6px;padding:0.5rem 1rem;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:10px;color:rgba(255,255,255,0.6);font-size:0.82rem;font-weight:600;text-decoration:none;transition:all 0.2s;flex-shrink:0;"
           onmouseover="this.style.background='rgba(255,255,255,0.12)';this.style.color='#fff';"
           onmouseout="this.style.background='rgba(255,255,255,0.06)';this.style.color='rgba(255,255,255,0.6)';">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <div>
            <h1 class="page-title" style="margin:0 0 2px;">Venue Map & Logistics</h1>
            <p class="page-subtitle" style="margin:0;">Interactive map with pitch locations and facilities</p>
        </div>
    </div>

    @if($tournaments && $tournaments->count() > 1)
        <!-- Tournament Selector -->
        <div class="card mb-lg" style="background: var(--color-bg-secondary); border: 1px solid var(--color-border);">
            <div class="card-body" style="padding: var(--spacing-lg);">
                <div style="display: flex; align-items: center; gap: var(--spacing-md);">
                    <div style="flex: 0 0 auto;">
                        <i class="fas fa-trophy" style="color: var(--color-rugby-green); font-size: 1.25rem;"></i>
                    </div>
                    <div style="flex: 1;">
                        <label for="tournament-selector" style="font-size: var(--font-size-sm); color: var(--color-text-muted); margin-bottom: var(--spacing-xs); display: block; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                            Select Tournament
                        </label>
                        <select id="tournament-selector" class="form-control" style="max-width: 400px; background: var(--color-bg-primary); border-color: var(--color-border); color: var(--color-text-primary); font-weight: 600;" onchange="window.location.href='{{ route('shared.venue-map') }}?tournament_id=' + this.value">
                            @foreach($tournaments as $t)
                                <option value="{{ $t->id }}" {{ $tournament && $tournament->id == $t->id ? 'selected' : '' }}>
                                    {{ $t->name }} - {{ $t->start_date ? $t->start_date->format('M d, Y') : 'TBD' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <!-- Map Container -->
    <div class="card mb-xl">
        <div class="card-header">
            <h2 class="card-title">{{ $tournament->venue ?? $tournament->venue_name ?? 'Tournament Venue' }}</h2>
            <div class="card-actions">
                <button class="btn btn-sm btn-secondary" id="center-map">
                    <i class="fas fa-crosshairs"></i> Center Map
                </button>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <!-- Leaflet Map -->
            <div id="venue-map" style="width: 100%; height: 600px; border-radius: 0 0 12px 12px; z-index: 1;"></div>
        </div>
    </div>

    <!-- Pitch Information (Generic/Empty State) -->
    <div class="grid grid-cols-2">
        <!-- Field 1 -->
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Main Pitch</h3>
                    <p class="card-subtitle">Primary Tournament Field</p>
                </div>
                <span class="badge badge-success">Active</span>
            </div>
            <div class="card-body">
                <div style="text-align: center; padding: var(--spacing-lg);">
                    <i class="fas fa-trophy"
                        style="font-size: 2rem; color: var(--color-rugby-green); opacity: 0.5; margin-bottom: var(--spacing-md);"></i>
                    <p class="text-muted">Awaiting scheduled matches.</p>
                </div>
            </div>
        </div>

        <!-- Field 2 -->
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Training Pitch</h3>
                    <p class="card-subtitle">Secondary Field</p>
                </div>
                <span class="badge badge-neutral">Available</span>
            </div>
            <div class="card-body">
                <div style="text-align: center; padding: var(--spacing-lg);">
                    <i class="fas fa-running"
                        style="font-size: 2rem; color: var(--color-text-muted); opacity: 0.5; margin-bottom: var(--spacing-md);"></i>
                    <p class="text-muted">Open for warm-ups.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Facilities Legend -->
    <div class="card mt-xl">
        <div class="card-header">
            <h3 class="card-title">Facilities & Amenities</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-4" style="gap: var(--spacing-lg);">
                <div style="display: flex; align-items: center; gap: var(--spacing-md);">
                    <div
                        style="width: 40px; height: 40px; background: rgba(0, 168, 107, 0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--color-rugby-green);">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; font-size: var(--font-size-sm);">Rugby Pitches</div>
                        <div style="font-size: var(--font-size-xs); color: var(--color-text-tertiary);">2 Active</div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: var(--spacing-md);">
                    <div
                        style="width: 40px; height: 40px; background: rgba(0, 212, 255, 0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--color-electric-blue);">
                        <i class="fas fa-restroom"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; font-size: var(--font-size-sm);">Restrooms</div>
                        <div style="font-size: var(--font-size-xs); color: var(--color-text-tertiary);">Check Map</div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: var(--spacing-md);">
                    <div
                        style="width: 40px; height: 40px; background: rgba(255, 167, 38, 0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--color-warning);">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; font-size: var(--font-size-sm);">Food & Bev</div>
                        <div style="font-size: var(--font-size-xs); color: var(--color-text-tertiary);">Main Pavilion</div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: var(--spacing-md);">
                    <div
                        style="width: 40px; height: 40px; background: rgba(239, 68, 68, 0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--color-danger);">
                        <i class="fas fa-first-aid"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; font-size: var(--font-size-sm);">Medical</div>
                        <div style="font-size: var(--font-size-xs); color: var(--color-text-tertiary);">Tent A</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Leaflet JS (Free Open Source Map) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get coordinates from tournament data
            @if($tournament && $tournament->location_coordinates)
                // Parse coordinates from database (format: "lat, lng")
                const coords = "{{ $tournament->location_coordinates }}".split(',');
                const defaultLat = parseFloat(coords[0].trim());
                const defaultLng = parseFloat(coords[1].trim());
            @else
                        // Fallback to MCKK if no coordinates
                        const defaultLat = 4.773531;
                const defaultLng = 100.941917;
            @endif

                const map = L.map('venue-map').setView([defaultLat, defaultLng], 17);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Add Marker for Main Venue
            L.marker([defaultLat, defaultLng]).addTo(map)
                .bindPopup('<b>{{ $tournament->venue ?? $tournament->venue_name ?? "Main Venue" }}</b><br>Main Pitch')
                .openPopup();

            // Add Medical Tent Marker (Approximate location relative to main)
            L.marker([defaultLat + 0.0005, defaultLng + 0.0005], {
                icon: L.divIcon({
                    className: 'custom-div-icon',
                    html: "<div style='background-color:#d32f2f;width:20px;height:20px;border-radius:50%;color:white;text-align:center;line-height:20px;font-weight:bold;'>+</div>",
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                })
            }).addTo(map).bindPopup('Medical Tent');

            // Center map button
            document.getElementById('center-map')?.addEventListener('click', function () {
                map.flyTo([defaultLat, defaultLng], 17);
            });

            // Fix map sizing on tab switch or load
            setTimeout(() => { map.invalidateSize(); }, 100);
        });
    </script>
@endpush