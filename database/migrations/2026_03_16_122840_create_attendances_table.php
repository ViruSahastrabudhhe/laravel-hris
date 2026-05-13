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
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
            $table->time('overtime_in')->nullable();
            $table->time('overtime_out')->nullable();
            $table->date('date');
            $table->enum('attendance_status', AttendanceStatus::cases())->default(AttendanceStatus::Missing->value);
            $table->integer('total_minutes')->default(0);
            $table->integer('overtime_minutes')->default(0);
            $table->integer('number_of_scans')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('employee_id')
                    ->references('id')->on('employees')
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
