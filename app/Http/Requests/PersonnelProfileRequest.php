<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonnelProfileRequest extends FormRequest
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
            'personnel_name'        => 'required|string|max:100',
            'personnel_position'    => 'required|string|max:100',
            'personnel_description' => 'required|string',
            'personnel_photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ];
    }

    public function messages(): array
    {
        return [
            'personnel_name.required'        => 'Nama personel wajib diisi.',
            'personnel_name.max'             => 'Nama personel lebih dari 100 karakter.',
            'personnel_position.required'    => 'Jabatan wajib dipilih.',
            'personnel_position.max'         => 'Jabatan lebih dari 100 karakter.',
            'personnel_description.required' => 'Deskripsi wajib diisi.',
            'personnel_photo.mimes'          => 'Format yang didukung JPG, JPEG, atau PNG.',
            'personnel_photo.max'            => 'Ukuran foto maksimal 1 MB.',
        ];
    }
}
