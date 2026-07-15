@extends('layouts.dashboard')

@section('title', 'Edit Tournament')
@section('page-title', 'Edit Tournament')

@push('styles')
    <!-- Leaflet Map CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    
    <style>
        /* ─── Premium Card Styling ────────────────────────────────────── */
        .premium-card {
            background: var(--color-bg-secondary) !important;
            border: 1px solid var(--color-border) !important;
            border-radius: 16px !important;
            box-shadow: var(--shadow-lg) !important;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .premium-card-header {
            background: linear-gradient(90deg, rgba(0, 168, 107, 0.08) 0%, rgba(0, 212, 255, 0.05) 100%) !important;
            border-bottom: 1px solid var(--color-border) !important;
            padding: 1.25rem 1.5rem !important;
        }

        .premium-card-title {
            font-size: 1.2rem !important;
            font-weight: 700 !important;
            color: var(--color-text-primary) !important;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .premium-card-title i {
            color: var(--color-rugby-green-light);
        }

        /* ─── Form Inputs Dark Theme ──────────────────────────────────── */
        .form-section-title {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--color-electric-blue);
            margin: 1.5rem 0 1rem;
            padding-bottom: 6px;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.08);
        }

        .form-section-title:first-of-type {
            margin-top: 0;
        }

        .form-group label {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--color-text-secondary);
            margin-bottom: 6px;
        }

        .form-control {
            background-color: var(--color-bg-primary) !important;
            border: 1px solid var(--color-border) !important;
            color: var(--color-text-primary) !important;
            border-radius: 10px !important;
            padding: 0.65rem 1rem !important;
            height: auto !important;
            transition: all var(--transition-fast) !important;
        }

        .form-control:focus {
            border-color: var(--color-electric-blue) !important;
            box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.15) !important;
            background-color: var(--color-bg-primary) !important;
        }

        .form-control::placeholder {
            color: var(--color-text-muted) !important;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right 1rem center !important;
            background-size: 16px 12px !important;
            padding-right: 2.5rem !important;
        }

        /* ─── Interactive Map & Geocode ───────────────────────────────── */
        #map-preview {
            height: 280px;
            border-radius: 12px;
            border: 1px solid var(--color-border);
            margin-top: 12px;
            z-index: 10;
        }

        .safety-readiness-indicator {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.82rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .safety-inactive {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        .safety-active {
            background: rgba(0, 168, 107, 0.08);
            border: 1px solid rgba(0, 168, 107, 0.25);
            color: var(--color-rugby-green-light, #34d399);
            box-shadow: 0 0 15px rgba(0, 168, 107, 0.1);
        }

        /* Leaflet Dark Theme Tiles override */
        .leaflet-container {
            background-color: var(--color-bg-primary) !important;
        }
    </style>
@endpush

@section('content')
    <div class="content pt-4">
        <div class="container-fluid">
            
            <form action="{{ route('admin.tournaments.update', $tournament->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card premium-card">
                    <div class="card-header premium-card-header">
                        <h3 class="premium-card-title">
                            <i class="fas fa-edit"></i> Edit Tournament Details
                        </h3>
                    </div>
                    
                    <div class="card-body" style="padding: 1.75rem;">
                        <div class="row">
                            
                            {{-- LEFT COLUMN: Basic Info & Scheduling ── --}}
                            <div class="col-lg-6">
                                <div class="form-section-title">General Information</div>
                                
                                <div class="form-group mb-4">
                                    <label for="name">Tournament Name</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                        value="{{ $tournament->name }}" required autocomplete="off">
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="start_date">Start Date</label>
                                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                                value="{{ optional($tournament->start_date)->format('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="end_date">End Date</label>
                                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                                value="{{ optional($tournament->end_date)->format('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="status">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="upcoming" {{ $tournament->status == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                                <option value="ongoing" {{ $tournament->status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                                <option value="completed" {{ $tournament->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="fee">Registration Fee (RM)</label>
                                            <input type="number" step="0.01" class="form-control" id="fee" name="fee"
                                                value="{{ $tournament->fee ?? 250.00 }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="max_teams">Maximum Teams Allowed</label>
                                            <input type="number" class="form-control" id="max_teams" name="max_teams"
                                                value="{{ $tournament->max_teams }}" placeholder="e.g. 16 (Leave blank for unlimited)">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="registration_deadline">Registration Deadline</label>
                                            <input type="datetime-local" class="form-control" id="registration_deadline" name="registration_deadline"
                                                value="{{ $tournament->registration_deadline ? $tournament->registration_deadline->format('Y-m-d\TH:i') : '' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="categories">Categories <small class="text-muted">(Comma separated)</small></label>
                                    <input type="text" class="form-control" id="categories" name="categories"
                                        value="{{ old('categories', $tournament->categories) }}" placeholder="e.g. Open 10s, Under-20, Veterans 10s">
                                    <small class="form-text text-muted">Leave blank if no specific categories apply.</small>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description & Rules Summary</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" 
                                        placeholder="Write tournament rules, schedule descriptions..."></textarea>
                                </div>
                            </div>
                            
                            {{-- RIGHT COLUMN: Venue, Map & Safety Geolocation ── --}}
                            <div class="col-lg-6 mt-lg-0 mt-4">
                                <div class="form-section-title">📍 Venue & Safety Geolocation</div>
                                
                                <div class="form-group mb-4">
                                    <label for="venue_name">Venue Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="venue_name" name="venue_name"
                                            value="{{ $tournament->venue_name }}" required autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-info px-3 font-weight-bold" id="geocode-btn">
                                                <i class="fas fa-search-location"></i> Find Location
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="location_coordinates">Location Coordinates (Lat, Lng)</label>
                                    <input type="text" class="form-control" id="location_coordinates" name="location_coordinates" 
                                        value="{{ $tournament->location_coordinates }}" placeholder="Click 'Find Location' or drag marker on map" readonly>
                                </div>

                                {{-- Safety Status Warning Banner --}}
                                <div id="safety-indicator" class="safety-readiness-indicator safety-inactive mb-3">
                                    <i class="fas fa-exclamation-triangle" id="safety-icon"></i>
                                    <span id="safety-text">No coordinates set. Weather safety alerts will not be active.</span>
                                </div>

                                <label class="mb-1 d-block font-weight-bold" style="font-size: 0.88rem; color: var(--color-text-secondary);">
                                    Interactive Pitch Position
                                </label>
                                {{-- Map preview container --}}
                                <div id="map-preview"></div>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer" style="background: rgba(0, 0, 0, 0.15); border-top: 1px solid var(--color-border); padding: 1.25rem 1.5rem;">
                        <button type="submit" class="btn btn-primary font-weight-bold px-4 py-2">
                            <i class="fas fa-save mr-1"></i> Update Tournament
                        </button>
                        <a href="{{ route('admin.tournaments.index') }}" class="btn btn-secondary float-right px-4 py-2">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initial map state: check if coordinates exist
            let defaultLat = 4.2105;
            let defaultLng = 101.9758;
            let initialZoom = 5;
            let hasInitialCoords = false;

            const existingCoords = "{{ $tournament->location_coordinates }}";
            if (existingCoords) {
                const parts = existingCoords.split(',');
                if (parts.length === 2) {
                    const parsedLat = parseFloat(parts[0].trim());
                    const parsedLng = parseFloat(parts[1].trim());
                    if (!isNaN(parsedLat) && !isNaN(parsedLng)) {
                        defaultLat = parsedLat;
                        defaultLng = parsedLng;
                        initialZoom = 16;
                        hasInitialCoords = true;
                    }
                }
            }
            
            const map = L.map('map-preview').setView([defaultLat, defaultLng], initialZoom);

            // Using CartoDB Dark Matter tiles to fit the Tournamate dark theme
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 20
            }).addTo(map);

            let marker = null;

            // Update safety readiness indicator
            function updateSafetyStatus(hasCoords) {
                const indicator = document.getElementById('safety-indicator');
                const icon = document.getElementById('safety-icon');
                const text = document.getElementById('safety-text');

                if (hasCoords) {
                    indicator.className = "safety-readiness-indicator safety-active mb-3";
                    icon.className = "fas fa-check-circle";
                    text.innerText = "Location Verified. Ready for real-time safety alerts & meteorological telemetry.";
                } else {
                    indicator.className = "safety-readiness-indicator safety-inactive mb-3";
                    icon.className = "fas fa-exclamation-triangle";
                    text.innerText = "No coordinates set. Weather safety alerts will not be active.";
                }
            }

            // Function to handle map updates
            function updateMapLocation(lat, lng, zoomLevel = 16) {
                const coordsStr = `${lat}, ${lng}`;
                document.getElementById('location_coordinates').value = coordsStr;
                updateSafetyStatus(true);

                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                    
                    // Listen to drag event on marker
                    marker.on('dragend', function (e) {
                        const newLat = parseFloat(marker.getLatLng().lat).toFixed(6);
                        const newLng = parseFloat(marker.getLatLng().lng).toFixed(6);
                        document.getElementById('location_coordinates').value = `${newLat}, ${newLng}`;
                    });
                }

                map.flyTo([lat, lng], zoomLevel);
            }

            // Initialize marker if coordinates already exist
            if (hasInitialCoords) {
                updateMapLocation(defaultLat, defaultLng, initialZoom);
            } else {
                updateSafetyStatus(false);
            }

            // Geocoding button search trigger
            document.getElementById('geocode-btn').addEventListener('click', async function () {
                const venueName = document.getElementById('venue_name').value;

                if (!venueName) {
                    alert('Please enter a venue name first!');
                    return;
                }

                const btn = this;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching...';
                btn.disabled = true;

                try {
                    const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(venueName)}&limit=1`);
                    const data = await response.json();

                    if (data && data.length > 0) {
                        const lat = parseFloat(data[0].lat).toFixed(6);
                        const lon = parseFloat(data[0].lon).toFixed(6);
                        
                        updateMapLocation(lat, lon, 16);

                        btn.innerHTML = '<i class="fas fa-check"></i> Found!';
                        setTimeout(() => {
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                        }, 2000);
                    } else {
                        alert('Location not found. Please try a more specific venue name (e.g., "Victoria Institution Field, Kuala Lumpur" or "Stadium Hang Jebat, Melaka")');
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }
                } catch (error) {
                    console.error('Geocoding error:', error);
                    alert('Error finding location. Please try again.');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            });

            // Auto refresh map view bounds on load to fix container resizing issues
            setTimeout(() => { map.invalidateSize(); }, 300);
        });
    </script>
@endpush