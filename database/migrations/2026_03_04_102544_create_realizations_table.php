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
        Schema::create('realizations', function (Blueprint $table) {
            $table->id();

            $table->dateTime('verification_date')->nullable()->comment('Tanggal Verifikasi');

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('User yang melakukan validasi');

            $table->foreignId('contract_id')
                ->nullable()
                ->constrained('contracts')
                ->nullOnDelete()
                ->comment('Relasi ke contracts.id');

            $table->foreignId('ls_payment_id')
                ->nullable()
                ->constrained('ls_payments')
                ->nullOnDelete()
                ->comment('Relasi ke ls_payments.id');

            $table->string('match_status')
                ->nullable()
                ->comment('Status kecocokan: SAMA / BEDA');

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

            $table->index('contract_id');
            $table->index('ls_payment_id');
            $table->index('verification_date');
            $table->index('match_status');
            $table->index(['contract_id', 'ls_payment_id']);

            $table->unique(['contract_id', 'ls_payment_id'], 'realizations_contract_ls_payment_unique');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realizations');
    }
};
