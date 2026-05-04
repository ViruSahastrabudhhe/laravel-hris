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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('country');
            $table->integer('zip_code');
            $table->string('address');
            $table->string('city');
            $table->string('province');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->timestamps();
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onUpdate('set null')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
