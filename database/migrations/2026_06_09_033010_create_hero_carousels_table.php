<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('hero_carousels', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150)->comment('Judul carousel');
            $table->text('description')->nullable()->comment('Deskripsi singkat carousel');
            $table->string('image_path')->comment('Path gambar carousel');
            $table->unsignedInteger('sort_order')->default(0)->comment('Urutan carousel');
            $table->boolean('is_active')->default(true)->comment('Status aktif');

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_carousels');
    }
};
