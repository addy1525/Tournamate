<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="mapbox-token" content="{{ env('MAPBOX_TOKEN', '') }}">

    <title>@yield('title', 'Dashboard') - Tournamate</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- AdminLTE & Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <!-- Tournamate Styles -->
    <link rel="stylesheet" href="{{ asset('css/tournamate-styles.css') }}">

    @stack('styles')
</head>

<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="sidebar" id="sidebar">
            <!-- Brand -->
            <div class="sidebar-brand">
                <div class="brand-logo">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-rugby-green);">
                        <path d="M5 22V2 M19 22V2 M5 10H19" />
                    </svg>
                </div>
                <div class="brand-text">
                    <div class="brand-name">Tournamate</div>
                    <div class="brand-tagline">Rugby Management</div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav>
                @auth
                    @php
                        $userRole = strtolower(Auth::user()->role ?? 'spectator');
                    @endphp

                    @if($userRole === 'admin')
                        @include('components.nav-admin')
                    @elseif($userRole === 'manager')
                        @include('components.nav-manager')
                    @elseif($userRole === 'referee')
                        @include('components.nav-referee')
                    @else
                        @include('components.nav-spectator')
                    @endif
                @endauth
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Header -->
            <header class="dashboard-header">
                <div class="header-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="header-title">@yield('page-title', 'Dashboard')</h1>
                </div>

                <div class="header-right" style="display: flex; align-items: center; gap: 1.5rem;">
                    @auth
                        <!-- Notifications Dropdown -->
                        <div class="dropdown notification-dropdown" style="position: relative;">
                            <button class="btn btn-link position-relative" type="button" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: var(--color-text-secondary); padding: 0; font-size: 1.25rem; text-decoration: none; border: none; background: transparent;">
                                <i class="fas fa-bell"></i>
                                @php
                                    $unreadCount = Auth::user()->unreadNotifications->count();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="badge badge-danger position-absolute" style="top: -5px; right: -8px; font-size: 0.65rem; border-radius: 50%; padding: 3px 6px; line-height: 1; background: #ef4444;">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationDropdown" style="width: 320px; max-height: 400px; overflow-y: auto; padding: 0; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border-radius: var(--border-radius-lg); border: 1px solid var(--color-border); background: var(--color-bg-card);">
                                <div class="dropdown-header" style="font-weight: 700; color: var(--color-text-primary); border-bottom: 1px solid var(--color-border); padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; background: var(--color-bg-tertiary);">
                                    <span>Notifications</span>
                                    @if($unreadCount > 0)
                                        <form action="{{ route('notifications.markAllRead') }}" method="POST" style="margin: 0;">
                                            @csrf
                                            <button type="submit" class="btn btn-link p-0 text-success" style="font-size: 0.75rem; font-weight: 600; text-decoration: none; border: none; background: transparent;">Mark all as read</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="dropdown-body" style="padding: 0;">
                                    @if(Auth::user()->notifications->count() > 0)
                                        @foreach(Auth::user()->notifications->take(5) as $notification)
                                            <div class="dropdown-item d-flex align-items-start {{ $notification->read_at ? '' : 'unread' }}" style="padding: 12px 16px; border-bottom: 1px solid var(--color-border); white-space: normal; cursor: pointer; transition: background 0.2s; background: {{ $notification->read_at ? 'transparent' : 'rgba(0, 168, 107, 0.05)' }};" onclick="window.location='{{ $notification->data['url'] ?? '#' }}'">
                                                <div style="margin-right: 12px; color: {{ $notification->data['color'] ?? 'var(--color-electric-blue)' }}; font-size: 1rem;">
                                                    <i class="{{ $notification->data['icon'] ?? 'fas fa-info-circle' }}"></i>
                                                </div>
                                                <div style="flex: 1;">
                                                    <div style="font-size: 0.85rem; font-weight: {{ $notification->read_at ? '500' : '700' }}; color: var(--color-text-primary); margin-bottom: 2px;">
                                                        {{ $notification->data['title'] ?? 'Notification' }}
                                                    </div>
                                                    <div style="font-size: 0.75rem; color: var(--color-text-secondary); line-height: 1.3; margin-bottom: 4px;">
                                                        {{ $notification->data['message'] ?? '' }}
                                                    </div>
                                                    <div style="font-size: 0.65rem; color: var(--color-text-tertiary);">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div style="padding: 24px; text-align: center; color: var(--color-text-tertiary);">
                                            <i class="fas fa-bell-slash" style="font-size: 1.5rem; margin-bottom: 8px; display: block;"></i>
                                            <span style="font-size: 0.85rem;">No notifications yet</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="dropdown-footer text-center" style="border-top: 1px solid var(--color-border); padding: 10px 16px;">
                                    <a href="{{ route('notifications.index') }}" style="font-size: 0.75rem; font-weight: 600; color: var(--color-electric-blue); text-decoration: none; display: block;">View all notifications</a>
                                </div>
                            </div>
                        </div>

                        <!-- User Profile -->
                        <div class="user-profile">
                            <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" alt="User Avatar"
                                class="user-avatar"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=00a86b&color=fff'">
                            <div class="user-info">
                                <div class="user-name">{{ Auth::user()->name }}</div>
                                <div class="user-role">{{ Auth::user()->role ?? 'User' }}</div>
                            </div>
                        </div>
                    @endauth
                </div>
            </header>

            <!-- Safety Bar -->
            @if(!request()->routeIs('login') && !request()->routeIs('register'))
                @php
                    $globalSafetyLog = \App\Models\SafetyLog::latest()->first();
                    $wbgtValue = $globalSafetyLog->wbgt ?? null;
                    $lightningValue = $globalSafetyLog->lightning_risk ?? null;

                    // Determine WBGT status
                    $wbgtStatus = 'safe';
                    if ($wbgtValue !== null) {
                        if ($wbgtValue >= 32)
                            $wbgtStatus = 'danger';
                        elseif ($wbgtValue >= 28)
                            $wbgtStatus = 'warning';
                    }

                    // Determine Lightning status
                    $lightningStatus = 'safe';
                    if ($lightningValue !== null) {
                        if ($lightningValue <= 10)
                            $lightningStatus = 'danger';
                        elseif ($lightningValue <= 20)
                            $lightningStatus = 'warning';
                    }
                @endphp
                <div class="safety-bar">
                    <div class="safety-metrics">
                        <div class="safety-metric" data-metric="wbgt">
                            <div class="safety-metric-icon {{ $wbgtStatus }}">
                                <i class="fas fa-temperature-high"></i>
                            </div>
                            <div class="safety-metric-data">
                                <div class="safety-metric-label">WBGT</div>
                                <div class="safety-metric-value">
                                    {{ $wbgtValue ? number_format($wbgtValue, 1) . '°C' : 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="safety-metric" data-metric="lightning">
                            <div class="safety-metric-icon {{ $lightningStatus }}">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div class="safety-metric-data">
                                <div class="safety-metric-label">Lightning</div>
                                <div class="safety-metric-value">
                                    {{ $lightningValue ? number_format($lightningValue, 1) . ' km' : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <div style="font-size: var(--font-size-xs); color: var(--color-text-tertiary);">
                        <i class="fas fa-sync-alt"></i> Updated
                        {{ $globalSafetyLog ? $globalSafetyLog->created_at->diffForHumans() : 'never' }}
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <main class="content-area">
                @if(session('success'))
                    <div class="alert alert-success">
                        <div class="alert-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="alert-content">
                            <div class="alert-title">Success</div>
                            <div>{{ session('success') }}</div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <div class="alert-icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="alert-content">
                            <div class="alert-title">Error</div>
                            <div>{{ session('error') }}</div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- AdminLTE & Bootstrap Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Tournamate JavaScript -->
    <script src="{{ asset('js/tournamate-app.js') }}"></script>

    @stack('scripts')
</body>

</html>