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
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('training_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', EmployeeTrainingStatus::cases())->default(EmployeeTrainingStatus::Enrolled->value);
            $table->date('completion_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onUpdate('set null')
                ->onDelete('set null');
            $table->foreign('training_id')
                ->references('id')->on('trainings')
                ->onUpdate('set null')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('set null')
                ->onDelete('set null');
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
