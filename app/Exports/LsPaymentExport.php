<?php

namespace App\Exports;

use App\Models\LsPayment;
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

class LsPaymentExport implements
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
        'skpd_code',
        'skpd_name',
        'sub_skpd_code',
        'sub_skpd_name',
        'function_code',
        'function_name',
        'sub_function_code',
        'sub_function_name',
        'affair_code',
        'affair_name',
        'field_affair_code',
        'field_affair_name',
        'program_code',
        'program_name',
        'activity_code',
        'activity_name',
        'sub_activity_code',
        'sub_activity_name',
        'account_code',
        'account_name',
        'document_number',
        'document_type',
        'transaction_type',
        'dpt_number',
        'document_date',
        'document_description',
        'realization_value',
        'deposit_value',
        'nip',
        'personnel_name',
        'saved_date',
        'spd_number',
        'spd_period',
        'spd_value',
        'spd_stage',
        'sub_stage_name',
        'apbd_stage',
        'spp_number',
        'spp_date',
        'spm_number',
        'spm_date',
        'sp2d_number',
        'sp2d_date',
        'transfer_date',
        'sp2d_value',
    ];

    private array $dateColumns = [
        'document_date',
        'saved_date',
        'spp_date',
        'spm_date',
        'sp2d_date',
        'transfer_date',
    ];

    private array $numericColumns = [
        'realization_value',
        'deposit_value',
        'spd_value',
        'sp2d_value',
    ];

    private array $headingsMap = [
        'skpd_code' => 'Kode SKPD',
        'skpd_name' => 'Nama SKPD',
        'sub_skpd_code' => 'Kode Sub SKPD',
        'sub_skpd_name' => 'Nama Sub SKPD',
        'function_code' => 'Kode Fungsi',
        'function_name' => 'Nama Fungsi',
        'sub_function_code' => 'Kode Sub Fungsi',
        'sub_function_name' => 'Nama Sub Fungsi',
        'affair_code' => 'Kode Urusan',
        'affair_name' => 'Nama Urusan',
        'field_affair_code' => 'Kode Bidang Urusan',
        'field_affair_name' => 'Nama Bidang Urusan',
        'program_code' => 'Kode Program',
        'program_name' => 'Nama Program',
        'activity_code' => 'Kode Kegiatan',
        'activity_name' => 'Nama Kegiatan',
        'sub_activity_code' => 'Kode Sub Kegiatan',
        'sub_activity_name' => 'Nama Sub Kegiatan',
        'account_code' => 'Kode Rekening',
        'account_name' => 'Nama Rekening',
        'document_number' => 'Nomor Dokumen',
        'document_type' => 'Jenis Dokumen',
        'transaction_type' => 'Jenis Transaksi',
        'dpt_number' => 'Nomor DPT',
        'document_date' => 'Tanggal Dokumen',
        'document_description' => 'Keterangan Dokumen',
        'realization_value' => 'Nilai Realisasi',
        'deposit_value' => 'Nilai Setoran',
        'nip' => 'NIP',
        'personnel_name' => 'Nama Pegawai',
        'saved_date' => 'Tanggal Simpan',
        'spd_number' => 'Nomor SPD',
        'spd_period' => 'Periode SPD',
        'spd_value' => 'Nilai SPD',
        'spd_stage' => 'Tahapan SPD',
        'sub_stage_name' => 'Nama Sub Tahapan Jadwal',
        'apbd_stage' => 'Tahapan APBD',
        'spp_number' => 'Nomor SPP',
        'spp_date' => 'Tanggal SPP',
        'spm_number' => 'Nomor SPM',
        'spm_date' => 'Tanggal SPM',
        'sp2d_number' => 'Nomor SP2D',
        'sp2d_date' => 'Tanggal SP2D',
        'transfer_date' => 'Tanggal Transfer',
        'sp2d_value' => 'Nilai SP2D',
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
            Schema::getColumnListing('ls_payments'),
            ['id', 'created_at', 'updated_at', 'created_by', 'updated_by']
        ));
    }

    public function query()
    {
        $selectColumns = array_values(array_intersect($this->exportOrder, $this->columns));

        $query = LsPayment::query()
            ->select($selectColumns)
            ->orderBy('created_at')
            ->orderBy('id');

        if (!empty($this->startDate) && !empty($this->endDate)) {
            $query->whereBetween('created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59',
            ]);
        } elseif (!empty($this->startDate)) {
            $query->where('created_at', '>=', $this->startDate . ' 00:00:00');
        } elseif (!empty($this->endDate)) {
            $query->where('created_at', '<=', $this->endDate . ' 23:59:59');
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

            if ($column === 'nip') {
                $mappedData[] = !empty($value)
                    ? "'" . ltrim((string) $value, "'")
                    : null;

                continue;
            }

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
