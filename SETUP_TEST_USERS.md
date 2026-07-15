# Setting Up Test User Credentials

## Step 1: Add Role Column to Users Table

First, create a migration to add the `role` column:

```bash
php artisan make:migration add_role_to_users_table
```

Then edit the migration file in `database/migrations/`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('spectator')->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
```

Run the migration:
```bash
php artisan migrate
```

## Step 2: Create Test Users

Run the seeder I created:

```bash
php artisan db:seed --class=RoleUsersSeeder
```

## Step 3: Test Credentials

After running the seeder, you can login with these accounts:

### Admin Login (http://127.0.0.1:8000/login/admin)
- **Email**: `admin@tournamate.com`
- **Password**: `password123`

### Team Manager Login (http://127.0.0.1:8000/login/manager)
- **Email**: `manager@team.com`
- **Password**: `password123`

### Referee Login (http://127.0.0.1:8000/login/referee)
- **Email**: `referee@tournamate.com`
- **Password**: `password123`

### Spectator Login (http://127.0.0.1:8000/login/spectator)
- **Email**: `fan@email.com`
- **Password**: `password123`

## Manual Creation (Alternative)

If you prefer to create users manually via MySQL:

```sql
-- Add role column if not exists
ALTER TABLE users ADD COLUMN role VARCHAR(20) DEFAULT 'spectator' AFTER email;

-- Create test users
INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES
('Admin User', 'admin@tournamate.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW()),
('Team Manager', 'manager@team.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager', NOW(), NOW()),
('Match Referee', 'referee@tournamate.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'referee', NOW(), NOW()),
('Rugby Fan', 'fan@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'spectator', NOW(), NOW());
```

Note: The password hash above is for `password` (default Laravel hash). Use `password123` for the seeded accounts.

## Quick Command Reference

```bash
# Create migration
php artisan make:migration add_role_to_users_table

# Run migration
php artisan migrate

# Seed test users
php artisan db:seed --class=RoleUsersSeeder

# Or fresh migration with seeding
php artisan migrate:fresh --seed
```
