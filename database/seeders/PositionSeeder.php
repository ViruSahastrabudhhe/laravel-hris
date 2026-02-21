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
                ['title' => 'Administrative Aide I'],
                ['title' => 'Teacher I'],
                ['title' => 'Clerk I'],
                ['title' => 'Teacher III'],
                ['title' => 'Director III'],
                ['title' => 'Senator'],
                ['title' => 'Engineer III'],
                ['title' => 'Principal I'],
                ['title' => 'Administrative Aide II'],
                ['title' => 'Teacher II'],
            ))
            ->create();
    }
}
