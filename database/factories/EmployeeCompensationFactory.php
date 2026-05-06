<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Compensation;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeCompensation>
 */
class EmployeeCompensationFactory extends Factory
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
            'compensation_id' => 1,
            'amount' => 100,
        ];
    }

    public function configure(): static {
        return $this->afterCreating(function ($employeeCompensation) {
            $compensation = Compensation::find($employeeCompensation->compensation_id);
            $salary = $employeeCompensation->employee->salary->amount ?? 0;

            $employeeCompensation->update([
                'amount' => $salary * ($compensation->rate ?? 0),
            ]);
        });
    }
}
