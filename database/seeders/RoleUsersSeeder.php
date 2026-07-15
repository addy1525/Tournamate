<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RoleUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@tournamate.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Create Team Manager User
        User::create([
            'name' => 'Team Manager',
            'email' => 'manager@team.com',
            'password' => Hash::make('password123'),
            'role' => 'manager',
        ]);

        // Create Referee User
        User::create([
            'name' => 'Match Referee',
            'email' => 'referee@tournamate.com',
            'password' => Hash::make('password123'),
            'role' => 'referee',
        ]);

        // Create Spectator User
        User::create([
            'name' => 'Rugby Fan',
            'email' => 'fan@email.com',
            'password' => Hash::make('password123'),
            'role' => 'spectator',
        ]);
    }
}
