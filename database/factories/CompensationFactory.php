<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\CompensationCategory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Compensation>
 */
class CompensationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category' => CompensationCategory::Deduction->value,
            'name' => 'GSIS Contribution',
            'is_mandatory' => false,
            'description' => '',
        ];
    }
}
