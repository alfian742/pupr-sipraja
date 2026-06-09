<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogCategoryRequest;
use App\Models\BlogCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BlogCategoryController extends Controller
{
    public function index()
    {
        return view('dashboard.blog.categories.index');
    }

    public function getData()
    {
        if (request()->ajax()) {
            $query = BlogCategory::query()
                ->with(['creator', 'updater'])
                ->withCount('articles');

            return DataTables::of($query)
                ->editColumn('description', function ($data) {
                    $text = strip_tags($data->description);

                    if (Str::length($text) > 50) {
                        return Str::limit($text, 50) .
                            ' <a href="' . route('dashboard.blog.categories.edit', $data->id) . '" class="indigo font-italic">selengkapnya</a>';
                    }

                    return '<div class="text-wrap">' . ($text ?: '-') . '</div>';
                })
                ->addColumn('status', function ($data) {
                    if ($data->is_active) {
                        return '<span class="badge badge-success">Aktif</span>';
                    }

                    return '<span class="badge badge-secondary">Tidak Aktif</span>';
                })
                ->addColumn('articles_count_label', function ($data) {
                    return '<span class="badge badge-info">' . $data->articles_count . ' Artikel</span>';
                })
                ->addColumn('action', function ($data) {
                    $editRoute = route('dashboard.blog.categories.edit', $data->id);

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
                    $createdBy = optional($data->creator)->name ?? '-';
                    $updatedBy = optional($data->updater)->name ?? '-';

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
                ->rawColumns(['action', 'history', 'description', 'status', 'articles_count_label'])
                ->make(true);
        }
    }

    public function create()
    {
        return view('dashboard.blog.categories.create');
    }

    public function store(BlogCategoryRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            BlogCategory::create([
                'created_by'  => Auth::user()->id,
                'updated_by'  => Auth::user()->id,
                'name'        => $validated['name'],
                'slug'        => $this->generateUniqueSlug($validated['name']),
                'description' => $validated['description'] ?? null,
                'is_active'   => $request->boolean('is_active'),
                'sort_order'  => $validated['sort_order'] ?? 0,
            ]);

            DB::commit();

            return redirect()
                ->route('dashboard.blog.categories.index')
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal menyimpan data kategori blog: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data gagal disimpan.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $data = BlogCategory::findOrFail($id);

        return view('dashboard.blog.categories.edit', compact('data'));
    }

    public function update(BlogCategoryRequest $request, $id)
    {
        $data = BlogCategory::findOrFail($id);

        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $data->update([
                'updated_by'  => Auth::user()->id,
                'name'        => $validated['name'],
                'slug'        => $this->generateUniqueSlug($validated['name'], $data->id),
                'description' => $validated['description'] ?? null,
                'is_active'   => $request->boolean('is_active'),
                'sort_order'  => $validated['sort_order'] ?? 0,
            ]);

            DB::commit();

            return redirect()
                ->route('dashboard.blog.categories.index')
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal memperbarui data kategori blog: ' . $e->getMessage());

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
            BlogCategory::whereIn('id', $ids)->delete();

            DB::commit();

            return redirect()
                ->route('dashboard.blog.categories.index')
                ->with('success', 'Data yang dipilih berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal menghapus data kategori blog: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data yang dipilih gagal dihapus.');
        }
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name) ?: 'blog-category';
        $slug = $baseSlug;
        $counter = 1;

        while (
            BlogCategory::where('slug', $slug)
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
