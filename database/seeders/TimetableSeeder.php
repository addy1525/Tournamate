<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Timetable;

class TimetableSeeder extends Seeder
{
    public function run(): void
    {
        $timetables = [
            [
                'day_id' => 1,
                'group_id' => 1,
                'subject_id' => 1,
                'hall_id' => 1,
                'start_time' => '08:00:00',
                'end_time' => '09:30:00',
            ],
            [
                'day_id' => 2,
                'group_id' => 2,
                'subject_id' => 2,
                'hall_id' => 2,
                'start_time' => '09:30:00',
                'end_time' => '11:00:00',
            ],
            [
                'day_id' => 3,
                'group_id' => 3,
                'subject_id' => 3,
                'hall_id' => 3,
                'start_time' => '11:00:00',
                'end_time' => '12:30:00',
            ],
            [
                'day_id' => 4,
                'group_id' => 4,
                'subject_id' => 4,
                'hall_id' => 4,
                'start_time' => '13:00:00',
                'end_time' => '14:30:00',
            ],
            [
                'day_id' => 5,
                'group_id' => 5,
                'subject_id' => 5,
                'hall_id' => 5,
                'start_time' => '14:30:00',
                'end_time' => '16:00:00',
            ],
        ];

        foreach ($timetables as $timetable) {
            Timetable::firstOrCreate($timetable);
        }
    }
}
