<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\PositionSeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\DeductionSeeder;
use Database\Seeders\EmployeeSeeder;
use Database\Seeders\AddressSeeder;
use Database\Seeders\LeaveTypeSeeder;
use Database\Seeders\EmployeeLeaveBalanceSeeder;
use Database\Seeders\WorkScheduleSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            AddressSeeder::class,
            LeaveTypeSeeder::class,
            PositionSeeder::class,
            DepartmentSeeder::class,
            EmployeeSeeder::class,
            EmployeeLeaveBalanceSeeder::class,
            DeductionSeeder::class,
            WorkScheduleSeeder::class,
        ]);
    }
}
