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
        Schema::create('attendance_scan_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('employee_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('scan_type');
            $table->timestamp('scanned_at');
            $table->string('device_name')->nullable();
            $table->ipAddress()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_scan_logs');
    }
};
