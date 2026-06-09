<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RealizationRequest extends FormRequest
{
    /**
     * Tentukan apakah user berhak menggunakan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalisasi input sebelum divalidasi.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'contract_id'   => $this->filled('contract_id') ? $this->contract_id : null,
            'ls_payment_id' => $this->filled('ls_payment_id') ? $this->ls_payment_id : null,
        ]);
    }

    /**
     * Aturan validasi untuk store maupun update.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $realizationId = $this->route('id')?->id ?? $this->route('id');

        return [
            'ls_payment_id' => [
                'required',
                'integer',
                'exists:ls_payments,id',
                Rule::unique('realizations', 'ls_payment_id')
                    ->where(fn($query) => $query->where('contract_id', $this->contract_id))
                    ->ignore($realizationId),
            ],

            'contract_id' => [
                'required',
                'integer',
                'exists:contracts,id',
            ],

            'match_status' => [
                'nullable',
                'string',
                'in:SAMA,BEDA',
            ],
        ];
    }

    /**
     * Pesan validasi dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'ls_payment_id.required' => 'Nomor SPM wajib dipilih.',
            'ls_payment_id.integer'  => 'Nomor SPM tidak valid.',
            'ls_payment_id.exists'   => 'Data Nomor SPM yang dipilih tidak ditemukan.',
            'ls_payment_id.unique'   => 'Kombinasi Nomor Kontrak dan Nomor SPM sudah digunakan.',

            'contract_id.required' => 'Nomor kontrak wajib dipilih.',
            'contract_id.integer'  => 'Nomor kontrak tidak valid.',
            'contract_id.exists'   => 'Data nomor kontrak yang dipilih tidak ditemukan.',

            'match_status.string' => 'Status kecocokan harus berupa teks.',
            'match_status.in'     => 'Status kecocokan harus bernilai SAMA atau BEDA.',
        ];
    }

    /**
     * Nama atribut agar lebih ramah dibaca user.
     */
    public function attributes(): array
    {
        return [
            'ls_payment_id'     => 'Nomor SPM',
            'contract_id'       => 'Nomor Kontrak',
            'match_status'      => 'Status Kecocokan',
        ];
    }
}
