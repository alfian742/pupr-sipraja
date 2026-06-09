<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegionalPerformanceIndicator extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'regional_performance_indicators';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'indicator_code',
        'indicator_type',
        'indicator_name',
        'indicator_unit',
        'baseline_year',
        'baseline_value',
        'measurement_year',
        'target_value',
        'achievement_value',
        'performance_value',
        'document_url',
        'modified_by',
    ];

    /**
     * Atribut yang harus di-cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'baseline_year' => 'integer',
        'baseline_value' => 'decimal:2',
        'measurement_year' => 'integer',
        'target_value' => 'decimal:2',
        'achievement_value' => 'decimal:2',
        'performance_value' => 'decimal:2',
    ];

    // Relasi ke user yang melakukan modifikasi
    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}
