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
        Schema::create('public_information_portals', function (Blueprint $table) {
            $table->id();
            $table->string('portal_name')->unique()->comment('Nama Portal Informasi');
            $table->string('slug')->comment('Slug untuk URL');
            $table->text('description')->nullable()->comment('Deskripsi Singkat Portal Informasi');
            $table->string('website_url')->nullable()->comment('Link Website Portal Informasi');
            $table->string('logo')->nullable()->comment('Path URL Logo Portal Informasi');
            $table->foreignId('modified_by')->nullable()->constrained('users')->nullOnDelete()->comment('User yang terakhir mengubah data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_information_portals');
    }
};
