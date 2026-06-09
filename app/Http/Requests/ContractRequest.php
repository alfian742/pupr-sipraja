<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractRequest extends FormRequest
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
            'budget_value',
            'contract_value',
            'contract_realization_value',
            'balance_value',
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
     * - Mengikuti skema database final contracts
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
            | KONTRAK
            |--------------------------------------------------------------------------
            */

            'contract_number' => ['nullable', 'string', 'max:255'],
            'contract_start_date' => ['nullable', 'date'],
            'contract_end_date' => ['nullable', 'date'],

            /*
            |--------------------------------------------------------------------------
            | PIHAK KETIGA
            |--------------------------------------------------------------------------
            */

            'third_party_name' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | KODE / REKENING
            |--------------------------------------------------------------------------
            */

            'activity_code' => ['nullable', 'string', 'max:255'],
            'sub_account_code' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | URAIAN / BIDANG
            |--------------------------------------------------------------------------
            */

            'activity_description' => ['nullable', 'string'],
            'department' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | NILAI
            |--------------------------------------------------------------------------
            */

            'budget_value' => ['nullable', 'numeric', 'min:0'],
            'contract_value' => ['nullable', 'numeric', 'min:0'],

            /*
            |--------------------------------------------------------------------------
            | DOKUMEN PENDUKUNG
            |--------------------------------------------------------------------------
            */

            'fund_source' => ['nullable', 'string', 'max:255'],
            'bast_number' => ['nullable', 'string', 'max:255'],
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
        ];
    }

    /**
     * Nama attribute agar pesan validasi lebih ramah dibaca user.
     */
    public function attributes(): array
    {
        return [
            'contract_number' => 'Nomor Kontrak',
            'contract_start_date' => 'Tanggal Mulai',
            'contract_end_date' => 'Tanggal Berakhir',

            'third_party_name' => 'Pihak III',

            'activity_code' => 'Kode Kegiatan',
            'sub_account_code' => 'Sub Rek',

            'activity_description' => 'Uraian Kegiatan',
            'department' => 'Bidang',

            'budget_value' => 'Anggaran',
            'contract_value' => 'Nilai Kontrak',

            'fund_source' => 'Sumber Dana',
            'bast_number' => 'Nomor BAST',
        ];
    }
}
