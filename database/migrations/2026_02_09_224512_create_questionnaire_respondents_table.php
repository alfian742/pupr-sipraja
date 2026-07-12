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
        Schema::create('questionnaire_respondents', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable()->unique()->comment('UUID');
            $table->dateTime('survey_date')->nullable()->index()->comment('Tanggal Survei');
            $table->string('gender', 10)->nullable()->comment('Jenis Kelamin');
            $table->string('age', 50)->nullable()->comment('Rentang Usia');
            $table->string('education', 100)->nullable()->comment('Pendidikan Terakhir');
            $table->string('occupation', 100)->nullable()->comment('Pekerjaan');
            $table->string('disability_status', 20)->nullable()->comment('Disabilitas/Non-Disabilitas');
            $table->string('district', 50)->nullable()->comment('Kecamatan sesuai Domisili');
            $table->string('village', 50)->nullable()->comment('Kelurahan/Desa sesuai Domisili');
            $table->text('address')->nullable()->comment('Alamat sesuai Domisili (Lingkungan/Dusun, RT)');
            $table->string('priority_infrastructure', 50)->nullable()->comment('Jenis infrastruktur yang perlu ditingkatkan');
            $table->string('priority_improvement', 100)->nullable()->comment('Aspek/hal yang perlu ditingkatkan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_respondents');
    }
};
