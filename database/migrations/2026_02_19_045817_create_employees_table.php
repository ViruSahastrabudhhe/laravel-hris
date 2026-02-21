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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->string('email');
            $table->dateTime('date_of_birth');
            $table->string('address');
            $table->string('phone_number');
            $table->string('employment_type');
            $table->double('salary');
            $table->string('is_active');
            $table->integer('position_id');
            $table->integer('department_id');
            $table->integer('user_id');
            $table->timestamps();
            $table->foreign('position_id')
                    ->references('id')->on('positions')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->foreign('department_id')
                    ->references('id')->on('departments')
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
        Schema::dropIfExists('employees');
    }
};
