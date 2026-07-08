<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicInformationPortalRequest;
use App\Models\PublicInformationPortal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PublicInformationPortalController extends Controller
{
    public function index()
    {
        return view('dashboard.other-informations.public-information-portals.index');
    }

    public function getData()
    {
        if (request()->ajax()) {
            $query = PublicInformationPortal::query()
                ->with('modifiedBy');

            return DataTables::of($query)
                ->editColumn('description', function ($data) {
                    $text = strip_tags($data->description);

                    if (Str::length($text) > 50) {
                        return e(Str::limit($text, 50)) .
                            ' <a href="' . route('dashboard.other-informations.public-information-portals.edit', $data->id) . '" class="indigo font-italic">selengkapnya</a>';
                    }

                    return '<div class="text-wrap">' . e($text) . '</div>';
                })
                ->addColumn('action', function ($data) {
                    $editRoute = route('dashboard.other-informations.public-information-portals.edit', $data->id);

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
                ->addColumn('website_url', function ($data) {
                    if (empty($data->website_url)) {
                        return '-';
                    }

                    return '<a href="' . e($data->website_url) . '"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-sm btn-info"
                                title="Buka Website OPD">
                                <i class="fa fa-external-link"></i> Website
                            </a>';
                })
                ->addColumn('logo', function ($data) {
                    $defaultLogo = 'assets/images/logo-loteng-square.png';

                    $logoDiskPath = !empty($data->logo)
                        ? Str::replaceStart('storage/', '', Str::replaceStart('uploads/', '', $data->logo))
                        : null;

                    $logoPath = ($logoDiskPath && Storage::disk('public')->exists($logoDiskPath))
                        ? 'storage/' . $logoDiskPath
                        : $defaultLogo;

                    return '<img src="' . asset($logoPath) . '"
                                style="height:80px; width:80px; object-fit: cover;"
                                class="rounded d-block mx-auto"
                                loading="lazy"
                                alt="' . e($data->portal_name) . '">';
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
                        return '<small class="font-italic">Ditambahkan pada: ' . $createdAt . '<br>Oleh: ' . e($user) . '</small>';
                    }

                    return '<small class="font-italic">Diperbarui pada: ' . $updatedAt . '<br>Oleh: ' . e($user) . '</small>';
                })
                ->rawColumns(['action', 'logo', 'history', 'description', 'website_url'])
                ->make(true);
        }
    }

    public function create()
    {
        return view('dashboard.other-informations.public-information-portals.create');
    }

    public function store(PublicInformationPortalRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $logoPath = null;

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');

                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

                $destinationPath = 'images/public-information-portals';

                Storage::disk('public')->putFileAs($destinationPath, $file, $filename);

                $logoPath = 'images/public-information-portals/' . $filename;
            }

            PublicInformationPortal::create([
                'portal_name' => $validated['portal_name'],
                'slug'              => $this->generateUniqueSlug($validated['portal_name']),
                'description'       => $validated['description'] ?? null,
                'website_url'       => $validated['website_url'] ?? null,
                'logo'              => $logoPath,
                'modified_by'       => Auth::id(),
            ]);

            DB::commit();

            return redirect()
                ->route('dashboard.other-informations.public-information-portals.index')
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal menyimpan data OPD: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data gagal disimpan.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $data = PublicInformationPortal::findOrFail($id);

        return view('dashboard.other-informations.public-information-portals.edit', compact('data'));
    }

    public function update(PublicInformationPortalRequest $request, $id)
    {
        $data = PublicInformationPortal::findOrFail($id);

        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $logoPath = $data->logo;
            $destinationPath = 'images/public-information-portals';

            if ($request->filled('remove_logo') && $request->remove_logo == 1) {
                if ($logoPath) {
                    $logoDiskPath = Str::replaceStart('storage/', '', Str::replaceStart('uploads/', '', $logoPath));

                    if (Storage::disk('public')->exists($logoDiskPath)) {
                        Storage::disk('public')->delete($logoDiskPath);
                    }
                }

                $logoPath = null;
            }

            if ($request->hasFile('logo')) {
                if ($logoPath) {
                    $logoDiskPath = Str::replaceStart('storage/', '', Str::replaceStart('uploads/', '', $logoPath));

                    if (Storage::disk('public')->exists($logoDiskPath)) {
                        Storage::disk('public')->delete($logoDiskPath);
                    }
                }

                $file = $request->file('logo');

                $newFilename = Str::uuid() . '.' . $file->getClientOriginalExtension();

                Storage::disk('public')->putFileAs($destinationPath, $file, $newFilename);

                $logoPath = 'images/public-information-portals/' . $newFilename;
            }

            $data->update([
                'portal_name' => $validated['portal_name'],
                'slug'              => $this->generateUniqueSlug($validated['portal_name'], $data->id),
                'description'       => $validated['description'] ?? null,
                'website_url'       => $validated['website_url'] ?? null,
                'logo'              => $logoPath,
                'modified_by'       => Auth::id(),
            ]);

            DB::commit();

            return redirect()
                ->route('dashboard.other-informations.public-information-portals.index')
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal memperbarui data OPD: ' . $e->getMessage());

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
            $organizations = PublicInformationPortal::whereIn('id', $ids)->get();

            foreach ($organizations as $data) {
                $logoPath = $data->logo;

                if ($logoPath) {
                    $logoDiskPath = Str::replaceStart('storage/', '', Str::replaceStart('uploads/', '', $logoPath));

                    if (Storage::disk('public')->exists($logoDiskPath)) {
                        Storage::disk('public')->delete($logoDiskPath);
                    }
                }
            }

            PublicInformationPortal::whereIn('id', $ids)->delete();

            DB::commit();

            return redirect()
                ->route('dashboard.other-informations.public-information-portals.index')
                ->with('success', 'Data yang dipilih berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal menghapus data OPD: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data yang dipilih gagal dihapus.');
        }
    }

    private function generateUniqueSlug(string $organizationName, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($organizationName) ?: 'opd';
        $slug = $baseSlug;
        $counter = 1;

        while (
            PublicInformationPortal::where('slug', $slug)
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
