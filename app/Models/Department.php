<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'departments';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'department_name',
        'slug',
        'description',
        'logo',
        'modified_by',
    ];

    // Relasi ke user yang melakukan modifikasi
    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}
