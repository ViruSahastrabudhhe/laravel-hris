<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Position;
use App\Enums\PositionStatus;
use Illuminate\Database\Eloquent\Factories\Sequence;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Position::factory()
            ->count(10)
            ->state(new Sequence(
                [ 'title' => 'Administrative Aide I', 'total_employees' => 15, 'status' => PositionStatus::Hiring->value ],
                [ 'title' => 'Teacher I', 'total_employees' => 10, 'status' => PositionStatus::Hiring->value ],
                [ 'title' => 'Cash Clerk I', 'total_employees' => 5, 'status' => PositionStatus::Hiring->value ],
                [ 'title' => 'Teacher III', 'total_employees' => 8, 'status' => PositionStatus::Hiring->value ],
                [ 'title' => 'Director III', 'total_employees' => 1, 'status' => PositionStatus::Hiring->value ],
                [ 'title' => 'Senator', 'total_employees' => 2, 'status' => PositionStatus::Hiring->value ],
                [ 'title' => 'Engineer III', 'total_employees' => 4, 'status' => PositionStatus::Hiring->value ],
                [ 'title' => 'Elementary School Principal I', 'total_employees' => 6, 'status' => PositionStatus::Hiring->value ],
                [ 'title' => 'Administrative Aide II', 'total_employees' => 12, 'status' => PositionStatus::Hiring->value ],
                [ 'title' => 'Administrative Assistant V', 'total_employees' => 7, 'status' => PositionStatus::Hiring->value ],
            ))
            ->create();
    }
}
