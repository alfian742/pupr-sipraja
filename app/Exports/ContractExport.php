<?php

namespace App\Exports;

use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
        'sub_activity_code',
        'account_code',
        'activity_description',
        'department',
        'budget_value',
        'contract_value',
        'sp2d_value',
        'balance_value',
        'fund_source',
        'bast_number',
    ];

    private array $dateColumns = []; // Tidak ada kolom tanggal yang perlu diubah menjadi format Y-m-d

    private array $numericColumns = [
        'budget_value',
        'contract_value',
        'sp2d_value',
        'balance_value',
    ];

    private array $headingsMap = [
        'contract_start_date' => 'Tanggal Mulai',
        'contract_end_date' => 'Tanggal Berakhir',
        'contract_number' => 'Nomor Kontrak',
        'third_party_name' => 'Pihak III',
        'sub_activity_code' => 'Kode Sub Kegiatan',
        'account_code' => 'Kode Rekening',
        'activity_description' => 'Uraian Kegiatan',
        'department' => 'Bidang',
        'budget_value' => 'Anggaran',
        'contract_value' => 'Nilai Kontrak',
        'sp2d_value' => 'Realisasi',
        'balance_value' => 'Saldo',
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

        $this->columns = array_values(array_merge(
            array_diff(
                Schema::getColumnListing('contracts'),
                ['id', 'created_at', 'updated_at', 'created_by', 'updated_by']
            ),
            [
                'sp2d_value',
                'balance_value',
            ]
        ));
    }

    public function query()
    {
        $selectColumns = array_values(array_intersect(
            $this->exportOrder,
            $this->columns
        ));

        /*
        |--------------------------------------------------------------------------
        | SUBQUERY REALISASI LS
        |--------------------------------------------------------------------------
        | Ambil total SP2D dari LS Payment yang benar-benar terhubung ke realisasi.
        |--------------------------------------------------------------------------
        */

        $realizationSubquery = DB::table('realizations')
            ->leftJoin('ls_payments', 'ls_payments.id', '=', 'realizations.ls_payment_id')
            ->select([
                'realizations.contract_id',
                DB::raw('COALESCE(SUM(ls_payments.sp2d_value), 0) as realized_sp2d_value'),
            ])
            ->groupBy('realizations.contract_id');

        /*
        |--------------------------------------------------------------------------
        | KOLOM CONTRACT
        |--------------------------------------------------------------------------
        */

        $contractColumns = array_values(array_diff(
            $selectColumns,
            [
                'sp2d_value',
                'balance_value',
            ]
        ));

        $contractColumns = array_map(
            fn($column) => "contracts.{$column}",
            $contractColumns
        );

        /*
        |--------------------------------------------------------------------------
        | BASE QUERY
        |--------------------------------------------------------------------------
        */

        $query = Contract::query()
            ->select($contractColumns)
            ->addSelect([
                DB::raw('COALESCE(realization_totals.realized_sp2d_value, 0) as sp2d_value'),
                DB::raw('(COALESCE(contracts.contract_value, 0) - COALESCE(realization_totals.realized_sp2d_value, 0)) as balance_value'),
            ])
            ->leftJoinSub($realizationSubquery, 'realization_totals', function ($join) {
                $join->on('contracts.id', '=', 'realization_totals.contract_id');
            })
            ->orderBy('contracts.created_at')
            ->orderBy('contracts.id');

        if (!empty($this->startDate)) {
            $query->where(
                'contracts.created_at',
                '>=',
                Carbon::parse($this->startDate)->startOfDay()
            );
        }

        if (!empty($this->endDate)) {
            $query->where(
                'contracts.created_at',
                '<',
                Carbon::parse($this->endDate)->addDay()->startOfDay()
            );
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
        return 500;
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
