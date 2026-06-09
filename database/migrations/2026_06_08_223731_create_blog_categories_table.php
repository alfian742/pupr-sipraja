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
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Dibuat Oleh');

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Diperbarui Oleh');

            $table->string('name')->comment('Nama Kategori');
            $table->string('slug')->unique()->comment('Slug Kategori');
            $table->text('description')->nullable()->comment('Deskripsi');
            $table->boolean('is_active')->default(true)->comment('Status Aktif');
            $table->unsignedInteger('sort_order')->default(0)->comment('Urutan');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'sort_order']);
            $table->index(['created_by', 'updated_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};
