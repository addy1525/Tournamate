<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'admin@tournamate.com')->first();

if ($user) {
    $user->password = Hash::make('password123');
    $user->save();
    echo "✓ Password reset successfully for {$user->email}\n";
    echo "Email: admin@tournamate.com\n";
    echo "Password: password123\n";
} else {
    echo "✗ User not found!\n";
    
    // Create the admin user
    echo "Creating admin user...\n";
    User::create([
        'name' => 'Admin User',
        'email' => 'admin@tournamate.com',
        'password' => Hash::make('password123'),
        'role' => 'admin',
    ]);
    echo "✓ Admin user created!\n";
    echo "Email: admin@tournamate.com\n";
    echo "Password: password123\n";
}
