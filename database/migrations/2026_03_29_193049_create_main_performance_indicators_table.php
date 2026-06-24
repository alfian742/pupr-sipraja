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
        Schema::create('main_performance_indicators', function (Blueprint $table) {
            $table->id();
            $table->string('indicator_code')->nullable()->comment('Kode Indikator');
            $table->text('indicator_name')->nullable()->comment('Nama Indikator');
            $table->string('indicator_unit', 20)->nullable()->comment('Satuan Indikator');
            $table->year('baseline_year')->nullable()->comment('Tahun Baseline');
            $table->decimal('baseline_value', 5, 2)->nullable()->comment('Nilai Baseline');
            $table->year('measurement_year')->nullable()->comment('Tahun Pengukuran');
            $table->decimal('target_value', 5, 2)->nullable()->comment('Target');
            $table->decimal('achievement_value', 5, 2)->nullable()->comment('Capaian');
            $table->decimal('performance_value', 5, 2)->nullable()->comment('Kinerja');
            $table->string('document_url')->nullable()->comment('Link Dokumen Pendukung');
            $table->foreignId('modified_by')->nullable()->constrained('users')->nullOnDelete()->comment('User yang terakhir mengubah data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_performance_indicators');
    }
};
