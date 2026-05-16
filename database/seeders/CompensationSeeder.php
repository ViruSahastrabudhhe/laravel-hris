<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Compensation;
use App\Enums\CompensationCategory;
use Illuminate\Database\Eloquent\Factories\Sequence;

class CompensationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Compensation::factory()
            ->count(20)
            ->state(new Sequence(
                ['name' => 'GSIS Contribution', 'rate' => 0.09, 'category' => CompensationCategory::Deduction->value, 'is_mandatory' => true, 'description' => 'If regular, 5% deduction on salary'],
                ['name' => 'PhilHealth Personal Share Contribution', 'rate' => 0.025, 'category' => CompensationCategory::Deduction->value, 'is_mandatory' => true, 'description' => 'If regular, 2.5% deduction on salary'],
                ['name' => 'Pag-Ibig Personal Share Contribution', 'rate' => 0.02, 'category' => CompensationCategory::Deduction->value, 'is_mandatory' => true, 'description' => 'If regular, 2% deduction on salary if > P1,500, else 1% deduction'],
                ['name' => 'GSIS MPL Lite', 'category' => CompensationCategory::Deduction->value],
                ['name' => 'GSIS Consoloan', 'category' => CompensationCategory::Deduction->value],
                ['name' => 'GSIS Emergency Loan', 'category' => CompensationCategory::Deduction->value],
                ['name' => 'GSIS Educational Loan', 'category' => CompensationCategory::Deduction->value],
                ['name' => 'GSIS Policy Loan (Regular)', 'category' => CompensationCategory::Deduction->value],
                ['name' => 'GSIS UOLI', 'category' => CompensationCategory::Deduction->value],
                ['name' => 'GSIS Computer Loan', 'category' => CompensationCategory::Deduction->value],
                ['name' => 'GSIS MPL', 'category' => CompensationCategory::Deduction->value],
                ['name' => 'GSIS GFAL', 'category' => CompensationCategory::Deduction->value],
                ['name' => 'Pag-Ibig MPL', 'category' => CompensationCategory::Deduction->value],
                ['name' => 'Pag-Ibig CAL', 'category' => CompensationCategory::Deduction->value],
                ['name' => 'Pag-Ibig MP2', 'category' => CompensationCategory::Deduction->value],
                ['name' => 'GSIS State Insurance', 'category' => CompensationCategory::Deduction->value],
                ['name' => 'Medical Allowance', 'category' => CompensationCategory::Earning->value],
                ['name' => 'Uniform/Clothing Allowance', 'category' => CompensationCategory::Earning->value],
                ['name' => 'Meal Allowance', 'category' => CompensationCategory::Earning->value],
                ['name' => 'Cash Gift', 'category' => CompensationCategory::Earning->value],
                ['name' => 'Year-End Bonus', 'category' => CompensationCategory::Earning->value],
            ))
            ->create();
    }
}
