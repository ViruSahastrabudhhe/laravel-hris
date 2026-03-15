<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Sequence;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LeaveType::factory()
            ->count(10)
            ->state(new Sequence(
                ['leave_type' => 'Service Incentive Leave', 'days_of_leave' => 5, 'is_active' => 1, 'user_id' => 1],
                ['leave_type' => 'Maternity Leave', 'days_of_leave' => 105, 'is_active' => 1, 'user_id' => 1],
                ['leave_type' => 'Paternity Leave', 'days_of_leave' => 7, 'is_active' => 1, 'user_id' => 1],
                ['leave_type' => 'Special Leave for Women', 'days_of_leave' => 60, 'is_active' => 1, 'user_id' => 1],
                ['leave_type' => 'Solo Parent Leave', 'days_of_leave' => 7, 'is_active' => 1, 'user_id' => 1],
                ['leave_type' => 'Vacation Leave', 'days_of_leave' => 15, 'is_active' => 1, 'user_id' => 1],
                ['leave_type' => 'Sick Leave', 'days_of_leave' => 15, 'is_active' => 1, 'user_id' => 1],
                ['leave_type' => 'Bereavement Leave', 'days_of_leave' => 5, 'is_active' => 1, 'user_id' => 1],
                ['leave_type' => 'Emergency Leave', 'days_of_leave' => 0, 'is_active' => 1, 'user_id' => 1],
                ['leave_type' => 'Leave Without Pay', 'days_of_leave' => 0, 'is_active' => 1, 'user_id' => 1],
            ))
            ->create();
    }
}
