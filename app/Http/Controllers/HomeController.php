<?php

namespace App\Http\Controllers;

use App\Models\BlogArticle;
use App\Models\BlogCategory;
use App\Models\Contact;
use App\Models\Department;
use App\Models\DownloadCenter;
use App\Models\FAQ;
use App\Models\HeroCarousel;
use App\Models\OrganizationProfile;
use App\Models\PersonnelProfile;
use App\Models\PublicInformationPortal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Beranda.
     */
    public function index()
    {
        $departments = Department::take(5)->get();
        $organizationProfile = OrganizationProfile::first();
        $faqs = FAQ::take(5)->get();
        $publicInformationPortals = PublicInformationPortal::all();

        $heroCarousels = HeroCarousel::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $latestArticles = BlogArticle::with(['category', 'author'])
            ->latestPublished()
            ->take(5)
            ->get();

        return view('landing.index', compact(
            'departments',
            'organizationProfile',
            'faqs',
            'publicInformationPortals',
            'heroCarousels',
            'latestArticles'
        ));
    }

    /**
     * Halaman dalam pengembangan.
     */
    public function development()
    {
        return view('landing.development');
    }

    /**
     * Layanan Publik.
     */
    public function publicServices()
    {
        return view('landing.public-services.index');
    }

    /**
     * Struktur Organisasi.
     */
    public function organizationStructure()
    {
        $organizationStructure = OrganizationProfile::first()?->organization_structure;

        return view('landing.organization-profiles.organization-structure', compact('organizationStructure'));
    }

    /**
     * Visi dan Misi.
     */
    public function visionMission()
    {
        $organizationProfile = OrganizationProfile::first();

        return view('landing.organization-profiles.vision-and-mission', compact('organizationProfile'));
    }

    /**
     * Profil Personil.
     */
    public function personnelProfile(Request $request)
    {
        $search = $request->input('search');
        $personnelProfiles = PersonnelProfile::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('personnel_name', 'like', "%{$search}%")
                    ->orWhere('personnel_position', 'like', "%{$search}%")
                    ->orWhere('personnel_description', 'like', "%{$search}%");
            });
        })->paginate(6)->withQueryString();

        return view('landing.organization-profiles.personnel-profiles', compact('personnelProfiles'));
    }

    /**
     * Profil Personil.
     */
    public function faq(Request $request)
    {
        $search = $request->input('search');
        $faqs = FAQ::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('faq_question', 'like', "%{$search}%")
                    ->orWhere('faq_answer', 'like', "%{$search}%");
            });
        })->paginate(10)->withQueryString();

        return view('landing.other-informations.faqs', compact('faqs'));
    }

    /**
     * Pusat Unduhan.
     */
    public function downloadCenterIndex(Request $request)
    {
        $show = (int) ($request->input('show') ?? 10);
        $search = $request->input('search');
        $downloadCenter = DownloadCenter::where('status', 'publish')->orderBy('updated_at', 'desc')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('document_title', 'like', "%{$search}%");
                });
            })->paginate($show)->withQueryString();

        return view('landing.other-informations.download-center.index', compact('downloadCenter'));
    }

    /**
     * Detail Dokumen.
     */
    public function downloadCenterShow($slug)
    {
        $data = DownloadCenter::where('slug', $slug)
            ->where('status', 'publish')
            ->firstOrFail();

        return view('landing.other-informations.download-center.show', compact('data'));
    }

    /**
     * Blog.
     */
    public function blogIndex(Request $request)
    {
        $show = (int) ($request->input('show') ?? 9);
        $search = $request->input('search');

        $articles = BlogArticle::with(['category', 'author'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%")
                        ->orWhere('meta_keywords', 'like', "%{$search}%");
                });
            })
            ->latestPublished()
            ->paginate($show)
            ->withQueryString();

        $categories = BlogCategory::active()
            ->withCount([
                'articles as published_articles_count' => function ($query) {
                    $query->published();
                },
            ])
            ->ordered()
            ->get();

        return view('landing.blog.index', compact('articles', 'categories'));
    }

    /**
     * Kategori Blog.
     */
    public function blogCategory(Request $request, $slug)
    {
        $show = (int) ($request->input('show') ?? 9);
        $search = $request->input('search');

        $category = BlogCategory::active()
            ->where('slug', $slug)
            ->firstOrFail();

        $articles = BlogArticle::with(['category', 'author'])
            ->where('blog_category_id', $category->id)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%")
                        ->orWhere('meta_keywords', 'like', "%{$search}%");
                });
            })
            ->latestPublished()
            ->paginate($show)
            ->withQueryString();

        $categories = BlogCategory::active()
            ->withCount([
                'articles as published_articles_count' => function ($query) {
                    $query->published();
                },
            ])
            ->ordered()
            ->get();

        return view('landing.blog.category', compact('articles', 'categories', 'category'));
    }

    /**
     * Detail Blog.
     */
    public function blogShow($slug)
    {
        $article = BlogArticle::with(['category', 'author'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $article->increment('views_count');

        $categories = BlogCategory::active()
            ->withCount([
                'articles as published_articles_count' => function ($query) {
                    $query->published();
                },
            ])
            ->ordered()
            ->get();

        $previousArticle = BlogArticle::published()
            ->where('published_at', '<', $article->published_at)
            ->latest('published_at')
            ->first();

        $nextArticle = BlogArticle::published()
            ->where('published_at', '>', $article->published_at)
            ->oldest('published_at')
            ->first();

        $relatedArticles = BlogArticle::with(['category', 'author'])
            ->published()
            ->where('id', '!=', $article->id)
            ->where('blog_category_id', $article->blog_category_id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        $featuredArticles = BlogArticle::with(['category', 'author'])
            ->published()
            ->featured()
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->limit(5)
            ->get();

        return view('landing.blog.show', compact(
            'article',
            'categories',
            'previousArticle',
            'nextArticle',
            'relatedArticles',
            'featuredArticles'
        ));
    }

    /**
     * Kontak dan Pengaduan.
     */
    public function contact()
    {
        // Menggunakan cache untuk menyimpan data kontak agar tidak perlu query ke database setiap kali halaman diakses
        $contact = Cache::rememberForever('contact', function () {
            return Contact::first();
        });

        return view('landing.other-informations.contact', compact('contact'));
    }
}
