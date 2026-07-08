<?php

namespace App\Http\Controllers\IkliSurvey;

use App\Http\Controllers\Controller;
use App\Models\InternetNetwork;
use App\Models\Irrigation;
use App\Models\PowerGrid;
use App\Models\QuestionnaireRespondent;
use App\Models\Road;
use App\Models\TransportationTerminal;
use App\Models\WasteManagementSystem;
use App\Models\WastewaterManagementSystem;
use App\Models\WaterSupplySystem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Model yang digunakan
     */
    protected function model()
    {
        return [
            InternetNetwork::class,
            Irrigation::class,
            PowerGrid::class,
            Road::class,
            TransportationTerminal::class,
            WastewaterManagementSystem::class,
            WasteManagementSystem::class,
            WaterSupplySystem::class,
        ];
    }

    protected string $respondentModel = QuestionnaireRespondent::class;

    /**
     * Jenis infrastruktur
     */
    protected function infrastructureType(): array
    {
        return [
            'transportation-terminal'       => 'Prasarana Terminal',
            'road'                          => 'Jaringan Jalan',
            'irrigation'                    => 'Jaringan Irigasi',
            'water-supply-system'           => 'Prasarana Air Minum',
            'wastewater-management-system'  => 'Prasarana Air Limbah',
            'waste-management-system'       => 'Prasarana Persampahan',
            'power-grid'                    => 'Jaringan Listrik',
            'internet-network'              => 'Jaringan Telekomunikasi/Internet',
        ];
    }

    /**
     * Dashboard utama
     */
    public function index()
    {
        $infrastructureType = $this->infrastructureType();

        return view('ikli-survey.dashboard.index', compact('infrastructureType'));
    }

    /**
     * Halaman About
     */
    public function about()
    {
        return view('ikli-survey.dashboard.about');
    }

    /**
     * Endpoint monitoring kuesioner per bulan
     */
    public function getQuestionnaireStatistic(Request $request)
    {
        $year  = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate   = Carbon::create($year, $month, 1)->endOfMonth();

        $rawData = ($this->respondentModel)::selectRaw('DATE(survey_date) as date, COUNT(*) as total')
            ->whereBetween('survey_date', [$startDate, $endDate])
            ->groupByRaw('DATE(survey_date)')
            ->pluck('total', 'date');

        $dataPerDay = [];

        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');

            $dataPerDay[] = [
                'date'  => $formattedDate,
                'total' => (int) ($rawData[$formattedDate] ?? 0),
            ];
        }

        $totalInMonth = (int) $rawData->sum();

        return response()->json([
            'status'         => 'success',
            'year'           => (int) $year,
            'month'          => (int) $month,
            'total_in_month' => $totalInMonth,
            'per_day'        => $dataPerDay
        ]);
    }

    /**
     * Endpoint monitoring kuesioner per kecamatan
     */
    public function getDistrictStatistic()
    {
        $rawData = ($this->respondentModel)::selectRaw('district, COUNT(*) as total')
            ->whereNotNull('district')
            ->where('district', '!=', '')
            ->groupBy('district')
            ->orderBy('district')
            ->get();

        $totalAll = (int) $rawData->sum('total');

        $data = $rawData->map(function ($item) {
            return [
                'district' => $item->district,
                'total'    => (int) $item->total,
            ];
        })->values();

        return response()->json([
            'status'    => 'success',
            'total_all' => $totalAll,
            'data'      => $data,
        ]);
    }

    /**
     * Endpoint monitoring statistik responden
     */
    public function getRespondentStatistic(Request $request)
    {
        $statistics = [
            'gender' => [
                'label'  => 'Jenis Kelamin',
                'column' => 'gender',
            ],
            'age' => [
                'label'  => 'Usia',
                'column' => 'age',
            ],
            'education' => [
                'label'  => 'Pendidikan Terakhir',
                'column' => 'education',
            ],
            'occupation' => [
                'label'  => 'Pekerjaan',
                'column' => 'occupation',
            ],
            'disability_status' => [
                'label'  => 'Disabilitas/Non-Disabilitas',
                'column' => 'disability_status',
            ],
        ];

        $data = [];

        foreach ($statistics as $key => $statistic) {
            $column = $statistic['column'];

            $query = ($this->respondentModel)::query();

            // ===============================
            // Filter dashboard by kecamatan
            // ===============================
            if ($request->filled('district')) {
                $query->where('district', $request->district);
            }

            $rawData = $query->selectRaw($column . ' as label, COUNT(*) as total')
                ->whereNotNull($column)
                ->where($column, '!=', '')
                ->groupBy($column)
                ->orderBy($column)
                ->get();

            $data[$key] = [
                'label'     => $statistic['label'],
                'total_all' => (int) $rawData->sum('total'),
                'data'      => $rawData->map(function ($item) {
                    return [
                        'label' => $item->label,
                        'total' => (int) $item->total,
                    ];
                })->values(),
            ];
        }

        return response()->json([
            'status' => 'success',
            'data'   => $data,
        ]);
    }

    /**
     * Endpoint monitoring kuesioner prioritas infrastruktur
     */
    public function getPriorityStatistic(Request $request)
    {
        $query = ($this->respondentModel)::query()
            ->selectRaw("
                    priority_infrastructure,
                    CASE 
                        WHEN priority_improvement IS NULL OR priority_improvement = '' 
                        THEN 'TIDAK DIISI' 
                        ELSE priority_improvement 
                    END as priority_improvement,
                    COUNT(*) as total
                ")
            ->whereNotNull('priority_infrastructure')
            ->where('priority_infrastructure', '!=', '');

        // ===============================
        // Filter dashboard by kecamatan
        // ===============================
        if ($request->filled('district')) {
            $query->where('district', $request->district);
        }

        $rawData = $query
            ->groupByRaw("
                    priority_infrastructure,
                    CASE 
                        WHEN priority_improvement IS NULL OR priority_improvement = '' 
                        THEN 'TIDAK DIISI' 
                        ELSE priority_improvement 
                    END
                ")
            ->orderBy('priority_infrastructure')
            ->get();

        $totalAll = (int) $rawData->sum('total');

        $data = $rawData
            ->groupBy('priority_infrastructure')
            ->map(function ($items, $priorityInfrastructure) {

                $total = (int) $items->sum('total');

                $improvements = $items
                    ->sortByDesc('total')
                    ->map(function ($item) {
                        return [
                            'priority_improvement' => $item->priority_improvement,
                            'total'                => (int) $item->total,
                        ];
                    })
                    ->values();

                return [
                    'priority_infrastructure' => $priorityInfrastructure,
                    'total'                   => $total,
                    'improvements'            => $improvements,
                ];
            })
            ->sortByDesc('total')
            ->values();

        return response()->json([
            'status'    => 'success',
            'total_all' => $totalAll,
            'data'      => $data,
        ]);
    }
}
