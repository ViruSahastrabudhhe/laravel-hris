<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = \App\Models\Employee::all();
        foreach ($employees as $employee) {
            \App\Models\Salary::factory()->create([
                'employee_id' => $employee->id,
            ]);
        }
    }
}
