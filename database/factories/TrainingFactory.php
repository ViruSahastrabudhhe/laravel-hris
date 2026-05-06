<?php

namespace Database\Factories;

use App\Models\Training;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\TrainingType;
use App\Enums\TrainingStatus;
use Carbon\Carbon;

/**
 * @extends Factory<Training>
 */
class TrainingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomDate = Carbon::today()->subDays(rand(0, Carbon::now()->day - 1));

        return [
            'program_title' => 'Leadership Seminar',
            'type' => TrainingType::Leadership->value,
            'capacity' => 20,
            'participants' => 15,
            'start_date' => $randomDate,
            'end_date' => $randomDate,
            'venue' => 'Municipal Hall',
            'status' => TrainingStatus::Scheduled->value,
        ];
    }
}
