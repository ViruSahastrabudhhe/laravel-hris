<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\EmployeeTrainingStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                ->references('id')->on('employees');
            $table->foreignId('training_id')
                ->references('id')->on('trainings');
            $table->foreignId('user_id')
                ->references('id')->on('users');
            $table->enum('status', EmployeeTrainingStatus::cases())->default(EmployeeTrainingStatus::Enrolled->value);
            $table->date('completion_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_trainings');
    }
};
