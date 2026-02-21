<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::factory()
            ->count(4)
            ->state(new Sequence(
                ['name' => 'Human Resources Management'],
                ['name' => 'Finance/Accounting'],
                ['name' => 'Administration'],
                ['name' => 'Research & Development'],
            ))
            ->create();
    }
}
