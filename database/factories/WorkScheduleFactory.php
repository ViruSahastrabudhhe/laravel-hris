<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\WorkSchedule;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkSchedule>
 */
class WorkScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = WorkSchedule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Standard',
            'start_time' => Carbon::parse('08:00'),
            'end_time' => Carbon::parse('17:00'),
            'pm_start_time' => Carbon::parse('13:00'),
            'break_minutes' => 60,
            'work_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'grace_period_minutes' => 15,
            'user_id' => 1,
        ];
    }
}
