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
        Schema::create('organization_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('organization_structure')->nullable()->comment('Struktur Organisasi (berisi URL Google Drive)');
            $table->foreignId('organization_structure_modified_by')->nullable()->constrained('users')->nullOnDelete()->comment('User yang terakhir mengubah struktur organisasi');
            $table->dateTime('organization_structure_modified_at')->nullable()->comment('Waktu terakhir struktur organisasi diubah');
            $table->text('organization_about')->nullable()->comment('Tentang Organisasi');
            $table->text('organization_summary')->nullable()->comment('Ringkasan Organisasi');
            $table->text('organization_vision')->nullable()->comment('Visi Organisasi');
            $table->text('organization_mission')->nullable()->comment('Misi Organisasi');
            $table->foreignId('profile_modified_by')->nullable()->constrained('users')->nullOnDelete()->comment('User yang terakhir mengubah profil organisasi');
            $table->dateTime('profile_modified_at')->nullable()->comment('Waktu terakhir profil organisasi diubah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_profiles');
    }
};
