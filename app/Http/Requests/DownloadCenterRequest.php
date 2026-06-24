<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DownloadCenterRequest extends FormRequest
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
        $id = $this->route('id');

        return [
            'document_title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('download_centers', 'document_title')->ignore($id),
            ],
            'document_type' => ['required', 'string'],
            'description' => ['required', 'string'],
            'document_url' => ['required', 'url'],
            'status' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'document_title.required' => 'Judul dokumen wajib diisi.',
            'document_title.unique'   => 'Judul dokumen sudah digunakan.',
            'document_type.required' => 'Jenis dokumen wajib diisi.',
            'document_url.required'   => 'Link dokumen wajib diisi.',
            'document_url.url'        => 'Link dokumen tidak valid.',
            'description.required'   => 'Deskripsi dokumen wajib diisi.',
            'status.required'         => 'Status wajib dipilih.',
        ];
    }
}
