<?php

namespace App\Imports;

use App\Models\QuestionnaireQuestion;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class QuestionnaireQuestionImport implements
    ToModel,
    WithHeadingRow,
    WithChunkReading,
    WithBatchInserts,
    SkipsEmptyRows
{
    /** 
     * 🔹 Cache daftar kolom dari tabel untuk fleksibilitas 
     */
    private array $columns;

    /** 
     * 🔹 Mapping heading Excel → kolom database 
     */
    protected array $headingMap = [
        'survey_indicator' => 'Indikator',
        'infrastructure_type' => 'Jenis Infrastruktur',
        'description' => 'Keterangan',
        'option_1' => 'Opsi 1',
        'option_2' => 'Opsi 2',
        'option_3' => 'Opsi 3',
        'option_4' => 'Opsi 4',
    ];


    public function __construct()
    {
        // 🔹 Cache nama kolom untuk efisiensi
        $this->columns = array_diff(
            Schema::getColumnListing('questionnaire_questions'),
            ['id', 'created_at', 'updated_at']
        );
    }

    /**
     * 🔹 Proses tiap baris
     */
    public function model(array $row)
    {
        $normalizedRow = $this->normalizeKeys($row);
        $data = [];

        foreach ($this->headingMap as $dbColumn => $excelHeading) {
            $headingKey = $this->normalizeKey($excelHeading);
            $value = $normalizedRow[$headingKey] ?? null;

            $data[$dbColumn] = $value;
        }

        return new QuestionnaireQuestion($data);
    }

    /**
     * 🔹 Normalisasi nama kolom agar robust (spasi, huruf besar, tanda baca)
     */
    private function normalizeKeys(array $row): array
    {
        $normalized = [];
        foreach ($row as $key => $value) {
            $normalized[$this->normalizeKey($key)] = $value;
        }
        return $normalized;
    }

    private function normalizeKey(string $key): string
    {
        return strtolower(Str::slug(str_replace(' ', '_', $key), '_'));
    }

    /**
     * 🔹 Baca data per chunk agar hemat memori
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * 🔹 Insert per batch agar efisien
     */
    public function batchSize(): int
    {
        return 500;
    }
}
