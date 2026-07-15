<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['subject_code' => 'MAT101', 'subject_name' => 'Mathematics', 'lecturer_name' => 'Dr. Ahmad'],
            ['subject_code' => 'SCI102', 'subject_name' => 'Science', 'lecturer_name' => 'Ms. Nurul'],
            ['subject_code' => 'ENG103', 'subject_name' => 'English', 'lecturer_name' => 'Mr. John'],
            ['subject_code' => 'HIS104', 'subject_name' => 'History', 'lecturer_name' => 'Ms. Aina'],
            ['subject_code' => 'CSC105', 'subject_name' => 'Computer Science', 'lecturer_name' => 'Dr. Lim'],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate($subject);
        }
    }
}
