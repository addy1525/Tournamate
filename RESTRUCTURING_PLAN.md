# Project Restructuring Plan

## New Role-Based Structure

```
resources/views/
├── admin/
│   ├── dashboard.blade.php
│   ├── tournaments/
│   ├── teams/
│   └── users/
├── manager/
│   ├── dashboard.blade.php
│   ├── squads/
│   │   └── manage.blade.php
│   └── payments/
├── referee/
│   ├── dashboard.blade.php
│   └── console.blade.php
├── spectator/
│   ├── dashboard.blade.php
│   ├── live-stream.blade.php
│   └── brackets.blade.php
└── shared/
    └── venue-map.blade.php
```

## Controllers Structure

```
app/Http/Controllers/
├── Admin/
│   ├── DashboardController.php
│   ├── TournamentController.php
│   └── TeamController.php
├── Manager/
│   ├── DashboardController.php
│   ├── SquadController.php
│   └── PaymentController.php
├── Referee/
│   ├── DashboardController.php
│   └── ConsoleController.php
├── Spectator/
│   ├── DashboardController.php
│   └── StreamController.php
```

## Routes Structure

```
routes/
├── web.php (main + auth)
├── admin.php
├── manager.php
├── referee.php
└── spectator.php
```
