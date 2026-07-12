<?php

namespace App\Http\Controllers\IkliSurvey;

use App\Http\Controllers\Controller;
use App\Imports\QuestionnaireQuestionImport;
use App\Models\QuestionnaireQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;
use Yajra\DataTables\Facades\DataTables;

class QuestionnaireQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('ikli-survey.dashboard.questionnaire.question.index');
    }

    /**
     *  get data for DataTables  
     */

    public function getData()
    {
        if (request()->ajax()) {
            $query = QuestionnaireQuestion::query();

            return DataTables::of($query)
                ->addIndexColumn()
                ->make(true);
        }
    }

    /**
     *  Get template for importing questionnaire questions 
     */
    public function getTemplate()
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

        $filename = 'Templat_Formulir_Kuesioner_IKLI.xlsx'; // Nama file

        $path = Storage::disk('templates')->path('documents/' . $filename);

        // Jika file tidak ditemukan, tampilkan error 404
        if (!file_exists($path)) {
            abort(404);
        }

        // Unduh file dengan nama aslinya
        return response()->download($path, $filename);
    }

    /**
     *  Import questionnaire questions from excel file  
     */
    public function import(Request $request)
    {
        // --- 1️⃣ Validasi file upload ---
        $request->validate(
            [
                'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            ],
            [
                'file.required' => 'Berkas wajib diunggah.',
                'file.mimes'    => 'Format berkas tidak valid. Harap unggah berkas dengan format .xlsx, .xls, atau .csv.',
                'file.max'      => 'Ukuran berkas terlalu besar. Maksimum 10 MB.',
            ]
        );

        // --- 2️⃣ Nonaktifkan timeout dan batasi memory ---
        ini_set('max_execution_time', 600); // 10 menit
        ini_set('memory_limit', '1024M');

        DB::beginTransaction();

        try {
            // --- 3️⃣ Hapus data lama dan jalankan proses import ---
            QuestionnaireQuestion::query()->delete();
            Excel::import(new QuestionnaireQuestionImport, $request->file('file'));

            DB::commit();

            // --- 4️⃣ Respon sukses ---
            return redirect()
                ->route('ikli-survey.dashboard.questionnaire.question.index')
                ->with('success', 'Pertanyaan berhasil diimpor ke sistem.');
        } catch (ExcelValidationException $e) {
            DB::rollBack();

            // Tangkap error validasi baris Excel
            $failures = $e->failures();
            $messages = collect($failures)->map(function ($failure) {
                return "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            })->take(5)->join('; ');

            Log::warning('Impor gagal karena data tidak valid.', [
                'failures' => $messages,
            ]);

            return redirect()
                ->route('ikli-survey.dashboard.questionnaire.question.index')
                ->with('error', 'Impor gagal karena data tidak valid.');
        } catch (\Throwable $e) {
            DB::rollBack();

            // --- 5️⃣ Logging error teknis ---
            Log::error('Gagal mengimpor data.', [
                'error' => $e->getMessage(),
                'file'  => $request->file('file')->getClientOriginalName(),
                'trace' => $e->getTraceAsString(),
            ]);

            // --- 6️⃣ Respon error user-friendly ---
            return redirect()
                ->route('ikli-survey.dashboard.questionnaire.question.index')
                ->with('error', 'Gagal mengimpor data. Pastikan format dan struktur berkas sesuai templat yang ditetapkan.');
        }
    }
}
