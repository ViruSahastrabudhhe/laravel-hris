<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Enums\EmploymentType;
use Illuminate\Database\Eloquent\Factories\Sequence;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::factory()
            ->count(2)
            ->state(new Sequence(
                [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'gender' => 'Male',
                'email' => 'johndoe@example.com',
                'date_of_birth' => '2004-12-10',
                'phone_number' => '09123456789',
                'employment_type' => EmploymentType::Regular->value,
                'address_id' => 1,
                'is_active' => 1,
                'position_id' => 2,
                'department_id' => 2,
                'user_id' => 1,
                ],
                [
                'first_name' => 'Jane',
                'last_name' => 'Mary',
                'gender' => 'Female',
                'email' => 'maryjane@example.com',
                'date_of_birth' => '2004-08-28',
                'phone_number' => '09123456789',
                'employment_type' => EmploymentType::Regular->value,
                'address_id' => 1,
                'is_active' => 1,
                'position_id' => 3,
                'department_id' => 3,
                'user_id' => 1,
                ],

            ))
            ->create();
    }
}
