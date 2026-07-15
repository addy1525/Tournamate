/**
 * Tournamate - Smart Rugby Management System
 * JavaScript Application Logic
 */

// ==================== GLOBAL STATE ====================
const TournamateApp = {
    state: {
        sidebarCollapsed: false,
        currentUser: null,
        isMobile: window.innerWidth < 1024
    },
    
    init() {
        this.setupEventListeners();
        this.detectMobile();
        this.initSidebar();
        this.loadUserData();
    },
    
    setupEventListeners() {
        // Sidebar toggle
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => this.toggleSidebar());
        }
        
        // Close sidebar on outside click (mobile)
        document.addEventListener('click', (e) => {
            if (this.state.isMobile) {
                const sidebar = document.querySelector('.sidebar');
                const sidebarToggle = document.querySelector('.sidebar-toggle');
                if (sidebar && !sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    this.closeSidebar();
                }
            }
        });
        
        // Window resize handler
        window.addEventListener('resize', () => this.detectMobile());
    },
    
    detectMobile() {
        const wasMobile = this.state.isMobile;
        this.state.isMobile = window.innerWidth < 1024;
        
        if (wasMobile !== this.state.isMobile) {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                if (this.state.isMobile) {
                    sidebar.classList.remove('collapsed');
                    sidebar.classList.remove('open');
                } else {
                    sidebar.classList.remove('open');
                }
            }
        }
    },
    
    initSidebar() {
        const savedState = localStorage.getItem('sidebarCollapsed');
        if (savedState === 'true' && !this.state.isMobile) {
            this.state.sidebarCollapsed = true;
            document.querySelector('.sidebar')?.classList.add('collapsed');
        }
    },
    
    toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        if (!sidebar) return;
        
        if (this.state.isMobile) {
            sidebar.classList.toggle('open');
        } else {
            this.state.sidebarCollapsed = !this.state.sidebarCollapsed;
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', this.state.sidebarCollapsed);
        }
    },
    
    closeSidebar() {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar && this.state.isMobile) {
            sidebar.classList.remove('open');
        }
    },
    
    loadUserData() {
        // This would typically load from backend
        // For now, we can read from the DOM if available
        const userName = document.querySelector('.user-name')?.textContent;
        const userRole = document.querySelector('.user-role')?.textContent;
        
        if (userName && userRole) {
            this.state.currentUser = {
                name: userName,
                role: userRole
            };
        }
    }
};

// ==================== SAFETY BAR ====================
const SafetyBar = {
    updateInterval: null,
    
    init() {
        this.startAutoUpdate();
    },
    
    startAutoUpdate() {
        // Update every 60 seconds
        this.updateInterval = setInterval(() => {
            this.fetchWeatherData();
        }, 60000);
        
        // Initial load
        this.fetchWeatherData();
    },
    
    async fetchWeatherData() {
        try {
            // Placeholder API endpoint - replace with actual backend route
            const response = await fetch('/api/weather/current');
            
            if (!response.ok) {
                // Use demo data if API not configured
                this.renderDemoData();
                return;
            }
            
            const data = await response.json();
            this.render(data);
        } catch (error) {
            console.log('Weather API not configured, using demo data');
            this.renderDemoData();
        }
    },
    
    renderDemoData() {
        const demoData = {
            wbgt: 24.5,
            wbgtStatus: 'safe', // safe, warning, danger
            lightningDistance: 15.2,
            lightningStatus: 'safe'
        };
        this.render(demoData);
    },
    
    render(data) {
        const safetyBar = document.querySelector('.safety-bar');
        if (!safetyBar) return;
        
        // Update WBGT
        const wbgtValue = safetyBar.querySelector('[data-metric="wbgt"] .safety-metric-value');
        const wbgtIcon = safetyBar.querySelector('[data-metric="wbgt"] .safety-metric-icon');
        if (wbgtValue) {
            wbgtValue.textContent = `${data.wbgt}°C`;
        }
        if (wbgtIcon) {
            wbgtIcon.className = `safety-metric-icon ${data.wbgtStatus}`;
        }
        
        // Update Lightning Distance
        const lightningValue = safetyBar.querySelector('[data-metric="lightning"] .safety-metric-value');
        const lightningIcon = safetyBar.querySelector('[data-metric="lightning"] .safety-metric-icon');
        if (lightningValue) {
            lightningValue.textContent = `${data.lightningDistance} km`;
        }
        if (lightningIcon) {
            lightningIcon.className = `safety-metric-icon ${data.lightningStatus}`;
        }
        
        // Update safety bar class
        safetyBar.className = 'safety-bar';
        if (data.wbgtStatus === 'danger' || data.lightningStatus === 'danger') {
            safetyBar.classList.add('danger');
        } else if (data.wbgtStatus === 'warning' || data.lightningStatus === 'warning') {
            safetyBar.classList.add('warning');
        }
    },
    
    stop() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
        }
    }
};

// ==================== DASHBOARD ====================
const Dashboard = {
    init() {
        this.loadTournamentStandings();
        this.loadFixtures();
        this.loadStats();
    },
    
    async loadTournamentStandings() {
        try {
            const response = await fetch('/api/toornament/standings');
            if (!response.ok) throw new Error('API not configured');
            
            const data = await response.json();
            this.renderStandings(data);
        } catch (error) {
            console.log('Tournament API not configured, using demo data');
            this.renderDemoStandings();
        }
    },
    
    renderDemoStandings() {
        const demoData = [
            { position: 1, team: 'Wellington Warriors', played: 5, won: 5, drawn: 0, lost: 0, points: 25 },
            { position: 2, team: 'Auckland Aces', played: 5, won: 4, drawn: 0, lost: 1, points: 20 },
            { position: 3, team: 'Canterbury Crusaders', played: 5, won: 3, drawn: 0, lost: 2, points: 15 },
            { position: 4, team: 'Otago Highlanders', played: 5, won: 2, drawn: 0, lost: 3, points: 10 }
        ];
        this.renderStandings(demoData);
    },
    
    renderStandings(data) {
        const tbody = document.querySelector('#standings-table tbody');
        if (!tbody) return;
        
        tbody.innerHTML = data.map(team => `
            <tr>
                <td class="font-bold">${team.position}</td>
                <td>${team.team}</td>
                <td>${team.played}</td>
                <td>${team.won}</td>
                <td>${team.drawn}</td>
                <td>${team.lost}</td>
                <td class="font-bold" style="color: var(--color-rugby-green)">${team.points}</td>
            </tr>
        `).join('');
    },
    
    async loadFixtures() {
        try {
            const response = await fetch('/api/toornament/fixtures');
            if (!response.ok) throw new Error('API not configured');
            
            const data = await response.json();
            this.renderFixtures(data);
        } catch (error) {
            console.log('Fixtures API not configured, using demo data');
            this.renderDemoFixtures();
        }
    },
    
    renderDemoFixtures() {
        const demoData = [
            { time: '10:00', teamA: 'Wellington Warriors', teamB: 'Auckland Aces', pitch: 'Field 1' },
            { time: '12:00', teamA: 'Canterbury Crusaders', teamB: 'Otago Highlanders', pitch: 'Field 2' },
            { time: '14:00', teamA: 'Auckland Aces', teamB: 'Canterbury Crusaders', pitch: 'Field 1' }
        ];
        this.renderFixtures(demoData);
    },
    
    renderFixtures(data) {
        const container = document.querySelector('#fixtures-list');
        if (!container) return;
        
        container.innerHTML = data.map(fixture => `
            <div style="padding: var(--spacing-md); border-bottom: 1px solid var(--color-border);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 600; color: var(--color-text-primary);">
                            ${fixture.teamA} vs ${fixture.teamB}
                        </div>
                        <div style="font-size: var(--font-size-sm); color: var(--color-text-tertiary); margin-top: 4px;">
                            ${fixture.pitch}
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div class="badge badge-info">${fixture.time}</div>
                    </div>
                </div>
            </div>
        `).join('');
    },
    
    async loadStats() {
        // Load dashboard statistics
        const stats = {
            activeTournaments: 3,
            totalTeams: 24,
            upcomingMatches: 12
        };
        
        this.renderStats(stats);
    },
    
    renderStats(stats) {
        const tournamentsStat = document.querySelector('[data-stat="tournaments"] .stat-value');
        const teamsStat = document.querySelector('[data-stat="teams"] .stat-value');
        const matchesStat = document.querySelector('[data-stat="matches"] .stat-value');
        
        if (tournamentsStat) tournamentsStat.textContent = stats.activeTournaments;
        if (teamsStat) teamsStat.textContent = stats.totalTeams;
        if (matchesStat) matchesStat.textContent = stats.upcomingMatches;
    }
};

// ==================== REFEREE CONSOLE ====================
const RefereeConsole = {
    matchData: {
        homeScore: 0,
        awayScore: 0,
        period: 1,
        timeElapsed: 0,
        isRunning: false,
        events: []
    },
    
    timer: null,
    
    init() {
        this.setupControls();
    },
    
    setupControls() {
        // Start/Pause Timer
        const startBtn = document.querySelector('#start-timer');
        const pauseBtn = document.querySelector('#pause-timer');
        
        if (startBtn) {
            startBtn.addEventListener('click', () => this.startTimer());
        }
        if (pauseBtn) {
            pauseBtn.addEventListener('click', () => this.pauseTimer());
        }
        
        // Scoring buttons
        const scoreButtons = document.querySelectorAll('[data-score-action]');
        scoreButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const action = e.target.dataset.scoreAction;
                const team = e.target.dataset.team;
                const points = parseInt(e.target.dataset.points);
                this.addScore(team, points, action);
            });
        });
        
        // Card buttons
        const cardButtons = document.querySelectorAll('[data-card-action]');
        cardButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const cardType = e.target.dataset.cardAction;
                const team = e.target.dataset.team;
                this.addCard(team, cardType);
            });
        });
    },
    
    startTimer() {
        this.matchData.isRunning = true;
        this.timer = setInterval(() => {
            this.matchData.timeElapsed++;
            this.updateTimerDisplay();
        }, 1000);
    },
    
    pauseTimer() {
        this.matchData.isRunning = false;
        if (this.timer) {
            clearInterval(this.timer);
            this.timer = null;
        }
    },
    
    updateTimerDisplay() {
        const display = document.querySelector('#match-timer');
        if (!display) return;
        
        const minutes = Math.floor(this.matchData.timeElapsed / 60);
        const seconds = this.matchData.timeElapsed % 60;
        display.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    },
    
    addScore(team, points, action) {
        if (team === 'home') {
            this.matchData.homeScore += points;
        } else {
            this.matchData.awayScore += points;
        }
        
        this.matchData.events.push({
            time: this.matchData.timeElapsed,
            type: 'score',
            team: team,
            action: action,
            points: points
        });
        
        this.updateScoreDisplay();
        this.updateEventLog();
        this.saveMatch();
    },
    
    addCard(team, cardType) {
        this.matchData.events.push({
            time: this.matchData.timeElapsed,
            type: 'card',
            team: team,
            cardType: cardType
        });
        
        this.updateEventLog();
        this.saveMatch();
    },
    
    updateScoreDisplay() {
        const homeScoreEl = document.querySelector('#home-score');
        const awayScoreEl = document.querySelector('#away-score');
        
        if (homeScoreEl) homeScoreEl.textContent = this.matchData.homeScore;
        if (awayScoreEl) awayScoreEl.textContent = this.matchData.awayScore;
    },
    
    updateEventLog() {
        const eventLog = document.querySelector('#event-log');
        if (!eventLog) return;
        
        const eventsHtml = this.matchData.events.slice().reverse().map(event => {
            const minutes = Math.floor(event.time / 60);
            const timeStr = `${minutes}'`;
            
            if (event.type === 'score') {
                return `
                    <div style="padding: var(--spacing-sm); border-bottom: 1px solid var(--color-border); display: flex; justify-content: space-between;">
                        <span style="color: var(--color-text-tertiary);">${timeStr}</span>
                        <span style="color: var(--color-text-primary);">${event.action} - ${event.team === 'home' ? 'Home' : 'Away'}</span>
                        <span class="badge badge-success">+${event.points}</span>
                    </div>
                `;
            } else {
                const badgeClass = event.cardType === 'yellow' ? 'badge-warning' : 'badge-danger';
                return `
                    <div style="padding: var(--spacing-sm); border-bottom: 1px solid var(--color-border); display: flex; justify-content: space-between;">
                        <span style="color: var(--color-text-tertiary);">${timeStr}</span>
                        <span style="color: var(--color-text-primary);">${event.cardType.toUpperCase()} Card - ${event.team === 'home' ? 'Home' : 'Away'}</span>
                        <span class="badge ${badgeClass}">CARD</span>
                    </div>
                `;
            }
        }).join('');
        
        eventLog.innerHTML = eventsHtml || '<div style="padding: var(--spacing-lg); text-align: center; color: var(--color-text-muted);">No events recorded</div>';
    },
    
    async saveMatch() {
        // Save match data to backend
        try {
            await fetch('/api/matches/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify(this.matchData)
            });
        } catch (error) {
            console.log('Auto-save failed (API not configured)');
        }
    }
};

// ==================== PAYMENT INTEGRATION ====================
const PaymentManager = {
    async initiatePayment(teamId, amount) {
        try {
            // Call backend to create Stripe checkout session
            const response = await fetch('/api/payments/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({ teamId, amount })
            });
            
            if (!response.ok) {
                throw new Error('Payment initialization failed');
            }
            
            const data = await response.json();
            
            // Redirect to Stripe Checkout
            if (data.checkoutUrl) {
                window.location.href = data.checkoutUrl;
            }
        } catch (error) {
            console.error('Payment error:', error);
            alert('Payment system not configured. Please contact administrator.');
        }
    }
};

// ==================== MAPBOX INTEGRATION ====================
const VenueMap = {
    map: null,
    
    init(containerId, center, zoom = 15) {
        if (typeof mapboxgl === 'undefined') {
            console.error('Mapbox GL JS not loaded');
            return;
        }
        
        const mapboxToken = document.querySelector('meta[name="mapbox-token"]')?.getAttribute('content');
        if (!mapboxToken) {
            console.error('Mapbox token not configured');
            return;
        }
        
        mapboxgl.accessToken = mapboxToken;
        
        this.map = new mapboxgl.Map({
            container: containerId,
            style: 'mapbox://styles/mapbox/dark-v11',
            center: center,
            zoom: zoom
        });
        
        this.map.addControl(new mapboxgl.NavigationControl());
        
        this.addPitchMarkers();
    },
    
    addPitchMarkers() {
        // This would load from backend
        const pitches = [
            { lat: -41.2865, lng: 174.7762, name: 'Field 1' },
            { lat: -41.2875, lng: 174.7772, name: 'Field 2' }
        ];
        
        pitches.forEach(pitch => {
            const marker = new mapboxgl.Marker({ color: '#00a86b' })
                .setLngLat([pitch.lng, pitch.lat])
                .setPopup(new mapboxgl.Popup().setHTML(`<strong>${pitch.name}</strong>`))
                .addTo(this.map);
        });
    }
};

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', () => {
    // Initialize core app
    TournamateApp.init();
    
    // Initialize page-specific modules
    if (document.querySelector('.safety-bar')) {
        SafetyBar.init();
    }
    
    if (document.querySelector('#standings-table')) {
        Dashboard.init();
    }
    
    if (document.querySelector('#referee-console')) {
        RefereeConsole.init();
    }
    
    if (document.querySelector('#venue-map')) {
        const mapCenter = [-41.2865, 174.7762]; // Wellington, NZ as default
        VenueMap.init('venue-map', mapCenter);
    }
});

// Clean up on page unload
window.addEventListener('beforeunload', () => {
    SafetyBar.stop();
    if (RefereeConsole.timer) {
        RefereeConsole.pauseTimer();
    }
});

// Export for use in other scripts
window.Tournamate = {
    App: TournamateApp,
    SafetyBar: SafetyBar,
    Dashboard: Dashboard,
    RefereeConsole: RefereeConsole,
    PaymentManager: PaymentManager,
    VenueMap: VenueMap
};
