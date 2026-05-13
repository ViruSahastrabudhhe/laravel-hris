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
        Schema::create('employee_compensations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('compensation_id');
            $table->float('amount');
            $table->timestamps();
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('compensation_id')
                ->references('id')->on('compensations')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_compensations');
    }
};
