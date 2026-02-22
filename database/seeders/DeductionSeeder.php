<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Deduction;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DeductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Deduction::factory()
            ->count(3)
            ->state(new Sequence(
                ['deduction' => 'GSIS Contribution', 'description' => '9% deduction on salary'],
                ['deduction' => 'PhilHealth Contribution', 'description' => '2.5% deduction on salary'],
                ['deduction' => 'Pag-Ibig Contribution', 'description' => '2% deduction on salary if > P1,500, else 1% deduction'],
            ))
            ->create();
    }
}
