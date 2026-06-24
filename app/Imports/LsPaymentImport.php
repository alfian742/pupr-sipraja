<?php

namespace App\Imports;

use App\Models\LsPayment;
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

class LsPaymentImport implements
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
    protected $userId;

    protected array $headingMap = [
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

    protected array $dateColumns = [
        'document_date',
        'saved_date',
        'spp_date',
        'spm_date',
        'sp2d_date',
        'transfer_date',
    ];

    protected array $numericColumns = [
        'realization_value',
        'deposit_value',
        'spd_value',
        'sp2d_value',
    ];

    public function __construct($userId = null)
    {
        $this->userId = $userId;

        $this->columns = array_values(array_diff(
            Schema::getColumnListing('ls_payments'),
            ['id', 'created_at', 'updated_at']
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

            if ($dbColumn === 'nip' && $value !== null) {
                $value = $this->sanitizeNip($value);
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

        return new LsPayment($data);
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
        return 1000;
    }

    public function batchSize(): int
    {
        return 500;
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
        // Antisipasi BOM UTF-8 dari file CSV
        $key = preg_replace('/^\xEF\xBB\xBF/', '', $key);

        // Antisipasi non-breaking space dari Excel
        $key = str_replace("\xC2\xA0", ' ', $key);

        $key = trim($key);

        return strtolower(Str::slug(str_replace(' ', '_', $key), '_'));
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
                return Carbon::instance(ExcelDate::excelToDateTimeObject($value))->format('Y-m-d');
            }

            $value = trim((string) $value);

            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                return Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d');
            }

            $normalized = str_replace(['.', '-', ' '], '/', $value);

            return Carbon::createFromFormat('d/m/Y', $normalized)->format('Y-m-d');
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

    private function sanitizeNip($value): ?string
    {
        $value = trim((string) $value);

        // Buang leading apostrophe dari hasil export CSV/XLSX
        $value = ltrim($value, "'");

        // Hilangkan spasi
        $value = preg_replace('/\s+/', '', $value);

        return $value !== '' ? $value : null;
    }
}
