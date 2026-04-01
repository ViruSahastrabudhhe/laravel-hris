<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('attendance_id');
            $table->unsignedBigInteger('work_schedule_id');
            $table->date('date');
            $table->integer('late_minutes')->nullable(); 
            $table->integer('undertime_minutes')->nullable(); 
            $table->integer('overtime_minutes')->nullable();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('attendance_id')
                ->references('id')->on('attendances')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('work_schedule_id')
                ->references('id')->on('work_schedules')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_attendances');
    }
};
