@extends('layouts.dashboard')

@section('title', 'Team Detail')
@section('page-title', $team->name)

@section('content')
    <style>
        .tab-navigation {
            display: flex;
            gap: var(--spacing-sm);
            margin-bottom: var(--spacing-xl);
            border-bottom: 2px solid var(--color-border);
        }

        .tab-btn {
            padding: var(--spacing-md) var(--spacing-lg);
            background: transparent;
            border: none;
            color: var(--color-text-secondary);
            cursor: pointer;
            font-weight: 600;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .tab-btn:hover {
            color: var(--color-text-primary);
        }

        .tab-btn.active {
            color: var(--color-rugby-green);
            border-bottom-color: var(--color-rugby-green);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .player-table {
            width: 100%;
            border-collapse: collapse;
        }

        .player-table th {
            text-align: left;
            padding: var(--spacing-md);
            background: var(--color-bg-tertiary);
            color: var(--color-text-muted);
            font-size: var(--font-size-xs);
            text-transform: uppercase;
            font-weight: 600;
        }

        .player-table td {
            padding: var(--spacing-md);
            border-bottom: 1px solid var(--color-border);
            color: var(--color-text-primary);
        }

        .player-table tr:hover {
            background: var(--color-bg-tertiary);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: var(--color-bg-secondary);
            padding: var(--spacing-xl);
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            border: 1px solid var(--color-border);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        }

        .form-group {
            margin-bottom: var(--spacing-lg);
        }

        .form-group label {
            display: block;
            margin-bottom: var(--spacing-sm);
            color: var(--color-text-secondary);
            font-weight: 600;
            font-size: var(--font-size-sm);
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: var(--spacing-md);
            background: var(--color-bg-tertiary);
            border: 1px solid var(--color-border);
            border-radius: 6px;
            color: var(--color-text-primary);
            font-size: var(--font-size-md);
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--color-rugby-green);
        }

        .player-counter {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-sm) var(--spacing-md);
            background: var(--color-bg-tertiary);
            border-radius: 20px;
            font-size: var(--font-size-sm);
            font-weight: 600;
        }

        .player-counter .count {
            color: var(--color-rugby-green);
        }

        .team-logo-preview {
            width: 120px;
            height: 120px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid var(--color-border);
        }
    </style>

    <!-- Header -->
    <div class="card mb-xl">
        <div class="card-body" style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: var(--spacing-lg);">
                <div>
                    <h1 style="font-size: var(--font-size-xxl); font-weight: 700; margin-bottom: var(--spacing-sm);">
                        {{ $team->name }}
                    </h1>
                    @if($registration)
                        <p style="color: var(--color-text-secondary); font-size: var(--font-size-md);">
                            <i class="fas fa-trophy"></i> {{ $registration->tournament->name }}
                        </p>
                    @endif
                </div>
            </div>
            <div>
                @if($registration)
                    @if($registration->payment_status === 'paid')
                        <span class="badge badge-success"
                            style="font-size: var(--font-size-md); padding: var(--spacing-sm) var(--spacing-lg);">
                            <i class="fas fa-check-circle"></i> Confirmed
                        </span>
                    @else
                        <span class="badge badge-warning"
                            style="font-size: var(--font-size-md); padding: var(--spacing-sm) var(--spacing-lg);">
                            <i class="fas fa-clock"></i> Payment Pending
                        </span>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tab-navigation">
        <button class="tab-btn active" onclick="switchTab('team-info')">
            <i class="fas fa-info-circle"></i> Team Info
        </button>
        <button class="tab-btn" onclick="switchTab('squad-roster')">
            <i class="fas fa-users"></i> Squad Roster
        </button>
        <button class="tab-btn" onclick="switchTab('matches')">
            <i class="fas fa-calendar-alt"></i> Matches
        </button>
    </div>

    <!-- Tab Content: Team Info -->
    <div id="team-info" class="tab-content active">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Team Information</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('manager.teams.update-info', $team->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-2" style="gap: var(--spacing-xl);">
                        <div>
                            <div class="form-group">
                                <label>Team Logo</label>
                                @if($team->logo)
                                    <img src="{{ asset('storage/' . $team->logo) }}" alt="Team Logo" class="team-logo-preview"
                                        style="margin-bottom: var(--spacing-md);">
                                @else
                                    <div class="team-logo-preview"
                                        style="display: flex; align-items: center; justify-content: center; background: var(--color-bg-tertiary); margin-bottom: var(--spacing-md);">
                                        <i class="fas fa-shield-alt fa-3x" style="color: var(--color-text-muted);"></i>
                                    </div>
                                @endif
                                <input type="file" name="logo" accept="image/*" class="form-control">
                                <p
                                    style="font-size: var(--font-size-xs); color: var(--color-text-muted); margin-top: var(--spacing-sm);">
                                    Max 2MB, JPG/PNG/GIF
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label>Head Coach Name</label>
                                <input type="text" name="head_coach" value="{{ $team->head_coach ?? '' }}"
                                    placeholder="Enter coach name">
                            </div>
                            <div class="form-group">
                                <label>Manager Name</label>
                                <input type="text" value="{{ $team->manager->name }}" disabled style="opacity: 0.6;">
                            </div>
                            <div class="form-group">
                                <label>Contact Phone</label>
                                <input type="text" value="{{ $team->manager->phone ?? 'Not set' }}" disabled
                                    style="opacity: 0.6;">
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: var(--spacing-xl);">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tab Content: Squad Roster -->
    <div id="squad-roster" class="tab-content">
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 class="card-title">Squad Roster</h3>
                    <div class="player-counter">
                        <i class="fas fa-users"></i>
                        <span class="count" id="player-count">{{ $team->players->count() }}</span> / {{ $maxPlayers }}
                        Players
                    </div>
                </div>
                <button class="btn btn-primary" onclick="openAddPlayerModal()" id="add-player-btn" {{ $team->players->count() >= $maxPlayers ? 'disabled' : '' }}>
                    <i class="fas fa-user-plus"></i> Add Player
                </button>
            </div>
            <div class="card-body">
                @if($team->players->count() > 0)
                    <table class="player-table" id="player-table">
                        <thead>
                            <tr>
                                <th>Jersey No</th>
                                <th>Player Name</th>
                                <th>Position</th>
                                <th>IC/ID Number</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($team->players as $player)
                                <tr id="player-row-{{ $player->id }}">
                                    <td><strong style="color: var(--color-rugby-green);">#{{ $player->jersey_number }}</strong></td>
                                    <td>{{ $player->name }}</td>
                                    <td>
                                        <span class="badge {{ $player->position == 'forward' ? 'badge-info' : 'badge-warning' }}">
                                            {{ ucfirst($player->position) }}
                                        </span>
                                    </td>
                                    <td>{{ $player->ic_number }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-danger" onclick="deletePlayer({{ $player->id }})">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="text-align: center; padding: var(--spacing-xxl); color: var(--color-text-muted);">
                        <i class="fas fa-users fa-4x" style="margin-bottom: var(--spacing-lg);"></i>
                        <h3 style="color: var(--color-text-secondary); margin-bottom: var(--spacing-sm);">No Players Added Yet
                        </h3>
                        <p style="font-size: var(--font-size-sm);">Click "Add Player" to start building your roster</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tab Content: Matches -->
    <div id="matches" class="tab-content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Team Matches & Fixtures</h3>
            </div>
            <div class="card-body">
                @if(isset($fixtures) && $fixtures->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
                        @foreach($fixtures as $fix)
                            @php
                                $isLive = $fix->status === 'in_progress' || $fix->status === 'ongoing';
                                $isCompleted = $fix->status === 'completed';
                                $cardBg = $isLive ? 'rgba(239, 68, 68, 0.04)' : ($isCompleted ? 'rgba(255,255,255,0.01)' : 'var(--color-bg-tertiary)');
                                $cardBorder = $isLive ? '1px solid rgba(239, 68, 68, 0.2)' : '1px solid var(--color-border)';
                            @endphp
                            <div style="padding: 1.25rem; background: {{ $cardBg }}; border: {{ $cardBorder }}; border-radius: 8px; display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
                                <div style="flex: 1;">
                                    <div style="font-size: 0.75rem; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                                        {{ $fix->tournament->name ?? 'Tournament' }}
                                        @if($fix->pool) • {{ $fix->pool->name }} @elseif($fix->stage) • {{ $fix->stage }} @endif
                                        @if($fix->start_time)
                                             • {{ \Carbon\Carbon::parse($fix->start_time)->format('d M Y, h:i A') }}
                                        @endif
                                    </div>
                                    <div style="font-size: 1rem; font-weight: 600; color: var(--color-text-primary); display: flex; align-items: center; gap: 12px;">
                                        <span style="{{ $fix->home_team_id == $team->id ? 'color: var(--color-rugby-green-light); font-weight: 700;' : '' }}">{{ $fix->homeTeam->name ?? 'TBD' }}</span>
                                        <span style="font-weight: 800; font-size: 1.1rem; color: {{ $isLive ? '#ef4444' : 'var(--color-text-muted)' }}; background: rgba(0,0,0,0.25); padding: 2px 10px; border-radius: 4px;">
                                            {{ $fix->home_score ?? 0 }} – {{ $fix->away_score ?? 0 }}
                                        </span>
                                        <span style="{{ $fix->away_team_id == $team->id ? 'color: var(--color-rugby-green-light); font-weight: 700;' : '' }}">{{ $fix->awayTeam->name ?? 'TBD' }}</span>
                                    </div>
                                </div>
                                <div style="flex-shrink: 0;">
                                    @if($isLive)
                                        <span class="badge badge-danger" style="animation: pulse 1.5s infinite; background: #ef4444; border: none;">● LIVE NOW</span>
                                    @elseif($isCompleted)
                                        <span class="badge badge-success" style="background: rgba(0,168,107,0.15); color: #34d399; border: 1px solid rgba(0,168,107,0.3);">Completed</span>
                                    @elseif($fix->status == 'draft')
                                        <span class="badge badge-secondary" style="background: rgba(255,255,255,0.05); color: var(--color-text-secondary);">Draft</span>
                                    @else
                                        <span class="badge badge-info" style="background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.3);">Scheduled</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: var(--spacing-xxl); color: var(--color-text-muted);">
                        <i class="fas fa-calendar-times fa-4x" style="margin-bottom: var(--spacing-lg);"></i>
                        <h3 style="color: var(--color-text-secondary); margin-bottom: var(--spacing-sm);">No Matches Scheduled</h3>
                        <p style="font-size: var(--font-size-sm);">Match fixtures will appear here once the tournament scheduler generates them.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Player Modal -->
    <div id="add-player-modal" class="modal">
        <div class="modal-content">
            <div
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-xl);">
                <h3 style="font-size: var(--font-size-xl); font-weight: 700;">Add New Player</h3>
                <button onclick="closeAddPlayerModal()"
                    style="background: none; border: none; color: var(--color-text-muted); font-size: var(--font-size-xl); cursor: pointer;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="add-player-form" onsubmit="submitAddPlayer(event)">
                <div class="form-group">
                    <label>Jersey Number *</label>
                    <input type="number" name="jersey_number" min="1" max="99" required>
                </div>
                <div class="form-group">
                    <label>Player Name *</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Position *</label>
                    <select name="position" required>
                        <option value="">Select Position</option>
                        <option value="forward">Forward</option>
                        <option value="back">Back</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>IC/ID Number *</label>
                    <input type="text" name="ic_number" required>
                </div>
                <div id="error-message"
                    style="display: none; padding: var(--spacing-md); background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; border-radius: 6px; color: #ef4444; margin-bottom: var(--spacing-md);">
                </div>
                <div style="display: flex; gap: var(--spacing-sm);">
                    <button type="submit" class="btn btn-primary flex-1">
                        <i class="fas fa-check"></i> Add Player
                    </button>
                    <button type="button" onclick="closeAddPlayerModal()" class="btn btn-secondary">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Tab Switching
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));

            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }

        // Modal Functions
        function openAddPlayerModal() {
            document.getElementById('add-player-modal').classList.add('active');
            document.getElementById('error-message').style.display = 'none';
        }

        function closeAddPlayerModal() {
            document.getElementById('add-player-modal').classList.remove('active');
            document.getElementById('add-player-form').reset();
            document.getElementById('error-message').style.display = 'none';
        }

        // Add Player AJAX
        function submitAddPlayer(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            fetch('{{ route('manager.teams.add-player', $team->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Reload to show new player
                    } else {
                        document.getElementById('error-message').textContent = data.message;
                        document.getElementById('error-message').style.display = 'block';
                    }
                })
                .catch(error => {
                    document.getElementById('error-message').textContent = 'An error occurred. Please try again.';
                    document.getElementById('error-message').style.display = 'block';
                });
        }

        // Delete Player AJAX
        function deletePlayer(playerId) {
            if (!confirm('Are you sure you want to remove this player?')) return;

            fetch(`{{ route('manager.teams.detail', $team->id) }}/players/${playerId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Reload to update roster
                    }
                });
        }
    </script>
@endsection