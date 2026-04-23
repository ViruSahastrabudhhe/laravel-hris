<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\EmployeeWorkSchedule;
use App\Models\EmployeeDeduction;
use App\Enums\EmploymentType;
use App\Models\Salary;
use Illuminate\Database\Eloquent\Factories\Sequence;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::factory()
            ->has(
                EmployeeWorkSchedule::factory()
                ->state(new Sequence(
                    [
                    'employee_id' => 1,
                    'work_schedule_id' => 1,
                    'user_id' => 1,
                    ],
                    [
                    'employee_id' => 2,
                    'work_schedule_id' => 1,
                    'user_id' => 1,
                    ],
                ))
            )
            ->has(
                Salary::factory()
                ->state(new Sequence(
                    [
                    'amount' => 14000,
                    'salary_grade' => 11,
                    'step' => 1,
                    'salary_type' => \App\Enums\SalaryType::Monthly->value,
                    'employee_id' => 1,
                    'user_id' => 1,
                    ],
                    [
                    'amount' => 17000,
                    'salary_grade' => 16,
                    'step' => 1,
                    'salary_type' => \App\Enums\SalaryType::Monthly->value,
                    'employee_id' => 2,
                    'user_id' => 1,
                    ],
                ))
            )
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
            ->count(2)
            ->has(
                EmployeeDeduction::factory()
                ->state(new Sequence(
                    [
                        'employee_id' => 1,
                        'deduction_id' => 1,
                        'amount' => 0,
                        'user_id' => 1,
                    ],
                    [
                        'employee_id' => 1,
                        'deduction_id' => 2,
                        'amount' => 0,
                        'user_id' => 1,
                    ],
                    [
                        'employee_id' => 1,
                        'deduction_id' => 3,
                        'amount' => 0,
                        'user_id' => 1,
                    ],
                    [
                        'employee_id' => 2,
                        'deduction_id' => 1,
                        'amount' => 0,
                        'user_id' => 1,
                    ],
                    [
                        'employee_id' => 2,
                        'deduction_id' => 2,
                        'amount' => 0,
                        'user_id' => 1,
                    ],
                    [
                        'employee_id' => 2,
                        'deduction_id' => 3,
                        'amount' => 0,
                        'user_id' => 1,
                    ],
                ))
                ->count(3)
            )
            ->create();
    }
}
