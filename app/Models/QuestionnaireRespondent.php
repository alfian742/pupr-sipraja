<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireRespondent extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * Tabel terkait dengan model.
     *
     * @var list<string>
     */
    protected $table = 'questionnaire_respondents';

    /**
     * Atribut yang dapat diisi massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'survey_date',
        'gender',
        'age',
        'education',
        'occupation',
        'disability_status',
        'district',
        'village',
        'address',
        'priority_infrastructure',
        'priority_improvement',
    ];

    /**
     * Relasi ke masing-masing infrastruktur.
     */
    public function internetNetwork()
    {
        return $this->hasOne(InternetNetwork::class, 'respondent_id');
    }

    public function irrigation()
    {
        return $this->hasOne(Irrigation::class, 'respondent_id');
    }

    public function powerGrid()
    {
        return $this->hasOne(PowerGrid::class, 'respondent_id');
    }

    public function road()
    {
        return $this->hasOne(Road::class, 'respondent_id');
    }

    public function transportationTerminal()
    {
        return $this->hasOne(TransportationTerminal::class, 'respondent_id');
    }

    public function wastewaterManagementSystem()
    {
        return $this->hasOne(WastewaterManagementSystem::class, 'respondent_id');
    }

    public function wasteManagementSystem()
    {
        return $this->hasOne(WasteManagementSystem::class, 'respondent_id');
    }

    public function waterSupplySystem()
    {
        return $this->hasOne(WaterSupplySystem::class, 'respondent_id');
    }
}
