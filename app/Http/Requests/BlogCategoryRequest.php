<?php

namespace App\Http\Requests;

use App\Models\BlogCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogCategoryRequest extends FormRequest
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
        $blogCategoryId = $this->getBlogCategoryId();

        return [
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('blog_categories', 'name')->ignore($blogCategoryId),
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.string'   => 'Nama kategori harus berupa teks.',
            'name.max'      => 'Nama kategori maksimal 150 karakter.',
            'name.unique'   => 'Nama kategori sudah digunakan.',

            'description.string' => 'Deskripsi harus berupa teks.',

            'is_active.boolean' => 'Format status aktif tidak valid.',

            'sort_order.integer' => 'Urutan harus berupa angka.',
            'sort_order.min'     => 'Urutan tidak boleh kurang dari 0.',
        ];
    }

    private function getBlogCategoryId(): mixed
    {
        $blogCategory = $this->route('blog_category') ?? $this->route('category') ?? $this->route('id');

        if ($blogCategory instanceof BlogCategory) {
            return $blogCategory->id;
        }

        return $blogCategory;
    }
}
