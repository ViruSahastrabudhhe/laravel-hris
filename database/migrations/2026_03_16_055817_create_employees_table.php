<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\EmploymentType;

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
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('gender');
            $table->string('email')->unique();
            $table->date('date_of_birth');
            $table->string('phone_number');
            $table->foreignId('position_id')
                ->references('id')->on('positions');
            $table->foreignId('department_id')
                ->references('id')->on('departments');
            $table->foreignId('user_id')
                ->references('id')->on('users')->nullable();
            $table->enum('employment_type', EmploymentType::cases());
            $table->boolean('is_active');
            $table->timestamps();
            $table->softDeletes();
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
