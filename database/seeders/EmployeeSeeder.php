<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Sequence;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::factory()->create([   
            'first_name' => 'John',
            'last_name' => 'Doe',
            'gender' => 'Male',
            'email' => 'johndoe@example.com',
            'date_of_birth' => '2026-02-22',
            'address' => 'Address',
            'phone_number' => '09123456789',
            'employment_type' => 'Regular',
            'salary' => 14777.01,
            'is_active' => 1,
            'position_id' => 2,
            'department_id' => 2,
            'user_id' => 1,
        ]);
    }
}
