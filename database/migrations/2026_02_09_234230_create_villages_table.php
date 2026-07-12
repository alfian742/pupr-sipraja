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
        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->constrained('districts')->cascadeOnDelete()->comment('Id Kecamatan');
            $table->string('village_name', 50)->comment('Nama Kelurahan/Desa');
            $table->string('bps_code', 50)->nullable()->comment('Kode BPS');
            $table->string('kemendagri_code', 50)->nullable()->comment('Kode Kemendagri');
            $table->integer('resident_count')->nullable()->comment('Jumlah Penduduk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('villages');
    }
};
