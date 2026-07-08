<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * Tabel terkait dengan model.
     *
     * @var list<string>
     */
    protected $table = 'villages';

    /**
     * Atribut yang dapat diisi massal.
     */
    protected $fillable = [
        'district_id',
        'village_name',
        'bps_code',
        'kemendagri_code',
        'resident_count',
    ];

    /**
     * Relasi: Village milik satu District
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
