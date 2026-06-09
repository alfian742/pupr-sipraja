<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ls_payments', function (Blueprint $table) {
            $table->id();

            // SKPD
            $table->string('skpd_code')->nullable()->comment('Kode SKPD');
            $table->string('skpd_name')->nullable()->comment('Nama SKPD');

            // Sub SKPD
            $table->string('sub_skpd_code')->nullable()->comment('Kode Sub SKPD');
            $table->string('sub_skpd_name')->nullable()->comment('Nama Sub SKPD');

            // Fungsi
            $table->string('function_code')->nullable()->comment('Kode Fungsi');
            $table->string('function_name')->nullable()->comment('Nama Fungsi');

            // Sub Fungsi
            $table->string('sub_function_code')->nullable()->comment('Kode Sub Fungsi');
            $table->string('sub_function_name')->nullable()->comment('Nama Sub Fungsi');

            // Urusan
            $table->string('affair_code')->nullable()->comment('Kode Urusan');
            $table->string('affair_name')->nullable()->comment('Nama Urusan');

            // Bidang Urusan
            $table->string('field_affair_code')->nullable()->comment('Kode Bidang Urusan');
            $table->string('field_affair_name')->nullable()->comment('Nama Bidang Urusan');

            // Program
            $table->string('program_code')->nullable()->comment('Kode Program');
            $table->string('program_name')->nullable()->comment('Nama Program');

            // Kegiatan
            $table->string('activity_code')->nullable()->comment('Kode Kegiatan');
            $table->string('activity_name')->nullable()->comment('Nama Kegiatan');

            // Sub Kegiatan
            $table->string('sub_activity_code')->nullable()->comment('Kode Sub Kegiatan');
            $table->string('sub_activity_name')->nullable()->comment('Nama Sub Kegiatan');

            // Rekening
            $table->string('account_code')->nullable()->comment('Kode Rekening');
            $table->string('account_name')->nullable()->comment('Nama Rekening');

            // Dokumen Umum
            $table->string('document_number')->nullable()->comment('Nomor Dokumen');
            $table->string('document_type')->nullable()->comment('Jenis Dokumen');
            $table->string('transaction_type')->nullable()->comment('Jenis Transaksi');
            $table->string('dpt_number')->nullable()->comment('Nomor DPT');
            $table->date('document_date')->nullable()->comment('Tanggal Dokumen');
            $table->text('document_description')->nullable()->comment('Keterangan Dokumen');

            $table->decimal('realization_value', 18, 2)->default(0)->comment('Nilai Realisasi');
            $table->decimal('deposit_value', 18, 2)->default(0)->comment('Nilai Setoran');

            // Pegawai
            $table->string('nip', 50)->nullable()->comment('NIP');
            $table->string('personnel_name', 150)->nullable()->comment('Nama Pegawai');

            $table->date('saved_date')->nullable()->comment('Tanggal Simpan');

            // SPD
            $table->string('spd_number')->nullable()->comment('Nomor SPD');
            $table->string('spd_period')->nullable()->comment('Periode SPD');
            $table->decimal('spd_value', 18, 2)->default(0)->comment('Nilai SPD');
            $table->string('spd_stage')->nullable()->comment('Tahapan SPD');
            $table->string('sub_stage_name')->nullable()->comment('Nama Sub Tahapan Jadwal');
            $table->string('apbd_stage')->nullable()->comment('Tahapan APBD');

            // SPP
            $table->string('spp_number')->nullable()->comment('Nomor SPP');
            $table->date('spp_date')->nullable()->comment('Tanggal SPP');

            // SPM
            $table->string('spm_number')->nullable()->comment('Nomor SPM');
            $table->date('spm_date')->nullable()->comment('Tanggal SPM');

            // SP2D
            $table->string('sp2d_number')->nullable()->comment('Nomor SP2D');
            $table->date('sp2d_date')->nullable()->comment('Tanggal SP2D');
            $table->date('transfer_date')->nullable()->comment('Tanggal Transfer');
            $table->decimal('sp2d_value', 18, 2)->default(0)->comment('Nilai SP2D');

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
            $table->index('spm_number');
            $table->index('sp2d_number');
            $table->index('document_number');
            $table->index('activity_code');
            $table->index('sub_activity_code');
            $table->index('account_code');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ls_payments');
    }
};
