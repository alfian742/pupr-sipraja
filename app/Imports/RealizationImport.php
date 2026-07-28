<?php

namespace App\Imports;

use App\Models\Realization;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class RealizationImport implements
    ToModel,
    WithHeadingRow,
    WithChunkReading,
    WithBatchInserts,
    SkipsEmptyRows,
    ShouldQueue,
    WithCustomCsvSettings
{
    use Importable;

    public int $tries = 3;
    public int $timeout = 1200;

    private array $columns;
    private ?int $userId;

    protected array $headingMap = [
        'verification_date' => 'Tanggal Verifikasi',
        'realization_contract_number' => 'Nomor Kontrak',
        'realization_spm_number' => 'Nomor SPM',
    ];

    protected array $dateColumns = []; // Tidak ada kolom tanggal yang perlu diubah menjadi format Y-m-d

    protected array $numericColumns = [
        'sp2d_value',
    ];

    public function __construct(?int $userId = null)
    {
        $this->userId = $userId;

        $this->columns = array_values(array_diff(
            Schema::getColumnListing('realizations'),
            ['id', 'created_at', 'updated_at', 'created_by', 'updated_by']
        ));
    }

    public function model(array $row)
    {
        $normalizedRow = $this->normalizeKeys($row);

        if (!$this->hasMeaningfulData($normalizedRow)) {
            return null;
        }

        $data = [];

        foreach ($this->headingMap as $dbColumn => $excelHeading) {
            $headingKey = $this->normalizeKey($excelHeading);
            $value = $normalizedRow[$headingKey] ?? null;

            if (is_string($value)) {
                $value = trim($value);
                $value = $value !== '' ? $value : null;
            }

            if (in_array($dbColumn, $this->dateColumns, true)) {
                $value = $this->convertToDate($value);
            }

            if (in_array($dbColumn, $this->numericColumns, true)) {
                $value = $this->convertToNumeric($value);
            }

            $data[$dbColumn] = $value;
        }

        $data = array_intersect_key($data, array_flip($this->columns));

        if (in_array('created_by', $this->columns, true)) {
            $data['created_by'] = $this->userId;
        }

        if (in_array('updated_by', $this->columns, true)) {
            $data['updated_by'] = $this->userId;
        }

        return new Realization($data);
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';',
            'enclosure' => '"',
            'escape_character' => '\\',
            'contiguous' => false,
            'input_encoding' => 'UTF-8',
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 50;
    }

    private function normalizeKeys(array $row): array
    {
        $normalized = [];

        foreach ($row as $key => $value) {
            $normalized[$this->normalizeKey((string) $key)] = $value;
        }

        return $normalized;
    }

    private function normalizeKey(string $key): string
    {
        // Antisipasi BOM dari CSV UTF-8
        $key = preg_replace('/^\xEF\xBB\xBF/', '', $key);

        // Antisipasi non-breaking space dari Excel
        $key = str_replace("\xC2\xA0", ' ', $key);

        $key = trim($key);

        return strtolower(
            Str::slug(
                str_replace(' ', '_', $key),
                '_'
            )
        );
    }

    private function hasMeaningfulData(array $row): bool
    {
        foreach ($this->headingMap as $excelHeading) {
            $headingKey = $this->normalizeKey($excelHeading);
            $value = $row[$headingKey] ?? null;

            if (is_string($value)) {
                $value = trim($value);
            }

            if ($value !== null && $value !== '') {
                return true;
            }
        }

        return false;
    }

    private function convertToDate($value): ?string
    {
        try {
            if ($value === null || $value === '') {
                return null;
            }

            if (is_numeric($value)) {
                return Carbon::instance(
                    ExcelDate::excelToDateTimeObject($value)
                )->format('Y-m-d');
            }

            $value = trim((string) $value);

            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                return Carbon::createFromFormat(
                    'Y-m-d',
                    $value
                )->format('Y-m-d');
            }

            $normalized = str_replace(
                ['.', '-', ' '],
                '/',
                $value
            );

            return Carbon::createFromFormat(
                'd/m/Y',
                $normalized
            )->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function convertToNumeric($value): float
    {
        if ($value === null || $value === '') {
            return 0;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $value = trim((string) $value);
        $value = preg_replace('/[^0-9,\.\-]/', '', $value);

        if ($value === '' || $value === null) {
            return 0;
        }

        if (str_contains($value, ',') && str_contains($value, '.')) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        } elseif (str_contains($value, ',')) {
            $value = str_replace(',', '.', $value);
        }

        return is_numeric($value) ? (float) $value : 0;
    }
}
