<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationProfile extends Model
{
    use HasFactory;
    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'organization_profiles';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organization_structure',
        'organization_structure_modified_by',
        'organization_structure_modified_at',
        'organization_about',
        'organization_summary',
        'organization_vision',
        'organization_mission',
        'profile_modified_by',
        'profile_modified_at',
    ];

    // Relasi ke user yang mengubah struktur organisasi
    public function structureModifiedBy()
    {
        return $this->belongsTo(User::class, 'organization_structure_modified_by');
    }

    // Relasi ke user yang mengubah profil organisasi
    public function profileModifiedBy()
    {
        return $this->belongsTo(User::class, 'profile_modified_by');
    }
}
