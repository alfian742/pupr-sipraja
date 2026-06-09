<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadCenter extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'download_centers';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'document_title',
        'slug',
        'description',
        'document_url',
        'status',
        'modified_by',
    ];

    // Relasi ke user yang melakukan modifikasi
    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}
