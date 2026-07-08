<?php

namespace App\Http\Controllers\IkliSurvey;

use App\Http\Controllers\Controller;
use App\Models\Irrigation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class IrrigationController extends Controller
{
    /**
     * Key routing (digunakan untuk dynamic route name).
     */
    protected string $key = 'irrigation';

    /**
     * Label jenis infrastruktur (digunakan untuk filtering pertanyaan & view).
     */
    protected string $infrastructureType = 'Jaringan Irigasi';

    /**
     * Model yang digunakan oleh controller.
     */
    protected string $model = Irrigation::class;

    /**
     * Mapping field database ke label indikator.
     * Digunakan untuk render hasil survei secara dinamis.
     */
    protected const IKLI_INDICATOR = [
        'physical_availability_score'   => 'KETERSEDIAAN FISIK',
        'quality_score'                 => 'KUALITAS',
        'suitability_score'             => 'KESESUAIAN',
        'utilization_score'             => 'PEMANFAATAN',
    ];

    protected const IHLI_INDICATOR = [
        'expectation_score'   => 'HARAPAN',
    ];

    /**
     * Base query builder.
     * Dipisahkan agar mudah override jika ke depan butuh eager loading atau scope tambahan.
     */
    protected function query()
    {
        return ($this->model)::query();
    }

    /**
     * Generate daftar route dinamis berdasarkan key controller.
     * Membuat controller tetap DRY dan scalable.
     */
    protected function routes(): object
    {
        return (object)[
            'index'       => route("ikli-survey.dashboard.questionnaire.{$this->key}.index"),
            'data'        => route("ikli-survey.dashboard.questionnaire.{$this->key}.data"),
            'recap'       => route("ikli-survey.dashboard.questionnaire.{$this->key}.recap"),
            'recapData'   => route("ikli-survey.dashboard.questionnaire.{$this->key}.recap-data"),
            'export'      => route("ikli-survey.dashboard.questionnaire.{$this->key}.export"),
        ];
    }

    /* ========================================================= */

    /**
     * Menampilkan halaman daftar survei.
     */
    public function index()
    {
        return view(
            "ikli-survey.dashboard.questionnaire.infrastructure-type.index",
            [
                'infrastructureType' => $this->infrastructureType,
                'infrastructureTypeKey' => $this->key,
                'routeList' => $this->routes(),
            ]
        );
    }

    /**
     * Endpoint untuk DataTables (AJAX).
     * Menggunakan filtering dinamis dengan when() agar clean & readable.
     */
    public function getData()
    {
        if (request()->ajax()) {
            $query = $this->query()
                ->with('respondent')
                ->when(request('start_date') && request('end_date'), function ($q) {
                    $q->whereHas('respondent', function ($respondentQuery) {
                        $respondentQuery->whereBetween('survey_date', [
                            request('start_date') . ' 00:00:00',
                            request('end_date') . ' 23:59:59',
                        ]);
                    });
                })
                ->when(request('is_valid'), function ($q, $v) {
                    $q->whereHas('respondent', function ($respondentQuery) use ($v) {

                        if ($v === 'invalid') {
                            $respondentQuery->where(function ($query) {
                                $query->whereNull('priority_improvement')
                                    ->orWhereRaw("TRIM(priority_improvement) = ''");
                            });
                        } else {
                            $respondentQuery->whereRaw("TRIM(COALESCE(priority_improvement, '')) != ''");
                        }
                    });
                })
                ->when(request('district'), function ($q, $v) {
                    $q->whereHas('respondent', function ($respondentQuery) use ($v) {
                        $respondentQuery->where('district', $v);
                    });
                })
                ->when(request('village'), function ($q, $v) {
                    $q->whereHas('respondent', function ($respondentQuery) use ($v) {
                        $respondentQuery->where('village', $v);
                    });
                });

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('survey_date', function ($data) {
                    return $data->respondent && $data->respondent->survey_date
                        ? Carbon::parse($data->respondent->survey_date)
                        ->locale(app()->getLocale())
                        ->translatedFormat('d F Y H:i')
                        : '-';
                })
                ->addColumn('district', function ($data) {
                    return $data->respondent->district ?? '-';
                })
                ->addColumn('village', function ($data) {
                    return $data->respondent->village ?? '-';
                })
                ->addColumn('gender', function ($data) {
                    return $data->respondent->gender ?? '-';
                })
                ->addColumn('age', function ($data) {
                    return $data->respondent->age ?? '-';
                })
                ->addColumn('education', function ($data) {
                    return $data->respondent->education ?? '-';
                })
                ->addColumn('occupation', function ($data) {
                    return $data->respondent->occupation ?? '-';
                })
                ->addColumn('disability_status', function ($data) {
                    return $data->respondent->disability_status ?? '-';
                })
                ->addColumn('action', function ($data) {
                    $editRoute = route('ikli-survey.dashboard.questionnaire.respondent.edit', ['id' => $data->respondent_id, 'infrastructureType' => $this->key]);

                    return '
                    <div class="d-flex align-items-center justify-content-between" style="gap: 1rem">
                        <a href="' . $editRoute . '" class="btn btn-sm btn-indigo" title="Edit Data">
                            <i class="fa fa-pencil"></i>
                        </a>

                        <input type="checkbox" class="custom-form-check-input check-item"
                            aria-label="Pilih Item" title="Pilih Item" value="' . $data->respondent_id . '">
                    </div>
                ';
                })
                ->addColumn('is_valid', function ($data) {
                    $value = $data->respondent->priority_improvement ?? '';

                    if (trim($value) !== '') {
                        return '<i class="fa fa-check-circle text-success" style="font-size: 1.5rem;"></i>';
                    }

                    return '<i class="fa fa-times-circle text-danger" style="font-size: 1.5rem;"></i>';
                })
                ->rawColumns(['action', 'is_valid'])
                ->make(true);
        }
    }

    /**
     * Menampilkan halaman daftar rekap.
     */
    public function recap()
    {
        return view('ikli-survey.dashboard.questionnaire.infrastructure-type.recap', [
            'infrastructureType' => $this->infrastructureType,
            'routeList' => $this->routes(),
        ]);
    }

    /**
     * Endpoint untuk rekap IKM dan IHM (AJAX).
     */
    public function getRecapData()
    {
        $ikliColumns = array_keys(static::IKLI_INDICATOR);

        $ihliColumns = array_keys(static::IHLI_INDICATOR);

        $columns = array_merge($ikliColumns, $ihliColumns);

        $selectAvg = collect($columns)
            ->map(fn($col) => "AVG($col) as $col")
            ->implode(',');

        $baseQuery = $this->query()
            ->with('respondent')
            ->when(request('district'), function ($q, $v) {
                $q->whereHas('respondent', function ($respondentQuery) use ($v) {
                    $respondentQuery->where('district', $v);
                });
            });

        $query = (clone $baseQuery)
            ->selectRaw($selectAvg)
            ->first();

        $ikmData = collect(static::IKLI_INDICATOR)
            ->map(function ($label, $field) use ($query) {

                $average = (float) ($query->{$field} ?? 0);
                $score   = $average * 25;

                return [
                    'indicator' => $label,
                    'average'   => round($average, 2),
                    'score'     => round($score, 2),
                ];
            })
            ->values();

        $ihmData = collect(static::IHLI_INDICATOR)
            ->map(function ($label, $field) use ($query) {

                $average = (float) ($query->{$field} ?? 0);
                $score   = $average * 25;

                return [
                    'indicator' => $label,
                    'average'   => round($average, 2),
                    'score'     => round($score, 2),
                ];
            })
            ->values();

        $totalRespondent = (clone $baseQuery)->count();

        $ikmRawTotalScore = collect($ikliColumns)
            ->sum(function ($field) use ($query) {
                $field = (string) $field;

                return ((float) ($query->{$field} ?? 0)) * 25;
            });

        $ikmTotalScore = round($ikmRawTotalScore, 2);
        $ikmValue      = count($ikliColumns) > 0
            ? round(($ikmRawTotalScore / count($ikliColumns)), 2)
            : 0;

        $ihmRawTotalScore = collect($ihliColumns)
            ->sum(function ($field) use ($query) {
                $field = (string) $field;

                return ((float) ($query->{$field} ?? 0)) * 25;
            });

        $ihmTotalScore = round($ihmRawTotalScore, 2);
        $ihmValue      = count($ihliColumns) > 0
            ? round(($ihmRawTotalScore / count($ihliColumns)), 2)
            : 0;

        /**
         * Mutu IKM
         */
        if ($ikmValue >= 88.31 && $ikmValue <= 100) {
            $ikmQuality = 'A';
            $ikmLabel = 'SANGAT BAIK';
        } elseif ($ikmValue >= 76.61 && $ikmValue <= 88.30) {
            $ikmQuality = 'B';
            $ikmLabel = 'BAIK';
        } elseif ($ikmValue >= 65.00 && $ikmValue <= 76.60) {
            $ikmQuality = 'C';
            $ikmLabel = 'KURANG BAIK';
        } else {
            $ikmQuality = 'D';
            $ikmLabel = 'TIDAK BAIK';
        }

        /**
         * Mutu IHM
         */
        if ($ihmValue >= 88.31 && $ihmValue <= 100) {
            $ihmQuality = 'A';
            $ihmLabel = 'SANGAT PENTING';
        } elseif ($ihmValue >= 76.61 && $ihmValue <= 88.30) {
            $ihmQuality = 'B';
            $ihmLabel = 'PENTING';
        } elseif ($ihmValue >= 65.00 && $ihmValue <= 76.60) {
            $ihmQuality = 'C';
            $ihmLabel = 'KURANG PENTING';
        } else {
            $ihmQuality = 'D';
            $ihmLabel = 'TIDAK PENTING';
        }

        return response()->json([
            'success' => true,
            'data' => [
                'ikm' => [
                    'indicators' => $ikmData,
                    'summary' => [
                        'totalRespondent' => $totalRespondent,
                        'totalScore'      => $ikmTotalScore,
                        'value'           => $ikmValue,
                        'label'           => $ikmLabel,
                        'quality'         => $ikmQuality,
                    ],
                ],
                'ihm' => [
                    'indicators' => $ihmData,
                    'summary' => [
                        'totalRespondent' => $totalRespondent,
                        'totalScore'      => $ihmTotalScore,
                        'value'           => $ihmValue,
                        'label'           => $ihmLabel,
                        'quality'         => $ihmQuality,
                    ],
                ],
            ],
        ]);
    }

    /**
     * Ekspor data.
     */
    public function export(Request $request)
    {
        $request->validate([
            'export_mode' => 'required|in:all,range',

            'export_start_date' => 'required_if:export_mode,range|nullable|date',
            'export_end_date'   => 'required_if:export_mode,range|nullable|date|after_or_equal:export_start_date',
        ], [
            'export_start_date.required_if' => 'Tanggal mulai wajib diisi jika memilih rentang waktu.',
            'export_end_date.required_if'   => 'Tanggal selesai wajib diisi jika memilih rentang waktu.',
            'export_end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        ]);

        $startDate = null;
        $endDate   = null;

        if ($request->export_mode === 'range') {
            $startDate = $request->export_start_date;
            $endDate   = $request->export_end_date;
        }

        // konversi key
        $keyConversion = str_replace(' ', '', ucwords(str_replace('-', ' ', $this->key)));

        // nama class export dinamis
        $className = 'App\\Exports\\' . $keyConversion . 'Export';

        if (!class_exists($className)) {
            return back()->with('error', 'Format export tidak tersedia.');
        }

        $infrastructureType = strtoupper(trim(preg_replace('/[^a-zA-Z0-9]+/', '_', $this->infrastructureType), '_'));

        return Excel::download(
            new $className($startDate, $endDate),
            now()->format('Ymd_Hi_') . $infrastructureType . '.xlsx'
        );
    }
}
