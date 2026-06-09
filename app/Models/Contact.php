<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'contacts';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'email_alternative',
        'phone_number',
        'phone_number_alternative',
        'whatsapp_number',
        'whatsapp_number_alternative',
        'operational_time',
        'address',
        'google_maps_embed',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'youtube_url',
        'tiktok_url',
        'modified_by'
    ];

    // Relasi ke user yang mengubah data kontak
    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}
