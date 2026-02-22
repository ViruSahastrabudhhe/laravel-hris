<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Employee;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'gender' => 'Male',
            'email' => 'johndoe@example.com',
            'date_of_birth' => '2026-02-22',
            'address' => 'Address',
            'phone_number' => '09123456789',
            'employment_type' => 'Regular',
            'salary' => 14777.01,
            'is_active' => 1,
            'position_id' => 2,
            'department_id' => 2,
            'user_id' => 1,
        ];
    }
}
