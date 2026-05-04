<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TrainingType;
use App\Enums\TrainingStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('program_title');
            $table->enum('type', TrainingType::cases());
            $table->integer('capacity');
            $table->integer('participants')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('venue');
            $table->enum('status', TrainingStatus::cases())->default(TrainingStatus::Scheduled->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
