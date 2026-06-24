<?php

namespace App\Http\Requests;

use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
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
        $departmentId = $this->getDepartmentId();

        return [
            'department_name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('departments', 'department_name')->ignore($departmentId),
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:1024',
            ],
            'remove_logo' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'department_name.required' => 'Nama bidang wajib diisi.',
            'department_name.string'   => 'Nama bidang harus berupa teks.',
            'department_name.max'      => 'Nama bidang maksimal 150 karakter.',
            'department_name.unique'   => 'Nama bidang sudah digunakan.',

            'description.string' => 'Deskripsi harus berupa teks.',

            'logo.image' => 'Logo harus berupa gambar.',
            'logo.mimes' => 'Format logo yang didukung JPG, JPEG, atau PNG.',
            'logo.max'   => 'Ukuran logo maksimal 1 MB.',

            'remove_logo.boolean' => 'Format hapus logo tidak valid.',
        ];
    }

    private function getDepartmentId(): mixed
    {
        $department = $this->route('department') ?? $this->route('id');

        if ($department instanceof Department) {
            return $department->id;
        }

        return $department;
    }
}
