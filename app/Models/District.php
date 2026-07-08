<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * Tabel terkait dengan model.
     *
     * @var list<string>
     */
    protected $table = 'districts';

    /**
     * Atribut yang dapat diisi massal.
     */
    protected $fillable = [
        'district_name',
        'bps_code',
        'kemendagri_code',
        'resident_count',
    ];

    /**
     * Relasi: District memiliki banyak Village
     */
    public function villages()
    {
        return $this->hasMany(Village::class, 'district_id');
    }
}
