<div class="nav-section">
    <div class="nav-section-label">Main</div>
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-th-large"></i></span>
                <span class="nav-text">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('manager.browse-tournaments') }}"
                class="nav-link {{ request()->routeIs('manager.browse-tournaments') ? 'active' : '' }}"
                style="background: linear-gradient(135deg, rgba(0, 168, 107, 0.15), rgba(0, 132, 255, 0.1)); border-left: 3px solid var(--color-rugby-green);">
                <span class="nav-icon"><i class="fas fa-trophy"></i></span>
                <span class="nav-text">Browse Tournaments</span>
                <span class="nav-badge" style="background: var(--color-rugby-green);">New</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('manager.my-applications') }}"
                class="nav-link {{ request()->routeIs('manager.my-applications') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-clipboard-list"></i></span>
                <span class="nav-text">My Applications</span>
            </a>
        </li>
    </ul>
</div>

<div class="nav-section">
    <div class="nav-section-label">Finance</div>
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('manager.payment-history') }}"
                class="nav-link {{ request()->routeIs('manager.payment-history') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-receipt"></i></span>
                <span class="nav-text">Payment History</span>
            </a>
        </li>
    </ul>
</div>

<div class="nav-section">
    <div class="nav-section-label">Information</div>
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('manager.schedule') }}"
                class="nav-link {{ request()->routeIs('manager.schedule') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-calendar-alt"></i></span>
                <span class="nav-text">Schedule</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('shared.standings') }}"
                class="nav-link {{ request()->routeIs('shared.standings') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-list-ol"></i></span>
                <span class="nav-text">Standings</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('manager.schedule') }}#pool-draw"
                class="nav-link {{ request()->routeIs('manager.schedule') ? 'active' : '' }}"
                style="position: relative;">
                <span class="nav-icon"><i class="fas fa-layer-group"></i></span>
                <span class="nav-text">Pool Draw</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('shared.live-stream') }}"
                class="nav-link {{ request()->routeIs('shared.live-stream') ? 'active' : '' }}"
                style="position: relative;">
                <span class="nav-icon"><i class="fas fa-broadcast-tower"></i></span>
                <span class="nav-text">Live Streams</span>
                @php
                    $liveCount = \App\Models\LiveStream::where('status', 'live')->count();
                @endphp
                @if($liveCount > 0)
                    <span class="nav-badge" style="background: #ef4444; animation: pulse 1.5s infinite;">LIVE</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('shared.brackets') }}"
                class="nav-link {{ request()->routeIs('shared.brackets') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-sitemap"></i></span>
                <span class="nav-text">Brackets</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('shared.venue-map') }}"
                class="nav-link {{ request()->routeIs('shared.venue-map') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-map-marked-alt"></i></span>
                <span class="nav-text">Venue Map</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('shared.info') }}"
                class="nav-link {{ request()->routeIs('shared.info') ? 'active' : '' }}">
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