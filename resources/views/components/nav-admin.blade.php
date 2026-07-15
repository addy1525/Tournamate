<div class="nav-section">
    <div class="nav-section-label">Main</div>
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span>
                <span class="nav-text">Dashboard</span>
            </a>
        </li>
    </ul>
</div>

<div class="nav-section">
    <div class="nav-section-label">Management</div>
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('admin.tournaments.index') }}"
                class="nav-link {{ request()->routeIs('admin.tournaments.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-trophy"></i></span>
                <span class="nav-text">Tournaments</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('teams.index') }}" class="nav-link {{ request()->routeIs('teams.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-users"></i></span>
                <span class="nav-text">Teams</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-user-shield"></i></span>
                <span class="nav-text">Users & Roles</span>
            </a>
        </li>
    </ul>
</div>

<div class="nav-section">
    <div class="nav-section-label">Operations</div>
    <ul class="nav-menu">

        <li class="nav-item">
            <a href="{{ route('operations.index') }}"
                class="nav-link {{ request()->routeIs('operations.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-cloud-sun"></i></span>
                <span class="nav-text">Safety & Weather</span>
            </a>
        </li>
    </ul>
</div>

<div class="nav-section">
    <div class="nav-section-label">Media</div>
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('admin.live-stream.index') }}"
                class="nav-link {{ request()->routeIs('admin.live-stream.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-broadcast-tower"></i></span>
                <span class="nav-text">Live Streams</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('shared.brackets') }}"
                class="nav-link {{ request()->routeIs('shared.brackets') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-sitemap"></i></span>
                <span class="nav-text">Brackets</span>
            </a>
        </li>
    </ul>
</div>

<div class="nav-section">
    <div class="nav-section-label">System</div>
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('reports.index') }}"
                class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-chart-bar"></i></span>
                <span class="nav-text">Reports</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('settings.index') }}"
                class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-cog"></i></span>
                <span class="nav-text">Settings</span>
            </a>
        </li>
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