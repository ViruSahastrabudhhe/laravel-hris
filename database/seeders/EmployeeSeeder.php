<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\EmployeeWorkSchedule;
use App\Models\EmployeeDeduction;
use App\Models\Salary;
use App\Enums\EmploymentType;
use App\Enums\SalaryType;
use Illuminate\Database\Eloquent\Factories\Sequence;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
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
            [
                'first_name' => 'Job',
                'last_name' => 'Order',
                'gender' => 'Female',
                'email' => 'joborder@example.com',
                'date_of_birth' => '1997-06-07',
                'phone_number' => '09123456789',
                'employment_type' => EmploymentType::JobOrder->value,
                'address_id' => 1,
                'is_active' => 1,
                'position_id' => 4,
                'department_id' => 4,
                'user_id' => 1,
            ],
        ];

        $salaries = [
            ['amount' => 14000, 'salary_grade' => 1, 'step' => 1, 'salary_type' => SalaryType::Monthly->value, 'user_id' => 1],
            ['amount' => 17000, 'salary_grade' => 3, 'step' => 1, 'salary_type' => SalaryType::Monthly->value, 'user_id' => 1],
            ['amount' => 16000, 'salary_grade' => 2, 'step' => 1, 'salary_type' => SalaryType::Monthly->value, 'user_id' => 1],
        ];

        $workSchedules = [
            ['work_schedule_id' => 1, 'user_id' => 1],
            ['work_schedule_id' => 1, 'user_id' => 1],
            ['work_schedule_id' => 1, 'user_id' => 1],
        ];

        $deductions = [
            [
                ['deduction_id' => 1, 'amount' => 0, 'user_id' => 1],
                ['deduction_id' => 2, 'amount' => 0, 'user_id' => 1],
                ['deduction_id' => 3, 'amount' => 0, 'user_id' => 1],
            ],
            [
                ['deduction_id' => 1, 'amount' => 0, 'user_id' => 1],
                ['deduction_id' => 2, 'amount' => 0, 'user_id' => 1],
                ['deduction_id' => 3, 'amount' => 0, 'user_id' => 1],
            ],
            [
                ['deduction_id' => 1, 'amount' => 0, 'user_id' => 1],
                ['deduction_id' => 2, 'amount' => 0, 'user_id' => 1],
                ['deduction_id' => 3, 'amount' => 0, 'user_id' => 1],
            ],
        ];

        $passwords = [
            'password',
            'password',
            'password',
        ];

        foreach ($employees as $index => $employeeData) {
            $employee = Employee::factory()
                ->has(EmployeeWorkSchedule::factory()->state($workSchedules[$index]))
                ->has(Salary::factory()->state($salaries[$index]))
                ->has(EmployeeDeduction::factory()->count(3)->state(new Sequence(...$deductions[$index])))
                ->create($employeeData);

            $this->createEmployeeUserAccount($employee, $passwords[$index]);
        }
    }

    private function createEmployeeUserAccount(Employee $employee, string $password): void
    {
        $user = User::factory()->create([
            'name' => $employee->first_name . ' ' . $employee->last_name,
            'email' => $employee->email,
            'email_verified_at' => now(),
            'password' => Hash::make($password),
            'remember_token' => Str::random(10),
        ]);

        $user->assignRole('employee');
    }
}
