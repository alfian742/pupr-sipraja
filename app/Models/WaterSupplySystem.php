<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterSupplySystem extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * Tabel terkait dengan model.
     *
     * @var list<string>
     */
    protected $table = 'water_supply_systems';

    /**
     * Atribut yang dapat diisi massal.
     */
    protected $fillable = [
        'respondent_id',
        'physical_availability_score',
        'quality_score',
        'suitability_score',
        'utilization_score',
        'expectation_score',
    ];

    /**
     * Relasi ke responden.
     */
    public function respondent()
    {
        return $this->belongsTo(QuestionnaireRespondent::class, 'respondent_id');
    }
}
