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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable()->comment('Email');
            $table->string('email_alternative')->nullable()->comment('Email Alternatif');
            $table->string('phone_number', 20)->nullable()->comment('Telepon');
            $table->string('phone_number_alternative', 20)->nullable()->comment('Telepon Alternatif');
            $table->string('whatsapp_number', 20)->nullable()->comment('Nomor HP/WA');
            $table->string('whatsapp_number_alternative', 20)->nullable()->comment('Nomor HP/WA Alternatif');
            $table->string('operational_time', 100)->nullable()->comment('Waktu Operasional (Jam Kerja)');
            $table->text('address')->nullable()->comment('Alamat');
            $table->text('google_maps_embed')->nullable()->comment('Sematkan Google Maps');
            $table->string('facebook_url')->nullable()->comment('URL Facebook');
            $table->string('instagram_url')->nullable()->comment('URL Instagram');
            $table->string('twitter_url')->nullable()->comment('URL X/Twitter');
            $table->string('youtube_url')->nullable()->comment('URL YouTube');
            $table->string('tiktok_url')->nullable()->comment('URL TikTok');
            $table->foreignId('modified_by')->nullable()->constrained('users')->nullOnDelete()->comment('User yang terakhir mengubah data kontak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
