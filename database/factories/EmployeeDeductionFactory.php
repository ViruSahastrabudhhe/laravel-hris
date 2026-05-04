<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Deduction;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeDeduction>
 */
class EmployeeDeductionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => 1,
            'deduction_id' => 1,
            'amount' => 100,
        ];
    }

    public function configure(): static {
        return $this->afterCreating(function ($employeeDeduction) {
            $deduction = Deduction::find($employeeDeduction->deduction_id);
            $salary = $employeeDeduction->employee->salary->amount ?? 0;

            $employeeDeduction->update([
                'amount' => $salary * ($deduction->rate ?? 0),
            ]);
        });
    }
}
