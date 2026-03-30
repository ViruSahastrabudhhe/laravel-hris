<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WorkSchedule;
use Illuminate\Database\Eloquent\Factories\Sequence;

class WorkScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkSchedule::factory()->create();
    }
}
