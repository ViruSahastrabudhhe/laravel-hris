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
            $table->foreignId('employee_id')
                ->references('id')->on('employees')
                ->cascadeOnDelete();
            $table->integer('total_present')->default(0);
            $table->integer('total_late')->default(0);
            $table->integer('total_absent')->default(0);
            $table->integer('total_overtime')->default(0);
            $table->integer('total_leaves')->default(0);
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            $table->boolean('is_complete')->default(false);
            $table->timestamps();
            $table->softDeletes();
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
