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
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('work_schedule_id');
            $table->timestamps();
            $table->foreign('employee_id')
                    ->references('id')->on('employees')
                    ->onUpdate('set null')
                    ->onDelete('set null');
            $table->foreign('work_schedule_id')
                    ->references('id')->on('work_schedules')
                    ->onUpdate('set null')
                    ->onDelete('set null');
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
