<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\AttendanceStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->time('time_in');
            $table->time('time_out');
            $table->time('break_start');
            $table->time('break_end');
            $table->time('overtime_in');
            $table->time('overtime_out');
            $table->date('date');
            $table->enum('attendance_status', AttendanceStatus::cases());
            $table->integer('total_minutes')->default(0);
            $table->integer('overtime_minutes')->default(0);
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('employee_id')
                    ->references('id')->on('employees')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
