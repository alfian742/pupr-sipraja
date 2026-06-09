<?php

namespace App\Http\Controllers;

use App\Exports\LsPaymentExport;
use App\Http\Requests\LsPaymentRequest;
use App\Imports\LsPaymentImport;
use App\Jobs\MarkLsPaymentExportReady;
use App\Jobs\MarkLsPaymentImportReady;
use App\Models\LsPayment;
use App\Models\Realization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Yajra\DataTables\DataTables;

class LsPaymentController extends Controller
{
    private function getConfig(): array
    {
        return [
            'column_maps' => [
                ['field' => 'action', 'label' => 'Aksi', 'type' => 'special'],
                ['field' => 'history', 'label' => 'Riwayat', 'type' => 'special'],

                ['field' => 'skpd_code', 'label' => 'Kode SKPD', 'type' => 'text'],
                ['field' => 'skpd_name', 'label' => 'Nama SKPD', 'type' => 'text'],
                ['field' => 'sub_skpd_code', 'label' => 'Kode Sub SKPD', 'type' => 'text'],
                ['field' => 'sub_skpd_name', 'label' => 'Nama Sub SKPD', 'type' => 'text'],
                ['field' => 'function_code', 'label' => 'Kode Fungsi', 'type' => 'text'],
                ['field' => 'function_name', 'label' => 'Nama Fungsi', 'type' => 'text'],
                ['field' => 'sub_function_code', 'label' => 'Kode Sub Fungsi', 'type' => 'text'],
                ['field' => 'sub_function_name', 'label' => 'Nama Sub Fungsi', 'type' => 'text'],
                ['field' => 'affair_code', 'label' => 'Kode Urusan', 'type' => 'text'],
                ['field' => 'affair_name', 'label' => 'Nama Urusan', 'type' => 'text'],
                ['field' => 'field_affair_code', 'label' => 'Kode Bidang Urusan', 'type' => 'text'],
                ['field' => 'field_affair_name', 'label' => 'Nama Bidang Urusan', 'type' => 'text'],
                ['field' => 'program_code', 'label' => 'Kode Program', 'type' => 'text'],
                ['field' => 'program_name', 'label' => 'Nama Program', 'type' => 'text'],
                ['field' => 'activity_code', 'label' => 'Kode Kegiatan', 'type' => 'text'],
                ['field' => 'activity_name', 'label' => 'Nama Kegiatan', 'type' => 'text'],
                ['field' => 'sub_activity_code', 'label' => 'Kode Sub Kegiatan', 'type' => 'text'],
                ['field' => 'sub_activity_name', 'label' => 'Nama Sub Kegiatan', 'type' => 'text'],
                ['field' => 'account_code', 'label' => 'Kode Rekening', 'type' => 'text'],
                ['field' => 'account_name', 'label' => 'Nama Rekening', 'type' => 'text'],
                ['field' => 'document_number', 'label' => 'Nomor Dokumen', 'type' => 'text'],
                ['field' => 'document_type', 'label' => 'Jenis Dokumen', 'type' => 'text'],
                ['field' => 'transaction_type', 'label' => 'Jenis Transaksi', 'type' => 'text'],
                ['field' => 'dpt_number', 'label' => 'Nomor DPT', 'type' => 'text'],
                ['field' => 'document_date', 'label' => 'Tanggal Dokumen', 'type' => 'date'],
                ['field' => 'document_description', 'label' => 'Keterangan Dokumen', 'type' => 'text'],
                ['field' => 'realization_value', 'label' => 'Nilai Realisasi', 'type' => 'numeric'],
                ['field' => 'deposit_value', 'label' => 'Nilai Setoran', 'type' => 'numeric'],
                ['field' => 'nip', 'label' => 'NIP', 'type' => 'text'],
                ['field' => 'personnel_name', 'label' => 'Nama Pegawai', 'type' => 'text'],
                ['field' => 'saved_date', 'label' => 'Tanggal Simpan', 'type' => 'date'],
                ['field' => 'spd_number', 'label' => 'Nomor SPD', 'type' => 'text'],
                ['field' => 'spd_period', 'label' => 'Periode SPD', 'type' => 'text'],
                ['field' => 'spd_value', 'label' => 'Nilai SPD', 'type' => 'numeric'],
                ['field' => 'spd_stage', 'label' => 'Tahapan SPD', 'type' => 'text'],
                ['field' => 'sub_stage_name', 'label' => 'Nama Sub Tahapan Jadwal', 'type' => 'text'],
                ['field' => 'apbd_stage', 'label' => 'Tahapan APBD', 'type' => 'text'],
                ['field' => 'spp_number', 'label' => 'Nomor SPP', 'type' => 'text'],
                ['field' => 'spp_date', 'label' => 'Tanggal SPP', 'type' => 'date'],
                ['field' => 'spm_number', 'label' => 'Nomor SPM', 'type' => 'text'],
                ['field' => 'spm_date', 'label' => 'Tanggal SPM', 'type' => 'date'],
                ['field' => 'sp2d_number', 'label' => 'Nomor SP2D', 'type' => 'text'],
                ['field' => 'sp2d_date', 'label' => 'Tanggal SP2D', 'type' => 'date'],
                ['field' => 'transfer_date', 'label' => 'Tanggal Transfer', 'type' => 'date'],
                ['field' => 'sp2d_value', 'label' => 'Nilai SP2D', 'type' => 'numeric'],
            ],

            'months' => [
                'januari' => 'january',
                'februari' => 'february',
                'maret' => 'march',
                'april' => 'april',
                'mei' => 'may',
                'juni' => 'june',
                'juli' => 'july',
                'agustus' => 'august',
                'september' => 'september',
                'oktober' => 'october',
                'november' => 'november',
                'desember' => 'december',
            ],
        ];
    }

    private function getColumnLabels(array $columnMaps): array
    {
        return array_column($columnMaps, 'label');
    }

    private function getFieldsByType(array $columnMaps, string $type): array
    {
        return collect($columnMaps)
            ->where('type', $type)
            ->pluck('field')
            ->values()
            ->all();
    }

    private function formatRupiah($amount): string
    {
        $amount = $amount ?? 0;

        return 'Rp ' . number_format((float) $amount, 0, ',', '.');
    }

    public function index()
    {
        $config = $this->getConfig();

        $columnMaps = $config['column_maps'];
        $columns = $this->getColumnLabels($columnMaps);

        return view('dashboard.monev.finances.ls-payments.index', compact('columns', 'columnMaps'));
    }

    public function getData(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | CONFIG
        |--------------------------------------------------------------------------
        */

        $config = $this->getConfig();
        $columnMaps = $config['column_maps'];
        $months = $config['months'];

        $dateColumns = $this->getFieldsByType($columnMaps, 'date');
        $numericColumns = $this->getFieldsByType($columnMaps, 'numeric');
        $regularColumns = $this->getFieldsByType($columnMaps, 'text');

        /*
        |--------------------------------------------------------------------------
        | BASE QUERY
        |--------------------------------------------------------------------------
        */

        $datas = LsPayment::query()
            ->with([
                'creator:id,name',
                'updater:id,name',
            ])
            ->orderByDesc('ls_payments.created_at');

        $dataTable = DataTables::of($datas);

        /*
        |--------------------------------------------------------------------------
        | HELPERS
        |--------------------------------------------------------------------------
        */

        $normalizeText = function ($value): string {
            return mb_strtolower(trim((string) $value));
        };

        $parseDateKeyword = function ($keyword) use ($months) {
            $keyword = trim((string) $keyword);

            if ($keyword === '') {
                return null;
            }

            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $keyword)) {
                return $keyword;
            }

            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $keyword)) {
                try {
                    return Carbon::createFromFormat('d/m/Y', $keyword)->format('Y-m-d');
                } catch (\Throwable $e) {
                    return null;
                }
            }

            $keyword = strtr(mb_strtolower($keyword), $months);

            try {
                return Carbon::parse($keyword)->format('Y-m-d');
            } catch (\Throwable $e) {
                return null;
            }
        };

        $sanitizeNumericKeyword = function ($keyword) {
            $keyword = trim((string) $keyword);

            if ($keyword === '') {
                return null;
            }

            $clean = preg_replace('/[^\d,\.\-]/', '', $keyword);

            if ($clean === '' || $clean === null) {
                return null;
            }

            if (str_contains($clean, ',') && str_contains($clean, '.')) {
                $clean = str_replace('.', '', $clean);
                $clean = str_replace(',', '.', $clean);
            } elseif (str_contains($clean, ',')) {
                $clean = str_replace(',', '.', $clean);
            }

            return is_numeric($clean) ? $clean : null;
        };

        /*
        |--------------------------------------------------------------------------
        | FILTER DATE
        |--------------------------------------------------------------------------
        */

        foreach ($dateColumns as $column) {
            $dataTable->filterColumn($column, function ($query, $keyword) use ($column, $parseDateKeyword) {
                $parsedDate = $parseDateKeyword($keyword);

                if ($parsedDate) {
                    $query->whereDate($column, $parsedDate);
                    return;
                }

                $query->whereRaw("DATE_FORMAT(`{$column}`, '%Y-%m-%d') LIKE ?", ["%{$keyword}%"]);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER NUMERIC
        |--------------------------------------------------------------------------
        */

        foreach ($numericColumns as $column) {
            $dataTable->filterColumn($column, function ($query, $keyword) use ($column, $sanitizeNumericKeyword) {
                $clean = $sanitizeNumericKeyword($keyword);

                if ($clean !== null) {
                    $query->where($column, $clean);
                    return;
                }

                $query->whereRaw("CAST(`{$column}` AS CHAR) LIKE ?", ["%{$keyword}%"]);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER REGULAR TEXT
        |--------------------------------------------------------------------------
        */

        foreach ($regularColumns as $column) {
            $dataTable->filterColumn($column, function ($query, $keyword) use ($column, $normalizeText) {
                $keyword = $normalizeText($keyword);

                if ($keyword === '') {
                    return;
                }

                $query->whereRaw("LOWER(COALESCE(`{$column}`, '')) LIKE ?", ["%{$keyword}%"]);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | FORMAT TEXT COLUMN
        |--------------------------------------------------------------------------
        */

        foreach ($regularColumns as $column) {
            $dataTable->editColumn($column, function ($row) use ($column) {
                $value = $row->{$column};

                return '<div class="text-wrap-scroll">' . e($value ?: '-') . '</div>';
            });
        }

        /*
        |--------------------------------------------------------------------------
        | FORMAT NUMERIC COLUMN
        |--------------------------------------------------------------------------
        */

        foreach ($numericColumns as $column) {
            $dataTable->editColumn($column, function ($row) use ($column) {
                $value = $row->{$column};

                return '<div class="text-nowrap text-right">' . e($value !== null ? $this->formatRupiah($value) : '-') . '</div>';
            });
        }

        /*
        |--------------------------------------------------------------------------
        | FORMAT DATE COLUMN
        |--------------------------------------------------------------------------
        */

        foreach ($dateColumns as $column) {
            $dataTable->editColumn($column, function ($row) use ($column) {
                $value = $row->{$column}
                    ? Carbon::parse($row->{$column})->locale(app()->getLocale())->translatedFormat('d F Y')
                    : '-';

                return '<div class="text-nowrap">' . e($value) . '</div>';
            });
        }

        /*
        |--------------------------------------------------------------------------
        | HISTORY COLUMN
        |--------------------------------------------------------------------------
        */

        $dataTable->addColumn('history', function ($data) {
            $creator = optional($data->creator)->name ?? '-';
            $updater = optional($data->updater)->name ?? '-';

            $createdAt = $data->created_at
                ? Carbon::parse($data->created_at)->locale(app()->getLocale())->translatedFormat('d F Y H:i')
                : '-';

            $updatedAt = $data->updated_at
                ? Carbon::parse($data->updated_at)->locale(app()->getLocale())->translatedFormat('d F Y H:i')
                : '-';

            $updateSection = '';

            if ($data->updated_at && $data->created_at && $data->updated_at != $data->created_at) {
                $updateSection = '
                    <hr>
                    <small class="font-italic">Diperbarui pada: ' . e($updatedAt) . '<br>Oleh: ' . e($updater) . '</small>
                ';
            }

            return '
                <div class="text-nowrap">
                    <small class="font-italic">Ditambahkan pada: ' . e($createdAt) . '<br>Oleh: ' . e($creator) . '</small>
                    ' . $updateSection . '
                </div>
            ';
        });

        /*
        |--------------------------------------------------------------------------
        | ACTION COLUMN
        |--------------------------------------------------------------------------
        */
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $dataTable->addColumn('action', function ($data) {
                $editRoute = route('dashboard.monev.finances.ls-payments.edit', $data->id);

                return '
                <div class="d-flex align-items-center justify-content-between" style="gap: 1rem">
                    <a href="' . $editRoute . '" class="btn btn-sm btn-indigo" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <input type="checkbox" class="custom-form-check-input check-item"
                        aria-label="Pilih item untuk dihapus" title="Pilih Item" value="' . $data->id . '">
                </div>
            ';
            });
        } else {
            $dataTable->addColumn('action', function ($data) {
                $editRoute = route('dashboard.monev.finances.ls-payments.edit', $data->id);

                return '
                <div class="d-flex align-items-center justify-content-between" style="gap: 1rem">
                    <a href="' . $editRoute . '" class="btn btn-sm btn-indigo" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </a>
                </div>
            ';
            });
        }
        /*
        |--------------------------------------------------------------------------
        | RAW COLUMNS
        |--------------------------------------------------------------------------
        */

        $dataTable->rawColumns(array_merge(
            ['action', 'history'],
            $regularColumns,
            $numericColumns,
            $dateColumns
        ));

        return $dataTable->make(true);
    }

    public function create()
    {
        $config = $this->getConfig();

        $columnMaps = $config['column_maps'];
        $numericColumns = $this->getFieldsByType($columnMaps, 'numeric');

        return view('dashboard.monev.finances.ls-payments.create', compact('numericColumns'));
    }

    public function store(LsPaymentRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $userId = Auth::id();
            $validated['created_by'] = $userId;
            $validated['updated_by'] = $userId;

            LsPayment::create($validated);

            DB::commit();

            return redirect()
                ->route('dashboard.monev.finances.ls-payments.index')
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan data LS: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data gagal disimpan.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $data = LsPayment::findOrFail($id);

        $config = $this->getConfig();

        $columnMaps = $config['column_maps'];
        $numericColumns = $this->getFieldsByType($columnMaps, 'numeric');

        return view('dashboard.monev.finances.ls-payments.edit', compact('data', 'numericColumns'));
    }

    public function update(LsPaymentRequest $request, $id)
    {
        $data = LsPayment::findOrFail($id);

        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $userId = Auth::id();
            $validated['updated_by'] = $userId;

            $data->update($validated);

            DB::commit();

            return redirect()
                ->route('dashboard.monev.finances.ls-payments.index')
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui data LS: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data gagal diperbarui.')
                ->withInput();
        }
    }

    public function massAddToRealization(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || count($ids) === 0) {
            return redirect()
                ->back()
                ->with('error', 'Tidak ada data yang dipilih.');
        }

        DB::beginTransaction();

        try {

            // Ambil data sebagai collection
            $lsPayments = LsPayment::whereIn('id', $ids)->get();

            foreach ($lsPayments as $row) {

                // Optional: cegah duplikasi
                $exists = Realization::where('ls_payment_id', $row->id)->exists();

                if (!$exists) {
                    Realization::create([
                        'ls_payment_id' => $row->id,
                        // tambahkan field lain jika ada
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('dashboard.monev.finances.ls-payments.index')
                ->with('success', 'Data yang dipilih berhasil ditambahkan ke realisasi.');
        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error('Gagal menambahkan data LS ke realisasi: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data yang dipilih gagal ditambahkan ke realisasi.');
        }
    }

    public function massDestroy(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || count($ids) === 0) {
            return redirect()
                ->back()
                ->with('error', 'Tidak ada data yang dipilih.');
        }

        DB::beginTransaction();

        try {

            LsPayment::whereIn('id', $ids)->delete();

            DB::commit();

            return redirect()
                ->route('dashboard.monev.finances.ls-payments.index')
                ->with('success', 'Data yang dipilih berhasil dihapus.');
        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error('Gagal menghapus data LS: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data yang dipilih gagal dihapus.');
        }
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'export_start_date' => ['required', 'date'],
            'export_end_date' => ['required', 'date', 'after_or_equal:export_start_date'],
            'export_format' => ['required', 'string', 'in:xlsx,csv'],
        ]);

        $start = $validated['export_start_date'];
        $end = $validated['export_end_date'];
        $format = $validated['export_format'];

        $token = (string) Str::uuid();
        $datetime = now()->format('Ymd_His');

        $writerType = $format === 'csv'
            ? ExcelFormat::CSV
            : ExcelFormat::XLSX;

        $filename = "{$datetime}_LS_DPUPR.{$format}";
        $path = "exports/ls-payments/{$token}.{$format}";

        Cache::put("export_ls_payments_{$token}", [
            'status' => 'processing',
            'path' => $path,
            'filename' => $filename,
            'format' => $format,
        ], now()->addHours(2));

        (new LsPaymentExport($start, $end, $format))
            ->queue($path, 'local', $writerType)
            ->allOnQueue('exports')
            ->chain([
                new MarkLsPaymentExportReady($token, $path, $filename),
            ]);

        return response()->json([
            'message' => 'Export sedang diproses.',
            'token' => $token,
        ]);
    }

    public function checkExport(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
        ]);

        $data = Cache::get("export_ls_payments_{$validated['token']}");

        if (!$data) {
            return response()->json([
                'ready' => false,
                'status' => 'not_found',
            ]);
        }

        return response()->json([
            'ready' => $data['status'] === 'ready',
            'status' => $data['status'],
            'download_url' => $data['status'] === 'ready'
                ? route('dashboard.monev.finances.ls-payments.download-export', $validated['token'])
                : null,
        ]);
    }

    public function downloadExport(string $token)
    {
        $data = Cache::get("export_ls_payments_{$token}");

        abort_if(!$data || ($data['status'] ?? null) !== 'ready', 404);
        abort_if(!Storage::disk('local')->exists($data['path']), 404);

        Cache::forget("export_ls_payments_{$token}");

        return response()
            ->download(
                Storage::disk('local')->path($data['path']),
                $data['filename']
            )
            ->deleteFileAfterSend(true);
    }

    public function import(Request $request)
    {
        $request->validate(
            [
                'file' => ['required', 'file', 'max:10240'],
            ],
            [
                'file.required' => 'Berkas wajib diunggah.',
                'file.file' => 'Berkas yang diunggah tidak valid.',
                'file.max' => 'Ukuran berkas terlalu besar. Maksimum 10 MB.',
            ]
        );

        try {
            $file = $request->file('file');

            $extension = strtolower($file->getClientOriginalExtension());

            if (!in_array($extension, ['xlsx', 'csv'], true)) {
                return response()->json([
                    'message' => 'Format berkas tidak valid. Harap unggah berkas dengan format .xlsx atau .csv.',
                ], 422);
            }

            $userId = Auth::id();

            $token = (string) Str::uuid();
            $originalFilename = $file->getClientOriginalName();

            $storedFilename = now()->format('Ymd_His')
                . '_'
                . Str::slug(pathinfo($originalFilename, PATHINFO_FILENAME))
                . '.'
                . $extension;

            $path = $file->storeAs(
                "imports/ls-payments/{$token}",
                $storedFilename,
                'local'
            );

            Cache::put("import_ls_payments_{$token}", [
                'status' => 'processing',
                'filename' => $originalFilename,
            ], now()->addHours(2));

            $readerType = $extension === 'csv'
                ? ExcelFormat::CSV
                : ExcelFormat::XLSX;

            (new LsPaymentImport($userId))
                ->queue($path, 'local', $readerType)
                ->allOnQueue('imports')
                ->chain([
                    new MarkLsPaymentImportReady($token, $originalFilename, $path),
                ]);

            return response()->json([
                'message' => 'Import sedang diproses.',
                'token' => $token,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal memulai proses impor data.', [
                'error' => $e->getMessage(),
                'file' => $request->file('file')?->getClientOriginalName(),
            ]);

            return response()->json([
                'message' => 'Gagal memulai proses impor. Pastikan format dan struktur berkas sesuai templat yang ditetapkan.',
            ], 500);
        }
    }

    public function checkImport(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
        ]);

        $data = Cache::get("import_ls_payments_{$validated['token']}");

        if (!$data) {
            return response()->json([
                'ready' => false,
                'status' => 'not_found',
            ]);
        }

        return response()->json([
            'ready' => $data['status'] === 'ready',
            'status' => $data['status'],
            'filename' => $data['filename'] ?? null,
        ]);
    }

    public function downloadTemplate()
    {
        /**
         * Pastikan sudah menambahkan disk 'templates' di config/filesystems.php:
         * 
         * 'templates' => [
         *     'driver' => 'local',
         *     'root' => storage_path('app/templates'),
         * ],
         * 
         * Jalankan perintah berikut setelah menambahkan konfigurasi:
         * php artisan config:clear
         * 
         * File yang akan diunduh harus berada di:
         * storage/app/templates/documents/{filename}
         */

        $filename = 'TEMPLAT_LS_DPUPR.xlsx'; // Nama file

        $path = Storage::disk('templates')->path('documents/' . $filename);

        // Jika file tidak ditemukan, tampilkan error 404
        if (!file_exists($path)) {
            abort(404);
        }

        // Unduh file dengan nama aslinya
        return response()->download($path, $filename);
    }

    public function getList(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $data = LsPayment::query()
            ->select(
                'id',
                'spm_number',
                'account_code',
                'sub_activity_code',
                'document_description'
            )
            ->whereNotNull('spm_number')
            ->where('spm_number', '!=', '')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('spm_number', 'like', "%{$search}%")
                        ->orWhere('account_code', 'like', "%{$search}%")
                        ->orWhere('sub_activity_code', 'like', "%{$search}%")
                        ->orWhere('document_description', 'like', "%{$search}%");
                });
            })
            ->orderBy('spm_number')
            ->limit(20)
            ->get();

        return response()->json([
            'results' => $data->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->spm_number . ' | ' . $item->account_code . ' | ' . $item->sub_activity_code . ' | ' . $item->document_description,
                    'spm_number' => $item->spm_number,
                    'account_code' => $item->account_code,
                    'sub_activity_code' => $item->sub_activity_code,
                    'document_description' => $item->document_description,
                ];
            })->values(),
        ]);
    }
}
