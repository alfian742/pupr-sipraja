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
        Schema::create('power_grids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('respondent_id')->unique()->constrained('questionnaire_respondents')->cascadeOnDelete()->comment('ID Responden');
            $table->tinyInteger('physical_availability_score')->nullable()->comment('Ketersediaan Fisik (Nilai 1-4)');
            $table->tinyInteger('quality_score')->nullable()->comment('Kualitas (Nilai 1-4)');
            $table->tinyInteger('suitability_score')->nullable()->comment('Kesesuaian (Nilai 1-4)');
            $table->tinyInteger('utilization_score')->nullable()->comment('Pemanfaatan (Nilai 1-4)');
            $table->tinyInteger('expectation_score')->nullable()->comment('Harapan (Nilai 1-4)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('power_grids');
    }
};
