# Role-Based Login Pages - Route Configuration

This guide shows you how to add routes for the role-specific login pages.

## Add These Routes to Your Laravel Application

Open `routes/web.php` and add the following routes:

```php
use App\Http\Controllers\Auth\LoginController;

// Role-Specific Login Pages
Route::get('/login/admin', function () {
    return view('auth.login-admin');
})->name('login.admin');

Route::get('/login/manager', function () {
    return view('auth.login-manager');
})->name('login.manager');

Route::get('/login/referee', function () {
    return view('auth.login-referee');
})->name('login.referee');

Route::get('/login/spectator', function () {
    return view('auth.login-spectator');
})->name('login.spectator');

// Default login route (can redirect to role selection page)
Route::get('/login', function () {
    return view('auth.login-admin'); // Or create a role selector page
})->name('login');

// Handle login POST request
Route::post('/login', [LoginController::class, 'login']);
```

## Access URLs

Once routes are configured, access the login pages at:

- **Admin Login**: http://127.0.0.1:8000/login/admin
- **Team Manager Login**: http://127.0.0.1:8000/login/manager
- **Referee Login**: http://127.0.0.1:8000/login/referee
- **Spectator Login**: http://127.0.0.1:8000/login/spectator

## Optional: Role-Based Redirect After Login

Update your `LoginController` to redirect users to appropriate dashboards based on their role:

```php
// app/Http/Controllers/Auth/LoginController.php

protected function authenticated(Request $request, $user)
{
    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'manager':
            return redirect()->route('manager.dashboard');
        case 'referee':
            return redirect()->route('referee.dashboard');
        case 'spectator':
            return redirect()->route('spectator.dashboard');
        default:
            return redirect()->route('home');
    }
}
```

## Features of Each Login Page

### Admin Login
- **Icon**: Shield
- **Color**: Rugby Green gradient
- **Features**: Full system access, tournament & team management, reports

### Team Manager Login
- **Icon**: Users
- **Color**: Rugby Green gradient
- **Features**: Squad registration, payment processing, tournament viewing
- **Extra**: "Register New Team" link

### Referee Login
- **Icon**: Whistle
- **Color**: Electric Blue gradient
- **Features**: Match console, event logging, safety monitoring
- **Extra**: Today's assignments preview

### Spectator Login
- **Icon**: Eye
- **Color**: Neutral gray
- **Features**: Live streaming, brackets, venue maps
- **Extra**: "Create Free Account" option + Live matches indicator

## Cross-Links

Each login page includes quick-access buttons to switch between role logins, making it easy for users to find the correct portal.
