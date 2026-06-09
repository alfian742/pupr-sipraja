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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('department_name')->unique()->comment('Nama Bidang');
            $table->string('slug')->comment('Slug untuk URL');
            $table->string('description')->comment('Deskripsi Bidang');
            $table->string('logo')->nullable()->comment('Path URL Logo Bidang');
            $table->foreignId('modified_by')->nullable()->constrained('users')->nullOnDelete()->comment('User yang terakhir mengubah data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
