<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeWorkSchedule;
use App\Models\EmployeeCompensation;
use App\Models\EmployeeLeaveBalance;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Salary;
use App\Models\Address;
use App\Enums\EmploymentType;
use App\Enums\SalaryType;

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
                'is_active' => 1,
                'position_id' => 2,
                'department_id' => 2,
                'user_id' => 2,
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Mary',
                'gender' => 'Female',
                'email' => 'maryjane@example.com',
                'date_of_birth' => '2004-08-28',
                'phone_number' => '09123456789',
                'employment_type' => EmploymentType::Regular->value,
                'is_active' => 1,
                'position_id' => 3,
                'department_id' => 3,
                'user_id' => 3,
            ],
            [
                'first_name' => 'Job',
                'last_name' => 'Order',
                'gender' => 'Female',
                'email' => 'joborder@example.com',
                'date_of_birth' => '1997-06-07',
                'phone_number' => '09123456789',
                'employment_type' => EmploymentType::JobOrder->value,
                'is_active' => 1,
                'position_id' => 4,
                'department_id' => 4,
                'user_id' => 4,
            ],
        ];

        $salaries = [
            ['amount' => 14000, 'salary_grade' => 1, 'step' => 1, 'salary_type' => SalaryType::Monthly->value],
            ['amount' => 17000, 'salary_grade' => 3, 'step' => 1, 'salary_type' => SalaryType::Monthly->value],
            ['amount' => 16000, 'salary_grade' => 2, 'step' => 1, 'salary_type' => SalaryType::Monthly->value],
        ];

        $workSchedules = [
            ['work_schedule_id' => 1],
            ['work_schedule_id' => 1],
            ['work_schedule_id' => 1],
        ];

        $attendances = [
            ['total_present' => 0, 'total_absent' => 0, 'total_late' => 0, 'month' => Carbon::now()->month, 'year' => Carbon::now()->year],
            ['total_present' => 0, 'total_absent' => 0, 'total_late' => 0, 'month' => Carbon::now()->month, 'year' => Carbon::now()->year],
            ['total_present' => 0, 'total_absent' => 0, 'total_late' => 0, 'month' => Carbon::now()->month, 'year' => Carbon::now()->year],
        ];

            $leaveBalances = [
                [
                    ['amount' => 0, 'type' => 'Sick'],
                    ['amount' => 0, 'type' => 'Vacation'],
                ],
                [
                    ['amount' => 0, 'type' => 'Sick'],
                    ['amount' => 0, 'type' => 'Vacation'],
                ],
                [
                    ['amount' => 0, 'type' => 'Sick'],
                    ['amount' => 0, 'type' => 'Vacation'],
                ],
            ];

        $compensations = [
            [
                ['compensation_id' => 1, 'amount' => 0],
                ['compensation_id' => 2, 'amount' => 0],
                ['compensation_id' => 3, 'amount' => 0],
            ],
            [
                ['compensation_id' => 1, 'amount' => 0],
                ['compensation_id' => 2, 'amount' => 0],
                ['compensation_id' => 3, 'amount' => 0],
            ],
            [
                ['compensation_id' => 1, 'amount' => 0],
                ['compensation_id' => 2, 'amount' => 0],
                ['compensation_id' => 3, 'amount' => 0],
            ],
        ];

        $passwords = [
            'password',
            'password',
            'password',
        ];

        foreach ($employees as $index => $employeeData) {
            $user = $this->createEmployeeUserAccount($employeeData, $passwords[$index]);

            Employee::factory()
                ->has(EmployeeWorkSchedule::factory()->state($workSchedules[$index]))
                ->has(Salary::factory()->state($salaries[$index]))
//                ->has(EmployeeCompensation::factory()->count(3)->state(new Sequence(...$compensations[$index])))
                ->has(EmployeeAttendance::factory()->state($attendances[$index]))
                ->has(Address::factory())
                ->has(EmployeeLeaveBalance::factory()->count(2)->state(new Sequence(...$leaveBalances[$index])))
                ->create(array_merge($employeeData, ['user_id' => $user->id]));
        }
    }

    private function createEmployeeUserAccount(array $employeeData, string $password): User
    {
        $user = User::factory()->create([
            'name' => $employeeData['first_name'] . ' ' . $employeeData['last_name'],
            'email' => $employeeData['email'],
            'email_verified_at' => now(),
            'password' => Hash::make($password),
            'remember_token' => Str::random(10),
        ]);

        $user->assignRole('employee');

        return $user;
    }
}
