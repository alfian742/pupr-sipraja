<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireQuestion extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * Tabel terkait dengan model.
     *
     * @var list<string>
     */
    protected $table = 'questionnaire_questions';

    /**
     * Atribut yang dapat diisi massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'survey_indicator',
        'infrastructure_type',
        'description',
        'option_1',
        'option_2',
        'option_3',
        'option_4',
    ];
}
