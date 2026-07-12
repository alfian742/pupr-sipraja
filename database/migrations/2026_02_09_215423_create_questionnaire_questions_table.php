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
        Schema::create('questionnaire_questions', function (Blueprint $table) {
            $table->id();
            $table->text('survey_indicator')->nullable()->comment('Indikator');
            $table->string('infrastructure_type', 100)->nullable()->comment('Jenis Infrastruktur');
            $table->text('description')->nullable()->comment('Keterangan');
            $table->text('option_1')->nullable()->comment('Opsi 1');
            $table->text('option_2')->nullable()->comment('Opsi 2');
            $table->text('option_3')->nullable()->comment('Opsi 3');
            $table->text('option_4')->nullable()->comment('Opsi 4');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_questions');
    }
};
