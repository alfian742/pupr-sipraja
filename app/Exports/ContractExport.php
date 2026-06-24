<?php

namespace App\Exports;

use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ContractExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithColumnFormatting,
    WithCustomChunkSize,
    WithCustomCsvSettings
{
    use Exportable;

    private array $columns;
    private ?string $startDate;
    private ?string $endDate;
    private string $format;

    private array $exportOrder = [
        'contract_start_date',
        'contract_end_date',
        'contract_number',
        'third_party_name',
        'activity_code',
        'sub_account_code',
        'activity_description',
        'department',
        'budget_value',
        'contract_value',
        'fund_source',
        'bast_number',
    ];

    private array $dateColumns = [
        'contract_start_date',
        'contract_end_date',
    ];

    private array $numericColumns = [
        'budget_value',
        'contract_value',
    ];

    private array $headingsMap = [
        'contract_start_date' => 'Tanggal Mulai',
        'contract_end_date' => 'Tanggal Berakhir',
        'contract_number' => 'Nomor Kontrak',
        'third_party_name' => 'Pihak III',
        'activity_code' => 'Kode Kegiatan',
        'sub_account_code' => 'Sub Rek',
        'activity_description' => 'Uraian Kegiatan',
        'department' => 'Bidang',
        'budget_value' => 'Anggaran',
        'contract_value' => 'Nilai Kontrak',
        'fund_source' => 'Sumber Dana',
        'bast_number' => 'Nomor BAST',
    ];

    public function __construct(
        ?string $startDate = null,
        ?string $endDate = null,
        string $format = 'xlsx'
    ) {
        $this->startDate = $this->normalizeFilterDate($startDate);
        $this->endDate = $this->normalizeFilterDate($endDate);
        $this->format = strtolower($format) === 'csv' ? 'csv' : 'xlsx';

        $this->columns = array_values(array_diff(
            Schema::getColumnListing('contracts'),
            ['id', 'created_at', 'updated_at', 'created_by', 'updated_by']
        ));
    }

    public function query()
    {
        $selectColumns = array_values(array_intersect($this->exportOrder, $this->columns));

        $query = Contract::query()
            ->select($selectColumns)
            ->orderBy('contract_start_date')
            ->orderBy('id');

        if (!empty($this->startDate) && !empty($this->endDate)) {
            $query->where(function ($q) {
                $q->whereBetween('contract_start_date', [$this->startDate, $this->endDate])
                    ->orWhereBetween('contract_end_date', [$this->startDate, $this->endDate]);
            });
        }

        return $query;
    }

    public function headings(): array
    {
        $selectColumns = array_values(array_intersect($this->exportOrder, $this->columns));

        return array_map(fn($col) => $this->headingsMap[$col] ?? $col, $selectColumns);
    }

    public function map($row): array
    {
        $data = $row->toArray();
        $mappedData = [];

        $selectColumns = array_values(array_intersect($this->exportOrder, $this->columns));

        foreach ($selectColumns as $column) {
            $value = $data[$column] ?? null;

            if (in_array($column, $this->dateColumns, true) && !empty($value)) {
                try {
                    $date = Carbon::parse($value);

                    $value = $this->format === 'csv'
                        ? $date->format('d/m/Y')
                        : ExcelDate::dateTimeToExcel($date);
                } catch (\Throwable $e) {
                    $value = null;
                }
            }

            if (in_array($column, $this->numericColumns, true)) {
                $value = is_numeric($value) ? (float) $value : 0;
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
        $selectColumns = array_values(array_intersect($this->exportOrder, $this->columns));

        foreach ($selectColumns as $index => $column) {
            $columnLetter = $this->columnLetterFromIndex($index);

            if (in_array($column, $this->dateColumns, true)) {
                $formats[$columnLetter] = NumberFormat::FORMAT_DATE_DDMMYYYY;
            }

            if (in_array($column, $this->numericColumns, true)) {
                $formats[$columnLetter] = NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1;
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
        $index += 1;
        $letter = '';

        while ($index > 0) {
            $mod = ($index - 1) % 26;
            $letter = chr(65 + $mod) . $letter;
            $index = intdiv($index - $mod, 26);
        }

        return $letter;
    }

    private function normalizeFilterDate(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $value = trim($value);

        try {
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
                return Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
            }

            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }
}
