<?php

namespace App\Http\Controllers;

use App\Http\Requests\MainPerformanceIndicatorRequest;
use App\Models\MainPerformanceIndicator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        return view(
            'dashboard.performance-indicators.main-indicators.index',
            compact('routeList')
        );
    }

    // ============================= DATA =============================

    public function getData()
    {
        if (request()->ajax()) {
            $query = MainPerformanceIndicator::query()
                ->with('modifiedBy')
                ->latest();

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
                ->whereNotNull('measurement_year');

            if ($indicatorName) {
                $query->where('indicator_name', $indicatorName);
            }

            $rows = $query
                ->orderBy('measurement_year', 'asc')
                ->get([
                    'indicator_name',
                    'indicator_unit',
                    'measurement_year',
                    'target_value',
                    'achievement_value',
                    'performance_value',
                ]);

            if ($rows->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'title' => 'Indikator Kinerja Utama',
                        'unit' => '-',
                        'labels' => [],
                        'target' => [],
                        'achievement' => [],
                        'performance' => [],
                    ],
                ]);
            }

            $firstRow = $rows->first();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'title' => $firstRow->indicator_name ?? 'Indikator Kinerja Utama',
                    'unit' => $firstRow->indicator_unit ?? '-',
                    'labels' => $rows->pluck('measurement_year')
                        ->map(fn($year) => (string) $year)
                        ->values(),
                    'target' => $rows->pluck('target_value')
                        ->map(fn($value) => round((float) ($value ?? 0), 2))
                        ->values(),
                    'achievement' => $rows->pluck('achievement_value')
                        ->map(fn($value) => round((float) ($value ?? 0), 2))
                        ->values(),
                    'performance' => $rows->pluck('performance_value')
                        ->map(fn($value) => round((float) ($value ?? 0), 2))
                        ->values(),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal mengambil data chart indikator kinerja Utama: ' . $e->getMessage());

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
}
