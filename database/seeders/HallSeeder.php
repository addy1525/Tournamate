<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hall;

class HallSeeder extends Seeder
{
    public function run(): void
    {
        $halls = [
            ['lecture_hall_name' => 'Main Lecture Hall', 'lecture_hall_place' => 'Block A'],
            ['lecture_hall_name' => 'Auditorium', 'lecture_hall_place' => 'Block B'],
            ['lecture_hall_name' => 'Lecture Hall 1', 'lecture_hall_place' => 'Block C'],
            ['lecture_hall_name' => 'Lecture Hall 2', 'lecture_hall_place' => 'Block D'],
            ['lecture_hall_name' => 'Seminar Room', 'lecture_hall_place' => 'Block E'],
        ];

        foreach ($halls as $hall) {
            Hall::firstOrCreate($hall);
        }
    }
}
