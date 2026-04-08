<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Sequence;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::factory()
            ->count(4)
            ->state(new Sequence(
                ['type' => 'Super Admin'],
                ['type' => 'Admin'],
                ['type' => 'HR Manager'],
                ['type' => 'Employee'],
            ))
            ->create();
    }
}
