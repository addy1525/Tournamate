@extends('layouts.dashboard')

@section('title', 'Tournament Registrations')

@section('content')
<!-- Header -->
<div class="page-header" style="margin-bottom: var(--spacing-xl);">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 class="page-title">{{ $tournament->name }}</h1>
            <p style="color: var(--color-text-secondary); margin-top: var(--spacing-sm);">
                <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($tournament->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tournament->end_date)->format('d M Y') }}
            </p>
        </div>
        <a href="{{ route('admin.tournaments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Tournaments
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-4 mb-xl">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Teams</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Paid</div>
            <div class="stat-value">{{ $stats['paid'] }}</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-user-friends"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Players</div>
            <div class="stat-value">{{ $stats['total_players'] }}</div>
        </div>
    </div>
</div>

<!-- Registrations Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Team Registrations</h3>
        <div style="display: flex; gap: var(--spacing-sm);">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="search-input" placeholder="Search teams..." onkeyup="filterTable()">
            </div>
            <select id="status-filter" class="form-select" onchange="filterTable()" style="min-width: 150px;">
                <option value="">All Status</option>
                <option value="paid">Paid</option>
                <option value="pending">Pending</option>
            </select>
        </div>
    </div>
    <div class="card-body">
        @if($registrations->count() > 0)
            <table class="table" id="registrations-table">
                <thead>
                    <tr>
                        <th>Team Name</th>
                        <th>Manager</th>
                        <th>Category</th>
                        <th>Players</th>
                        <th>Payment Status</th>
                        <th>Registration Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrations as $registration)
                        <tr data-status="{{ $registration->payment_status }}" data-team="{{ strtolower($registration->team->name) }}">
                            <td>
                                <div style="display: flex; align-items: center; gap: var(--spacing-sm);">
                                    @if($registration->team->logo)
                                        <img src="{{ asset('storage/' . $registration->team->logo) }}" 
                                             style="width: 32px; height: 32px; border-radius: 6px; object-fit: cover;">
                                    @else
                                        <div style="width: 32px; height: 32px; border-radius: 6px; background: var(--color-bg-tertiary); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-shield-alt" style="color: var(--color-text-muted);"></i>
                                        </div>
                                    @endif
                                    <strong>{{ $registration->team->name }}</strong>
                                </div>
                            </td>
                            <td>
                                <div>{{ $registration->manager->name }}</div>
                                <div style="font-size: var(--font-size-xs); color: var(--color-text-muted);">
                                    {{ $registration->manager->email }}
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $registration->registered_category ?: 'Open' }}</span>
                            </td>
                            <td>
                                @php
                                    $playerCount = $registration->team->players->count();
                                    $maxPlayers = $tournament->max_players_per_team ?? 23;
                                    $percentage = $maxPlayers > 0 ? ($playerCount / $maxPlayers) * 100 : 0;
                                @endphp
                                <div style="display: flex; align-items: center; gap: var(--spacing-sm);">
                                    <strong style="color: {{ $percentage >= 100 ? 'var(--color-rugby-green)' : 'var(--color-warning)' }};">
                                        {{ $playerCount }}/{{ $maxPlayers }}
                                    </strong>
                                    <div style="flex: 1; background: var(--color-bg-tertiary); height: 6px; border-radius: 3px; overflow: hidden;">
                                        <div style="width: {{ min($percentage, 100) }}%; height: 100%; background: {{ $percentage >= 100 ? 'var(--color-rugby-green)' : 'var(--color-warning)' }};"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($registration->payment_status === 'paid')
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> Paid
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td>{{ $registration->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <div style="display: flex; gap: var(--spacing-xs);">
                                    <a href="{{ route('admin.tournaments.teams.roster', [$tournament->id, $registration->team_id]) }}" 
                                       class="btn btn-sm btn-primary" title="View Roster">
                                        <i class="fas fa-users"></i>
                                    </a>
                                    <a href="{{ route('manager.registrations.show', $registration->id) }}" 
                                       class="btn btn-sm btn-secondary" title="View Ticket">
                                        <i class="fas fa-ticket-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: var(--spacing-xxl); color: var(--color-text-muted);">
                <i class="fas fa-inbox fa-4x" style="margin-bottom: var(--spacing-lg);"></i>
                <h3 style="color: var(--color-text-secondary); margin-bottom: var(--spacing-sm);">No Registrations Yet</h3>
                <p style="font-size: var(--font-size-sm);">Teams will appear here once they register for this tournament</p>
            </div>
        @endif
    </div>
</div>

<script>
function filterTable() {
    const searchValue = document.getElementById('search-input').value.toLowerCase();
    const statusFilter = document.getElementById('status-filter').value.toLowerCase();
    const table = document.getElementById('registrations-table');
    const rows = table ? table.getElementsByTagName('tbody')[0].getElementsByTagName('tr') : [];
    
    for (let row of rows) {
        const teamName = row.getAttribute('data-team');
        const status = row.getAttribute('data-status');
        
        const matchesSearch = !searchValue || teamName.includes(searchValue);
        const matchesStatus = !statusFilter || status === statusFilter;
        
        row.style.display = matchesSearch && matchesStatus ? '' : 'none';
    }
}
</script>

<style>
.search-box {
    position: relative;
    display: flex;
    align-items: center;
}
.search-box i {
    position: absolute;
    left: var(--spacing-md);
    color: var(--color-text-muted);
}
.search-box input {
    padding: var(--spacing-sm) var(--spacing-md) var(--spacing-sm) var(--spacing-xl);
    background: var(--color-bg-tertiary);
    border: 1px solid var(--color-border);
    border-radius: 6px;
    color: var(--color-text-primary);
    min-width: 250px;
}
.search-box input:focus {
    outline: none;
    border-color: var(--color-rugby-green);
}
.form-select {
    padding: var(--spacing-sm) var(--spacing-md);
    background: var(--color-bg-tertiary);
    border: 1px solid var(--color-border);
    border-radius: 6px;
    color: var(--color-text-primary);
}
</style>
@endsection
