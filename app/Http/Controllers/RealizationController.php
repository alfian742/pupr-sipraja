<?php

namespace App\Http\Controllers;

use App\Exports\RealizationExport;
use App\Http\Requests\RealizationRequest;
use App\Imports\RealizationImport;
use App\Jobs\MarkRealizationExportReady;
use App\Jobs\MarkRealizationImportReady;
use App\Models\Contract;
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

class RealizationController extends Controller
{
    private function getConfig(): array
    {
        return [
            'column_maps' => [
                ['field' => 'action', 'label' => 'Aksi', 'type' => 'special'],
                ['field' => 'history', 'label' => 'Riwayat', 'type' => 'special'],

                ['field' => 'verification_date', 'label' => 'Tanggal Verifikasi', 'type' => 'text'],
                ['field' => 'verified_by', 'label' => 'Verifikasi Oleh', 'type' => 'text'],

                ['field' => 'realization_contract_number', 'label' => 'Nomor Kontrak', 'type' => 'text'],
                ['field' => 'third_party_name', 'label' => 'Pihak III', 'type' => 'text'],
                ['field' => 'sub_activity_code', 'label' => 'Kode Sub Kegiatan', 'type' => 'text'],
                ['field' => 'account_code', 'label' => 'Kode Rekening', 'type' => 'text'],
                ['field' => 'activity_description', 'label' => 'Uraian Kegiatan', 'type' => 'text'],
                ['field' => 'department', 'label' => 'Bidang', 'type' => 'text'],

                ['field' => 'realization_spm_number', 'label' => 'Nomor SPM', 'type' => 'text'],
                ['field' => 'sp2d_date', 'label' => 'Tanggal SP2D', 'type' => 'text'],
                ['field' => 'sp2d_number', 'label' => 'Nomor SP2D', 'type' => 'text'],
                ['field' => 'document_description', 'label' => 'Uraian Pekerjaan', 'type' => 'text'],
                ['field' => 'sp2d_value', 'label' => 'Realisasi', 'type' => 'numeric'],

                ['field' => 'match_status', 'label' => 'Status Kecocokan', 'type' => 'text'],
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

        return view('dashboard.monev.finances.realizations.index', compact('columns', 'columnMaps'));
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

        $datas = Realization::query()
            ->leftJoin(
                'contracts',
                'contracts.id',
                '=',
                'realizations.contract_id'
            )
            ->leftJoin(
                'ls_payments',
                'ls_payments.id',
                '=',
                'realizations.ls_payment_id'
            )
            ->select([
                'realizations.*',

                'contracts.third_party_name as third_party_name',
                'contracts.sub_activity_code',
                'contracts.account_code',
                'contracts.activity_description as activity_description',
                'contracts.department as department',

                'ls_payments.sp2d_date as sp2d_date',
                'ls_payments.sp2d_number as sp2d_number',
                'ls_payments.document_description as document_description',
                'ls_payments.sp2d_value as sp2d_value',
            ])
            ->with([
                'verifier:id,name',
                'creator:id,name',
                'updater:id,name',
            ])
            ->orderByDesc('realizations.created_at');

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

        $dataTable->editColumn('verified_by', function ($data) {
            return optional($data->verifier)->name ?? '-';
        });

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
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin' || Auth::user()->role === 'head_of_department') {
            $dataTable->addColumn('action', function ($data) {
                $editRoute = route('dashboard.monev.finances.realizations.edit', $data->id);

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
                $editRoute = route('dashboard.monev.finances.realizations.edit', $data->id);

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
        $selectedContract = null;
        $selectedLsPayment = null;

        if (old('contract_id')) {
            $selectedContract = Contract::select(
                'id',
                'contract_number',
                'account_code',
                'sub_activity_code',
                'activity_description'
            )->find(old('contract_id'));
        }

        if (old('ls_payment_id')) {
            $selectedLsPayment = LsPayment::select(
                'id',
                'spm_number',
                'account_code',
                'sub_activity_code',
                'document_description'
            )->find(old('ls_payment_id'));
        }

        return view('dashboard.monev.finances.realizations.create', compact(
            'selectedContract',
            'selectedLsPayment'
        ));
    }

    public function store(RealizationRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $userId = Auth::id();
            $validated['created_by'] = $userId;
            $validated['updated_by'] = $userId;

            Realization::create($validated);

            DB::commit();

            return redirect()
                ->route('dashboard.monev.finances.realizations.index')
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan data Realisasi: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data gagal disimpan.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $data = Realization::with(['contract', 'lsPayment'])->findOrFail($id);

        $selectedContract = null;
        $selectedLsPayment = null;

        $selectedContractId = old('contract_id', $data->contract_id);
        $selectedLsPaymentId = old('ls_payment_id', $data->ls_payment_id);

        if ($selectedContractId) {
            $selectedContract = Contract::select(
                'id',
                'contract_number',
                'account_code',
                'sub_activity_code',
                'activity_description'
            )->find($selectedContractId);
        }

        if ($selectedLsPaymentId) {
            $selectedLsPayment = LsPayment::select(
                'id',
                'spm_number',
                'account_code',
                'sub_activity_code',
                'document_description'
            )->find($selectedLsPaymentId);
        }

        return view('dashboard.monev.finances.realizations.edit', compact(
            'data',
            'selectedContract',
            'selectedLsPayment'
        ));
    }

    public function update(RealizationRequest $request, $id)
    {
        $data = Realization::findOrFail($id);

        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $userId = Auth::id();
            $validated['updated_by'] = $userId;

            $data->update($validated);

            DB::commit();

            return redirect()
                ->route('dashboard.monev.finances.realizations.index')
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui data Realisasi: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data gagal diperbarui.')
                ->withInput();
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

            Realization::whereIn('id', $ids)->delete();

            DB::commit();

            return redirect()
                ->route('dashboard.monev.finances.realizations.index')
                ->with('success', 'Data yang dipilih berhasil dihapus.');
        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error('Gagal menghapus data Realisasi: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data yang dipilih gagal dihapus.');
        }
    }

    public function massVerification(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || count($ids) === 0) {
            return redirect()
                ->back()
                ->with('error', 'Tidak ada data yang dipilih.');
        }

        DB::beginTransaction();

        try {

            // Realization::whereIn('id', $ids)->update([
            //     'verification_date' => now(),
            //     'verified_by'   => Auth::id(),
            // ]);

            Realization::whereIn('id', $ids)->update([
                'verification_date' => Carbon::now()->locale(app()->getLocale())->translatedFormat('d F Y'),
                'verified_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()
                ->route('dashboard.monev.finances.realizations.index')
                ->with('success', 'Data yang dipilih berhasil diverifikasi.');
        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error('Gagal memverifikasi data Realisasi: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data yang dipilih gagal diverifikasi.');
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

        $filename = "{$datetime}_REALISASI_DPUPR.{$format}";
        $path = "exports/realizations/{$token}.{$format}";

        Cache::put("export_realizations_{$token}", [
            'status' => 'processing',
            'path' => $path,
            'filename' => $filename,
            'format' => $format,
        ], now()->addHours(2));

        (new RealizationExport($start, $end, $format))
            ->queue($path, 'local', $writerType)
            ->allOnQueue('exports')
            ->chain([
                new MarkRealizationExportReady($token, $path, $filename),
            ]);

        return response()->json([
            'message' => 'Ekspor sedang diproses.',
            'token' => $token,
        ]);
    }

    public function checkExport(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
        ]);

        $data = Cache::get("export_realizations_{$validated['token']}");

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
                ? route('dashboard.monev.finances.realizations.download-export', $validated['token'])
                : null,
        ]);
    }

    public function downloadExport(string $token)
    {
        $data = Cache::get("export_realizations_{$token}");

        abort_if(!$data || ($data['status'] ?? null) !== 'ready', 404);
        abort_if(!Storage::disk('local')->exists($data['path']), 404);

        Cache::forget("export_realizations_{$token}");

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
                "imports/realizations/{$token}",
                $storedFilename,
                'local'
            );

            Cache::put("import_realizations_{$token}", [
                'status' => 'processing',
                'filename' => $originalFilename,
            ], now()->addHours(2));

            $readerType = $extension === 'csv'
                ? ExcelFormat::CSV
                : ExcelFormat::XLSX;

            (new RealizationImport($userId))
                ->queue($path, 'local', $readerType)
                ->allOnQueue('imports')
                ->chain([
                    new MarkRealizationImportReady($token, $originalFilename, $path),
                ]);

            return response()->json([
                'message' => 'Impor sedang diproses.',
                'token' => $token,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal memulai proses impor realisasi.', [
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

        $data = Cache::get("import_realizations_{$validated['token']}");

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

        $filename = 'TEMPLAT_REALISASI_DPUPR.xlsx'; // Nama file

        $path = Storage::disk('templates')->path('documents/' . $filename);

        // Jika file tidak ditemukan, tampilkan error 404
        if (!file_exists($path)) {
            abort(404);
        }

        // Unduh file dengan nama aslinya
        return response()->download($path, $filename);
    }


    // ======================================================
    // RESOLVE REFERENCE NUMBERS
    // ======================================================

    private function normalizeReferenceNumber($value): string
    {
        return mb_strtoupper(
            preg_replace('/\s+/u', '', trim((string) $value))
        );
    }

    public function massResolveReference(Request $request)
    {
        $validated = $request->validate(
            [
                'ids' => ['required', 'array', 'min:1'],
                'ids.*' => ['required', 'integer', 'exists:realizations,id'],
            ],
            [
                'ids.required' => 'Tidak ada data yang dipilih.',
                'ids.array' => 'Data yang dipilih tidak valid.',
                'ids.min' => 'Silakan pilih minimal satu data.',
                'ids.*.exists' => 'Salah satu data Realisasi tidak ditemukan.',
            ]
        );

        DB::beginTransaction();

        try {
            /*
        |--------------------------------------------------------------------------
        | AMBIL REALISASI YANG DIPILIH
        |--------------------------------------------------------------------------
        */

            $realizations = Realization::query()
                ->whereIn('id', $validated['ids'])
                ->lockForUpdate()
                ->get([
                    'id',
                    'contract_id',
                    'ls_payment_id',
                    'realization_contract_number',
                    'realization_spm_number',
                ]);

            /*
        |--------------------------------------------------------------------------
        | KUMPULKAN NOMOR KONTRAK
        |--------------------------------------------------------------------------
        */

            $contractNumbers = $realizations
                ->pluck('realization_contract_number')
                ->map(fn($value) => $this->normalizeReferenceNumber($value))
                ->filter()
                ->unique()
                ->values();

            /*
        |--------------------------------------------------------------------------
        | KUMPULKAN NOMOR SPM
        |--------------------------------------------------------------------------
        */

            $spmNumbers = $realizations
                ->pluck('realization_spm_number')
                ->map(fn($value) => $this->normalizeReferenceNumber($value))
                ->filter()
                ->unique()
                ->values();

            /*
        |--------------------------------------------------------------------------
        | AMBIL DATA KONTRAK
        |--------------------------------------------------------------------------
        */

            $contracts = collect();

            if ($contractNumbers->isNotEmpty()) {
                $contracts = Contract::query()
                    ->select([
                        'id',
                        'contract_number',
                        'account_code',
                        'sub_activity_code',
                    ])
                    ->where(function ($query) use ($contractNumbers) {
                        foreach ($contractNumbers as $contractNumber) {
                            $query->orWhereRaw(
                                "UPPER(REPLACE(TRIM(contract_number), ' ', '')) = ?",
                                [$contractNumber]
                            );
                        }
                    })
                    ->get()
                    ->groupBy(function ($contract) {
                        return $this->normalizeReferenceNumber(
                            $contract->contract_number
                        );
                    });
            }

            /*
        |--------------------------------------------------------------------------
        | AMBIL DATA LS PAYMENT
        |--------------------------------------------------------------------------
        */

            $lsPayments = collect();

            if ($spmNumbers->isNotEmpty()) {
                $lsPayments = LsPayment::query()
                    ->select([
                        'id',
                        'spm_number',
                        'account_code',
                        'sub_activity_code',
                    ])
                    ->where(function ($query) use ($spmNumbers) {
                        foreach ($spmNumbers as $spmNumber) {
                            $query->orWhereRaw(
                                "UPPER(REPLACE(TRIM(spm_number), ' ', '')) = ?",
                                [$spmNumber]
                            );
                        }
                    })
                    ->get()
                    ->groupBy(function ($lsPayment) {
                        return $this->normalizeReferenceNumber(
                            $lsPayment->spm_number
                        );
                    });
            }

            /*
        |--------------------------------------------------------------------------
        | PROSES PEMETAAN
        |--------------------------------------------------------------------------
        */

            $updatedCount = 0;
            $notFoundCount = 0;
            $ambiguousCount = 0;
            $duplicateCount = 0;

            foreach ($realizations as $realization) {
                $contractKey = $this->normalizeReferenceNumber(
                    $realization->realization_contract_number
                );

                $spmKey = $this->normalizeReferenceNumber(
                    $realization->realization_spm_number
                );

                $contractCandidates = $contractKey !== ''
                    ? $contracts->get($contractKey, collect())
                    : collect();

                $lsPaymentCandidates = $spmKey !== ''
                    ? $lsPayments->get($spmKey, collect())
                    : collect();

                /*
            |--------------------------------------------------------------------------
            | NOMOR DUPLIKAT PADA MASTER DATA
            |--------------------------------------------------------------------------
            | Jika satu nomor kontrak atau satu nomor SPM ditemukan lebih dari sekali,
            | sistem tidak boleh memilih ID secara sembarang.
            |--------------------------------------------------------------------------
            */

                if (
                    $contractCandidates->count() > 1 ||
                    $lsPaymentCandidates->count() > 1
                ) {
                    $ambiguousCount++;
                    continue;
                }

                $contract = $contractCandidates->first();
                $lsPayment = $lsPaymentCandidates->first();

                /*
            |--------------------------------------------------------------------------
            | KONTRAK ATAU LS TIDAK DITEMUKAN
            |--------------------------------------------------------------------------
            */

                if (!$contract || !$lsPayment) {
                    $notFoundCount++;
                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | CEGAH PASANGAN RELASI DUPLIKAT
            |--------------------------------------------------------------------------
            */

                $pairAlreadyExists = Realization::query()
                    ->where('id', '!=', $realization->id)
                    ->where('contract_id', $contract->id)
                    ->where('ls_payment_id', $lsPayment->id)
                    ->exists();

                if ($pairAlreadyExists) {
                    $duplicateCount++;
                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | HITUNG STATUS KECOCOKAN
            |--------------------------------------------------------------------------
            */

                $contractAccountCode = $this->normalizeReferenceNumber(
                    $contract->account_code
                );

                $lsAccountCode = $this->normalizeReferenceNumber(
                    $lsPayment->account_code
                );

                $contractSubActivityCode = $this->normalizeReferenceNumber(
                    $contract->sub_activity_code
                );

                $lsSubActivityCode = $this->normalizeReferenceNumber(
                    $lsPayment->sub_activity_code
                );

                $accountCodeMatch =
                    $contractAccountCode !== '' &&
                    $contractAccountCode === $lsAccountCode;

                $subActivityCodeMatch =
                    $contractSubActivityCode !== '' &&
                    $contractSubActivityCode === $lsSubActivityCode;

                $matchStatus =
                    $accountCodeMatch && $subActivityCodeMatch
                    ? 'SAMA'
                    : 'BEDA';

                /*
            |--------------------------------------------------------------------------
            | SIMPAN ID HASIL PENCARIAN
            |--------------------------------------------------------------------------
            */

                $realization->update([
                    'contract_id' => $contract->id,
                    'ls_payment_id' => $lsPayment->id,
                    'match_status' => $matchStatus,
                    'updated_by' => Auth::id(),
                ]);

                $updatedCount++;
            }

            DB::commit();

            $messages = [
                "{$updatedCount} data berhasil dihubungkan.",
            ];

            if ($notFoundCount > 0) {
                $messages[] =
                    "{$notFoundCount} data tidak menemukan nomor kontrak atau nomor SPM.";
            }

            if ($ambiguousCount > 0) {
                $messages[] =
                    "{$ambiguousCount} data memiliki nomor master yang duplikat.";
            }

            if ($duplicateCount > 0) {
                $messages[] =
                    "{$duplicateCount} data dilewati karena pasangan relasi sudah digunakan.";
            }

            return redirect()
                ->route('dashboard.monev.finances.realizations.index')
                ->with('success', implode(' ', $messages));
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal menghubungkan data Realisasi.', [
                'ids' => $validated['ids'],
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan, data Realisasi gagal dihubungkan.'
                );
        }
    }
}
