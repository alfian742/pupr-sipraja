<?php

namespace App\Http\Controllers;

use App\Http\Requests\HeroCarouselRequest;
use App\Models\HeroCarousel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class HeroCarouselController extends Controller
{
    public function index()
    {
        return view('dashboard.hero-carousels.index');
    }

    public function getData()
    {
        if (request()->ajax()) {
            $query = HeroCarousel::query()
                ->with(['createdBy', 'updatedBy'])
                ->orderBy('sort_order')
                ->orderBy('id');

            return DataTables::of($query)
                ->addColumn('image', function ($data) {
                    $defaultImage = 'assets/images/placeholder.svg';

                    $imagePath = (!empty($data->image_path) && File::exists(public_path($data->image_path)))
                        ? $data->image_path
                        : $defaultImage;

                    return '<img src="' . asset($imagePath) . '"
                                style="height:100px; aspect-ratio: 3 / 4; object-fit: cover;"
                                class="rounded d-block mx-auto"
                                loading="lazy"
                                alt="' . e($data->title) . '">';
                })
                ->editColumn('description', function ($data) {
                    $text = strip_tags($data->description);

                    if (Str::length($text) > 50) {
                        return Str::limit($text, 50) .
                            ' <a href="' . route('dashboard.hero-carousels.edit', $data->id) . '" class="indigo font-italic">selengkapnya</a>';
                    }

                    return '<div class="text-wrap">' . e($text) . '</div>';
                })
                ->editColumn('sort_order', function ($data) {
                    return '<span class="badge badge-secondary">' . $data->sort_order . '</span>';
                })
                ->editColumn('is_active', function ($data) {
                    if ($data->is_active) {
                        return '<span class="badge badge-success">Aktif</span>';
                    }

                    return '<span class="badge badge-danger">Tidak Aktif</span>';
                })
                ->addColumn('history', function ($data) {
                    $createdBy = optional($data->createdBy)->name ?? '-';
                    $updatedBy = optional($data->updatedBy)->name ?? '-';

                    $createdAt = Carbon::parse($data->created_at)
                        ->locale(app()->getLocale())
                        ->translatedFormat('d F Y H:i');

                    $updatedAt = Carbon::parse($data->updated_at)
                        ->locale(app()->getLocale())
                        ->translatedFormat('d F Y H:i');

                    if ($data->created_at->eq($data->updated_at)) {
                        return '<small class="font-italic">Ditambahkan pada: ' . $createdAt . '<br>Oleh: ' . $createdBy . '</small>';
                    }

                    return '<small class="font-italic">Diperbarui pada: ' . $updatedAt . '<br>Oleh: ' . $updatedBy . '</small>';
                })
                ->addColumn('action', function ($data) {
                    $editRoute = route('dashboard.hero-carousels.edit', $data->id);

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
                ->rawColumns(['image', 'description', 'sort_order', 'is_active', 'history', 'action'])
                ->make(true);
        }
    }

    public function create()
    {
        return view('dashboard.hero-carousels.create');
    }

    public function store(HeroCarouselRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $imagePath = null;

            if ($request->hasFile('image_path')) {
                $file = $request->file('image_path');

                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

                $destinationPath = public_path('uploads/images/hero-carousels');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $file->move($destinationPath, $filename);

                $imagePath = 'uploads/images/hero-carousels/' . $filename;
            }

            HeroCarousel::create([
                'title'       => $validated['title'],
                'description' => $validated['description'] ?? null,
                'image_path'  => $imagePath,
                'sort_order'  => $validated['sort_order'] ?? 0,
                'is_active'   => $request->has('is_active'),
                'created_by'  => Auth::id(),
                'updated_by'  => Auth::id(),
            ]);

            DB::commit();

            return redirect()
                ->route('dashboard.hero-carousels.index')
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal menyimpan data hero carousel: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data gagal disimpan.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $data = HeroCarousel::findOrFail($id);

        return view('dashboard.hero-carousels.edit', compact('data'));
    }

    public function update(HeroCarouselRequest $request, $id)
    {
        $data = HeroCarousel::findOrFail($id);

        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $imagePath = $data->image_path;
            $destinationPath = public_path('uploads/images/hero-carousels');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            if ($request->filled('remove_image') && $request->remove_image == 1) {
                if ($imagePath && File::exists(public_path($imagePath))) {
                    File::delete(public_path($imagePath));
                }

                $imagePath = null;
            }

            if ($request->hasFile('image_path')) {
                if ($imagePath && File::exists(public_path($imagePath))) {
                    File::delete(public_path($imagePath));
                }

                $file = $request->file('image_path');

                $newFilename = Str::uuid() . '.' . $file->getClientOriginalExtension();

                $file->move($destinationPath, $newFilename);

                $imagePath = 'uploads/images/hero-carousels/' . $newFilename;
            }

            $data->update([
                'title'       => $validated['title'],
                'description' => $validated['description'] ?? null,
                'image_path'  => $imagePath,
                'sort_order'  => $validated['sort_order'] ?? 0,
                'is_active'   => $request->has('is_active'),
                'updated_by'  => Auth::id(),
            ]);

            DB::commit();

            return redirect()
                ->route('dashboard.hero-carousels.index')
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal memperbarui data hero carousel: ' . $e->getMessage());

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
            $heroCarousels = HeroCarousel::whereIn('id', $ids)->get();

            foreach ($heroCarousels as $data) {
                $imagePath = $data->image_path;

                if ($imagePath && File::exists(public_path($imagePath))) {
                    File::delete(public_path($imagePath));
                }
            }

            HeroCarousel::whereIn('id', $ids)->delete();

            DB::commit();

            return redirect()
                ->route('dashboard.hero-carousels.index')
                ->with('success', 'Data yang dipilih berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal menghapus data hero carousel: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data yang dipilih gagal dihapus.');
        }
    }
}
