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
            ->count(15)
            ->state(new Sequence(
                ['name' => 'GSIS Contribution', 'rate' => 0.09, 'description' => 'If regular, 5% name on salary'],
                ['name' => 'PhilHealth Personal Share Contribution', 'rate' => 0.025, 'description' => 'If regular, 2.5% name on salary'],
                ['name' => 'Pag-Ibig Personal Share Contribution', 'rate' => 0.02, 'description' => 'If regular, 2% name on salary if > P1,500, else 1% name'],
                ['name' => 'GSIS MPL Lite', 'description' => ''],
                ['name' => 'GSIS Consoloan', 'description' => ''],
                ['name' => 'GSIS Emergency Loan', 'description' => ''],
                ['name' => 'GSIS Educational Loan', 'description' => ''],
                ['name' => 'GSIS Policy Loan (Regular)', 'description' => ''],
                ['name' => 'GSIS UOLI', 'description' => ''],
                ['name' => 'GSIS Computer Loan', 'description' => ''],
                ['name' => 'GSIS MPL', 'description' => ''],
                ['name' => 'GSIS GFAL', 'description' => ''],
                ['name' => 'Pag-Ibig MPL', 'description' => ''],
                ['name' => 'Pag-Ibig CAL', 'description' => ''],
                ['name' => 'Pag-Ibig MP2', 'description' => ''],
                ['name' => 'GSIS State Insurance', 'description' => ''],
            ))
            ->create();
    }
}
