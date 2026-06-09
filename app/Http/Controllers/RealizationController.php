<?php

namespace App\Http\Controllers;

use App\Exports\RealizationExport;
use App\Http\Requests\RealizationRequest;
use App\Jobs\MarkRealizationExportReady;
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

                ['field' => 'verification_date', 'label' => 'Tanggal Verifikasi', 'type' => 'date'],
                ['field' => 'verified_by', 'label' => 'Verifikasi Oleh', 'type' => 'text'],

                // Dipanggil via contract_id
                ['field' => 'contract_number', 'label' => 'Nomor Kontrak', 'type' => 'text'],
                ['field' => 'third_party_name', 'label' => 'Pihak III', 'type' => 'text'],
                ['field' => 'activity_code', 'label' => 'Kode Kegiatan', 'type' => 'text'],
                ['field' => 'sub_account_code', 'label' => 'Sub Rek', 'type' => 'text'],
                ['field' => 'activity_description', 'label' => 'Uraian Kegiatan', 'type' => 'text'],
                ['field' => 'department', 'label' => 'Bidang', 'type' => 'text'],

                // Dipanggil via ls_payment_id
                ['field' => 'spm_number', 'label' => 'Nomor SPM', 'type' => 'text'],
                ['field' => 'sp2d_date', 'label' => 'Tanggal SP2D', 'type' => 'date'],
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
            ->select([
                'realizations.*',

                'contracts.contract_number',
                'contracts.third_party_name',
                'contracts.activity_code',
                'contracts.sub_account_code',
                'contracts.activity_description',
                'contracts.department',

                'ls_payments.spm_number',
                'ls_payments.sp2d_date',
                'ls_payments.sp2d_number',
                'ls_payments.document_description',
                'ls_payments.sp2d_value',
            ])

            ->leftJoin('contracts', 'contracts.id', '=', 'realizations.contract_id')
            ->leftJoin('ls_payments', 'ls_payments.id', '=', 'realizations.ls_payment_id')

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
                'sub_account_code',
                'activity_code',
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
                'sub_account_code',
                'activity_code',
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

            Realization::whereIn('id', $ids)->update([
                'verification_date' => now(),
                'verified_by'   => Auth::id(),
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
            'message' => 'Export sedang diproses.',
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
}
