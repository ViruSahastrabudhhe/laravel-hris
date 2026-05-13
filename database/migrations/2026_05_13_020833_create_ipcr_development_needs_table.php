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
        Schema::create('ipcr_development_needs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ipcr_form_id')
                ->constrained('ipcr_forms')
                ->onDelete('cascade');

            $table->text('development_needs');

            $table->text('recommended_training')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipcr_development_needs');
    }
};
