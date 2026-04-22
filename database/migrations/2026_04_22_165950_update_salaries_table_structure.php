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
        Schema::table('salaries', function (Blueprint $table) {
            $table->dropColumn(['annual', 'position_id']);
            $table->integer('salary_grade')->after('amount')->nullable();
            $table->integer('step')->after('salary_grade')->nullable();
            $table->string('salary_type')->after('step'); // Use string for enum flexibility or literal enum
            $table->foreignId('employee_id')->after('salary_type')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->after('employee_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            $table->float('annual')->after('amount');
            $table->unsignedBigInteger('position_id')->after('annual');
            $table->dropConstrainedForeignId('employee_id');
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['salary_grade', 'step', 'salary_type']);
        });
    }
};
