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
        Schema::create('personnel_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('personnel_name', 100)->nullable()->comment('Nama Personil');
            $table->string('personnel_position', 100)->nullable()->comment('Jabatan Personil');
            $table->text('personnel_description')->nullable()->comment('Deskripsi Personil');
            $table->text('personnel_photo')->nullable()->comment('Foto Personil');
            $table->foreignId('modified_by')->nullable()->constrained('users')->nullOnDelete()->comment('User yang terakhir mengubah profil personil');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel_profiles');
    }
};
