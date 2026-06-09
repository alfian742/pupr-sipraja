<?php

namespace App\Http\Controllers;

use App\Http\Requests\DownloadCenterRequest;
use App\Models\DownloadCenter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class DownloadCenterController extends Controller
{
    public function index()
    {
        return view('dashboard.other-informations.download-center.index');
    }

    public function getData()
    {
        if (request()->ajax()) {
            $query = DownloadCenter::query()->orderBy('created_at', 'desc')
                ->when(request('date'), function ($q, $v) {
                    $q->whereDate('created_at', $v);
                })
                ->when(
                    request('document_title'),
                    fn($q, $v) =>
                    $q->where('document_title', 'like', "%{$v}%")
                )
                ->when(
                    request('document_type'),
                    fn($q, $v) =>
                    $q->where('document_type', 'like', "%{$v}%")
                )
                ->when(
                    request('status'),
                    fn($q, $v) =>
                    $q->where('status', $v)
                );

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($data) {
                    return $data->created_at ? Carbon::parse($data->created_at)->locale(app()->getLocale())->translatedFormat('d F Y H:i') : '-';
                })
                ->editColumn('document_title', function ($data) {
                    return $data->document_title ? '<div class="text-wrap">' . $data->document_title . '</div>' : '-';
                })
                ->editColumn('document_type', function ($data) {
                    return $data->document_type ? '<div class="text-wrap">' . $data->document_type . '</div>' : '-';
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
                ->editColumn('status', function ($data) {
                    if ($data->status === 'internal') {
                        return '<span class="badge bg-primary px-1">Dokumen Internal</span>';
                    } elseif ($data->status === 'publish') {
                        return '<span class="badge bg-indigo px-1">Terbit</span>';
                    } elseif ($data->status === 'archive') {
                        return '<span class="badge bg-warning px-1">Arsip</span>';
                    } else {
                        return '<span class="badge bg-secondary px-1">Draf</span>';
                    }
                })
                ->addColumn('action', function ($data) {
                    // Buat HTML untuk tombol aksi
                    $editRoute = route('dashboard.other-informations.download-center.edit', $data->id);

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

                ->rawColumns(['document_title', 'document_type', 'document_url', 'action', 'status', 'history'])
                ->make(true);
        }
    }

    public function create()
    {
        return view('dashboard.other-informations.download-center.create');
    }

    public function store(DownloadCenterRequest $request)
    {
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
            $validated['slug'] = Str::slug($validated['document_title']);
            $validated['modified_by'] = Auth::user()->id;
            DownloadCenter::create($validated);

            DB::commit();

            return redirect()
                ->route('dashboard.other-informations.download-center.index')
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan data pusat unduhan: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data gagal disimpan.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $data = DownloadCenter::findOrFail($id);

        return view('dashboard.other-informations.download-center.edit', compact('data'));
    }

    public function update(DownloadCenterRequest $request, $id)
    {
        $data = DownloadCenter::findOrFail($id);

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
            $validated['slug'] = Str::slug($validated['document_title']);
            $validated['modified_by'] = Auth::user()->id;
            $data->update($validated);

            DB::commit();

            return redirect()
                ->route('dashboard.other-informations.download-center.index')
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui data pusat unduhan: ' . $e->getMessage());

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

            DownloadCenter::whereIn('id', $ids)->delete();

            DB::commit();

            return redirect()
                ->route("dashboard.other-informations.download-center.index")
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
