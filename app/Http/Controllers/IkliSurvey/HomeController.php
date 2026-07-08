<?php

namespace App\Http\Controllers\IkliSurvey;

use App\Http\Controllers\Controller;
use App\Models\InternetNetwork;
use App\Models\Irrigation;
use App\Models\PowerGrid;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireRespondent;
use App\Models\Road;
use App\Models\TransportationTerminal;
use App\Models\WasteManagementSystem;
use App\Models\WastewaterManagementSystem;
use App\Models\WaterSupplySystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    protected function respondentRules(): array
    {
        return [
            'gender'            => ['required', 'string', 'max:10'],
            'age'               => ['required', 'string', 'max:50'],
            'education'         => ['required', 'string', 'max:100'],
            'occupation'        => ['required', 'string', 'max:100'],
            'disability_status' => ['required', 'string', 'max:20'],
            'district'          => ['required', 'string', 'max:50'],
            'village'           => ['required', 'string', 'max:50'],
            'address'           => ['nullable', 'string'],
        ];
    }

    protected function respondentMessages(): array
    {
        return [
            'gender.required'            => 'Jenis kelamin wajib dipilih.',
            'gender.string'              => 'Jenis kelamin tidak valid.',
            'gender.max'                 => 'Jenis kelamin maksimal 10 karakter.',

            'age.required'               => 'Usia wajib dipilih.',
            'age.string'                 => 'Usia tidak valid.',
            'age.max'                    => 'Usia maksimal 50 karakter.',

            'education.required'         => 'Pendidikan terakhir wajib dipilih.',
            'education.string'           => 'Pendidikan terakhir tidak valid.',
            'education.max'              => 'Pendidikan terakhir maksimal 100 karakter.',

            'occupation.required'        => 'Pekerjaan wajib dipilih.',
            'occupation.string'          => 'Pekerjaan tidak valid.',
            'occupation.max'             => 'Pekerjaan maksimal 100 karakter.',

            'disability_status.required' => 'Disabilitas/Non-Disabilitas wajib dipilih.',
            'disability_status.string'   => 'Status disabilitas tidak valid.',
            'disability_status.max'      => 'Status disabilitas maksimal 20 karakter.',

            'district.required'          => 'Kecamatan wajib dipilih.',
            'district.string'            => 'Kecamatan tidak valid.',
            'district.max'               => 'Kecamatan maksimal 50 karakter.',

            'village.required'           => 'Kelurahan/Desa wajib dipilih.',
            'village.string'             => 'Kelurahan/Desa tidak valid.',
            'village.max'                => 'Kelurahan/Desa maksimal 50 karakter.',

            'address.string'             => 'Alamat tidak valid.',
        ];
    }

    protected function infrastructureTables(): array
    {
        return [
            (new InternetNetwork())->getTable(),
            (new Irrigation())->getTable(),
            (new PowerGrid())->getTable(),
            (new Road())->getTable(),
            (new TransportationTerminal())->getTable(),
            (new WasteManagementSystem())->getTable(),
            (new WastewaterManagementSystem())->getTable(),
            (new WaterSupplySystem())->getTable(),
        ];
    }

    protected function surveyIndicators(): array
    {
        return [
            'physical-availability' => [
                'label' => 'KETERSEDIAAN FISIK',
                'column' => 'physical_availability_score',
                'next' => 'quality',
                'prev' => null,
            ],
            'quality' => [
                'label' => 'KUALITAS',
                'column' => 'quality_score',
                'next' => 'suitability',
                'prev' => 'physical-availability',
            ],
            'suitability' => [
                'label' => 'KESESUAIAN',
                'column' => 'suitability_score',
                'next' => 'utilization',
                'prev' => 'quality',
            ],
            'utilization' => [
                'label' => 'PEMANFAATAN',
                'column' => 'utilization_score',
                'next' => 'expectation',
                'prev' => 'suitability',
            ],
            'expectation' => [
                'label' => 'HARAPAN',
                'column' => 'expectation_score',
                'next' => null,
                'prev' => 'utilization',
            ],
        ];
    }

    protected function infrastructureMap(): array
    {
        return [
            'transportation_terminal' => [
                'label' => 'Prasarana Terminal',
                'model' => TransportationTerminal::class,
            ],
            'road' => [
                'label' => 'Jaringan Jalan',
                'model' => Road::class,
            ],
            'irrigation' => [
                'label' => 'Jaringan Irigasi',
                'model' => Irrigation::class,
            ],
            'water_supply_system' => [
                'label' => 'Prasarana Air Minum',
                'model' => WaterSupplySystem::class,
            ],
            'wastewater_management_system' => [
                'label' => 'Prasarana Air Limbah',
                'model' => WastewaterManagementSystem::class,
            ],
            'waste_management_system' => [
                'label' => 'Prasarana Persampahan',
                'model' => WasteManagementSystem::class,
            ],
            'power_grid' => [
                'label' => 'Jaringan Listrik',
                'model' => PowerGrid::class,
            ],
            'internet_network' => [
                'label' => 'Jaringan Telekomunikasi/Internet',
                'model' => InternetNetwork::class,
            ],
        ];
    }

    protected function infrastructureOrder(): array
    {
        return [
            'Prasarana Terminal',
            'Jaringan Jalan',
            'Jaringan Irigasi',
            'Prasarana Air Minum',
            'Prasarana Air Limbah',
            'Prasarana Persampahan',
            'Jaringan Listrik',
            'Jaringan Telekomunikasi/Internet',
        ];
    }

    protected function normalizeInfrastructure(string $value): ?string
    {
        return match (trim($value)) {
            'Prasarana Terminal' => 'transportation_terminal',
            'Jaringan Jalan' => 'road',
            'Jaringan Irigasi' => 'irrigation',
            'Prasarana Air Minum' => 'water_supply_system',
            'Prasarana Air Limbah' => 'wastewater_management_system',
            'Prasarana Persampahan' => 'waste_management_system',
            'Jaringan Listrik' => 'power_grid',
            'Jaringan Telekomunikasi/Internet' => 'internet_network',
            default => null,
        };
    }

    public function index()
    {
        // Cek App/Http/Middleware/EnsureQuestionnaireIsActive.php
        $questionnaireActive = false;

        $page = $questionnaireActive
            ? 'ikli-survey.home'
            : 'ikli-survey.close';

        return view($page);
    }

    public function finish()
    {
        return view('ikli-survey.finish');
    }

    // ============================================================

    public function surveyCreate()
    {
        return view('ikli-survey.survey.create');
    }

    public function surveyStore(Request $request)
    {
        if (config('services.recaptcha.enabled')) {
            // 1. Validasi token captcha ada
            $request->validate([
                'g-recaptcha-response' => ['required'],
            ], [
                'g-recaptcha-response.required' => 'Verifikasi captcha gagal.',
            ]);

            // 2. Verifikasi captcha ke Google
            $recaptchaResponse = Http::asForm()
                ->connectTimeout(5)
                ->timeout(10)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret'   => config('services.recaptcha.secret_key'),
                    'response' => $request->input('g-recaptcha-response'),
                    'remoteip' => $request->ip(),
                ]);

            if (!$recaptchaResponse->ok()) {
                return back()
                    ->withErrors(['captcha' => 'Layanan verifikasi captcha sedang bermasalah.'])
                    ->withInput();
            }

            $recaptchaData = $recaptchaResponse->json();

            // 3. Cek hasil verifikasi
            if (
                !($recaptchaData['success'] ?? false) ||
                ($recaptchaData['score'] ?? 0) < 0.5 ||
                ($recaptchaData['action'] ?? '') !== 'survey'
            ) {
                return back()
                    ->withErrors(['captcha' => 'Aktivitas terdeteksi tidak valid (bot).'])
                    ->withInput();
            }
        }

        // 4. Validasi input responden
        $validated = $request->validate(
            $this->respondentRules(),
            $this->respondentMessages()
        );

        $uuid = (string) Str::uuid();
        $now  = now();

        try {
            DB::transaction(function () use ($validated, $uuid, $now) {
                $respondentId = DB::table((new QuestionnaireRespondent())->getTable())
                    ->insertGetId(array_merge($validated, [
                        'uuid'        => $uuid,
                        'survey_date' => $now,
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ]));

                foreach ($this->infrastructureTables() as $table) {
                    DB::table($table)->insert([
                        'respondent_id' => $respondentId,
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ]);
                }
            }, 3);

            return redirect()->route('ikli-survey.questionnaire.physical-availability.edit', $uuid);
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.'])
                ->withInput();
        }
    }

    public function surveyEdit($uuid)
    {
        $routeList = (object) [
            'destroy' => route('ikli-survey.survey.destroy', $uuid),
        ];

        $data = QuestionnaireRespondent::query()
            ->select([
                'id',
                'uuid',
                'survey_date',
                'gender',
                'age',
                'education',
                'occupation',
                'disability_status',
                'district',
                'village',
                'address',
            ])
            ->where('uuid', $uuid)
            ->firstOrFail();

        return view('ikli-survey.survey.edit', compact('data', 'routeList'));
    }

    public function surveyUpdate(Request $request, $uuid)
    {
        $validated = $request->validate(
            $this->respondentRules(),
            $this->respondentMessages()
        );

        try {
            $validated['occupation'] = strtoupper($validated['occupation']);

            $updated = QuestionnaireRespondent::query()
                ->where('uuid', $uuid)
                ->update(array_merge($validated, [
                    'updated_at' => now(),
                ]));

            if (!$updated) {
                abort(404, 'Data responden tidak ditemukan.');
            }

            return redirect()
                ->route('ikli-survey.questionnaire.physical-availability.edit', $uuid)
                ->with('success', 'Identitas Responden berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Gagal memperbarui responden.', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, responden gagal diperbarui.')
                ->withInput();
        }
    }

    public function surveyDestroy($uuid)
    {
        try {
            $deleted = QuestionnaireRespondent::query()
                ->where('uuid', $uuid)
                ->delete();

            if (!$deleted) {
                abort(404, 'Data responden tidak ditemukan.');
            }

            return redirect()
                ->route('ikli-survey.home')
                ->with('success', 'Survei dibatalkan.');
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus data survei.', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, silakan coba lagi.');
        }
    }

    public function surveyQuestionnaireEdit(Request $request, $uuid, $surveyIndicator)
    {
        $indicators = $this->surveyIndicators();

        if (!isset($indicators[$surveyIndicator])) {
            abort(404, 'Indikator tidak ditemukan.');
        }

        $currentIndicator = $indicators[$surveyIndicator];
        $scoreColumn = $currentIndicator['column'];

        $respondent = QuestionnaireRespondent::query()
            ->select('id', 'uuid', 'priority_infrastructure', 'priority_improvement')
            ->where('uuid', $uuid)
            ->firstOrFail();

        $order = $this->infrastructureOrder();

        $questions = QuestionnaireQuestion::query()
            ->select([
                'id',
                'survey_indicator',
                'infrastructure_type',
                'description',
                'option_1',
                'option_2',
                'option_3',
                'option_4',
            ])
            ->where('survey_indicator', $currentIndicator['label'])
            ->orderByRaw(
                "FIELD(TRIM(infrastructure_type), " . implode(', ', array_fill(0, count($order), '?')) . ")",
                $order
            )
            ->get()
            ->map(function ($item) {
                $item->infrastructure_type = trim($item->infrastructure_type);
                $item->infra_key = $this->normalizeInfrastructure($item->infrastructure_type);

                return $item;
            });

        $infrastructureMap = $this->infrastructureMap();

        // fixed 8 query, bukan N+1 dinamis
        $answers = [];
        foreach ($infrastructureMap as $infraKey => $config) {
            $answers[$infraKey] = $config['model']::query()
                ->where('respondent_id', $respondent->id)
                ->value($scoreColumn);
        }

        $routeList = (object) [
            'update' => route("ikli-survey.questionnaire.{$surveyIndicator}.update", $uuid),
            'prev'   => $currentIndicator['prev']
                ? route("ikli-survey.questionnaire.{$currentIndicator['prev']}.edit", $uuid)
                : route('ikli-survey.survey.edit', $uuid),
            'next'   => $currentIndicator['next']
                ? route("ikli-survey.questionnaire.{$currentIndicator['next']}.edit", $uuid)
                : null,
        ];

        return view('ikli-survey.survey.questionnaire', compact(
            'respondent',
            'questions',
            'answers',
            'routeList',
            'surveyIndicator',
            'currentIndicator'
        ));
    }

    public function surveyQuestionnaireUpdate(Request $request, $uuid, $surveyIndicator)
    {
        $indicators = $this->surveyIndicators();

        if (!isset($indicators[$surveyIndicator])) {
            abort(404, 'Indikator tidak ditemukan.');
        }

        $currentIndicator   = $indicators[$surveyIndicator];
        $scoreColumn        = $currentIndicator['column'];
        $infrastructureMap  = $this->infrastructureMap();

        $respondent = QuestionnaireRespondent::query()
            ->select('id', 'uuid')
            ->where('uuid', $uuid)
            ->firstOrFail();

        $rules = [
            'answers' => ['required', 'array'],
        ];

        foreach (array_keys($infrastructureMap) as $infraKey) {
            $rules["answers.{$infraKey}"] = ['required', 'integer', 'between:1,4'];
        }

        $messages = [
            'answers.required'   => 'Jawaban wajib diisi.',
            'answers.*.required' => 'Semua infrastruktur wajib dijawab.',
            'answers.*.integer'  => 'Nilai jawaban harus berupa angka.',
            'answers.*.between'  => 'Nilai jawaban harus antara 1 sampai 4.',
        ];

        if ($surveyIndicator == 'expectation') {
            $rules['priority_infrastructure'] = ['required', 'string', 'max:50'];
            $rules['priority_improvement']    = ['required', 'string', 'max:100'];

            $messages['priority_infrastructure.required'] = 'Infrastruktur wajib dipilih.';
            $messages['priority_infrastructure.string']   = 'Infrastruktur tidak valid.';
            $messages['priority_infrastructure.max']      = 'Infrastruktur maksimal 50 karakter.';

            $messages['priority_improvement.required'] = 'Aspek yang perlu ditingkatkan wajib dipilih.';
            $messages['priority_improvement.string']   = 'Aspek yang perlu ditingkatkan tidak valid.';
            $messages['priority_improvement.max']      = 'Aspek yang perlu ditingkatkan maksimal 100 karakter.';
        }

        $validated = $request->validate($rules, $messages);

        $answers = $validated['answers'];

        // Resolve nama tabel sekali saja agar tidak instantiate model berulang di dalam transaction
        $infrastructureTables = [];
        foreach ($infrastructureMap as $infraKey => $config) {
            $modelClass = $config['model'];
            $infrastructureTables[$infraKey] = (new $modelClass)->getTable();
        }

        DB::transaction(function () use (
            $validated,
            $answers,
            $respondent,
            $scoreColumn,
            $surveyIndicator,
            $infrastructureTables
        ) {
            foreach ($infrastructureTables as $infraKey => $table) {
                DB::table($table)
                    ->where('respondent_id', $respondent->id)
                    ->update([
                        $scoreColumn => (int) $answers[$infraKey],
                    ]);
            }

            if ($surveyIndicator == 'expectation') {
                DB::table((new QuestionnaireRespondent())->getTable())
                    ->where('id', $respondent->id)
                    ->update([
                        'priority_infrastructure' => $validated['priority_infrastructure'],
                        'priority_improvement'    => strtoupper($validated['priority_improvement']),
                    ]);
            }
        });

        if ($currentIndicator['next']) {
            return redirect()
                ->route("ikli-survey.questionnaire.{$currentIndicator['next']}.edit", $uuid)
                ->with('success', 'Jawaban berhasil disimpan.');
        }

        // return redirect()
        //     ->route('ikli-survey.home')
        //     ->with('success', 'Terima kasih atas partisipasi Anda dalam pengisian survei.');

        return redirect()
            ->route('ikli-survey.finish')
            ->with('success', 'Jawaban berhasil disimpan.');
    }
}
