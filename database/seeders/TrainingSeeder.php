<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Models\Training;
use App\Enums\TrainingType;
use App\Enums\TrainingStatus;

class TrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Training::factory()
            ->count(6)
            ->state(new Sequence(
                [ 'program_title' => 'Leadership Seminar', 'type' => TrainingType::Leadership->value, 'capacity' => 20, 'participants' => 15, 'start_date' => '2024-06-01', 'end_date' => '2024-06-01', 'venue' => 'Municipal Hall', 'status' => TrainingStatus::Scheduled->value ],
                [ 'program_title' => 'Newly-Hired Employee Orientation', 'type' => TrainingType::Orientation->value, 'capacity' => 25, 'participants' => 20, 'start_date' => '2024-06-05', 'end_date' => '2024-06-05', 'venue' => 'Conference Room A', 'status' => TrainingStatus::Scheduled->value ],
                [ 'program_title' => 'Compliance Seminar', 'type' => TrainingType::Compliance->value, 'capacity' => 30, 'participants' => 28, 'start_date' => '2024-06-10', 'end_date' => '2024-06-10', 'venue' => 'Training Room B', 'status' => TrainingStatus::Scheduled->value ],
                [ 'program_title' => 'Safety in the Workplace Training', 'type' => TrainingType::Safety->value, 'capacity' => 15, 'participants' => 12, 'start_date' => '2024-06-15', 'end_date' => '2024-06-15', 'venue' => 'Conference Room C', 'status' => TrainingStatus::Scheduled->value ],
                [ 'program_title' => 'Customer Service Excellence', 'type' => TrainingType::Service->value, 'capacity' => 20, 'participants' => 18, 'start_date' => '2024-06-20', 'end_date' => '2024-06-20', 'venue' => 'Training Room D', 'status' => TrainingStatus::Scheduled->value ],
                [ 'program_title' => "Employee Diversity Program", "type" => TrainingType::DEI->value, "capacity" => 25, "participants" => 22, "start_date" => "2024-06-25", "end_date" => "2024-06-25", "venue" => "Gymnasium", 'status' => TrainingStatus::Scheduled->value ],
            ))
            ->create();
    }
}
