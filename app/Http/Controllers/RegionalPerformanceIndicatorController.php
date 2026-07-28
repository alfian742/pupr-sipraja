<?php

namespace App\Http\Controllers;

use App\Exports\RegionalPerformanceIndicatorExport;
use App\Http\Requests\RegionalPerformanceIndicatorRequest;
use App\Jobs\MarkRegionalPerformanceIndicatorExportReady;
use App\Models\RegionalPerformanceIndicator;
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

class RegionalPerformanceIndicatorController extends Controller
{
    protected function resolveSlugFromRoute()
    {
        $routeName = request()->route()->getName();

        // contoh:
        // dashboard.performance-indicators.regional-indicators.key-performance-indicators.index

        $segments = explode('.', $routeName);

        return $segments[3] ?? null;
    }

    protected function getIndicatorConfig()
    {
        $slug = $this->resolveSlugFromRoute();

        $configs = [
            'geographical-and-demographic-aspects' => [
                'type' => 'Aspek Geografi dan Demografi',
            ],
            'regional-competitiveness-aspects' => [
                'type' => 'Aspek Daya Saing Daerah',
            ],
            'key-performance-indicators' => [
                'type' => 'Indikator Kinerja Kunci',
            ],
        ];

        abort_if(!$slug || !isset($configs[$slug]), 404);

        return [
            'slug'  => $slug,
            'model' => RegionalPerformanceIndicator::class,
            'type'  => $configs[$slug]['type'],
        ];
    }

    // ============================= INDEX =============================

    public function index()
    {
        $config = $this->getIndicatorConfig();

        $slug = $config['slug'];
        $type = $config['type'];

        $routeList = (object)[
            'index' => route("dashboard.performance-indicators.regional-indicators.$slug.index"),
            'create' => route("dashboard.performance-indicators.regional-indicators.$slug.create"),
            'showChart' => route("dashboard.performance-indicators.regional-indicators.$slug.show-chart"),
            'data' => route("dashboard.performance-indicators.regional-indicators.$slug.data"),
            'massDestroy' => route("dashboard.performance-indicators.regional-indicators.$slug.mass-destroy"),
            'checkExport' => route("dashboard.performance-indicators.regional-indicators.$slug.check-export"),
            'export' => route("dashboard.performance-indicators.regional-indicators.$slug.export"),
        ];

        $measurementYears = RegionalPerformanceIndicator::query()
            ->select('measurement_year')
            ->whereNotNull('measurement_year')
            ->distinct()
            ->orderBy('measurement_year', 'desc')
            ->get();

        $filterApplied = request()->has('measurement_year')
            || request()->has('period');

        return view(
            'dashboard.performance-indicators.regional-indicators.index',
            compact('type', 'routeList', 'measurementYears', 'filterApplied')
        );
    }

    // ============================= DATA =============================

    public function getData()
    {
        if (request()->ajax()) {

            $config = $this->getIndicatorConfig();
            $slug = $config['slug'];
            $model = $config['model'];

            $query = $model::query()->where('indicator_type', $config['type']);

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
                ->editColumn('document_url', function ($data) {
                    if (!empty($data->document_url)) {
                        return '
                            <div class="text-center">
                                <a href="' . $data->document_url . '" class="btn btn-sm btn-primary" target="_blank" title="Lihat Dokumen">
                                    <i class="fa fa-external-link"></i> Lihat Dokumen
                                </a>
                            </div>
                        ';
                    }

                    return '-';
                })
                ->addColumn('action', function ($data) use ($slug) {

                    $editRoute = route("dashboard.performance-indicators.regional-indicators.$slug.edit", $data->id);

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
                    // Tentukan siapa yang memodifikasi
                    $user = optional($data->modifiedBy)->name ?? '-';

                    // Format tanggal dengan locale aplikasi
                    $createdAt = Carbon::parse($data->created_at)
                        ->locale(app()->getLocale())
                        ->translatedFormat('d F Y H:i');

                    $updatedAt = Carbon::parse($data->updated_at)
                        ->locale(app()->getLocale())
                        ->translatedFormat('d F Y H:i');

                    // Periksa apakah tanggal sama atau berbeda
                    if ($data->created_at->eq($data->updated_at)) {
                        return '<small class="font-italic">Ditambahkan pada: ' . $createdAt . '<br>Oleh: ' . $user . '</small>';
                    } else {
                        return '<small class="font-italic">Diperbarui pada: ' . $updatedAt . '<br>Oleh: ' . $user . '</small>';
                    }
                })
                ->rawColumns(['indicator_name', 'document_url', 'action', 'history'])
                ->make(true);
        }
    }

    // ============================= CHART DATA =============================

    public function getDataChart(Request $request)
    {
        try {
            $config = $this->getIndicatorConfig();

            $indicatorType = $config['type'];
            $indicatorName = $request->get('indicator_name');

            $query = $config['model']::query()
                ->where('indicator_type', $indicatorType)
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
                    'indicator_type',
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
                            ?: $indicatorType,
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
                        ?? $indicatorType,

                    'unit' => $firstRow->indicator_unit
                        ?? '-',

                    'charts' => $charts,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error(
                'Gagal mengambil data chart indikator kinerja daerah: '
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
        $config = $this->getIndicatorConfig();

        $slug = $config['slug'];
        $type = $config['type'];

        $indicatorNames = $config['model']::query()
            ->where('indicator_type', $type)
            ->whereNotNull('indicator_name')
            ->orderBy('indicator_name', 'asc')
            ->pluck('indicator_name')
            ->unique()
            ->values();

        $routeList = (object)[
            'index' => route("dashboard.performance-indicators.regional-indicators.$slug.index"),
            'chart' => route("dashboard.performance-indicators.regional-indicators.$slug.chart"),
        ];

        return view(
            'dashboard.performance-indicators.regional-indicators.show-chart',
            compact('type', 'routeList', 'indicatorNames')
        );
    }

    // ============================= CREATE =============================

    public function create()
    {
        $config = $this->getIndicatorConfig();

        $slug = $config['slug'];
        $type = $config['type'];

        $routeList = (object)[
            'index' => route("dashboard.performance-indicators.regional-indicators.$slug.index"),
            'store' => route("dashboard.performance-indicators.regional-indicators.$slug.store"),
        ];

        return view('dashboard.performance-indicators.regional-indicators.create', compact('type', 'routeList'));
    }

    // ============================= STORE =============================

    public function store(RegionalPerformanceIndicatorRequest $request)
    {
        $config = $this->getIndicatorConfig();

        $slug = $config['slug'];
        $model = $config['model'];
        $type = $config['type'];

        $validated = $request->validated();

        $url = $validated['document_url'] ?? null;

        if ($url) {
            // Cek apakah link Google Drive
            if (strpos($url, 'drive.google.com') !== false) {
                preg_match('/\/file\/d\/([^\/]+)/', $url, $matches);
                $fileId = $matches[1] ?? null;

                // Jika berhasil ambil file ID, ubah ke preview
                if ($fileId) {
                    $validated['document_url'] = "https://drive.google.com/file/d/{$fileId}/preview";
                } else {
                    // Jika gagal ambil ID, pakai URL asli
                    $validated['document_url'] = $url;
                }
            } else {
                // Jika bukan Google Drive, pakai URL asli
                $validated['document_url'] = $url;
            }
        }

        DB::beginTransaction();

        try {
            $validated['modified_by'] = Auth::user()->id;
            $model::create($validated);

            DB::commit();

            return redirect()
                ->route("dashboard.performance-indicators.regional-indicators.$slug.index")
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error("Gagal menyimpan data $type: " . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data gagal disimpan.')
                ->withInput();
        }
    }

    // ============================= EDIT =============================

    public function edit($id)
    {
        $config = $this->getIndicatorConfig();

        $slug = $config['slug'];
        $model = $config['model'];
        $type = $config['type'];

        $data = $model::findOrFail($id);

        $routeList = (object)[
            'index' => route("dashboard.performance-indicators.regional-indicators.$slug.index"),
            'update' => route("dashboard.performance-indicators.regional-indicators.$slug.update", $id),
        ];

        return view('dashboard.performance-indicators.regional-indicators.edit', compact('data', 'routeList'));
    }

    // ============================= UPDATE =============================

    public function update(RegionalPerformanceIndicatorRequest $request, $id)
    {
        $config = $this->getIndicatorConfig();

        $slug = $config['slug'];
        $model = $config['model'];
        $type = $config['type'];

        $data = $model::findOrFail($id);

        $validated = $request->validated();

        $url = $validated['document_url'] ?? null;

        if ($url) {
            // Cek apakah link Google Drive
            if (strpos($url, 'drive.google.com') !== false) {
                preg_match('/\/file\/d\/([^\/]+)/', $url, $matches);
                $fileId = $matches[1] ?? null;

                // Jika berhasil ambil file ID, ubah ke preview
                if ($fileId) {
                    $validated['document_url'] = "https://drive.google.com/file/d/{$fileId}/preview";
                } else {
                    // Jika gagal ambil ID, pakai URL asli
                    $validated['document_url'] = $url;
                }
            } else {
                // Jika bukan Google Drive, pakai URL asli
                $validated['document_url'] = $url;
            }
        }

        DB::beginTransaction();

        try {
            $validated['modified_by'] = Auth::user()->id;
            $data->update($validated);

            DB::commit();

            return redirect()
                ->route("dashboard.performance-indicators.regional-indicators.$slug.index")
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error("Gagal memperbarui data $type: " . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data gagal diperbarui.')
                ->withInput();
        }
    }

    // ============================= DESTROY =============================

    public function massDestroy(Request $request)
    {
        $config = $this->getIndicatorConfig();

        $slug = $config['slug'];
        $model = $config['model'];

        $ids = $request->ids;

        if (!$ids || count($ids) === 0) {
            return redirect()
                ->back()
                ->with('error', 'Tidak ada data yang dipilih.');
        }

        DB::beginTransaction();

        try {

            $model::whereIn('id', $ids)->delete();

            DB::commit();

            return redirect()
                ->route("dashboard.performance-indicators.regional-indicators.$slug.index")
                ->with('success', 'Data yang dipilih berhasil dihapus.');
        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error('Gagal menghapus data: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data yang dipilih gagal dihapus.');
        }
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

        $indicatorType = Str::of($this->getIndicatorConfig()['type'])
            ->upper()
            ->replaceMatches('/[^A-Z0-9]+/', '_')
            ->trim('_')
            ->toString();

        $filename = "{$datetime}_INDIKATOR_KINERJA_DAERAH_{$indicatorType}.{$format}";

        $path = "exports/regional-performance-indicators/{$token}.{$format}";

        Cache::put("export_regional_performance_indicators_{$token}", [
            'status' => 'processing',
            'path' => $path,
            'filename' => $filename,
            'format' => $format,
            'measurement_year' => $measurementYear,
            'period' => $period,
        ], now()->addHours(2));

        (new RegionalPerformanceIndicatorExport(
            $measurementYear,
            $period,
            $format
        ))
            ->queue($path, 'local', $writerType)
            ->allOnQueue('exports')
            ->chain([
                new MarkRegionalPerformanceIndicatorExportReady(
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

        $config = $this->getIndicatorConfig();
        $slug = $config['slug'];

        $data = Cache::get(
            "export_regional_performance_indicators_{$validated['token']}"
        );

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
                ? route(
                    "dashboard.performance-indicators.regional-indicators.$slug.download-export",
                    $validated['token']
                )
                : null,
        ]);
    }

    public function downloadExport(string $token)
    {
        $data = Cache::get("export_regional_performance_indicators_{$token}");

        abort_if(!$data || ($data['status'] ?? null) !== 'ready', 404);
        abort_if(!Storage::disk('local')->exists($data['path']), 404);

        Cache::forget("export_regional_performance_indicators_{$token}");

        return response()
            ->download(
                Storage::disk('local')->path($data['path']),
                $data['filename']
            )
            ->deleteFileAfterSend(true);
    }
}
