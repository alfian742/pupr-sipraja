<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FAQ extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'faqs';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'faq_question',
        'faq_answer',
        'modified_by',
    ];

    // Relasi ke user yang melakukan modifikasi
    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}
