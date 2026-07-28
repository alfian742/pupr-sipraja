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

            $table->string('contract_start_date')->nullable()->comment('Tanggal Mulai Kontrak'); // Supaya sesuai dengan data sumber yang ada di excel, maka kolom ini dibuat string, bukan date
            $table->string('contract_end_date')->nullable()->comment('Tanggal Berakhir Kontrak'); // Supaya sesuai dengan data sumber yang ada di excel, maka kolom ini dibuat string, bukan date

            $table->string('contract_number')->nullable()->comment('Nomor Kontrak');

            $table->string('third_party_name')->nullable()->comment('Nama Rekanan / Pihak Ketiga');

            $table->string('sub_activity_code')->nullable()->comment('Kode Sub Kegiatan');
            $table->string('account_code')->nullable()->comment('Kode Rekening');

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
            $table->index('sub_activity_code');
            $table->index('account_code');
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
