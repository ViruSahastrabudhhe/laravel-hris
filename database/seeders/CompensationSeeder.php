<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Compensation;
use App\Enums\CompensationType;
use Illuminate\Database\Eloquent\Factories\Sequence;

class CompensationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Compensation::factory()
            ->count(15)
            ->state(new Sequence(
                ['name' => 'GSIS Contribution', 'rate' => 0.09, 'type' => CompensationType::Deduction->value, 'is_mandatory' => true, 'description' => 'If regular, 5% deduction on salary'],
                ['name' => 'PhilHealth Personal Share Contribution', 'rate' => 0.025, 'type' => CompensationType::Deduction->value, 'is_mandatory' => true, 'description' => 'If regular, 2.5% deduction on salary'],
                ['name' => 'Pag-Ibig Personal Share Contribution', 'rate' => 0.02, 'type' => CompensationType::Deduction->value, 'is_mandatory' => true, 'description' => 'If regular, 2% deduction on salary if > P1,500, else 1% deduction'],
                ['name' => 'GSIS MPL Lite', 'type' => CompensationType::Deduction->value],
                ['name' => 'GSIS Consoloan', 'type' => CompensationType::Deduction->value],
                ['name' => 'GSIS Emergency Loan', 'type' => CompensationType::Deduction->value],
                ['name' => 'GSIS Educational Loan', 'type' => CompensationType::Deduction->value],
                ['name' => 'GSIS Policy Loan (Regular)', 'type' => CompensationType::Deduction->value],
                ['name' => 'GSIS UOLI', 'type' => CompensationType::Deduction->value],
                ['name' => 'GSIS Computer Loan', 'type' => CompensationType::Deduction->value],
                ['name' => 'GSIS MPL', 'type' => CompensationType::Deduction->value],
                ['name' => 'GSIS GFAL', 'type' => CompensationType::Deduction->value],
                ['name' => 'Pag-Ibig MPL', 'type' => CompensationType::Deduction->value],
                ['name' => 'Pag-Ibig CAL', 'type' => CompensationType::Deduction->value],
                ['name' => 'Pag-Ibig MP2', 'type' => CompensationType::Deduction->value],
                ['name' => 'GSIS State Insurance', 'type' => CompensationType::Deduction->value],
            ))
            ->create();
    }
}
