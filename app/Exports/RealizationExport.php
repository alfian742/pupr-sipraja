<?php

namespace App\Exports;

use App\Models\Realization;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class RealizationExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithColumnFormatting,
    WithCustomChunkSize,
    WithCustomCsvSettings
{
    use Exportable;

    private ?string $startDate;
    private ?string $endDate;
    private string $format;

    private array $exportOrder = [
        'verfication_date',
        'verified_by',
        'contract_start_date',
        'contract_end_date',
        'contract_number',
        'third_party_name',
        'activity_code',
        'sub_account_code',
        'activity_description',
        'department',
        'fund_source',
        'spm_number',
        'sp2d_date',
        'sp2d_number',
        'document_description',
        'budget_value',
        'contract_value',
        'sp2d_value',
    ];

    private array $dateColumns = [
        'verfication_date',
        'contract_start_date',
        'contract_end_date',
        'sp2d_date',
    ];

    private array $numericColumns = [
        'budget_value',
        'contract_value',
        'sp2d_value',
    ];

    private array $headingsMap = [
        'verfication_date' => 'Tanggal Verifikasi',
        'verified_by' => 'Verifikasi Oleh',
        'contract_start_date' => 'Tanggal Mulai',
        'contract_end_date' => 'Tanggal Berakhir',
        'contract_number' => 'Nomor Kontrak',
        'third_party_name' => 'Pihak III',
        'activity_code' => 'Kode Kegiatan',
        'sub_account_code' => 'Sub Rek',
        'activity_description' => 'Uraian Kegiatan',
        'department' => 'Bidang',
        'fund_source' => 'Sumber Dana',
        'spm_number' => 'Nomor SPM',
        'sp2d_date' => 'Tanggal SP2D',
        'sp2d_number' => 'Nomor SP2D',
        'document_description' => 'Uraian Pekerjaan',
        'budget_value' => 'Anggaran',
        'contract_value' => 'Nilai Kontrak',
        'sp2d_value' => 'Realisasi',
    ];

    public function __construct(
        ?string $startDate = null,
        ?string $endDate = null,
        string $format = 'xlsx'
    ) {
        $this->startDate = $this->normalizeFilterDate($startDate);
        $this->endDate = $this->normalizeFilterDate($endDate);
        $this->format = strtolower($format) === 'csv' ? 'csv' : 'xlsx';
    }

    public function query()
    {
        $query = Realization::query()
            ->with([
                'verifier:id,name',
                'contract:id,contract_start_date,contract_end_date,contract_number,third_party_name,activity_code,sub_account_code,activity_description,department,fund_source,budget_value,contract_value',
                'lsPayment:id,spm_number,sp2d_date,sp2d_number,document_description,sp2d_value',
            ])
            ->orderBy('verification_date')
            ->orderBy('id');

        if (!empty($this->startDate) && !empty($this->endDate)) {
            $query->whereBetween('verification_date', [
                $this->startDate,
                $this->endDate,
            ]);
        } elseif (!empty($this->startDate)) {
            $query->where('verification_date', '>=', $this->startDate);
        } elseif (!empty($this->endDate)) {
            $query->where('verification_date', '<=', $this->endDate);
        }

        return $query;
    }

    public function headings(): array
    {
        return array_map(
            fn($col) => $this->headingsMap[$col] ?? $col,
            $this->exportOrder
        );
    }

    public function map($row): array
    {
        $data = [
            'verfication_date' => $row->verification_date,
            'verified_by' => $row->verifier?->name,
            'contract_start_date' => $row->contract?->contract_start_date,
            'contract_end_date' => $row->contract?->contract_end_date,
            'contract_number' => $row->contract?->contract_number,
            'third_party_name' => $row->contract?->third_party_name,
            'activity_code' => $row->contract?->activity_code,
            'sub_account_code' => $row->contract?->sub_account_code,
            'activity_description' => $row->contract?->activity_description,
            'department' => $row->contract?->department,
            'fund_source' => $row->contract?->fund_source,
            'spm_number' => $row->lsPayment?->spm_number,
            'sp2d_date' => $row->lsPayment?->sp2d_date,
            'sp2d_number' => $row->lsPayment?->sp2d_number,
            'document_description' => $row->lsPayment?->document_description,
            'budget_value' => $row->contract?->budget_value,
            'contract_value' => $row->contract?->contract_value,
            'sp2d_value' => $row->lsPayment?->sp2d_value,
        ];

        $mappedData = [];

        foreach ($this->exportOrder as $column) {
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

        foreach ($this->exportOrder as $index => $column) {
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
