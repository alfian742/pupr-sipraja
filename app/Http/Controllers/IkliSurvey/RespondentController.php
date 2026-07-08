<?php

namespace App\Http\Controllers\IkliSurvey;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\District;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class RespondentController extends Controller
{
    // ====================================================================
    // KELOLA RESPONDEN
    // ====================================================================

    /**
     * Model utama
     */
    protected string $respondentModel = QuestionnaireRespondent::class;

    /**
     * Model yang digunakan berdasarkan jenis infrastruktur
     */
    protected function model(): array
    {
        return [
            'transportation-terminal'      => TransportationTerminal::class,
            'road'                         => Road::class,
            'irrigation'                   => Irrigation::class,
            'water-supply-system'          => WaterSupplySystem::class,
            'wastewater-management-system' => WastewaterManagementSystem::class,
            'waste-management-system'      => WasteManagementSystem::class,
            'power-grid'                   => PowerGrid::class,
            'internet-network'             => InternetNetwork::class,
        ];
    }

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
            'waste-management-system'       => 'Prasarana Sampah',
            'power-grid'                    => 'Jaringan Listrik',
            'internet-network'              => 'Jaringan Telekomunikasi/Internet',
        ];
    }

    /**
     * Menampilkan halaman daftar survei prioritas peningkatan.
     */
    public function index()
    {
        $routeList = (object)[
            'data'        => route("ikli-survey.dashboard.questionnaire.infrastructure-improvement.data"),
            'export'      => route("ikli-survey.dashboard.questionnaire.infrastructure-improvement.export"),
        ];

        return view(
            "ikli-survey.dashboard.questionnaire.infrastructure-type.infrastructure-improvement",
            [
                'infrastructureType' => 'Prioritas Peningkatan',
                'infrastructureTypeKey' => 'infrastructure-improvement',
                'routeList' => $routeList,
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
            $query = ($this->respondentModel)::query()
                ->when(request('start_date') && request('end_date'), function ($q) {
                    $q->whereBetween('survey_date', [
                        request('start_date') . ' 00:00:00',
                        request('end_date') . ' 23:59:59',
                    ]);
                })
                ->when(request('is_valid'), function ($q, $v) {
                    if ($v === 'invalid') {
                        $q->where(function ($query) {
                            $query->whereNull('priority_improvement')
                                ->orWhereRaw("TRIM(priority_improvement) = ''");
                        });
                    } else {
                        $q->whereRaw("TRIM(COALESCE(priority_improvement, '')) != ''");
                    }
                })
                ->when(request('district'), function ($q, $v) {
                    $q->where('district', $v);
                })
                ->when(request('village'), function ($q, $v) {
                    $q->where('village', $v);
                });

            return DataTables::of($query)
                ->addIndexColumn()

                ->editColumn('survey_date', function ($data) {
                    return $data->survey_date
                        ? Carbon::parse($data->survey_date)
                        ->locale(app()->getLocale())
                        ->translatedFormat('d F Y H:i')
                        : '-';
                })
                ->addColumn('action', function ($data) {
                    $editRoute = route('ikli-survey.dashboard.questionnaire.respondent.edit', ['id' => $data->id, 'infrastructureType' => 'infrastructure-improvement']);

                    return '
                    <div class="d-flex align-items-center justify-content-between" style="gap: 1rem">
                        <a href="' . $editRoute . '" class="btn btn-sm btn-indigo" title="Edit Data">
                            <i class="fa fa-pencil"></i>
                        </a>

                        <input type="checkbox" class="custom-form-check-input check-item"
                            aria-label="Pilih Item" title="Pilih Item" value="' . $data->id . '">
                    </div>
                ';
                })
                ->addColumn('is_valid', function ($data) {
                    $value = $data->priority_improvement ?? '';

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
     * Menampilkan form edit.
     */
    public function edit($id, $infrastructureType)
    {
        $data = ($this->respondentModel)::findOrFail($id);

        // Mapping label infrastruktur
        $infrastructureMap = array_merge($this->infrastructureType(), ['infrastructure-improvement' => 'Prioritas Peningkatan']);

        if (!isset($infrastructureMap[$infrastructureType])) {
            return redirect()
                ->back()
                ->with('error', 'Jenis infrastruktur tidak valid.');
        }

        // Mapping model infrastruktur
        $modelMap = $this->model();

        // Ambil semua jawaban infrastruktur berdasarkan respondent_id
        $questionnaireAnswers = [];

        foreach ($modelMap as $key => $modelClass) {
            $questionnaireAnswers[$key] = $modelClass::where('respondent_id', $data->id)->first();
        }

        // Navigasi previous & next berdasarkan ID responden
        $previous = ($this->respondentModel)::where('id', '<', $data->id)
            ->orderByDesc('id')
            ->first();

        $next = ($this->respondentModel)::where('id', '>', $data->id)
            ->orderBy('id')
            ->first();

        return view('ikli-survey.dashboard.questionnaire.infrastructure-type.edit', [
            'infrastructureType'      => $infrastructureType,
            'infrastructureTypeLabel' => $infrastructureMap[$infrastructureType],

            // Dikirim untuk looping tabel view
            'infrastructureOptions'   => $this->infrastructureType(),
            'questionnaireAnswers'    => $questionnaireAnswers,

            'data' => $data,

            'previous' => $previous
                ? route("ikli-survey.dashboard.questionnaire.respondent.edit", [$previous->id, $infrastructureType])
                : null,

            'next' => $next
                ? route("ikli-survey.dashboard.questionnaire.respondent.edit", [$next->id, $infrastructureType])
                : null,

            'back' => route("ikli-survey.dashboard.questionnaire.{$infrastructureType}.index"),
        ]);
    }

    /**
     * Memperbarui data survei.
     */
    public function update(Request $request, $id, $infrastructureType)
    {
        $data = ($this->respondentModel)::findOrFail($id);

        // Mapping label infrastruktur
        $infrastructureMap = array_merge($this->infrastructureType(), ['infrastructure-improvement' => 'Prioritas Peningkatan']);

        if (!isset($infrastructureMap[$infrastructureType])) {
            return redirect()
                ->back()
                ->with('error', 'Jenis infrastruktur tidak valid.')
                ->withInput();
        }

        // Mapping model infrastruktur
        $modelMap = $this->model();

        $rules = [
            'gender' => [
                'required',
                'string',
                'max:10',
            ],

            'age' => [
                'required',
                'string',
                'max:50',
            ],

            'education' => [
                'required',
                'string',
                'max:100',
            ],

            'occupation' => [
                'required',
                'string',
                'max:100',
            ],

            'disability_status' => [
                'required',
                'string',
                'max:20',
            ],

            'district' => [
                'required',
                'string',
                'max:50',
            ],

            'village' => [
                'required',
                'string',
                'max:50',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'priority_infrastructure' => [
                'required',
                'string',
                'max:50',
            ],

            'priority_improvement' => [
                'required',
                'string',
                'max:100',
            ],

            'answers' => [
                'required',
                'array',
            ],
        ];

        foreach ($modelMap as $key => $modelClass) {
            $rules["answers.{$key}"] = [
                'required',
                'array',
            ];

            $rules["answers.{$key}.physical_availability_score"] = [
                'required',
                'integer',
                Rule::in([1, 2, 3, 4]),
            ];

            $rules["answers.{$key}.quality_score"] = [
                'required',
                'integer',
                Rule::in([1, 2, 3, 4]),
            ];

            $rules["answers.{$key}.suitability_score"] = [
                'required',
                'integer',
                Rule::in([1, 2, 3, 4]),
            ];

            $rules["answers.{$key}.utilization_score"] = [
                'required',
                'integer',
                Rule::in([1, 2, 3, 4]),
            ];

            $rules["answers.{$key}.expectation_score"] = [
                'required',
                'integer',
                Rule::in([1, 2, 3, 4]),
            ];
        }

        $validated = $request->validate($rules, [
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.max' => 'Jenis kelamin maksimal 10 karakter.',

            'age.required' => 'Usia wajib dipilih.',
            'age.max' => 'Usia maksimal 50 karakter.',

            'education.required' => 'Pendidikan terakhir wajib dipilih.',
            'education.max' => 'Pendidikan terakhir maksimal 100 karakter.',

            'occupation.required' => 'Pekerjaan wajib dipilih.',
            'occupation.max' => 'Pekerjaan maksimal 100 karakter.',

            'disability_status.required' => 'Status disabilitas wajib dipilih.',
            'disability_status.max' => 'Status disabilitas maksimal 20 karakter.',

            'district.required' => 'Kecamatan wajib dipilih.',
            'district.max' => 'Kecamatan maksimal 50 karakter.',

            'village.required' => 'Kelurahan/Desa wajib dipilih.',
            'village.max' => 'Kelurahan/Desa maksimal 50 karakter.',

            'priority_infrastructure.required' => 'Infrastruktur prioritas wajib dipilih.',
            'priority_infrastructure.max' => 'Infrastruktur prioritas maksimal 50 karakter.',

            'priority_improvement.required' => 'Aspek prioritas wajib dipilih.',
            'priority_improvement.max' => 'Aspek prioritas maksimal 100 karakter.',

            'answers.required' => 'Data penilaian wajib diisi.',
            'answers.array' => 'Data penilaian tidak valid.',

            'answers.*.required' => 'Data penilaian infrastruktur wajib diisi.',
            'answers.*.array' => 'Data penilaian infrastruktur tidak valid.',

            'answers.*.physical_availability_score.required' => 'Nilai ketersediaan fisik wajib dipilih.',
            'answers.*.quality_score.required' => 'Nilai kualitas wajib dipilih.',
            'answers.*.suitability_score.required' => 'Nilai kesesuaian wajib dipilih.',
            'answers.*.utilization_score.required' => 'Nilai pemanfaatan wajib dipilih.',

            'answers.*.physical_availability_score.integer' => 'Nilai ketersediaan fisik tidak valid.',
            'answers.*.quality_score.integer' => 'Nilai kualitas tidak valid.',
            'answers.*.suitability_score.integer' => 'Nilai kesesuaian tidak valid.',
            'answers.*.utilization_score.integer' => 'Nilai pemanfaatan tidak valid.',

            'answers.*.physical_availability_score.in' => 'Nilai ketersediaan fisik harus 1 sampai 4.',
            'answers.*.quality_score.in' => 'Nilai kualitas harus 1 sampai 4.',
            'answers.*.suitability_score.in' => 'Nilai kesesuaian harus 1 sampai 4.',
            'answers.*.utilization_score.in' => 'Nilai pemanfaatan harus 1 sampai 4.',
        ]);

        DB::beginTransaction();

        try {
            // ======================
            // UPDATE RESPONDEN
            // ======================
            $data->update([
                'gender'                  => $validated['gender'],
                'age'                     => $validated['age'],
                'education'               => $validated['education'],
                'disability_status'       => $validated['disability_status'],
                'district'                => $validated['district'],
                'village'                 => $validated['village'],
                'occupation'              => strtoupper($validated['occupation']),
                'address'                 => $validated['address'] ?? null,
                'priority_infrastructure' => strtoupper($validated['priority_infrastructure']),
                'priority_improvement'    => strtoupper($validated['priority_improvement']),
            ]);

            // ======================
            // UPDATE JAWABAN INFRASTRUKTUR
            // ======================
            foreach ($modelMap as $key => $modelClass) {
                $answer = $validated['answers'][$key];

                $modelClass::updateOrCreate(
                    [
                        'respondent_id' => $data->id,
                    ],
                    [
                        'physical_availability_score' => $answer['physical_availability_score'],
                        'quality_score'                => $answer['quality_score'],
                        'suitability_score'            => $answer['suitability_score'],
                        'utilization_score'            => $answer['utilization_score'],
                        'expectation_score'            => $answer['expectation_score'],
                    ]
                );
            }

            DB::commit();

            return redirect()
                ->route('ikli-survey.dashboard.questionnaire.respondent.edit', [
                    'id'                 => $data->id,
                    'infrastructureType' => $infrastructureType,
                ])
                ->with('success', "Data survei ID {$data->id} berhasil diperbarui.");
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error("Gagal memperbarui data survei ID {$data->id}: " . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data survei gagal diperbarui.')
                ->withInput();
        }
    }

    /**
     * Menghapus satu data survei.
     */
    public function destroy($id, $infrastructureType)
    {
        $data = ($this->respondentModel)::findOrFail($id);

        $deletedId = $data->id;

        DB::beginTransaction();

        try {
            $data->delete();

            DB::commit();

            // Cari data berikutnya
            $nextId = ($this->respondentModel)::where('id', '>', $deletedId)
                ->orderBy('id', 'asc')
                ->value('id');

            // Jika data berikutnya tidak ada, cari data sebelumnya
            if (!$nextId) {
                $nextId = ($this->respondentModel)::where('id', '<', $deletedId)
                    ->orderBy('id', 'desc')
                    ->value('id');
            }

            // Jika masih ada data, arahkan ke halaman edit data tersebut
            if ($nextId) {
                return redirect()
                    ->route('ikli-survey.dashboard.questionnaire.respondent.edit', [
                        'id'                 => $nextId,
                        'infrastructureType' => $infrastructureType,
                    ])
                    ->with('success', "Data survei ID {$deletedId} telah dihapus.");
            }

            // Jika semua data sudah kosong
            return redirect()
                ->route("ikli-survey.dashboard.questionnaire.{$infrastructureType}.index")
                ->with('success', "Data survei ID {$deletedId} telah dihapus.");
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error("Gagal menghapus data survei ID {$deletedId}: " . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data survei gagal dihapus.');
        }
    }

    /**
     * Menghapus beberapa data sekaligus.
     */
    public function massDestroy(Request $request)
    {
        $request->validate([
            'ids'                 => 'required|array|min:1',
            'ids.*'               => 'integer|exists:questionnaire_respondents,id',
            'infrastructure_type' => [
                'required',
                'string',
                Rule::in(array_merge(array_keys($this->infrastructureType()), ['infrastructure-improvement']))
            ],
        ], [
            'ids.required'                 => 'Tidak ada data yang dipilih.',
            'ids.array'                    => 'Format data yang dipilih tidak valid.',
            'ids.min'                      => 'Pilih minimal 1 data untuk dihapus.',
            'ids.*.exists'                 => 'Data responden tidak ditemukan.',
            'infrastructure_type.required' => 'Jenis infrastruktur tidak ditemukan.',
            'infrastructure_type.in'       => 'Jenis infrastruktur tidak valid.',
        ]);

        $ids = $request->ids;
        $infrastructureType = $request->infrastructure_type;

        DB::beginTransaction();

        try {
            // Hapus semua data responden.
            // Data pada tabel infrastruktur akan ikut terhapus jika foreign key menggunakan cascadeOnDelete().
            ($this->respondentModel)::whereIn('id', $ids)->delete();

            DB::commit();

            return redirect()
                ->route("ikli-survey.dashboard.questionnaire.{$infrastructureType}.index")
                ->with('success', 'Data survei yang dipilih berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal menghapus data survei: ' . $e->getMessage(), [
                'ids' => $ids,
                'infrastructure_type' => $infrastructureType,
            ]);

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data survei yang dipilih gagal dihapus.');
        }
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
        $keyConversion = str_replace(' ', '', ucwords(str_replace('-', ' ', 'infrastructure-improvement')));

        // nama class export dinamis
        $className = 'App\\Exports\\' . $keyConversion . 'Export';

        if (!class_exists($className)) {
            return back()->with('error', 'Format export tidak tersedia.');
        }

        $infrastructureType = strtoupper(trim(preg_replace('/[^a-zA-Z0-9]+/', '_', 'PRIORITAS PENINGKATAN'), '_'));

        return Excel::download(
            new $className($startDate, $endDate),
            now()->format('Ymd_Hi_') . $infrastructureType . '.xlsx'
        );
    }

    // ====================================================================
    // MONITOR RESPONDEN
    // ====================================================================

    /**
     * Monitoring berdasarkan tanggal (harian).
     * 
     * Hierarki :
     * - Semua Data
     * - Per Tanggal
     * -- Per Kecamatan
     * --- Per Kelurahan/Desa
     */
    public function monitorByDate()
    {
        $data = ($this->respondentModel)::query();

        // Hitung semua data
        $countAll = (clone $data)->count();

        // Hitung semua data per tanggal
        $countDate = (clone $data)
            ->selectRaw('DATE(survey_date) as survey_date, COUNT(*) as total')
            ->whereNotNull('survey_date')
            ->groupByRaw('DATE(survey_date)')
            ->orderByDesc('survey_date')
            ->paginate(10)
            ->withQueryString();

        $dates = $countDate
            ->pluck('survey_date')
            ->filter()
            ->values();

        // Hitung semua data per kecamatan
        $countDistrict = (clone $data)
            ->selectRaw('DATE(survey_date) as survey_date, district, COUNT(*) as total')
            ->whereNotNull('survey_date')
            ->whereIn(DB::raw('DATE(survey_date)'), $dates)
            ->groupByRaw('DATE(survey_date), district')
            ->orderByRaw('DATE(survey_date) DESC')
            ->orderBy('district')
            ->get()
            ->groupBy('survey_date');

        // Hitung semua data per kelurahan/desa
        $countVillage = (clone $data)
            ->selectRaw('DATE(survey_date) as survey_date, district, village, COUNT(*) as total')
            ->whereNotNull('survey_date')
            ->whereIn(DB::raw('DATE(survey_date)'), $dates)
            ->groupByRaw('DATE(survey_date), district, village')
            ->orderByRaw('DATE(survey_date) DESC')
            ->orderBy('district')
            ->orderBy('village')
            ->get()
            ->groupBy([
                'survey_date',
                'district',
            ]);

        return view('ikli-survey.dashboard.monitor.date', compact(
            'countAll',
            'countDate',
            'countDistrict',
            'countVillage',
        ));
    }

    /**
     * Monitoring berdasarkan wilayah (kecamatan).
     * 
     * Hierarki :
     * - Semua Data
     * - Per Kecamatan
     * -- Per Kelurahan/Desa
     */
    public function monitorByDistrict()
    {
        $data = ($this->respondentModel)::query();

        // Hitung semua data
        $countAll = (clone $data)->count();

        // Hitung semua data per kecamatan
        $countDistrict = (clone $data)
            ->selectRaw('district, COUNT(*) as total')
            ->whereNotNull('district')
            ->groupBy('district')
            ->orderBy('district')
            ->get();

        $districts = $countDistrict
            ->pluck('district')
            ->filter()
            ->values();

        // Hitung semua data per kelurahan/desa
        $countVillage = (clone $data)
            ->selectRaw('district, village, COUNT(*) as total')
            ->whereNotNull('district')
            ->whereIn('district', $districts)
            ->groupBy('district', 'village')
            ->orderBy('district')
            ->orderBy('village')
            ->get()
            ->groupBy('district');

        return view('ikli-survey.dashboard.monitor.district', compact(
            'countAll',
            'countDistrict',
            'countVillage',
        ));
    }

    // ====================================================================
    // SAMPEL RESPONDEN
    // ====================================================================

    /**
     * Model yang digunakan untuk perhitungan sampel responden.
     */
    protected string $districtModel = District::class;

    public function respondentSampel()
    {
        $districts = ($this->districtModel)::get();
        $totalPopulation = $districts->sum('resident_count') ?? 0;

        return view('ikli-survey.dashboard.questionnaire.respondent.index', compact('districts', 'totalPopulation'));
    }

    /**
     * Rumus Slovin
     * n = N / (1 + N(e²))
     */
    public function slovinFormula(Request $request)
    {
        // Default
        // $e = 0.05; // 5%

        $e = (float) $request->input('margin_error', 0.05);

        $districts = ($this->districtModel)::get();
        $totalPopulation = (float) $districts->sum('resident_count') ?? 0;

        if ($totalPopulation <= 0) {
            return response()->json([
                'error' => 'Total populasi tidak tersedia'
            ], 400);
        }

        // Hitung sample size
        $sample = $totalPopulation / (1 + ($totalPopulation * pow($e, 2)));
        $sample = round($sample); // hasil akan 400

        // Distribusi proporsional
        $distribution = $this->proportionalDistribution(
            $districts,
            $totalPopulation,
            $sample
        );

        return response()->json([
            'method' => 'Slovin',
            'total_population' => $totalPopulation,
            'margin_error' => $e,
            'sample_size' => $sample,
            'distribution' => $distribution,
        ]);
    }

    /**
     * Rumus Krejcie & Morgan
     * n = (χ² * N * P(1-P)) / (d²(N-1) + χ²P(1-P))
     */
    public function krejcieMorganFormula(Request $request)
    {
        // Default
        // $chiSquare = 3.841; // 95%
        // $p = 0.5;
        // $d = 0.05;

        $chiSquare = (float) $request->input('confidence', 3.841);
        $p = (float) $request->input('p', 0.5);
        $d = (float) $request->input('margin_error', 0.05);

        $districts = ($this->districtModel)::get();
        $totalPopulation = (float) $districts->sum('resident_count') ?? 0;

        if ($totalPopulation <= 0) {
            return response()->json([
                'error' => 'Total populasi tidak tersedia'
            ], 400);
        }

        $numerator = $chiSquare * $totalPopulation * $p * (1 - $p);
        $denominator = (pow($d, 2) * ($totalPopulation - 1)) +
            ($chiSquare * $p * (1 - $p));

        $sample = $numerator / $denominator;
        $sample = round($sample); // hasil akan 384

        // Distribusi proporsional
        $distribution = $this->proportionalDistribution(
            $districts,
            $totalPopulation,
            $sample
        );

        return response()->json([
            'method' => 'Krejcie & Morgan',
            'total_population' => $totalPopulation,
            'margin_error' => $d,
            'confidence_level' => '95%',
            'sample_size' => $sample,
            'distribution' => $distribution,
        ]);
    }

    /**
     * DISTRIBUSI PROPORSIONAL PRESISI
     * Total pasti = sample_size
     */
    private function proportionalDistribution($districts, $totalPopulation, $sampleSize)
    {
        $result = [];
        $temp = [];
        $totalAssigned = 0;

        // Hitung nilai desimal awal
        foreach ($districts as $district) {
            $exact = ($district->resident_count / $totalPopulation) * $sampleSize;
            $floor = floor($exact);
            $decimal = $exact - $floor;

            $temp[] = [
                'id' => $district->id,
                'district_name' => $district->district_name,
                'population' => $district->resident_count,
                'sample' => (int) $floor,
                'decimal' => $decimal
            ];

            $totalAssigned += $floor;
        }

        // Hitung sisa
        $remaining = $sampleSize - $totalAssigned;

        // Urutkan berdasarkan decimal terbesar
        usort($temp, function ($a, $b) {
            return $b['decimal'] <=> $a['decimal'];
        });

        // Tambahkan sisa ke decimal terbesar
        for ($i = 0; $i < $remaining; $i++) {
            $temp[$i]['sample'] += 1;
        }

        // Hapus decimal dari output
        foreach ($temp as $row) {
            $result[] = [
                'id' => $row['id'],
                'district_name' => $row['district_name'],
                'population' => $row['population'],
                'sample' => $row['sample']
            ];
        }

        return $result;
    }
}
