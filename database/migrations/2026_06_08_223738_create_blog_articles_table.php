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
        Schema::create('blog_articles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete()
                ->comment('Penulis');

            $table->foreignId('blog_category_id')
                ->nullable()
                ->constrained('blog_categories')
                ->nullOnDelete()
                ->comment('Kategori');

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

            $table->string('title')->comment('Judul');
            $table->string('slug')->unique()->comment('Slug Artikel');
            $table->text('excerpt')->nullable()->comment('Ringkasan');
            $table->longText('content')->comment('Konten');
            $table->string('thumbnail')->nullable()->comment('Thumbnail');

            $table->enum('status', ['draft', 'published', 'archived'])
                ->default('draft')
                ->comment('Status');

            $table->boolean('is_featured')->default(false)->comment('Artikel Unggulan');
            $table->unsignedBigInteger('views_count')->default(0)->comment('Jumlah Dilihat');
            $table->timestamp('published_at')->nullable()->comment('Tanggal Publikasi');

            $table->string('meta_title')->nullable()->comment('Meta Title');
            $table->text('meta_description')->nullable()->comment('Meta Description');
            $table->string('meta_keywords')->nullable()->comment('Meta Keywords');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'blog_category_id']);
            $table->index(['created_by', 'updated_by']);
            $table->index(['status', 'published_at']);
            $table->index(['is_featured', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_articles');
    }
};
