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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            $table->date('contract_start_date')->nullable()->comment('Tanggal Mulai Kontrak');
            $table->date('contract_end_date')->nullable()->comment('Tanggal Berakhir Kontrak');

            $table->string('contract_number')->nullable()->comment('Nomor Kontrak');

            $table->string('third_party_name')->nullable()->comment('Nama Rekanan / Pihak Ketiga');

            $table->string('activity_code')->nullable()->comment('Kode Kegiatan');
            $table->string('sub_account_code')->nullable()->comment('Kode Sub Rekening');

            $table->text('activity_description')->nullable()->comment('Uraian Kegiatan / Pekerjaan');
            $table->string('department')->nullable()->comment('Bidang');

            $table->decimal('budget_value', 18, 2)->default(0)->comment('Anggaran');
            $table->decimal('contract_value', 18, 2)->default(0)->comment('Nilai Kontrak');

            $table->string('fund_source')->nullable()->comment('Sumber Dana');
            $table->string('bast_number')->nullable()->comment('Nomor BAST');

            // Audit Trail
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('User yang membuat data');

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('User yang terakhir mengubah data');

            // Index lookup / filter
            $table->index('contract_number');
            $table->index('third_party_name');
            $table->index('activity_code');
            $table->index('sub_account_code');
            $table->index('department');
            $table->index('fund_source');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
