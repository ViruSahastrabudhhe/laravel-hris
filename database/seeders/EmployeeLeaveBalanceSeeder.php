<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmployeeLeaveBalance;
use Illuminate\Database\Eloquent\Factories\Sequence;

class EmployeeLeaveBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmployeeLeaveBalance::factory()
            ->count(3)
            ->state(new Sequence(
                [
                    'leave_balance' => 15,
                    'employee_id' => 1,
                    'user_id' => 1
                ],
                [
                    'leave_balance' => 15,
                    'employee_id' => 2,
                    'user_id' => 1
                ],
                [
                    'leave_balance' => 0,
                    'employee_id' => 3,
                    'user_id' => 1
                ],
            ))
            ->create();
    }
}
