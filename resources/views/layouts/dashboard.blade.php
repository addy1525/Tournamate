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

                <div class="header-right">
                    @auth
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