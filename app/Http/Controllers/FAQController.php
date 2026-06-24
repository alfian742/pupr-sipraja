<?php

namespace App\Http\Controllers;

use App\Http\Requests\FAQRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Models\FAQ;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FAQController extends Controller
{
    public function index()
    {
        return view('dashboard.other-informations.faqs.index');
    }

    public function getData()
    {
        if (request()->ajax()) {
            $query = FAQ::query();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('faq_question', function ($data) {
                    return '<div class="text-wrap">' . $data->faq_question . '</div>';
                })
                ->editColumn('faq_answer', function ($data) {
                    $text = strip_tags($data->faq_answer);

                    if (Str::length($text) > 50) {
                        return Str::limit($text, 50) .
                            ' <a href="' . route('dashboard.other-informations.faqs.edit', $data->id) . '" class="indigo font-italic">selengkapnya</a>';
                    }
                    return '<div class="text-wrap">' . $text . '</div>';
                })
                ->addColumn('action', function ($data) {
                    // Buat HTML untuk tombol aksi
                    $editRoute = route('dashboard.other-informations.faqs.edit', $data->id);

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
                // Tambahkan kolom lain yang mungkin tidak ada di DB, misal kolom nomor urut jika diperlukan

                ->rawColumns(['action', 'faq_question', 'faq_answer', 'history'])->make(true);
        }
    }

    public function create()
    {
        return view('dashboard.other-informations.faqs.create');
    }

    public function store(FAQRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $validated['modified_by'] = Auth::user()->id;

            FAQ::create($validated);

            DB::commit();

            return redirect()
                ->route('dashboard.other-informations.faqs.index')
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan data FAQ: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data gagal disimpan.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $data = FAQ::findOrFail($id);

        return view('dashboard.other-informations.faqs.edit', compact('data'));
    }

    public function update(FAQRequest $request, $id)
    {
        $data = FAQ::findOrFail($id);

        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $validated['modified_by'] = Auth::user()->id;

            $data->update($validated);

            DB::commit();

            return redirect()
                ->route('dashboard.other-informations.faqs.index')
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui data FAQ: ' . $e->getMessage());

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

            FAQ::whereIn('id', $ids)->delete();

            DB::commit();

            return redirect()
                ->route("dashboard.other-informations.faqs.index")
                ->with('success', 'Data yang dipilih berhasil dihapus.');
        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error('Gagal menghapus data: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data yang dipilih gagal dihapus.');
        }
    }
}
