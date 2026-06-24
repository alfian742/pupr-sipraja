<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonnelProfileRequest;
use App\Models\PersonnelProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PersonnelProfileController extends Controller
{
    public function index()
    {
        return view('dashboard.organization-profiles.personnel-profiles.index');
    }

    public function getData()
    {
        if (request()->ajax()) {
            $query = PersonnelProfile::query();

            return DataTables::of($query)
                ->addColumn('action', function ($data) {
                    // Buat HTML untuk tombol aksi
                    $editRoute = route('dashboard.organization-profiles.personnel-profiles.edit', $data->id);

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
                ->addColumn('personnel_photo', function ($data) {
                    $defaultPhoto = 'assets/images/avatar.png';

                    // Periksa apakah foto ada dan file benar-benar ada di server
                    $photoPath = (!empty($data->personnel_photo) && File::exists(public_path($data->personnel_photo)))
                        ? $data->personnel_photo
                        : $defaultPhoto;

                    // Kembalikan elemen img dengan atribut yang rapi
                    return '<img src="' . asset($photoPath) . '" 
                                style="height:80px; aspect-ratio:3/4; object-fit: cover;"
                                class="rounded d-block mx-auto"
                                loading="lazy"
                                alt="' . $data->personnel_name . '">';
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

                ->rawColumns(['action', 'personnel_photo', 'history'])->make(true);
        }
    }

    public function create()
    {
        return view('dashboard.organization-profiles.personnel-profiles.create');
    }

    public function store(PersonnelProfileRequest $request)
    {
        $request->validated();

        DB::beginTransaction();

        try {
            $photoPath = null;

            if ($request->hasFile('personnel_photo')) {

                $file = $request->file('personnel_photo');

                // Generate nama file unik
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

                // Tentukan folder tujuan di public
                $destinationPath = public_path('uploads/images/personnel');

                // Buat folder jika belum ada
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                // Pindahkan file langsung ke public
                $file->move($destinationPath, $filename);

                // Simpan path relatif untuk database
                $photoPath = 'uploads/images/personnel/' . $filename;
            }

            PersonnelProfile::create([
                'personnel_name'        => $request->personnel_name,
                'personnel_position'    => $request->personnel_position,
                'personnel_description' => $request->personnel_description,
                'personnel_photo'       => $photoPath,
                'modified_by'           => Auth::user()->id,
            ]);

            DB::commit();

            return redirect()
                ->route('dashboard.organization-profiles.personnel-profiles.index')
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan data personel: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data gagal disimpan.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $data = PersonnelProfile::findOrFail($id);

        return view('dashboard.organization-profiles.personnel-profiles.edit', compact('data'));
    }

    public function update(PersonnelProfileRequest $request, $id)
    {
        $data = PersonnelProfile::findOrFail($id);

        $request->validated();

        DB::beginTransaction();

        try {

            $photoPath = $data->personnel_photo;
            $destinationPath = public_path('uploads/images/personnel');

            // Pastikan folder ada
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            if ($request->filled('remove_photo') && $request->remove_photo == 1) {

                if ($photoPath && File::exists(public_path($photoPath))) {
                    File::delete(public_path($photoPath));
                }

                $photoPath = null;
            }

            if ($request->hasFile('personnel_photo')) {

                // Hapus foto lama jika ada
                if ($photoPath && File::exists(public_path($photoPath))) {
                    File::delete(public_path($photoPath));
                }

                $file = $request->file('personnel_photo');
                $newFilename = Str::uuid() . '.' . $file->getClientOriginalExtension();

                $file->move($destinationPath, $newFilename);

                $photoPath = 'uploads/images/personnel/' . $newFilename;
            }

            $data->update([
                'personnel_name'        => $request->personnel_name,
                'personnel_position'    => $request->personnel_position,
                'personnel_description' => $request->personnel_description,
                'personnel_photo'       => $photoPath,
                'modified_by'           => Auth::user()->id,
            ]);

            DB::commit();

            return redirect()
                ->route('dashboard.organization-profiles.personnel-profiles.index')
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error('Gagal memperbarui data personel: ' . $e->getMessage());

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

            $personnels = PersonnelProfile::whereIn('id', $ids)->get();

            foreach ($personnels as $data) {

                $photoPath = $data->personnel_photo;

                // Hapus file jika ada
                if ($photoPath && File::exists(public_path($photoPath))) {
                    File::delete(public_path($photoPath));
                }
            }

            PersonnelProfile::whereIn('id', $ids)->delete();

            DB::commit();

            return redirect()
                ->route("dashboard.organization-profiles.personnel-profiles.index")
                ->with('success', 'Data yang dipilih berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal menghapus data personel: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data yang dipilih gagal dihapus.');
        }
    }
}
