<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        $group_seed = [
            ['id' => 1, 'name' => 'Group A', 'part' => 'Part 1'],
            ['id' => 2, 'name' => 'Group B', 'part' => 'Part 1'],
            ['id' => 3, 'name' => 'Group C', 'part' => 'Part 2'],
            ['id' => 4, 'name' => 'Group D', 'part' => 'Part 2'],
            ['id' => 5, 'name' => 'Group E', 'part' => 'Part 3'],
        ];

        foreach ($group_seed as $group) {
            Group::firstOrCreate($group);
        }
    }
}

