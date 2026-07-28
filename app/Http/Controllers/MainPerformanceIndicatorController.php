<?php

namespace App\Http\Controllers;

use App\Exports\MainPerformanceIndicatorExport;
use App\Http\Requests\MainPerformanceIndicatorRequest;
use App\Jobs\MarkMainPerformanceIndicatorExportReady;
use App\Models\MainPerformanceIndicator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Yajra\DataTables\Facades\DataTables;

class MainPerformanceIndicatorController extends Controller
{
    // ============================= INDEX =============================

    public function index()
    {
        $routeList = (object)[
            'create' => route('dashboard.performance-indicators.main-indicators.create'),
            'showChart' => route('dashboard.performance-indicators.main-indicators.show-chart'),
            'data' => route('dashboard.performance-indicators.main-indicators.data'),
            'massDestroy' => route('dashboard.performance-indicators.main-indicators.mass-destroy'),
        ];

        $measurementYears = MainPerformanceIndicator::query()
            ->select('measurement_year')
            ->whereNotNull('measurement_year')
            ->distinct()
            ->orderBy('measurement_year', 'desc')
            ->get();

        $filterApplied = request()->has('measurement_year')
            || request()->has('period');

        return view(
            'dashboard.performance-indicators.main-indicators.index',
            compact('routeList', 'measurementYears', 'filterApplied')
        );
    }

    // ============================= DATA =============================

    public function getData()
    {
        if (request()->ajax()) {
            $query = MainPerformanceIndicator::query()
                ->with('modifiedBy')
                ->latest();

            if (request()->filled('measurement_year')) {
                $query->where(
                    'measurement_year',
                    request('measurement_year')
                );
            }

            if (request()->filled('period')) {
                $query->where(
                    'period',
                    request('period')
                );
            }

            return DataTables::of($query)
                ->editColumn('indicator_code', function ($data) {
                    return !empty($data->indicator_code) ? e($data->indicator_code) : '-';
                })
                ->editColumn('indicator_name', function ($data) {
                    if (!empty($data->indicator_name)) {
                        return '<div class="text-wrap-scroll">' . e($data->indicator_name) . '</div>';
                    }

                    return '-';
                })
                ->editColumn('indicator_unit', function ($data) {
                    return !empty($data->indicator_unit) ? e($data->indicator_unit) : '-';
                })
                ->editColumn('baseline_year', function ($data) {
                    return $data->baseline_year ?? '-';
                })
                ->editColumn('baseline_value', function ($data) {
                    return $this->formatDecimal($data->baseline_value);
                })
                ->editColumn('measurement_year', function ($data) {
                    return $data->measurement_year ?? '-';
                })
                ->editColumn('target_value', function ($data) {
                    return $this->formatDecimal($data->target_value);
                })
                ->editColumn('achievement_value', function ($data) {
                    return $this->formatDecimal($data->achievement_value);
                })
                ->editColumn('performance_value', function ($data) {
                    return $this->formatDecimal($data->performance_value);
                })
                ->editColumn('period', function ($data) {
                    return $data->period ?? '-';
                })
                ->editColumn('document_url', function ($data) {
                    if (!empty($data->document_url)) {
                        return '
                            <div class="text-center">
                                <a href="' . e($data->document_url) . '" class="btn btn-sm btn-primary" target="_blank" title="Lihat Dokumen">
                                    <i class="fa fa-external-link"></i> Lihat Dokumen
                                </a>
                            </div>
                        ';
                    }

                    return '-';
                })
                ->addColumn('action', function ($data) {
                    $editRoute = route(
                        'dashboard.performance-indicators.main-indicators.edit',
                        $data->id
                    );

                    return '
                        <div class="d-flex align-items-center justify-content-between" style="gap: 1rem">
                            <a href="' . $editRoute . '" class="btn btn-sm btn-indigo" title="Edit">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <input type="checkbox" class="custom-form-check-input check-item"
                                aria-label="Pilih Item" title="Pilih Item" value="' . $data->id . '">
                        </div>
                    ';
                })
                ->addColumn('history', function ($data) {
                    $user = optional($data->modifiedBy)->name ?? '-';

                    $createdAt = Carbon::parse($data->created_at)
                        ->locale(app()->getLocale())
                        ->translatedFormat('d F Y H:i');

                    $updatedAt = Carbon::parse($data->updated_at)
                        ->locale(app()->getLocale())
                        ->translatedFormat('d F Y H:i');

                    if ($data->created_at->eq($data->updated_at)) {
                        return '<small class="font-italic">Ditambahkan pada: ' . $createdAt . '<br>Oleh: ' . $user . '</small>';
                    }

                    return '<small class="font-italic">Diperbarui pada: ' . $updatedAt . '<br>Oleh: ' . $user . '</small>';
                })
                ->rawColumns([
                    'indicator_name',
                    'document_url',
                    'action',
                    'history',
                ])
                ->make(true);
        }

        abort(404);
    }

    // ============================= CHART DATA =============================

    public function getDataChart(Request $request)
    {
        try {
            $indicatorName = $request->get('indicator_name');

            $query = MainPerformanceIndicator::query()
                ->whereNotNull('measurement_year')
                ->whereNotNull('period');

            if (!empty($indicatorName)) {
                $query->where('indicator_name', $indicatorName);
            }

            $rows = $query
                ->orderBy('measurement_year', 'asc')
                ->orderBy('id', 'asc')
                ->get([
                    'id',
                    'indicator_name',
                    'indicator_unit',
                    'measurement_year',
                    'period',
                    'target_value',
                    'achievement_value',
                    'performance_value',
                ]);

            if ($rows->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'title' => $indicatorName
                            ?: 'Indikator Kinerja Utama',
                        'unit' => '-',
                        'charts' => [],
                    ],
                ]);
            }

            $firstRow = $rows->first();

            $periodOrder = [
                'Triwulan I',
                'Triwulan II',
                'Triwulan III',
                'Triwulan IV',
            ];

            $charts = $rows
                ->groupBy(function ($row) {
                    return (string) $row->measurement_year;
                })
                ->sortKeysDesc()
                ->map(function ($yearRows, $year) use ($periodOrder) {
                    /*
                 * Diasumsikan satu indikator hanya memiliki satu data
                 * untuk setiap kombinasi tahun dan periode.
                 */
                    $rowsByPeriod = $yearRows->keyBy('period');

                    $availablePeriods = collect($periodOrder)
                        ->filter(function ($period) use ($rowsByPeriod) {
                            return $rowsByPeriod->has($period);
                        })
                        ->values();

                    return [
                        'year' => (string) $year,

                        'labels' => $availablePeriods,

                        'target' => $availablePeriods
                            ->map(function ($period) use ($rowsByPeriod) {
                                $value = $rowsByPeriod
                                    ->get($period)
                                    ?->target_value;

                                return $value !== null
                                    ? round((float) $value, 2)
                                    : null;
                            })
                            ->values(),

                        'achievement' => $availablePeriods
                            ->map(function ($period) use ($rowsByPeriod) {
                                $value = $rowsByPeriod
                                    ->get($period)
                                    ?->achievement_value;

                                return $value !== null
                                    ? round((float) $value, 2)
                                    : null;
                            })
                            ->values(),

                        'performance' => $availablePeriods
                            ->map(function ($period) use ($rowsByPeriod) {
                                $value = $rowsByPeriod
                                    ->get($period)
                                    ?->performance_value;

                                return $value !== null
                                    ? round((float) $value, 2)
                                    : null;
                            })
                            ->values(),
                    ];
                })
                ->values();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'title' => $firstRow->indicator_name
                        ?? 'Indikator Kinerja Utama',

                    'unit' => $firstRow->indicator_unit
                        ?? '-',

                    'charts' => $charts,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error(
                'Gagal mengambil data chart indikator kinerja utama: '
                    . $e->getMessage()
            );

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data chart.',
            ], 500);
        }
    }

    public function showChart()
    {
        $indicatorNames = MainPerformanceIndicator::query()
            ->whereNotNull('indicator_name')
            ->orderBy('indicator_name', 'asc')
            ->pluck('indicator_name')
            ->unique()
            ->values();

        $routeList = (object)[
            'index' => route('dashboard.performance-indicators.main-indicators.index'),
            'chart' => route('dashboard.performance-indicators.main-indicators.chart'),
        ];

        return view(
            'dashboard.performance-indicators.main-indicators.show-chart',
            compact('routeList', 'indicatorNames')
        );
    }

    // ============================= CREATE =============================

    public function create()
    {
        $routeList = (object)[
            'index' => route('dashboard.performance-indicators.main-indicators.index'),
            'store' => route('dashboard.performance-indicators.main-indicators.store'),
        ];

        return view(
            'dashboard.performance-indicators.main-indicators.create',
            compact('routeList')
        );
    }

    // ============================= STORE =============================

    public function store(MainPerformanceIndicatorRequest $request)
    {
        $validated = $request->validated();

        $validated['document_url'] = $this->normalizeDocumentUrl(
            $validated['document_url'] ?? null
        );

        DB::beginTransaction();

        try {
            $validated['modified_by'] = Auth::id();

            MainPerformanceIndicator::create($validated);

            DB::commit();

            return redirect()
                ->route('dashboard.performance-indicators.main-indicators.index')
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal menyimpan data indikator kinerja Utama: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data gagal disimpan.')
                ->withInput();
        }
    }

    // ============================= EDIT =============================

    public function edit($id)
    {
        $data = MainPerformanceIndicator::findOrFail($id);

        $routeList = (object)[
            'index' => route('dashboard.performance-indicators.main-indicators.index'),
            'update' => route('dashboard.performance-indicators.main-indicators.update', $id),
        ];

        return view(
            'dashboard.performance-indicators.main-indicators.edit',
            compact('data', 'routeList')
        );
    }

    // ============================= UPDATE =============================

    public function update(MainPerformanceIndicatorRequest $request, $id)
    {
        $data = MainPerformanceIndicator::findOrFail($id);

        $validated = $request->validated();

        $validated['document_url'] = $this->normalizeDocumentUrl(
            $validated['document_url'] ?? null
        );

        DB::beginTransaction();

        try {
            $validated['modified_by'] = Auth::id();

            $data->update($validated);

            DB::commit();

            return redirect()
                ->route('dashboard.performance-indicators.main-indicators.index')
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal memperbarui data indikator kinerja Utama: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data gagal diperbarui.')
                ->withInput();
        }
    }

    // ============================= DESTROY =============================

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
            MainPerformanceIndicator::whereIn('id', $ids)->delete();

            DB::commit();

            return redirect()
                ->route('dashboard.performance-indicators.main-indicators.index')
                ->with('success', 'Data yang dipilih berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal menghapus data indikator kinerja Utama: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data yang dipilih gagal dihapus.');
        }
    }

    // ============================= HELPER =============================

    private function normalizeDocumentUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        if (str_contains($url, 'drive.google.com')) {
            preg_match('/\/file\/d\/([^\/]+)/', $url, $matches);

            $fileId = $matches[1] ?? null;

            if ($fileId) {
                return "https://drive.google.com/file/d/{$fileId}/preview";
            }
        }

        return $url;
    }

    private function formatDecimal($value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        return number_format((float) $value, 2, ',', '.');
    }

    // ============================= EXPORT =============================

    public function export(Request $request)
    {
        $validated = $request->validate([
            'measurement_year' => [
                'nullable',
                'integer',
            ],
            'period' => [
                'nullable',
                'string',
                'in:Triwulan I,Triwulan II,Triwulan III,Triwulan IV',
            ],
            'export_format' => [
                'required',
                'string',
                'in:xlsx,csv',
            ],
        ]);

        $measurementYear = isset($validated['measurement_year'])
            ? (string) $validated['measurement_year']
            : null;

        $period = $validated['period'] ?? null;
        $format = $validated['export_format'];

        $token = (string) Str::uuid();
        $datetime = now()->format('Ymd_His');

        $writerType = $format === 'csv'
            ? ExcelFormat::CSV
            : ExcelFormat::XLSX;

        $filename = "{$datetime}_INDIKATOR_KINERJA_UTAMA.{$format}";

        $path = "exports/main-performance-indicators/{$token}.{$format}";

        Cache::put("export_main_performance_indicators_{$token}", [
            'status' => 'processing',
            'path' => $path,
            'filename' => $filename,
            'format' => $format,
            'measurement_year' => $measurementYear,
            'period' => $period,
        ], now()->addHours(2));

        (new MainPerformanceIndicatorExport(
            $measurementYear,
            $period,
            $format
        ))
            ->queue($path, 'local', $writerType)
            ->allOnQueue('exports')
            ->chain([
                new MarkMainPerformanceIndicatorExportReady(
                    $token,
                    $path,
                    $filename
                ),
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

        $data = Cache::get("export_main_performance_indicators_{$validated['token']}");

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
                ? route('dashboard.performance-indicators.main-indicators.download-export', $validated['token'])
                : null,
        ]);
    }

    public function downloadExport(string $token)
    {
        $data = Cache::get("export_main_performance_indicators_{$token}");

        abort_if(!$data || ($data['status'] ?? null) !== 'ready', 404);
        abort_if(!Storage::disk('local')->exists($data['path']), 404);

        Cache::forget("export_main_performance_indicators_{$token}");

        return response()
            ->download(
                Storage::disk('local')->path($data['path']),
                $data['filename']
            )
            ->deleteFileAfterSend(true);
    }
}
