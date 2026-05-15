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
        Schema::create('employee_work_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                    ->references('id')->on('employees');
            $table->foreignId('work_schedule_id')
                    ->references('id')->on('work_schedules');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_work_schedules');
    }
};
