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
        // Schema::create('payrolls', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('employee_id');
        //     $table->float('gross_pay');
        //     $table->float('tax_deduction');
        //     $table->float('cash_advance');
        //     $table->float('adjustment');
        //     $table->float('total_deductions');
        //     $table->float('net_pay');
        //     $table->unsignedBigInteger('user_id');
        //     $table->timestamps();
        //     $table->foreign('employee_id')
        //             ->references('id')->on('employees')
        //             ->onUpdate('cascade')
        //             ->onDelete('cascade');
        //     $table->foreign('deductions_id')
        //             ->references('id')->on('deductions')
        //             ->onUpdate('cascade')
        //             ->onDelete('cascade');
        //     $table->foreign('user_id')
        //             ->references('id')->on('users')
        //             ->onUpdate('cascade')
        //             ->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
