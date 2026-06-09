<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('dashboard.organization-profiles.departments.index');
    }

    public function getData()
    {
        if (request()->ajax()) {
            $query = Department::query()
                ->with('modifiedBy');

            return DataTables::of($query)
                ->editColumn('description', function ($data) {
                    $text = strip_tags($data->description);

                    if (Str::length($text) > 50) {
                        return Str::limit($text, 50) .
                            ' <a href="' . route('dashboard.organization-profiles.departments.edit', $data->id) . '" class="indigo font-italic">selengkapnya</a>';
                    }
                    return '<div class="text-wrap">' . $text . '</div>';
                })
                ->addColumn('action', function ($data) {
                    $editRoute = route('dashboard.organization-profiles.departments.edit', $data->id);

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
                ->addColumn('logo', function ($data) {
                    $defaultLogo = 'public/assets/images/placeholder.svg';

                    $logoPath = (!empty($data->logo) && File::exists(public_path($data->logo)))
                        ? 'public/' . $data->logo
                        : $defaultLogo;

                    return '<img src="' . asset($logoPath) . '"
                                style="height:80px; width:80px; object-fit: cover;"
                                class="rounded d-block mx-auto"
                                loading="lazy"
                                alt="' . e($data->department_name) . '">';
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
                ->rawColumns(['action', 'logo', 'history', 'description'])
                ->make(true);
        }
    }

    public function create()
    {
        return view('dashboard.organization-profiles.departments.create');
    }

    public function store(DepartmentRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $logoPath = null;

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');

                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

                $destinationPath = public_path('uploads/images/departments');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $file->move($destinationPath, $filename);

                $logoPath = 'uploads/images/departments/' . $filename;
            }

            Department::create([
                'department_name' => $validated['department_name'],
                'slug'            => $this->generateUniqueSlug($validated['department_name']),
                'description'     => $validated['description'] ?? null,
                'logo'            => $logoPath,
                'modified_by'     => Auth::user()->id,
            ]);

            DB::commit();

            return redirect()
                ->route('dashboard.organization-profiles.departments.index')
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal menyimpan data bidang: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data gagal disimpan.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $data = Department::findOrFail($id);

        return view('dashboard.organization-profiles.departments.edit', compact('data'));
    }

    public function update(DepartmentRequest $request, $id)
    {
        $data = Department::findOrFail($id);

        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $logoPath = $data->logo;
            $destinationPath = public_path('uploads/images/departments');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            if ($request->filled('remove_logo') && $request->remove_logo == 1) {
                if ($logoPath && File::exists(public_path($logoPath))) {
                    File::delete(public_path($logoPath));
                }

                $logoPath = null;
            }

            if ($request->hasFile('logo')) {
                if ($logoPath && File::exists(public_path($logoPath))) {
                    File::delete(public_path($logoPath));
                }

                $file = $request->file('logo');

                $newFilename = Str::uuid() . '.' . $file->getClientOriginalExtension();

                $file->move($destinationPath, $newFilename);

                $logoPath = 'uploads/images/departments/' . $newFilename;
            }

            $data->update([
                'department_name' => $validated['department_name'],
                'slug'            => $this->generateUniqueSlug($validated['department_name'], $data->id),
                'description'     => $validated['description'] ?? null,
                'logo'            => $logoPath,
                'modified_by'     => Auth::user()->id,
            ]);

            DB::commit();

            return redirect()
                ->route('dashboard.organization-profiles.departments.index')
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal memperbarui data bidang: ' . $e->getMessage());

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
            $departments = Department::whereIn('id', $ids)->get();

            foreach ($departments as $data) {
                $logoPath = $data->logo;

                if ($logoPath && File::exists(public_path($logoPath))) {
                    File::delete(public_path($logoPath));
                }
            }

            Department::whereIn('id', $ids)->delete();

            DB::commit();

            return redirect()
                ->route('dashboard.organization-profiles.departments.index')
                ->with('success', 'Data yang dipilih berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal menghapus data bidang: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data yang dipilih gagal dihapus.');
        }
    }

    private function generateUniqueSlug(string $departmentName, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($departmentName) ?: 'department';
        $slug = $baseSlug;
        $counter = 1;

        while (
            Department::where('slug', $slug)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
