# Tournamate - Role-Based Project Structure

## ✅ Completed Reorganization

The project has been fully restructured with role-based architecture for better maintainability and scalability.

---

## 📁 New Directory Structure

### Views Organization
```
resources/views/
├── admin/
│   └── dashboard.blade.php           ← Admin full-feature dashboard
├── manager/
│   ├── dashboard.blade.php           ← Team Manager dashboard
│   └── squads/
│       └── manage.blade.php          ← Squad management
├── referee/
│   ├── dashboard.blade.php           ← Referee dashboard
│   └── console.blade.php             ← Match official console
├── spectator/
│   ├── dashboard.blade.php           ← Spectator dashboard
│   └── live-stream.blade.php         ← Live streaming
└── shared/
    ├── brackets.blade.php            ← Tournament brackets
    └── venue-map.blade.php           ← Venue map with Mapbox
```

### Routes Organization
```
routes/
└── web.php
    ├── Auth routes (login/register)
    ├── Manager routes (/manager/*)
    ├── Referee routes (/referee/*)
    ├── Spectator routes (/spectator/*)
    └── Shared routes (/shared/*)
```

---

## 🔗 Route Structure

### Manager Routes
- **Prefix**: `/manager`
- **Name**: `manager.*`
- **Routes**:
  - `GET /manager/squads/manage/{id}` → `manager.squads.manage`
  - `GET /manager/squads/create` → `manager.squads.create`
  - `GET /manager/payments` → `manager.payments.index`

### Referee Routes
- **Prefix**: `/referee`
- **Name**: `referee.*`
- **Routes**:
  - `GET /referee/console` → `referee.console`

### Spectator Routes
- **Prefix**: `/spectator`
- **Name**: `spectator.*`
- **Routes**:
  - `GET /spectator/live-stream` → `spectator.live-stream`

### Shared Routes (All Roles)
- **Prefix**: `/shared`
- **Name**: `shared.*`
- **Routes**:
  - `GET /shared/brackets` → `shared.brackets`
  - `GET /shared/venue-map` → `shared.venue-map`

---

## 🎯 How It Works

### 1. Login Flow
1. User visits http://127.0.0.1:8000
2. Role selection page appears
3. User clicks their role (Admin/Manager/Referee/Spectator)
4. Redirected to role-specific login page
5. After login, `HomeController` routes to appropriate dashboard

### 2. Dashboard Routing

The `HomeController@index` method automatically routes users to their role-specific dashboard:

```php
switch ($user->role) {
    case 'admin':
        return view('admin.dashboard');      // resources/views/admin/dashboard.blade.php
    case 'manager':
        return view('manager.dashboard');    // resources/views/manager/dashboard.blade.php
    case 'referee':
        return view('referee.dashboard');    // resources/views/referee/dashboard.blade.php
    case 'spectator':
    default:
        return view('spectator.dashboard');  // resources/views/spectator/dashboard.blade.php
}
```

### 3. Navigation

Each role has a specific navigation menu component:
- `components/nav-admin.blade.php`
- `components/nav-manager.blade.php`
- `components/nav-referee.blade.php`
- `components/nav-spectator.blade.php`

The main layout (`layouts/dashboard.blade.php`) includes the appropriate menu based on user role.

---

## 📊 Role-Specific Features

### Admin Dashboard
- Full system statistics
- Tournament management
- Team oversight
- User administration
- All shared features

### Team Manager Dashboard
- My Teams overview (cards showing registration status)
- Player registration stats
- Payment status
- Upcoming matches for their teams only
- Quick actions: Add player, process payment, download roster

### Referee Dashboard
- Today's match assignments
- Matches officiated count
- Current status
- Match console access
- Recent match history

### Spectator Dashboard
- **LIVE** matches with scores
- Tournament standings
- Upcoming fixtures
- Quick links to live stream and brackets

---

## 🛠️ Benefits of This Structure

1. **Clear Separation**: Each role's code is in its own directory
2. **Maintainability**: Easy to find and update role-specific features
3. **Scalability**: Simple to add new roles or features
4. **Security**: Can apply middleware per role group
5. **Team Collaboration**: Different developers can work on different roles

---

## 📝 Next Steps for Development

### Add Role-Based Middleware (Recommended)

Create middleware to enforce role access:

```bash
php artisan make:middleware CheckRole
```

```php
// app/Http/Middleware/CheckRole.php
public function handle($request, Closure $next, ...$roles)
{
    if (!in_array(auth()->user()->role, $roles)) {
        abort(403, 'Unauthorized');
    }
    return $next($request);
}
```

Apply to route groups:

```php
Route::prefix('manager')->middleware(['auth', 'role:manager'])->group(...);
Route::prefix('referee')->middleware(['auth', 'role:referee'])->group(...);
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(...);
```

### Create Role-Specific Controllers

For better organization, create controllers per role:

```bash
php artisan make:controller Manager/DashboardController
php artisan make:controller Manager/SquadController
php artisan make:controller Referee/ConsoleController
php artisan make:controller Spectator/StreamController
```

---

## 🧪 Testing

Test each role by logging in with the test accounts:

```
Admin:     admin@tournamate.com      / password123
Manager:   manager@team.com          / password123
Referee:   referee@tournamate.com    / password123
Spectator: fan@email.com             / password123
```

Each user will see their appropriate dashboard and navigation.

---

## 📂 File Locations Quick Reference

| Role | Dashboard | Other Views |
|------|-----------|-------------|
| **Admin** | `admin/dashboard.blade.php` | All shared views |
| **Manager** | `manager/dashboard.blade.php` | `manager/squads/manage.blade.php` |
| **Referee** | `referee/dashboard.blade.php` | `referee/console.blade.php` |
| **Spectator** | `spectator/dashboard.blade.php` | `spectator/live-stream.blade.php` |
| **Shared** | — | `shared/brackets.blade.php`, `shared/venue-map.blade.php` |

---

**Structure is now clean, organized, and scalable!** 🎉
