<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Salary>
 */
class SalaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => $this->faker->numberBetween(15000, 50000),
            'salary_grade' => $this->faker->numberBetween(1, 33),
            'step' => $this->faker->numberBetween(1, 8),
            'salary_type' => 'monthly',
            'employee_id' => \App\Models\Employee::factory(),
            'user_id' => 1,
        ];
    }
}
