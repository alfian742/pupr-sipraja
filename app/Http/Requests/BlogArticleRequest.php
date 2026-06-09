<?php

namespace App\Http\Requests;

use App\Models\BlogArticle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogArticleRequest extends FormRequest
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
        $blogArticleId = $this->getBlogArticleId();

        return [
            'user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],
            'blog_category_id' => [
                'required',
                'integer',
                'exists:blog_categories,id',
            ],
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blog_articles', 'title')
                    ->ignore($blogArticleId)
                    ->whereNull('deleted_at'),
            ],
            'excerpt' => [
                'nullable',
                'string',
                'max:500',
            ],
            'content' => [
                'required',
                'string',
            ],
            'thumbnail' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'remove_thumbnail' => [
                'nullable',
                'boolean',
            ],
            'status' => [
                'required',
                'string',
                Rule::in([
                    BlogArticle::STATUS_DRAFT,
                    BlogArticle::STATUS_PUBLISHED,
                    BlogArticle::STATUS_ARCHIVED,
                ]),
            ],
            'is_featured' => [
                'nullable',
                'boolean',
            ],
            'published_at' => [
                'nullable',
                'date',
            ],
            'meta_title' => [
                'nullable',
                'string',
                'max:255',
            ],
            'meta_description' => [
                'nullable',
                'string',
                'max:500',
            ],
            'meta_keywords' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.integer' => 'Penulis tidak valid.',
            'user_id.exists'  => 'Penulis tidak ditemukan.',

            'blog_category_id.required' => 'Kategori wajib dipilih.',
            'blog_category_id.integer'  => 'Kategori tidak valid.',
            'blog_category_id.exists'   => 'Kategori tidak ditemukan.',

            'title.required' => 'Judul artikel wajib diisi.',
            'title.string'   => 'Judul artikel harus berupa teks.',
            'title.max'      => 'Judul artikel maksimal 255 karakter.',
            'title.unique'   => 'Judul artikel sudah digunakan.',

            'excerpt.string' => 'Ringkasan harus berupa teks.',
            'excerpt.max'    => 'Ringkasan maksimal 500 karakter.',

            'content.required' => 'Konten artikel wajib diisi.',
            'content.string'   => 'Konten artikel harus berupa teks.',

            'thumbnail.image' => 'Thumbnail harus berupa gambar.',
            'thumbnail.mimes' => 'Format thumbnail yang didukung JPG, JPEG, PNG, atau WEBP.',
            'thumbnail.max'   => 'Ukuran thumbnail maksimal 2 MB.',

            'remove_thumbnail.boolean' => 'Format hapus thumbnail tidak valid.',

            'status.required' => 'Status artikel wajib dipilih.',
            'status.string'   => 'Status artikel harus berupa teks.',
            'status.in'       => 'Status artikel tidak valid.',

            'is_featured.boolean' => 'Format artikel unggulan tidak valid.',

            'published_at.date' => 'Tanggal publikasi tidak valid.',

            'meta_title.string' => 'Meta title harus berupa teks.',
            'meta_title.max'    => 'Meta title maksimal 255 karakter.',

            'meta_description.string' => 'Meta description harus berupa teks.',
            'meta_description.max'    => 'Meta description maksimal 500 karakter.',

            'meta_keywords.string' => 'Meta keywords harus berupa teks.',
            'meta_keywords.max'    => 'Meta keywords maksimal 255 karakter.',
        ];
    }

    private function getBlogArticleId(): mixed
    {
        $blogArticle = $this->route('blog_article') ?? $this->route('article') ?? $this->route('id');

        if ($blogArticle instanceof BlogArticle) {
            return $blogArticle->id;
        }

        return $blogArticle;
    }
}
