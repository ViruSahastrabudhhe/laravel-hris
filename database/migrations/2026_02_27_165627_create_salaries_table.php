<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\SalaryType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->float('amount');
            $table->integer('salary_grade');
            $table->integer('step');
            $table->enum('salary_type', SalaryType::cases())->default(SalaryType::Hourly->value);
            $table->unsignedBigInteger('employee_id');
            $table->timestamps();
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
