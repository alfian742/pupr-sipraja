<?php

namespace App\Http\Requests;

use App\Models\PublicInformationPortal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PublicInformationPortalRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('website_url')) {
            $websiteUrl = trim($this->website_url);

            if (!preg_match('/^https?:\/\//i', $websiteUrl)) {
                $websiteUrl = 'https://' . $websiteUrl;
            }

            $this->merge([
                'website_url' => $websiteUrl,
            ]);
        }
    }

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
        $portalId = $this->getPortalId();

        return [
            'portal_name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('public_information_portals', 'portal_name')->ignore($portalId),
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'website_url' => [
                'nullable',
                'string',
                'max:255',
                'url',
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
            'portal_name.required' => 'Nama portal informasi publik wajib diisi.',
            'portal_name.string'   => 'Nama portal informasi publik harus berupa teks.',
            'portal_name.max'      => 'Nama portal informasi publik maksimal 150 karakter.',
            'portal_name.unique'   => 'Nama portal informasi publik sudah digunakan.',

            'description.string' => 'Deskripsi portal informasi publik harus berupa teks.',

            'website_url.string' => 'Link portal informasi publik harus berupa teks.',
            'website_url.max'    => 'Link portal informasi publik maksimal 255 karakter.',
            'website_url.url'    => 'Format link portal informasi publik tidak valid.',

            'logo.image' => 'Logo portal informasi publik harus berupa gambar.',
            'logo.mimes' => 'Format logo portal informasi publik yang didukung JPG, JPEG, atau PNG.',
            'logo.max'   => 'Ukuran logo portal informasi publik maksimal 1 MB.',

            'remove_logo.boolean' => 'Format hapus logo tidak valid.',
        ];
    }

    private function getPortalId(): mixed
    {
        $portal = $this->route('public-information-portals') ?? $this->route('id');

        if ($portal instanceof PublicInformationPortal) {
            return $portal->id;
        }

        return $portal;
    }
}
