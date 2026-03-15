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
            $table->string('last_name');
            $table->string('gender');
            $table->string('email');
            $table->date('date_of_birth');
            $table->string('phone_number');
            $table->enum('employment_type', EmploymentType::cases());
            $table->string('is_active');
            $table->unsignedBigInteger('address_id');
            $table->unsignedBigInteger('position_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->foreign('address_id')
                    ->references('id')->on('addresses')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
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
