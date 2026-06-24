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
        Schema::create('download_centers', function (Blueprint $table) {
            $table->id();
            $table->string('document_title', 255)->unique()->comment('Judul Dokumen');
            $table->string('slug', 255)->comment('Slug untuk URL');
            $table->text('description')->nullable()->comment('Deskripsi Dokumen');
            $table->string('document_type', 255)->nullable()->comment('Jenis Dokumen');
            $table->string('document_url')->nullable()->comment('Link Dokumen');
            $table->string('status', 15)->nullable()->comment('Status Dokumen');
            $table->foreignId('modified_by')->nullable()->constrained('users')->nullOnDelete()->comment('User yang terakhir mengubah data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('download_centers');
    }
};
