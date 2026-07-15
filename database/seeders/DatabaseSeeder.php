<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DaySeeder::class,
            GroupSeeder::class,
            SubjectSeeder::class,
            HallSeeder::class,
            TimetableSeeder::class, 
        ]);
    }
}
