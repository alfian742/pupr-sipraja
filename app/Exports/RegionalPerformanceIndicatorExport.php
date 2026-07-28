<?php

namespace App\Exports;

use App\Models\RegionalPerformanceIndicator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class RegionalPerformanceIndicatorExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithColumnFormatting,
    WithCustomChunkSize,
    WithCustomCsvSettings
{
    use Exportable;

    private ?string $measurementYear;
    private ?string $period;
    private string $format;

    private array $exportOrder = [
        'indicator_code',
        'indicator_name',
        'indicator_unit',
        'baseline_year',
        'baseline_value',
        'measurement_year',
        'period',
        'target_value',
        'achievement_value',
        'performance_value',
        'document_url',
    ];

    private array $numericColumns = [
        'baseline_value',
        'target_value',
        'achievement_value',
        'performance_value',
    ];

    private array $yearColumns = [
        'baseline_year',
        'measurement_year',
    ];

    private array $dateColumns = [];

    private array $headingsMap = [
        'indicator_code' => 'Kode Indikator',
        'indicator_name' => 'Nama Indikator',
        'indicator_unit' => 'Satuan',
        'baseline_year' => 'Tahun Baseline',
        'baseline_value' => 'Nilai Baseline',
        'measurement_year' => 'Tahun',
        'period' => 'Periode',
        'target_value' => 'Target',
        'achievement_value' => 'Capaian',
        'performance_value' => 'Kinerja',
        'document_url' => 'Dokumen',
    ];

    public function __construct(
        ?string $measurementYear = null,
        ?string $period = null,
        string $format = 'xlsx'
    ) {
        $this->measurementYear = $this->normalizeFilter($measurementYear);
        $this->period = $this->normalizeFilter($period);
        $this->format = strtolower($format) === 'csv' ? 'csv' : 'xlsx';
    }

    public function query(): Builder
    {
        return RegionalPerformanceIndicator::query()
            ->with('modifiedBy:id,name')
            ->when(
                $this->measurementYear !== null,
                function (Builder $query) {
                    $query->where(
                        'measurement_year',
                        $this->measurementYear
                    );
                }
            )
            ->when(
                $this->period !== null,
                function (Builder $query) {
                    $query->where(
                        'period',
                        $this->period
                    );
                }
            )
            ->orderBy('indicator_code')
            ->orderBy('measurement_year')
            ->orderBy('period')
            ->orderBy('id');
    }

    public function headings(): array
    {
        return array_map(
            fn($column) => $this->headingsMap[$column] ?? $column,
            $this->exportOrder
        );
    }

    public function map($row): array
    {
        $data = [
            'indicator_code' => $row->indicator_code,
            'indicator_name' => $row->indicator_name,
            'indicator_unit' => $row->indicator_unit,
            'baseline_year' => $row->baseline_year,
            'baseline_value' => $row->baseline_value,
            'measurement_year' => $row->measurement_year,
            'period' => $row->period,
            'target_value' => $row->target_value,
            'achievement_value' => $row->achievement_value,
            'performance_value' => $row->performance_value,
            'document_url' => $row->document_url,
        ];

        $mappedData = [];

        foreach ($this->exportOrder as $column) {
            $value = $data[$column] ?? null;

            if (
                in_array($column, $this->dateColumns, true) &&
                !empty($value)
            ) {
                try {
                    $date = Carbon::parse($value);

                    $value = $this->format === 'csv'
                        ? $date->format('d/m/Y H:i')
                        : ExcelDate::dateTimeToExcel($date);
                } catch (\Throwable $e) {
                    $value = null;
                }
            }

            if (in_array($column, $this->numericColumns, true)) {
                $value = is_numeric($value)
                    ? (float) $value
                    : null;
            }

            if (in_array($column, $this->yearColumns, true)) {
                $value = is_numeric($value)
                    ? (int) $value
                    : null;
            }

            $mappedData[] = $value;
        }

        return $mappedData;
    }

    public function columnFormats(): array
    {
        if ($this->format === 'csv') {
            return [];
        }

        $formats = [];

        foreach ($this->exportOrder as $index => $column) {
            $columnLetter = $this->columnLetterFromIndex($index);

            if (in_array($column, $this->dateColumns, true)) {
                $formats[$columnLetter] = 'dd/mm/yyyy hh:mm';
            }

            if (in_array($column, $this->numericColumns, true)) {
                $formats[$columnLetter] = '#,##0.00';
            }

            if (in_array($column, $this->yearColumns, true)) {
                $formats[$columnLetter] = '0';
            }

            if ($column === 'indicator_code') {
                $formats[$columnLetter] = NumberFormat::FORMAT_TEXT;
            }
        }

        return $formats;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';',
            'enclosure' => '"',
            'line_ending' => "\r\n",
            'use_bom' => true,
            'include_separator_line' => false,
            'excel_compatibility' => false,
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    private function columnLetterFromIndex(int $index): string
    {
        $index++;
        $letter = '';

        while ($index > 0) {
            $mod = ($index - 1) % 26;
            $letter = chr(65 + $mod) . $letter;
            $index = intdiv($index - $mod, 26);
        }

        return $letter;
    }

    private function normalizeFilter(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        return trim($value);
    }
}
