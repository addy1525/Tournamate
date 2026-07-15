<div class="nav-section">
    <div class="nav-section-label">Main</div>
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('spectator.dashboard') }}"
                class="nav-link {{ request()->routeIs('spectator.dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span>
                <span class="nav-text">Dashboard</span>
            </a>
        </li>
    </ul>
</div>

<div class="nav-section">
    <div class="nav-section-label">Tournament</div>
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('shared.standings') }}"
                class="nav-link {{ request()->routeIs('shared.standings') || request()->routeIs('spectator.standings') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-trophy"></i></span>
                <span class="nav-text">Standings</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('shared.schedule') }}"
                class="nav-link {{ request()->routeIs('shared.schedule') || request()->routeIs('spectator.schedule') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-calendar-alt"></i></span>
                <span class="nav-text">Match Schedule</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('shared.standings') }}?tab=composition"
                class="nav-link {{ false ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-layer-group"></i></span>
                <span class="nav-text">Pool Draw</span>
            </a>
        </li>
    </ul>
</div>

<div class="nav-section">
    <div class="nav-section-label">Media</div>
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('shared.live-stream') }}"
                class="nav-link {{ request()->routeIs('shared.live-stream') || request()->routeIs('spectator.live-stream') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-broadcast-tower"></i></span>
                <span class="nav-text">Live Streams</span>
                @php
                    $liveCount = \App\Models\LiveStream::where('status', 'live')->count();
                @endphp
                @if($liveCount > 0)
                    <span class="nav-badge" style="background: #ef4444;">LIVE</span>
                @endif
            </a>
        </li>
    </ul>
</div>


<div class="nav-section">
    <div class="nav-section-label">Information</div>
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('shared.venue-map') }}"
                class="nav-link {{ request()->routeIs('shared.venue-map') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-map-marked-alt"></i></span>
                <span class="nav-text">Venue Map</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('shared.info') }}"
                class="nav-link {{ request()->routeIs('shared.info') || request()->routeIs('spectator.info') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-info-circle"></i></span>
                <span class="nav-text">Tournament Info</span>
            </a>
        </li>
    </ul>
</div>

<div class="nav-section">
    <div class="nav-section-label">Account</div>
    <ul class="nav-menu">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span>
                <span class="nav-text">Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </li>
    </ul>
</div>