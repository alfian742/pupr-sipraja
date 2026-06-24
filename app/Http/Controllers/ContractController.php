<?php

namespace App\Http\Controllers;

use App\Exports\ContractExport;
use App\Http\Requests\ContractRequest;
use App\Imports\ContractImport;
use App\Jobs\MarkContractExportReady;
use App\Jobs\MarkContractImportReady;
use App\Models\Contract;
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

class ContractController extends Controller
{
    private function getConfig(): array
    {
        return [
            'column_maps' => [
                ['field' => 'action', 'label' => 'Aksi', 'type' => 'special'],
                ['field' => 'history', 'label' => 'Riwayat', 'type' => 'special'],

                ['field' => 'contract_start_date', 'label' => 'Tanggal Mulai', 'type' => 'date'],
                ['field' => 'contract_end_date', 'label' => 'Tanggal Berakhir', 'type' => 'date'],
                ['field' => 'contract_number', 'label' => 'Nomor Kontrak', 'type' => 'text'],
                ['field' => 'third_party_name', 'label' => 'Pihak III', 'type' => 'text'],
                ['field' => 'activity_code', 'label' => 'Kode Kegiatan', 'type' => 'text'],
                ['field' => 'sub_account_code', 'label' => 'Sub Rek', 'type' => 'text'],
                ['field' => 'activity_description', 'label' => 'Uraian Kegiatan', 'type' => 'text'],
                ['field' => 'department', 'label' => 'Bidang', 'type' => 'text'],
                ['field' => 'budget_value', 'label' => 'Anggaran', 'type' => 'numeric'],
                ['field' => 'contract_value', 'label' => 'Nilai Kontrak', 'type' => 'numeric'],
                ['field' => 'sp2d_value', 'label' => 'Realisasi', 'type' => 'numeric'],
                ['field' => 'balance_value', 'label' => 'Saldo', 'type' => 'numeric'],
                ['field' => 'fund_source', 'label' => 'Sumber Dana', 'type' => 'text'],
                ['field' => 'bast_number', 'label' => 'Nomor BAST', 'type' => 'text'],
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

        return view('dashboard.monev.finances.contracts.index', compact('columns', 'columnMaps'));
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
        | SUBQUERY REALISASI LS
        |--------------------------------------------------------------------------
        | Ambil total SP2D dari LS Payment yang benar-benar terhubung ke realisasi
        | dan hanya yang sudah terverifikasi.
        |--------------------------------------------------------------------------
        */

        $realizationSubquery = DB::table('realizations')
            ->leftJoin('ls_payments', 'ls_payments.id', '=', 'realizations.ls_payment_id')
            ->select([
                'realizations.contract_id',
                DB::raw('COALESCE(SUM(ls_payments.sp2d_value), 0) as realized_sp2d_value'),
            ])
            ->whereNotNull('realizations.verification_date')
            ->groupBy('realizations.contract_id');

        /*
        |--------------------------------------------------------------------------
        | BASE QUERY
        |--------------------------------------------------------------------------
        */

        $datas = Contract::query()
            ->select([
                'contracts.*',
                DB::raw('COALESCE(realization_totals.realized_sp2d_value, 0) as sp2d_value'),
                DB::raw('(COALESCE(contracts.contract_value, 0) - COALESCE(realization_totals.realized_sp2d_value, 0)) as balance_value'),
            ])
            ->leftJoinSub($realizationSubquery, 'realization_totals', function ($join) {
                $join->on('contracts.id', '=', 'realization_totals.contract_id');
            })
            ->with([
                'creator:id,name',
                'updater:id,name',
            ])
            ->orderByDesc('contracts.created_at');

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
                    $query->whereDate("contracts.{$column}", $parsedDate);
                    return;
                }

                $query->whereRaw("DATE_FORMAT(contracts.`{$column}`, '%Y-%m-%d') LIKE ?", ["%{$keyword}%"]);
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

                // mapping kolom numeric yang berupa alias / hasil perhitungan
                $numericExpression = match ($column) {
                    'sp2d_value'    => 'COALESCE(realization_totals.realized_sp2d_value, 0)',
                    'balance_value' => '(COALESCE(contracts.contract_value, 0) - COALESCE(realization_totals.realized_sp2d_value, 0))',
                    default         => "contracts.`{$column}`",
                };

                if ($clean !== null) {
                    $query->whereRaw("{$numericExpression} = ?", [$clean]);
                    return;
                }

                $query->whereRaw("CAST({$numericExpression} AS CHAR) LIKE ?", ["%{$keyword}%"]);
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

                $query->whereRaw("LOWER(COALESCE(contracts.`{$column}`, '')) LIKE ?", ["%{$keyword}%"]);
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

                return '<div class="text-nowrap text-right">' . e($this->formatRupiah($value ?? 0)) . '</div>';
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
                $showRoute = route('dashboard.monev.finances.contracts.show', $data->id);
                $editRoute = route('dashboard.monev.finances.contracts.edit', $data->id);

                return '
                    <div class="d-flex align-items-center justify-content-between" style="gap: 1rem">
                        <a href="' . $showRoute . '" class="btn btn-sm btn-info" title="Detail">
                            <i class="fa fa-info-circle"></i>
                        </a>
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
                $showRoute = route('dashboard.monev.finances.contracts.show', $data->id);
                $editRoute = route('dashboard.monev.finances.contracts.edit', $data->id);

                return '
                    <div class="d-flex align-items-center justify-content-between" style="gap: 1rem">
                        <a href="' . $showRoute . '" class="btn btn-sm btn-info" title="Detail">
                            <i class="fa fa-info-circle"></i>
                        </a>
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

    public function getDataChart(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | SUBQUERY REALISASI PER KONTRAK
        |--------------------------------------------------------------------------
        */

        $realizationSubquery = DB::table('realizations')
            ->leftJoin('ls_payments', 'ls_payments.id', '=', 'realizations.ls_payment_id')
            ->select([
                'realizations.contract_id',
                DB::raw('COALESCE(SUM(COALESCE(ls_payments.sp2d_value, 0)), 0) as total_realization'),
            ])
            ->groupBy('realizations.contract_id');

        /*
        |--------------------------------------------------------------------------
        | REKAP BIDANG
        |--------------------------------------------------------------------------
        */

        $rows = Contract::query()
            ->select([
                DB::raw('COALESCE(contracts.department, "") as department'),
                DB::raw('COALESCE(SUM(COALESCE(contracts.budget_value, 0)), 0) as total_budget_value'),
                DB::raw('COALESCE(SUM(COALESCE(contracts.contract_value, 0)), 0) as total_contract_value'),
                DB::raw('COALESCE(SUM(COALESCE(realization_totals.total_realization, 0)), 0) as total_realization'),
                DB::raw('(COALESCE(SUM(COALESCE(contracts.contract_value, 0)), 0) - COALESCE(SUM(COALESCE(realization_totals.total_realization, 0)), 0)) as total_balance'),
            ])
            ->leftJoinSub($realizationSubquery, 'realization_totals', function ($join) {
                $join->on('contracts.id', '=', 'realization_totals.contract_id');
            })
            ->groupBy('contracts.department')
            ->orderBy('contracts.department')
            ->get()
            ->map(function ($item) {
                $budgetValue = (float) ($item->total_budget_value ?? 0);
                $contractValue = (float) ($item->total_contract_value ?? 0);
                $totalRealization = (float) ($item->total_realization ?? 0);
                $totalBalance = (float) ($item->total_balance ?? 0);

                $percentage = $contractValue > 0
                    ? ($totalRealization / $contractValue) * 100
                    : 0;

                return [
                    'department' => $item->department,
                    'department_label' => $item->department !== '' ? $item->department : 'Tanpa Bidang',
                    'total_budget_value' => $budgetValue,
                    'total_contract_value' => $contractValue,
                    'total_realization' => $totalRealization,
                    'total_balance' => $totalBalance,
                    'percentage' => $percentage,
                    'total_budget_value_formatted' => $this->formatRupiah($budgetValue),
                    'total_contract_value_formatted' => $this->formatRupiah($contractValue),
                    'total_realization_formatted' => $this->formatRupiah($totalRealization),
                    'total_balance_formatted' => $this->formatRupiah($totalBalance),
                    'percentage_formatted' => number_format($percentage, 0, ',', '.') . '%',
                ];
            })
            ->values();

        $totalBudgetValue = (float) $rows->sum('total_budget_value');
        $totalContractValue = (float) $rows->sum('total_contract_value');
        $totalRealization = (float) $rows->sum('total_realization');
        $totalBalance = (float) $rows->sum('total_balance');

        $totalPercentage = $totalContractValue > 0
            ? ($totalRealization / $totalContractValue) * 100
            : 0;

        $summary = [
            'department_label' => 'Semua Bidang',
            'total_budget_value' => $totalBudgetValue,
            'total_contract_value' => $totalContractValue,
            'total_realization' => $totalRealization,
            'total_balance' => $totalBalance,
            'percentage' => $totalPercentage,
            'total_budget_value_formatted' => $this->formatRupiah($totalBudgetValue),
            'total_contract_value_formatted' => $this->formatRupiah($totalContractValue),
            'total_realization_formatted' => $this->formatRupiah($totalRealization),
            'total_balance_formatted' => $this->formatRupiah($totalBalance),
            'percentage_formatted' => number_format($totalPercentage, 0, ',', '.') . '%',
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => $summary,
                'departments' => $rows,
            ],
        ]);
    }

    public function create()
    {
        $config = $this->getConfig();

        $columnMaps = $config['column_maps'];
        $numericColumns = $this->getFieldsByType($columnMaps, 'numeric');

        return view('dashboard.monev.finances.contracts.create', compact('numericColumns'));
    }

    public function store(ContractRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $userId = Auth::id();
            $validated['created_by'] = $userId;
            $validated['updated_by'] = $userId;

            Contract::create($validated);

            DB::commit();

            return redirect()
                ->route('dashboard.monev.finances.contracts.index')
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan data Kontrak: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data gagal disimpan.')
                ->withInput();
        }
    }

    public function show($id)
    {
        $data = Contract::query()
            ->with([
                'realizations' => function ($query) {
                    $query->select([
                        'id',
                        'contract_id',
                        'ls_payment_id',
                        'verification_date',
                        'match_status',
                    ])->with([
                        'lsPayment:id,sp2d_date,sp2d_number,document_description,sp2d_value',
                    ]);
                },
            ])
            ->findOrFail($id);

        $realizations = $data->realizations
            ->filter(function ($item) {
                return !is_null($item->lsPayment);
            })
            ->sortBy(function ($item) {
                return [
                    $item->lsPayment->sp2d_date ?? '',
                    $item->lsPayment->sp2d_number ?? '',
                ];
            })
            ->values()
            ->map(function ($item) use ($data) {
                return (object) [
                    'sp2d_date' => $item->lsPayment->sp2d_date
                        ? Carbon::parse($item->lsPayment->sp2d_date)
                        ->locale(app()->getLocale())
                        ->translatedFormat('d F Y')
                        : '-',
                    'contract_number' => $data->contract_number ?? '-',
                    'sp2d_number' => $item->lsPayment->sp2d_number ?? '-',
                    'document_description' => $item->lsPayment->document_description ?? '-',
                    'sp2d_value' => $item->lsPayment->sp2d_value ?? 0,
                    'sp2d_value_formatted' => $this->formatRupiah($item->lsPayment->sp2d_value ?? 0),
                ];
            });

        $contractValue = (float) ($data->contract_value ?? 0);
        $totalRealization = (float) $realizations->sum('sp2d_value');
        $balanceValue = $contractValue - $totalRealization;
        $retentionValue = $contractValue * 0.05;
        $realizationPercentage = $contractValue > 0
            ? ($totalRealization / $contractValue) * 100
            : 0;

        $data->contract_value_formatted = $this->formatRupiah($contractValue);
        $data->balance_value_formatted = $this->formatRupiah($balanceValue);
        $data->retention_value_formatted = $this->formatRupiah($retentionValue);
        $data->total_realization_formatted = $this->formatRupiah($totalRealization);
        $data->realization_percentage = number_format($realizationPercentage, 2, ',', '.');

        return view('dashboard.monev.finances.contracts.show', compact('data', 'realizations'));
    }

    public function edit($id)
    {
        $data = Contract::findOrFail($id);

        $config = $this->getConfig();

        $columnMaps = $config['column_maps'];
        $numericColumns = $this->getFieldsByType($columnMaps, 'numeric');

        return view('dashboard.monev.finances.contracts.edit', compact('data', 'numericColumns'));
    }

    public function update(ContractRequest $request, $id)
    {
        $data = Contract::findOrFail($id);

        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $userId = Auth::id();
            $validated['updated_by'] = $userId;

            $data->update($validated);

            DB::commit();

            return redirect()
                ->route('dashboard.monev.finances.contracts.index')
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui data Kontrak: ' . $e->getMessage());

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

            Contract::whereIn('id', $ids)->delete();

            DB::commit();

            return redirect()
                ->route('dashboard.monev.finances.contracts.index')
                ->with('success', 'Data yang dipilih berhasil dihapus.');
        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error('Gagal menghapus data Kontrak: ' . $e->getMessage());

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

        $filename = "{$datetime}_KONTRAK_DPUPR.{$format}";
        $path = "exports/contracts/{$token}.{$format}";

        Cache::put("export_contracts_{$token}", [
            'status' => 'processing',
            'path' => $path,
            'filename' => $filename,
            'format' => $format,
        ], now()->addHours(2));

        (new ContractExport($start, $end, $format))
            ->queue($path, 'local', $writerType)
            ->allOnQueue('exports')
            ->chain([
                new MarkContractExportReady($token, $path, $filename),
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

        $data = Cache::get("export_contracts_{$validated['token']}");

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
                ? route('dashboard.monev.finances.contracts.download-export', $validated['token'])
                : null,
        ]);
    }

    public function downloadExport(string $token)
    {
        $data = Cache::get("export_contracts_{$token}");

        abort_if(!$data || ($data['status'] ?? null) !== 'ready', 404);
        abort_if(!Storage::disk('local')->exists($data['path']), 404);

        Cache::forget("export_contracts_{$token}");

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
                "imports/contracts/{$token}",
                $storedFilename,
                'local'
            );

            Cache::put("import_contracts_{$token}", [
                'status' => 'processing',
                'filename' => $originalFilename,
            ], now()->addHours(2));

            $readerType = $extension === 'csv'
                ? ExcelFormat::CSV
                : ExcelFormat::XLSX;

            (new ContractImport($userId))
                ->queue($path, 'local', $readerType)
                ->allOnQueue('imports')
                ->chain([
                    new MarkContractImportReady($token, $originalFilename, $path),
                ]);

            return response()->json([
                'message' => 'Import sedang diproses.',
                'token' => $token,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal memulai proses impor kontrak.', [
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

        $data = Cache::get("import_contracts_{$validated['token']}");

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

        $filename = 'TEMPLAT_KONTRAK_DPUPR.xlsx'; // Nama file

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

        $data = Contract::query()
            ->select(
                'id',
                'contract_number',
                'sub_account_code',
                'activity_code',
                'activity_description'
            )
            ->whereNotNull('contract_number')
            ->where('contract_number', '!=', '')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('contract_number', 'like', "%{$search}%")
                        ->orWhere('sub_account_code', 'like', "%{$search}%")
                        ->orWhere('activity_code', 'like', "%{$search}%")
                        ->orWhere('activity_description', 'like', "%{$search}%");
                });
            })
            ->orderBy('contract_number')
            ->limit(20)
            ->get();

        return response()->json([
            'results' => $data->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->contract_number . ' | ' . $item->sub_account_code . ' | ' . $item->activity_code . ' | ' . $item->activity_description,
                    'contract_number' => $item->contract_number,
                    'sub_account_code' => $item->sub_account_code,
                    'activity_code' => $item->activity_code,
                    'activity_description' => $item->activity_description,
                ];
            })->values(),
        ]);
    }

    public function recap()
    {
        /*
        |--------------------------------------------------------------------------
        | SUBQUERY REALISASI PER KONTRAK
        |--------------------------------------------------------------------------
        */

        $realizationSubquery = DB::table('realizations')
            ->leftJoin('ls_payments', 'ls_payments.id', '=', 'realizations.ls_payment_id')
            ->select([
                'realizations.contract_id',
                DB::raw('COALESCE(SUM(COALESCE(ls_payments.sp2d_value, 0)), 0) as total_realization'),
            ])
            ->groupBy('realizations.contract_id');

        /*
        |--------------------------------------------------------------------------
        | REKAP BIDANG
        |--------------------------------------------------------------------------
        */

        $rawRecapDepartments = Contract::query()
            ->select([
                DB::raw('COALESCE(contracts.department, "") as department'),
                DB::raw('COUNT(contracts.id) as total_contracts'),
                DB::raw('COALESCE(SUM(COALESCE(contracts.budget_value, 0)), 0) as total_budget_value'),
                DB::raw('COALESCE(SUM(COALESCE(contracts.contract_value, 0)), 0) as total_contract_value'),
                DB::raw('COALESCE(SUM(COALESCE(realization_totals.total_realization, 0)), 0) as total_realization'),
                DB::raw('(COALESCE(SUM(COALESCE(contracts.contract_value, 0)), 0) - COALESCE(SUM(COALESCE(realization_totals.total_realization, 0)), 0)) as total_balance'),
            ])
            ->leftJoinSub($realizationSubquery, 'realization_totals', function ($join) {
                $join->on('contracts.id', '=', 'realization_totals.contract_id');
            })
            ->groupBy('contracts.department')
            ->orderBy('contracts.department')
            ->get();

        $recapDepartments = $rawRecapDepartments->map(function ($item) {
            $contractValue = (float) ($item->total_contract_value ?? 0);
            $totalRealization = (float) ($item->total_realization ?? 0);

            $item->department_label = $item->department !== '' ? $item->department : 'Tanpa Bidang';
            $item->realization_percentage = $contractValue > 0
                ? ($totalRealization / $contractValue) * 100
                : 0;
            $item->realization_percentage_formatted = number_format($item->realization_percentage, 2, ',', '.') . '%';

            $item->total_contracts_formatted = number_format($item->total_contracts ?? 0, 0, ',', '.');
            $item->total_budget_value_formatted = $this->formatRupiah($item->total_budget_value ?? 0);
            $item->total_contract_value_formatted = $this->formatRupiah($item->total_contract_value ?? 0);
            $item->total_realization_formatted = $this->formatRupiah($item->total_realization ?? 0);
            $item->total_balance_formatted = $this->formatRupiah($item->total_balance ?? 0);

            return $item;
        })->values();

        $departmentTotalContracts = (int) $rawRecapDepartments->sum('total_contracts');
        $departmentTotalBudgetValue = (float) $rawRecapDepartments->sum('total_budget_value');
        $departmentTotalContractValue = (float) $rawRecapDepartments->sum('total_contract_value');
        $departmentTotalRealization = (float) $rawRecapDepartments->sum('total_realization');
        $departmentTotalBalance = (float) $rawRecapDepartments->sum('total_balance');
        $departmentTotalPercentage = $departmentTotalContractValue > 0
            ? ($departmentTotalRealization / $departmentTotalContractValue) * 100
            : 0;

        $departmentSummary = (object) [
            'total_contracts' => $departmentTotalContracts,
            'total_contracts_formatted' => number_format($departmentTotalContracts, 0, ',', '.'),
            'total_budget_value' => $departmentTotalBudgetValue,
            'total_budget_value_formatted' => $this->formatRupiah($departmentTotalBudgetValue),
            'total_contract_value' => $departmentTotalContractValue,
            'total_contract_value_formatted' => $this->formatRupiah($departmentTotalContractValue),
            'total_realization' => $departmentTotalRealization,
            'total_realization_formatted' => $this->formatRupiah($departmentTotalRealization),
            'total_balance' => $departmentTotalBalance,
            'total_balance_formatted' => $this->formatRupiah($departmentTotalBalance),
            'realization_percentage' => $departmentTotalPercentage,
            'realization_percentage_formatted' => number_format($departmentTotalPercentage, 2, ',', '.') . '%',
        ];

        /*
        |--------------------------------------------------------------------------
        | REKAP SUMBER DANA
        |--------------------------------------------------------------------------
        */

        $rawRecapFundSources = Contract::query()
            ->select([
                DB::raw('COALESCE(contracts.fund_source, "") as fund_source'),
                DB::raw('COUNT(contracts.id) as total_contracts'),
                DB::raw('COALESCE(SUM(COALESCE(contracts.budget_value, 0)), 0) as total_budget_value'),
                DB::raw('COALESCE(SUM(COALESCE(contracts.contract_value, 0)), 0) as total_contract_value'),
                DB::raw('COALESCE(SUM(COALESCE(realization_totals.total_realization, 0)), 0) as total_realization'),
                DB::raw('(COALESCE(SUM(COALESCE(contracts.contract_value, 0)), 0) - COALESCE(SUM(COALESCE(realization_totals.total_realization, 0)), 0)) as total_balance'),
            ])
            ->leftJoinSub($realizationSubquery, 'realization_totals', function ($join) {
                $join->on('contracts.id', '=', 'realization_totals.contract_id');
            })
            ->groupBy('contracts.fund_source')
            ->orderBy('contracts.fund_source')
            ->get();

        $recapFundSources = $rawRecapFundSources->map(function ($item) {
            $contractValue = (float) ($item->total_contract_value ?? 0);
            $totalRealization = (float) ($item->total_realization ?? 0);

            $item->fund_source_label = $item->fund_source !== '' ? $item->fund_source : 'Tanpa Sumber Dana';
            $item->realization_percentage = $contractValue > 0
                ? ($totalRealization / $contractValue) * 100
                : 0;
            $item->realization_percentage_formatted = number_format($item->realization_percentage, 2, ',', '.') . '%';

            $item->total_contracts_formatted = number_format($item->total_contracts ?? 0, 0, ',', '.');
            $item->total_budget_value_formatted = $this->formatRupiah($item->total_budget_value ?? 0);
            $item->total_contract_value_formatted = $this->formatRupiah($item->total_contract_value ?? 0);
            $item->total_realization_formatted = $this->formatRupiah($item->total_realization ?? 0);
            $item->total_balance_formatted = $this->formatRupiah($item->total_balance ?? 0);

            return $item;
        })->values();

        $fundSourceTotalContracts = (int) $rawRecapFundSources->sum('total_contracts');
        $fundSourceTotalBudgetValue = (float) $rawRecapFundSources->sum('total_budget_value');
        $fundSourceTotalContractValue = (float) $rawRecapFundSources->sum('total_contract_value');
        $fundSourceTotalRealization = (float) $rawRecapFundSources->sum('total_realization');
        $fundSourceTotalBalance = (float) $rawRecapFundSources->sum('total_balance');
        $fundSourceTotalPercentage = $fundSourceTotalContractValue > 0
            ? ($fundSourceTotalRealization / $fundSourceTotalContractValue) * 100
            : 0;

        $fundSourceSummary = (object) [
            'total_contracts' => $fundSourceTotalContracts,
            'total_contracts_formatted' => number_format($fundSourceTotalContracts, 0, ',', '.'),
            'total_budget_value' => $fundSourceTotalBudgetValue,
            'total_budget_value_formatted' => $this->formatRupiah($fundSourceTotalBudgetValue),
            'total_contract_value' => $fundSourceTotalContractValue,
            'total_contract_value_formatted' => $this->formatRupiah($fundSourceTotalContractValue),
            'total_realization' => $fundSourceTotalRealization,
            'total_realization_formatted' => $this->formatRupiah($fundSourceTotalRealization),
            'total_balance' => $fundSourceTotalBalance,
            'total_balance_formatted' => $this->formatRupiah($fundSourceTotalBalance),
            'realization_percentage' => $fundSourceTotalPercentage,
            'realization_percentage_formatted' => number_format($fundSourceTotalPercentage, 2, ',', '.') . '%',
        ];

        return view('dashboard.monev.finances.contracts.recap', compact(
            'recapDepartments',
            'departmentSummary',
            'recapFundSources',
            'fundSourceSummary'
        ));
    }
}
