<div class="nav-section">
    <div class="nav-section-label">Match Officials</div>
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('referee.console') }}"
                class="nav-link {{ request()->routeIs('referee.console') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                        <path d="M14.4 6L14 4H5v17h2v-7h5.6l.4 2h7V6z" />
                    </svg>
                </span>
                <span class="nav-text">Match Console</span>
                @php $liveCount = \App\Models\Fixture::where('status', 'in_progress')->count(); @endphp
                @if($liveCount > 0)
                    <span class="nav-badge" style="background: #ef4444; animation: pulse 1.5s infinite;">LIVE</span>
                @else
                    <span class="nav-badge" style="background: var(--color-electric-blue); color: #fff;">READY</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('referee.assignments') }}"
                class="nav-link {{ request()->routeIs('referee.assignments') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-clipboard-list"></i></span>
                <span class="nav-text">Assignments</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('referee.history') }}"
                class="nav-link {{ request()->routeIs('referee.history') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-history"></i></span>
                <span class="nav-text">Match History</span>
            </a>
        </li>
    </ul>
</div>



<div class="nav-section">
    <div class="nav-section-label">Safety</div>
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('referee.safety') }}"
                class="nav-link {{ request()->routeIs('referee.safety') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-cloud-sun"></i></span>
                <span class="nav-text">Safety Conditions</span>
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