<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LsPaymentRequest extends FormRequest
{
    /**
     * Tentukan apakah user berhak menggunakan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalisasi input sebelum proses validasi.
     * Jika field numeric kosong maka otomatis menjadi 0.
     */
    protected function prepareForValidation(): void
    {
        $numericFields = [
            'realization_value',
            'deposit_value',
            'spd_value',
            'sp2d_value',
        ];

        foreach ($numericFields as $field) {
            if ($this->input($field) === null || $this->input($field) === '') {
                $this->merge([
                    $field => 0
                ]);
            }
        }
    }

    /**
     * Aturan validasi yang berlaku untuk store maupun update.
     *
     * Catatan:
     * - Mengikuti skema database final ls_payments
     * - Field tetap dibuat nullable sesuai skema
     * - Numeric mengikuti tipe decimal di database
     * - Date mengikuti field tanggal pada skema
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | SKPD
            |--------------------------------------------------------------------------
            */

            'skpd_code' => ['nullable', 'string', 'max:255'],
            'skpd_name' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | SUB SKPD
            |--------------------------------------------------------------------------
            */

            'sub_skpd_code' => ['nullable', 'string', 'max:255'],
            'sub_skpd_name' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | FUNGSI
            |--------------------------------------------------------------------------
            */

            'function_code' => ['nullable', 'string', 'max:255'],
            'function_name' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | SUB FUNGSI
            |--------------------------------------------------------------------------
            */

            'sub_function_code' => ['nullable', 'string', 'max:255'],
            'sub_function_name' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | URUSAN
            |--------------------------------------------------------------------------
            */

            'affair_code' => ['nullable', 'string', 'max:255'],
            'affair_name' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | BIDANG URUSAN
            |--------------------------------------------------------------------------
            */

            'field_affair_code' => ['nullable', 'string', 'max:255'],
            'field_affair_name' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | PROGRAM
            |--------------------------------------------------------------------------
            */

            'program_code' => ['nullable', 'string', 'max:255'],
            'program_name' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | KEGIATAN
            |--------------------------------------------------------------------------
            */

            'activity_code' => ['nullable', 'string', 'max:255'],
            'activity_name' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | SUB KEGIATAN
            |--------------------------------------------------------------------------
            */

            'sub_activity_code' => ['nullable', 'string', 'max:255'],
            'sub_activity_name' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | REKENING
            |--------------------------------------------------------------------------
            */

            'account_code' => ['nullable', 'string', 'max:255'],
            'account_name' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | DOKUMEN UMUM
            |--------------------------------------------------------------------------
            */

            'document_number' => ['nullable', 'string', 'max:255'],
            'document_type' => ['nullable', 'string', 'max:255'],
            'transaction_type' => ['nullable', 'string', 'max:255'],
            'dpt_number' => ['nullable', 'string', 'max:255'],
            'document_date' => ['nullable', 'date'],
            'document_description' => ['nullable', 'string'],

            'realization_value' => ['nullable', 'numeric', 'min:0'],
            'deposit_value' => ['nullable', 'numeric', 'min:0'],

            /*
            |--------------------------------------------------------------------------
            | PEGAWAI
            |--------------------------------------------------------------------------
            */

            'nip' => ['nullable', 'string', 'max:20'],
            'personnel_name' => ['nullable', 'string', 'max:150'],
            'saved_date' => ['nullable', 'date'],

            /*
            |--------------------------------------------------------------------------
            | SPD
            |--------------------------------------------------------------------------
            */

            'spd_number' => ['nullable', 'string', 'max:255'],
            'spd_period' => ['nullable', 'string', 'max:255'],
            'spd_value' => ['nullable', 'numeric', 'min:0'],
            'spd_stage' => ['nullable', 'string', 'max:255'],
            'sub_stage_name' => ['nullable', 'string', 'max:255'],
            'apbd_stage' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | SPP
            |--------------------------------------------------------------------------
            */

            'spp_number' => ['nullable', 'string', 'max:255'],
            'spp_date' => ['nullable', 'date'],

            /*
            |--------------------------------------------------------------------------
            | SPM
            |--------------------------------------------------------------------------
            */

            'spm_number' => ['nullable', 'string', 'max:255'],
            'spm_date' => ['nullable', 'date'],

            /*
            |--------------------------------------------------------------------------
            | SP2D
            |--------------------------------------------------------------------------
            */

            'sp2d_number' => ['nullable', 'string', 'max:255'],
            'sp2d_date' => ['nullable', 'date'],
            'transfer_date' => ['nullable', 'date'],
            'sp2d_value' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * Pesan validasi dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | STRING
            |--------------------------------------------------------------------------
            */

            '*.string' => 'Kolom :attribute harus berupa teks.',
            '*.max' => 'Kolom :attribute tidak boleh lebih dari :max karakter.',

            /*
            |--------------------------------------------------------------------------
            | DATE
            |--------------------------------------------------------------------------
            */

            '*.date' => 'Kolom :attribute harus berupa tanggal yang valid.',

            /*
            |--------------------------------------------------------------------------
            | NUMERIC
            |--------------------------------------------------------------------------
            */

            '*.numeric' => 'Kolom :attribute harus berupa angka.',
            '*.min' => 'Kolom :attribute tidak boleh kurang dari :min.',

            /*
            |--------------------------------------------------------------------------
            | KHUSUS
            |--------------------------------------------------------------------------
            */

            'nip.max' => 'Kolom NIP tidak boleh lebih dari :max karakter.',
            'personnel_name.max' => 'Kolom Nama Pegawai tidak boleh lebih dari :max karakter.',
        ];
    }

    /**
     * Nama attribute agar pesan validasi lebih ramah dibaca user.
     */
    public function attributes(): array
    {
        return [
            'skpd_code' => 'Kode SKPD',
            'skpd_name' => 'Nama SKPD',

            'sub_skpd_code' => 'Kode Sub SKPD',
            'sub_skpd_name' => 'Nama Sub SKPD',

            'function_code' => 'Kode Fungsi',
            'function_name' => 'Nama Fungsi',

            'sub_function_code' => 'Kode Sub Fungsi',
            'sub_function_name' => 'Nama Sub Fungsi',

            'affair_code' => 'Kode Urusan',
            'affair_name' => 'Nama Urusan',

            'field_affair_code' => 'Kode Bidang Urusan',
            'field_affair_name' => 'Nama Bidang Urusan',

            'program_code' => 'Kode Program',
            'program_name' => 'Nama Program',

            'activity_code' => 'Kode Kegiatan',
            'activity_name' => 'Nama Kegiatan',

            'sub_activity_code' => 'Kode Sub Kegiatan',
            'sub_activity_name' => 'Nama Sub Kegiatan',

            'account_code' => 'Kode Rekening',
            'account_name' => 'Nama Rekening',

            'document_number' => 'Nomor Dokumen',
            'document_type' => 'Jenis Dokumen',
            'transaction_type' => 'Jenis Transaksi',
            'dpt_number' => 'Nomor DPT',
            'document_date' => 'Tanggal Dokumen',
            'document_description' => 'Keterangan Dokumen',

            'realization_value' => 'Nilai Realisasi',
            'deposit_value' => 'Nilai Setoran',

            'nip' => 'NIP',
            'personnel_name' => 'Nama Pegawai',
            'saved_date' => 'Tanggal Simpan',

            'spd_number' => 'Nomor SPD',
            'spd_period' => 'Periode SPD',
            'spd_value' => 'Nilai SPD',
            'spd_stage' => 'Tahapan SPD',
            'sub_stage_name' => 'Nama Sub Tahapan',
            'apbd_stage' => 'Tahapan APBD',

            'spp_number' => 'Nomor SPP',
            'spp_date' => 'Tanggal SPP',

            'spm_number' => 'Nomor SPM',
            'spm_date' => 'Tanggal SPM',

            'sp2d_number' => 'Nomor SP2D',
            'sp2d_date' => 'Tanggal SP2D',
            'transfer_date' => 'Tanggal Transfer',
            'sp2d_value' => 'Nilai SP2D',
        ];
    }
}
