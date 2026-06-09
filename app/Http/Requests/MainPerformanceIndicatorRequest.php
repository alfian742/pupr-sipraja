<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MainPerformanceIndicatorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'indicator_code'      => 'required|string|max:255',
            'indicator_name'      => 'required|string',
            'indicator_unit'      => 'required|string|max:20',
            'baseline_year'       => 'required|integer|min:1900|max:2100',
            'baseline_value'      => 'nullable|numeric',
            'measurement_year'    => 'required|integer|min:1900|max:2100',
            'target_value'        => 'required|numeric',
            'achievement_value'   => 'nullable|numeric',
            'performance_value'   => 'nullable|numeric',
            'document_url'        => 'nullable|url',
        ];
    }

    public function messages(): array
    {
        return [
            // indicator code
            'indicator_code.required' => 'Kode indikator wajib diisi.',
            'indicator_code.string'   => 'Kode indikator harus berupa teks.',
            'indicator_code.max'      => 'Kode indikator maksimal 255 karakter.',

            // indicator name
            'indicator_name.required' => 'Nama indikator wajib diisi.',
            'indicator_name.string'   => 'Nama indikator harus berupa teks.',

            // indicator unit
            'indicator_unit.required' => 'Satuan indikator wajib diisi.',
            'indicator_unit.string'   => 'Satuan indikator harus berupa teks.',
            'indicator_unit.max'      => 'Satuan indikator maksimal 20 karakter.',

            // baseline year
            'baseline_year.required' => 'Tahun baseline wajib diisi.',
            'baseline_year.integer'  => 'Tahun baseline harus berupa angka.',
            'baseline_year.min'      => 'Tahun baseline minimal 1900.',
            'baseline_year.max'      => 'Tahun baseline maksimal 2100.',

            // baseline value
            'baseline_value.numeric' => 'Nilai baseline harus berupa angka.',

            // measurement year
            'measurement_year.required' => 'Tahun pengukuran wajib diisi.',
            'measurement_year.integer'  => 'Tahun pengukuran harus berupa angka.',
            'measurement_year.min'      => 'Tahun pengukuran minimal 1900.',
            'measurement_year.max'      => 'Tahun pengukuran maksimal 2100.',

            // target value
            'target_value.required' => 'Nilai target wajib diisi.',
            'target_value.numeric'  => 'Nilai target harus berupa angka.',

            // achievement value
            'achievement_value.numeric' => 'Nilai capaian harus berupa angka.',

            // performance value
            'performance_value.numeric' => 'Nilai kinerja harus berupa angka.',

            // document url
            'document_url.url' => 'Link dokumen tidak valid.',
        ];
    }
}
