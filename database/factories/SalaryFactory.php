<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Salary;
use App\Enums\SalaryType;

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
            'salary_type' => SalaryType::Monthly->value,
            'employee_id' => 1,
            'user_id' => 1,
        ];
    }
}
