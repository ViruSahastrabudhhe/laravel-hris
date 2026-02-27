<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Position;
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
                ['title' => 'Administrative Aide I', 'salary_grade' => 1, 'salary_amount' => 14634],
                ['title' => 'Teacher I', 'salary_grade' => 11, 'salary_amount' => 31705],
                ['title' => 'Cash Clerk I', 'salary_grade' => 4, 'salary_amount' => 17506],
                ['title' => 'Teacher III', 'salary_grade' => 13, 'salary_amount' => 36125],
                ['title' => 'Director III', 'salary_grade' => 27, 'salary_amount' => 148940],
                ['title' => 'Senator', 'salary_grade' => 31, 'salary_amount' => 300961],
                ['title' => 'Engineer III', 'salary_grade' => 19, 'salary_amount' => 59153],
                ['title' => 'Elementary School Principal I', 'salary_grade' => 19, 'salary_amount' => 59153],
                ['title' => 'Administrative Aide II', 'salary_grade' => 2, 'salary_amount' => 16486],
                ['title' => 'Administrative Assistant V', 'salary_grade' => 11, 'salary_amount' => 31705],
            ))
            ->create();
    }
}
