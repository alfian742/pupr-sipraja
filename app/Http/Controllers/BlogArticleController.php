<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogArticleRequest;
use App\Models\BlogArticle;
use App\Models\BlogCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BlogArticleController extends Controller
{
    public function index()
    {
        return view('dashboard.blog.articles.index');
    }

    public function getData()
    {
        if (request()->ajax()) {
            $query = BlogArticle::query()
                ->with(['category', 'author', 'creator', 'updater']);

            return DataTables::of($query)
                ->editColumn('title', function ($data) {
                    return '<div class="text-wrap font-weight-bold">' . e($data->title) . '</div>';
                })
                ->editColumn('excerpt', function ($data) {
                    $text = strip_tags($data->excerpt);

                    if (Str::length($text) > 50) {
                        return Str::limit($text, 50) .
                            ' <a href="' . route('dashboard.blog.articles.edit', $data->id) . '" class="indigo font-italic">selengkapnya</a>';
                    }

                    return '<div class="text-wrap">' . ($text ?: '-') . '</div>';
                })
                ->addColumn('thumbnail', function ($data) {
                    $defaultThumbnail = 'assets/images/placeholder.svg';

                    $thumbnailDiskPath = !empty($data->thumbnail)
                        ? Str::replaceStart('storage/', '', Str::replaceStart('uploads/', '', $data->thumbnail))
                        : null;

                    $thumbnailPath = ($thumbnailDiskPath && Storage::disk('public')->exists($thumbnailDiskPath))
                        ? 'storage/' . $thumbnailDiskPath
                        : $defaultThumbnail;

                    return '<img src="' . asset($thumbnailPath) . '"
                                style="height:80px; aspect-ratio: 4 / 3; object-fit: cover;"
                                class="rounded d-block mx-auto"
                                loading="lazy"
                                alt="' . e($data->title) . '">';
                })
                ->addColumn('category_name', function ($data) {
                    return optional($data->category)->name ?? '-';
                })
                ->addColumn('author_name', function ($data) {
                    return optional($data->author)->name ?? '-';
                })
                ->addColumn('status_label', function ($data) {
                    if ($data->status === BlogArticle::STATUS_PUBLISHED) {
                        return '<span class="badge badge-success">Published</span>';
                    }

                    if ($data->status === BlogArticle::STATUS_ARCHIVED) {
                        return '<span class="badge badge-secondary">Archived</span>';
                    }

                    return '<span class="badge badge-warning">Draft</span>';
                })
                ->addColumn('featured_label', function ($data) {
                    if ($data->is_featured) {
                        return '<span class="badge badge-info">Unggulan</span>';
                    }

                    return '<span class="badge badge-secondary">Reguler</span>';
                })
                ->addColumn('published_at_label', function ($data) {
                    if (!$data->published_at) {
                        return '-';
                    }

                    return Carbon::parse($data->published_at)
                        ->locale(app()->getLocale())
                        ->translatedFormat('d F Y H:i');
                })
                ->addColumn('action', function ($data) {
                    $editRoute = route('dashboard.blog.articles.edit', $data->id);

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
                ->rawColumns([
                    'title',
                    'excerpt',
                    'thumbnail',
                    'status_label',
                    'featured_label',
                    'action',
                    'history',
                ])
                ->make(true);
        }
    }

    public function create()
    {
        $categories = BlogCategory::query()
            ->active()
            ->ordered()
            ->get();

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view('dashboard.blog.articles.create', compact('categories', 'users'));
    }

    public function store(BlogArticleRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $thumbnailPath = null;

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');

                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

                $destinationPath = 'images/blog/articles';

                Storage::disk('public')->putFileAs($destinationPath, $file, $filename);

                $thumbnailPath = 'images/blog/articles/' . $filename;
            }

            BlogArticle::create([
                'user_id'          => $validated['user_id'] ?? Auth::user()->id,
                'blog_category_id' => $validated['blog_category_id'] ?? null,
                'created_by'       => Auth::user()->id,
                'updated_by'       => Auth::user()->id,
                'title'            => $validated['title'],
                'slug'             => $this->generateUniqueSlug($validated['title']),
                'excerpt'          => $validated['excerpt'] ?? null,
                'content'          => $validated['content'],
                'thumbnail'        => $thumbnailPath,
                'status'           => $validated['status'],
                'is_featured'      => $request->boolean('is_featured'),
                'views_count'      => 0,
                'published_at'     => $this->resolvePublishedAt($validated['status'], $validated['published_at'] ?? null),
                'meta_title'       => $validated['meta_title'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
                'meta_keywords'    => $validated['meta_keywords'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('dashboard.blog.articles.index')
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal menyimpan data artikel blog: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data gagal disimpan.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $data = BlogArticle::findOrFail($id);

        $categories = BlogCategory::query()
            ->active()
            ->ordered()
            ->get();

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view('dashboard.blog.articles.edit', compact('data', 'categories', 'users'));
    }

    public function update(BlogArticleRequest $request, $id)
    {
        $data = BlogArticle::findOrFail($id);

        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $thumbnailPath = $data->thumbnail;
            $destinationPath = 'images/blog/articles';

            if ($request->filled('remove_thumbnail') && $request->remove_thumbnail == 1) {
                if ($thumbnailPath) {
                    $thumbnailDiskPath = Str::replaceStart('storage/', '', Str::replaceStart('uploads/', '', $thumbnailPath));

                    if (Storage::disk('public')->exists($thumbnailDiskPath)) {
                        Storage::disk('public')->delete($thumbnailDiskPath);
                    }
                }

                $thumbnailPath = null;
            }

            if ($request->hasFile('thumbnail')) {
                if ($thumbnailPath) {
                    $thumbnailDiskPath = Str::replaceStart('storage/', '', Str::replaceStart('uploads/', '', $thumbnailPath));

                    if (Storage::disk('public')->exists($thumbnailDiskPath)) {
                        Storage::disk('public')->delete($thumbnailDiskPath);
                    }
                }

                $file = $request->file('thumbnail');

                $newFilename = Str::uuid() . '.' . $file->getClientOriginalExtension();

                Storage::disk('public')->putFileAs($destinationPath, $file, $newFilename);

                $thumbnailPath = 'images/blog/articles/' . $newFilename;
            }

            $data->update([
                'user_id'          => $validated['user_id'] ?? Auth::user()->id,
                'blog_category_id' => $validated['blog_category_id'] ?? null,
                'updated_by'       => Auth::user()->id,
                'title'            => $validated['title'],
                'slug'             => $this->generateUniqueSlug($validated['title'], $data->id),
                'excerpt'          => $validated['excerpt'] ?? null,
                'content'          => $validated['content'],
                'thumbnail'        => $thumbnailPath,
                'status'           => $validated['status'],
                'is_featured'      => $request->boolean('is_featured'),
                'published_at'     => $this->resolvePublishedAt($validated['status'], $validated['published_at'] ?? null, $data->published_at),
                'meta_title'       => $validated['meta_title'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
                'meta_keywords'    => $validated['meta_keywords'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('dashboard.blog.articles.index')
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal memperbarui data artikel blog: ' . $e->getMessage());

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
            $articles = BlogArticle::whereIn('id', $ids)->get();

            foreach ($articles as $data) {
                $thumbnailPath = $data->thumbnail;

                if ($thumbnailPath) {
                    $thumbnailDiskPath = Str::replaceStart('storage/', '', Str::replaceStart('uploads/', '', $thumbnailPath));

                    if (Storage::disk('public')->exists($thumbnailDiskPath)) {
                        Storage::disk('public')->delete($thumbnailDiskPath);
                    }
                }
            }

            BlogArticle::whereIn('id', $ids)->delete();

            DB::commit();

            return redirect()
                ->route('dashboard.blog.articles.index')
                ->with('success', 'Data yang dipilih berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal menghapus data artikel blog: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data yang dipilih gagal dihapus.');
        }
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title) ?: 'blog-article';
        $slug = $baseSlug;
        $counter = 1;

        while (
            BlogArticle::where('slug', $slug)
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

    private function resolvePublishedAt(string $status, ?string $publishedAt = null, mixed $currentPublishedAt = null): ?Carbon
    {
        if ($status === BlogArticle::STATUS_PUBLISHED) {
            if ($publishedAt) {
                return Carbon::parse($publishedAt);
            }

            return $currentPublishedAt ? Carbon::parse($currentPublishedAt) : now();
        }

        if ($publishedAt) {
            return Carbon::parse($publishedAt);
        }

        return null;
    }
}
