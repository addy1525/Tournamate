<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Creating Admin User\n";
echo "===================\n\n";

// Check if admin already exists
$existingAdmin = \App\Models\User::where('email', 'admin@tournamate.com')->first();

if ($existingAdmin) {
    echo "Admin user already exists!\n";
    echo "Email: admin@tournamate.com\n";
    echo "Resetting password to: password\n\n";
    
    $existingAdmin->password = bcrypt('password');
    $existingAdmin->role = 'admin';
    $existingAdmin->status = 'active';
    $existingAdmin->save();
    
    echo "✅ Password reset successfully!\n";
} else {
    echo "Creating new admin user...\n";
    
    $admin = \App\Models\User::create([
        'name' => 'Admin User',
        'email' => 'admin@tournamate.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'status' => 'active',
        'email_verified_at' => now(),
    ]);
    
    echo "✅ Admin user created successfully!\n";
}

echo "\n";
echo "Login Credentials:\n";
echo "==================\n";
echo "Email: admin@tournamate.com\n";
echo "Password: password\n";
echo "\n";

// List all users
echo "All users in database:\n";
echo "======================\n";
$users = \App\Models\User::all();
foreach ($users as $user) {
    echo "- {$user->email} ({$user->role}) - Status: " . ($user->status ?? 'N/A') . "\n";
}
echo "\nTotal: " . $users->count() . " users\n";
