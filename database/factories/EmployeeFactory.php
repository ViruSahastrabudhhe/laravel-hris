<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Employee;
use App\Enums\EmploymentType;

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
            'address_id' => 1,
            'phone_number' => '09123456789',
            'employment_type' => EmploymentType::Regular->value,
            'is_active' => 1,
            'position_id' => 2,
            'department_id' => 2,
            'user_id' => 1,
        ];
    }
}
