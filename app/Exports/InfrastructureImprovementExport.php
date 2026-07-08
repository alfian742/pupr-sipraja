<?php

namespace App\Exports;

use App\Models\QuestionnaireRespondent;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class InfrastructureImprovementExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithColumnFormatting
{
    use Exportable;

    /**
     * Kolom yang akan diekspor.
     *
     * @var list<string>
     */
    private array $columns = [
        'survey_date',
        'id',
        'district',
        'village',
        'address',
        'gender',
        'age',
        'education',
        'occupation',
        'disability_status',
        'priority_infrastructure',
        'priority_improvement',
    ];

    /**
     * Kolom bertipe tanggal.
     *
     * @var list<string>
     */
    private array $datetimeColumns = [
        'survey_date',
    ];

    /**
     * Kolom bertipe angka.
     *
     * @var list<string>
     */
    private array $numericColumns = [];

    /**
     * Kolom bertipe teks.
     *
     * @var list<string>
     */
    private array $textColumns = [];

    /**
     * Mapping heading kolom.
     *
     * @var array<string, string>
     */
    private array $headingsMap = [
        'survey_date' => 'TANGGAL PENGISIAN',
        'id' => 'ID RESPONDEN',
        'district' => 'KECAMATAN',
        'village' => 'KELURAHAN/DESA',
        'address' => 'ALAMAT',
        'gender' => 'JENIS KELAMIN',
        'age' => 'USIA (TAHUN)',
        'education' => 'PENDIDIKAN TERAKHIR',
        'occupation' => 'PEKERJAAN',
        'disability_status' => 'DISABILITAS/NON-DISABILITAS',
        'priority_infrastructure' => 'JENIS INFRASTRUKTUR PRIORITAS',
        'priority_improvement' => 'ASPEK PRIORITAS',
    ];

    /**
     * Filter berdasarkan tanggal.
     */
    private ?string $startDate;
    private ?string $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    /**
     * Ambil data menggunakan query agar hemat memori.
     */
    public function query()
    {
        $query = QuestionnaireRespondent::query()
            ->select([
                'questionnaire_respondents.survey_date',
                'questionnaire_respondents.id',
                'questionnaire_respondents.district',
                'questionnaire_respondents.village',
                'questionnaire_respondents.address',
                'questionnaire_respondents.gender',
                'questionnaire_respondents.age',
                'questionnaire_respondents.education',
                'questionnaire_respondents.occupation',
                'questionnaire_respondents.disability_status',
                'questionnaire_respondents.priority_infrastructure',
                'questionnaire_respondents.priority_improvement',
            ])
            ->orderBy('questionnaire_respondents.survey_date', 'asc')
            ->orderBy('questionnaire_respondents.id', 'asc');

        if ($this->startDate) {
            $query->where(
                'questionnaire_respondents.survey_date',
                '>=',
                Carbon::parse($this->startDate)->startOfDay()
            );
        }

        if ($this->endDate) {
            $query->where(
                'questionnaire_respondents.survey_date',
                '<=',
                Carbon::parse($this->endDate)->endOfDay()
            );
        }

        return $query;
    }

    /**
     * Mapping setiap baris sebelum diekspor.
     */
    public function map($transaction): array
    {
        $data = [
            'survey_date' => $this->transformDate($transaction->survey_date),
            'id' => $transaction->id,
            'district' => $transaction->district,
            'village' => $transaction->village,
            'address' => $transaction->address,
            'gender' => $transaction->gender,
            'age' => $transaction->age,
            'education' => $transaction->education,
            'occupation' => $transaction->occupation,
            'disability_status' => $transaction->disability_status,
            'priority_infrastructure' => $transaction->priority_infrastructure,
            'priority_improvement' => $transaction->priority_improvement,
        ];

        $mappedData = [];

        foreach ($this->columns as $column) {
            $mappedData[] = $data[$column] ?? null;
        }

        return $mappedData;
    }

    /**
     * Format kolom (tanggal & angka).
     */
    public function columnFormats(): array
    {
        $formats = [];

        foreach ($this->columns as $index => $column) {
            $columnLetter = $this->columnLetterFromIndex($index);

            if (in_array($column, $this->datetimeColumns)) {
                $formats[$columnLetter] = NumberFormat::FORMAT_DATE_DDMMYYYY;
            }

            if (in_array($column, $this->numericColumns)) {
                $formats[$columnLetter] = NumberFormat::FORMAT_NUMBER;
            }

            if (in_array($column, $this->textColumns)) {
                $formats[$columnLetter] = NumberFormat::FORMAT_TEXT;
            }
        }

        return $formats;
    }

    /**
     * Heading kolom dengan nama yang ramah dibaca.
     */
    public function headings(): array
    {
        return array_map(fn($col) => $this->headingsMap[$col] ?? $col, $this->columns);
    }

    /**
     * Ubah tanggal agar dikenali Excel.
     */
    private function transformDate($value)
    {
        if (empty($value)) {
            return null;
        }

        try {
            return ExcelDate::dateTimeToExcel(Carbon::parse($value));
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Pastikan angka tetap valid.
     */
    private function transformNumber($value): int
    {
        if ($value === null || $value === '') {
            return 0;
        }

        return (int) $value;
    }

    /**
     * Konversi index ke huruf kolom Excel.
     */
    private function columnLetterFromIndex($index): string
    {
        $index += 1;
        $letter = '';

        while ($index > 0) {
            $mod = ($index - 1) % 26;
            $letter = chr(65 + $mod) . $letter;
            $index = intval(($index - $mod) / 26);
        }

        return $letter;
    }
}
