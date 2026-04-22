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
                ['name' => 'Human Resources Management', 'department_code' => 'HR', 'department_head' => 'John Smith'],
                ['name' => 'Finance/Accounting', 'department_code' => 'FIN', 'department_head' => 'Sarah Johnson'],
                ['name' => 'Administration', 'department_code' => 'ADM', 'department_head' => 'Michael Brown'],
                ['name' => 'Research & Development', 'department_code' => 'RND', 'department_head' => 'Emily Davis'],
            ))
            ->create();
    }
}
