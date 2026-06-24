<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonnelProfile extends Model
{
    use HasFactory;
    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'personnel_profiles';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'personnel_name',
        'personnel_position',
        'personnel_description',
        'personnel_photo',
        'modified_by',
    ];

    // Relasi ke user yang melakukan modifikasi
    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}
