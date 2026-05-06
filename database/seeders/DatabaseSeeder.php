<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\PositionSeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\CompensationSeeder;
use Database\Seeders\EmployeeSeeder;
use Database\Seeders\AddressSeeder;
use Database\Seeders\LeaveTypeSeeder;
use Database\Seeders\EmployeeLeaveBalanceSeeder;
use Database\Seeders\WorkScheduleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\TrainingSeeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            LeaveTypeSeeder::class,
            PositionSeeder::class,
            DepartmentSeeder::class,
            CompensationSeeder::class,
            WorkScheduleSeeder::class,
            PermissionSeeder::class,
            EmployeeSeeder::class,
            EmployeeLeaveBalanceSeeder::class,
            TrainingSeeder::class,
        ]);

        $admin = Role::findByName('admin');
        $user->assignRole($admin);
    }

}
