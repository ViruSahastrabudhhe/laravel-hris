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
        Schema::create('ipcr_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ipcr_form_id')
                ->constrained('ipcr_forms');

            // IPCREntry CORE FIELDS
            $table->text('kra'); // Key Result Area
            $table->text('objectives');
            $table->text('success_indicators');

            $table->text('actual_accomplishments')->nullable();

            // CIVIL SERVICE RATING SYSTEM (QET)
            $table->decimal('quality_rating', 3, 2)->nullable();
            $table->decimal('efficiency_rating', 3, 2)->nullable();
            $table->decimal('timeliness_rating', 3, 2)->nullable();

            $table->decimal('average_rating', 5, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipcr_entries');
    }
};
