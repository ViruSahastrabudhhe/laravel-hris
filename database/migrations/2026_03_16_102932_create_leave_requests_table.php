<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\LeaveStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                ->references('id')->on('employees');
            $table->foreignId('user_id')
                ->references('id')->on('users');
            $table->foreignId('leave_type_id')
                ->references('id')->on('leave_types');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('leave_duration')->nullable();
            $table->text('leave_reason');
            $table->enum('leave_status', LeaveStatus::cases());
            $table->text('decline_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['employee_id', 'leave_type_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
