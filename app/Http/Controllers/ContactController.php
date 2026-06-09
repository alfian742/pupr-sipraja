<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contact = Contact::first();
        return view('dashboard.other-informations.contact.index', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        $validated = $request->validate([
            'email' => 'required|email',
            'email_alternative' => 'nullable|email',
            'phone_number' => 'required|string|max:20',
            'phone_number_alternative' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|digits_between:1,15',
            'whatsapp_number_alternative' => 'nullable|digits_between:1,15',
            'operational_time' => 'required|string|max:255',

            // Address
            'address' => 'required|string',
            'google_maps_embed' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/<iframe[^>]+src="https:\/\/www\.google\.com\/maps\/embed[^"]*"[^>]*><\/iframe>/s', trim($value))) {
                        $fail('Embed Google Maps tidak valid.');
                    }
                }
            ],

            // Social Media URL
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url'
        ], [
            // Email
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',

            'email_alternative.email' => 'Format email alternatif tidak valid.',

            // Phone Number
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.string' => 'Nomor telepon harus berupa teks.',
            'phone_number.max' => 'Nomor telepon maksimal 20 karakter.',

            'phone_number_alternative.string' => 'Nomor telepon alternatif harus berupa teks.',
            'phone_number_alternative.max' => 'Nomor telepon alternatif maksimal 20 karakter.',

            // WhatsApp Number
            'whatsapp_number.numeric' => 'Nomor WhatsApp harus berupa angka.',
            'whatsapp_number.digits_between' => 'Nomor WhatsApp maksimal 15 digit.',

            'whatsapp_number_alternative.numeric' => 'Nomor WhatsApp alternatif harus berupa angka.',
            'whatsapp_number_alternative.digits_between' => 'Nomor WhatsApp alternatif maksimal 15 digit.',

            // Waktu Operasional
            'operational_time.required' => 'Waktu operasional wajib diisi.',
            'operational_time.string' => 'Waktu operasional harus berupa teks.',
            'operational_time.max' => 'Waktu operasional maksimal 255 karakter.',

            // Address
            'address.required' => 'Alamat wajib diisi.',
            'address.string' => 'Alamat harus berupa teks.',

            // Social Media URL
            'facebook_url.url' => 'Link Facebook tidak valid.',
            'instagram_url.url' => 'Link Instagram tidak valid.',
            'twitter_url.url' => 'Link Twitter tidak valid.',
            'youtube_url.url' => 'Link YouTube tidak valid.',
            'tiktok_url.url' => 'Link TikTok tidak valid.',
        ]);

        DB::beginTransaction();

        try {
            $validated['modified_by'] = Auth::user()->id;

            $contact->update($validated);

            Cache::forget('contact'); // Hapus cache kontak agar data terbaru ditampilkan di frontend (Lihat AppServiceProvider untuk cache kontak)

            DB::commit();

            return redirect()->route('dashboard.other-informations.contact.index')->with('success', 'Kontak berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan data kontak: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan, data kontak gagal disimpan.')->withInput();
        }
    }
}
