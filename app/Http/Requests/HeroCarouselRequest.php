<?php

namespace App\Http\Requests;

use App\Models\HeroCarousel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HeroCarouselRequest extends FormRequest
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
        $heroCarouselId = $this->getHeroCarouselId();

        return [
            'title' => [
                'required',
                'string',
                'max:150',
                Rule::unique('hero_carousels', 'title')->ignore($heroCarouselId),
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'image_path' => [
                $heroCarouselId ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
            'remove_image' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul carousel wajib diisi.',
            'title.string'   => 'Judul carousel harus berupa teks.',
            'title.max'      => 'Judul carousel maksimal 150 karakter.',
            'title.unique'   => 'Judul carousel sudah digunakan.',

            'description.string' => 'Deskripsi singkat harus berupa teks.',

            'image_path.required' => 'Gambar carousel wajib diunggah.',
            'image_path.image'    => 'File carousel harus berupa gambar.',
            'image_path.mimes'    => 'Format gambar yang didukung JPG, JPEG, atau PNG.',
            'image_path.max'      => 'Ukuran gambar maksimal 2 MB.',

            'sort_order.required' => 'Urutan carousel wajib diisi.',
            'sort_order.integer'  => 'Urutan carousel harus berupa angka.',
            'sort_order.min'      => 'Urutan carousel minimal 0.',

            'is_active.boolean' => 'Format status aktif tidak valid.',

            'remove_image.boolean' => 'Format hapus gambar tidak valid.',
        ];
    }

    private function getHeroCarouselId(): mixed
    {
        $heroCarousel = $this->route('hero_carousel')
            ?? $this->route('heroCarousel')
            ?? $this->route('hero-carousel')
            ?? $this->route('id');

        if ($heroCarousel instanceof HeroCarousel) {
            return $heroCarousel->id;
        }

        return $heroCarousel;
    }
}
