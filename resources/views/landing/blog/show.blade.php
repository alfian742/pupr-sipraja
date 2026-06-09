<x-guest-layout>
    @php
        $pageTitle = $article->meta_title ?: $article->title;
        $defaultThumbnail = asset('public/assets/images/placeholder.svg');
        $currentThumbnail = $article->thumbnail ? asset('public/' . $article->thumbnail) : $defaultThumbnail;
    @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <section id="page-section">
        <div class="row g-5">
            {{-- MAIN CONTENT --}}
            <div class="col-lg-8">
                <article class="article-detail">
                    <header class="mb-4">
                        <div class="mb-3">
                            <a href="{{ route('blog.category', $article->category->slug ?? '#') }}"
                                class="badge bg-secondary text-decoration-none">
                                {{ $article->category->name ?? 'Tak Berkategori' }}
                            </a>

                            @if ($article->is_featured)
                                <span class="badge bg-warning text-dark">
                                    Unggulan
                                </span>
                            @endif
                        </div>

                        <h1 class="article-title fw-bold mb-3">
                            {{ $article->title }}
                        </h1>

                        <div class="d-flex flex-wrap text-secondary small mb-4" style="gap: 1rem">
                            <span>
                                <i class="fa fa-calendar-o me-1"></i>
                                <time datetime="{{ optional($article->published_at)->format('Y-m-d') }}">
                                    {{ $article->published_at ? $article->published_at->translatedFormat('d F Y') : '-' }}
                                </time>
                            </span>
                            <span>
                                <i class="fa fa-user-o me-1"></i>
                                {{ $article->author->name ?? 'Admin' }}
                            </span>
                            <span>
                                <i class="fa fa-eye me-1"></i>
                                {{ number_format($article->views_count ?? 0, 0, ',', '.') }} kali dilihat
                            </span>
                        </div>

                        <img src="{{ $currentThumbnail }}" class="img-fluid w-100 rounded shadow-sm"
                            style="max-height: 460px; aspect-ratio: 4/3; object-fit: cover;"
                            alt="{{ $article->title }}">
                    </header>

                    @if ($article->excerpt)
                        <div class="article-excerpt bg-light rounded p-4 mb-4">
                            <p class="mb-0 fst-italic text-secondary">
                                {{ $article->excerpt }}
                            </p>
                        </div>
                    @endif

                    <div class="article-content mb-5">
                        {!! $article->content !!}
                    </div>

                    {{-- ARTICLE NAVIGATION --}}
                    <div class="article-navigation mb-5">
                        <div class="row g-3">
                            <div class="col-md-6">
                                @if (isset($previousArticle) && $previousArticle)
                                    <a href="{{ route('blog.show', $previousArticle->slug) }}"
                                        class="card h-100 border-0 bg-light text-decoration-none shadow-sm">
                                        <div class="card-body p-4">
                                            <small class="text-secondary d-block mb-2">
                                                <i class="fa fa-arrow-left me-1"></i> Artikel Sebelumnya
                                            </small>
                                            <h6 class="mb-0 text-dark fw-bold">
                                                {{ $previousArticle->title }}
                                            </h6>
                                        </div>
                                    </a>
                                @else
                                    <div class="card h-100 border-0 bg-light shadow-sm opacity-75">
                                        <div class="card-body p-4">
                                            <small class="text-secondary d-block mb-2">
                                                <i class="fa fa-arrow-left me-1"></i> Artikel Sebelumnya
                                            </small>
                                            <h6 class="mb-0 text-muted fw-bold">
                                                Tidak tersedia
                                            </h6>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                @if (isset($nextArticle) && $nextArticle)
                                    <a href="{{ route('blog.show', $nextArticle->slug) }}"
                                        class="card h-100 border-0 bg-light text-md-end text-decoration-none shadow-sm">
                                        <div class="card-body p-4">
                                            <small class="text-secondary d-block mb-2">
                                                Artikel Berikutnya <i class="fa fa-arrow-right ms-1"></i>
                                            </small>
                                            <h6 class="mb-0 text-dark fw-bold">
                                                {{ $nextArticle->title }}
                                            </h6>
                                        </div>
                                    </a>
                                @else
                                    <div class="card h-100 border-0 bg-light text-md-end shadow-sm opacity-75">
                                        <div class="card-body p-4">
                                            <small class="text-secondary d-block mb-2">
                                                Artikel Berikutnya <i class="fa fa-arrow-right ms-1"></i>
                                            </small>
                                            <h6 class="mb-0 text-muted fw-bold">
                                                Tidak tersedia
                                            </h6>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- RELATED POSTS --}}
                    <div class="related-posts">
                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap" style="gap: 1rem">
                            <div>
                                <h3 class="fw-bold mb-1">Artikel Terkait</h3>
                                <p class="text-secondary mb-0">
                                    Artikel lain yang relevan berdasarkan kategori yang sama.
                                </p>
                            </div>
                        </div>

                        <div class="row g-4">
                            @forelse ($relatedArticles as $item)
                                <div class="col-md-4">
                                    <article class="card h-100 zoom-hover overflow-hidden rounded border-0 shadow-sm">
                                        <a href="{{ route('blog.show', $item->slug) }}" class="text-decoration-none">
                                            <img src="{{ $item->thumbnail ? asset('public/' . $item->thumbnail) : asset('public/assets/images/placeholder.svg') }}"
                                                class="card-img-top"
                                                style="height: 150px; aspect-ratio: 4/3; object-fit: cover;"
                                                alt="{{ $item->title ?? 'Artikel Terkait' }}">
                                        </a>

                                        <div class="card-body d-flex flex-column p-3">
                                            <h6 class="card-title fw-bold mb-2">
                                                <a href="{{ route('blog.show', $item->slug) }}"
                                                    class="text-dark text-decoration-none">
                                                    {{ $item->title }}
                                                </a>
                                            </h6>

                                            <p class="card-text text-secondary small mb-3">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($item->excerpt ?: $item->content), 90) }}
                                            </p>

                                            <div class="mt-auto">
                                                <a href="{{ route('blog.show', $item->slug) }}"
                                                    class="small text-secondary text-decoration-none">
                                                    Baca Artikel
                                                    <i class="fa fa-arrow-right ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="bg-light rounded p-4 text-center">
                                        <i class="fa fa-newspaper-o fa-3x text-muted mb-3 opacity-50"></i>
                                        <h5 class="text-muted fw-bold mb-1">Artikel Terkait Belum Tersedia</h5>
                                        <p class="text-muted mb-0">
                                            Belum ada artikel relevan lain pada kategori ini.
                                        </p>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        @if ($article->category)
                            <div class="text-center mt-4">
                                <a href="{{ route('blog.category', $article->category->slug) }}"
                                    class="btn btn-outline-secondary rounded-pill">
                                    Lihat Lebih Banyak Artikel Relevan
                                    <i class="fa fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </article>
            </div>

            {{-- SIDEBAR --}}
            <div class="col-lg-4">
                <aside class="blog-sidebar">
                    {{-- Search --}}
                    <div class="sidebar-widget bg-light rounded border-0 p-4 shadow-sm mb-4">
                        <h5 class="fw-bold mb-3">Cari Artikel</h5>

                        <form action="{{ route('blog.index') }}" method="GET">
                            <div class="input-group">
                                <input type="search" name="search" class="form-control" placeholder="Cari artikel..."
                                    value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Featured Posts --}}
                    <div class="sidebar-widget bg-light rounded border-0 p-4 shadow-sm mb-4">
                        <h5 class="fw-bold mb-3">Artikel Unggulan</h5>

                        @forelse ($featuredArticles as $item)
                            <div class="d-flex mb-3 pb-3 border-bottom">
                                <a href="{{ route('blog.show', $item->slug) }}" class="me-3 flex-shrink-0">
                                    <img src="{{ $item->thumbnail ? asset('public/' . $item->thumbnail) : asset('public/assets/images/placeholder.svg') }}"
                                        class="rounded" style="height: 76px; aspect-ratio: 4/3; object-fit: cover;"
                                        alt="{{ $item->title ?? 'Artikel Unggulan' }}">
                                </a>

                                <div>
                                    <h6 class="fw-bold mb-1">
                                        <a href="{{ route('blog.show', $item->slug) }}"
                                            class="text-dark text-decoration-none">
                                            {{ \Illuminate\Support\Str::limit($item->title, 60) }}
                                        </a>
                                    </h6>
                                    <small class="text-secondary">
                                        <i class="fa fa-calendar-o me-1"></i>
                                        {{ $item->published_at ? $item->published_at->translatedFormat('d M Y') : '-' }}
                                    </small>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">
                                Belum ada artikel unggulan.
                            </p>
                        @endforelse
                    </div>

                    {{-- Categories --}}
                    <div class="sidebar-widget bg-light rounded border-0 p-4 shadow-sm">
                        <h5 class="fw-bold mb-3">Kategori Artikel</h5>

                        <div class="list-group list-group-flush">
                            @forelse ($categories as $category)
                                <a href="{{ route('blog.category', $category->slug) }}"
                                    class="list-group-item list-group-item-action bg-transparent d-flex justify-content-between align-items-center px-0">
                                    <span>{{ $category->name }}</span>
                                    <span class="badge bg-secondary rounded-pill">
                                        {{ $category->published_articles_count ?? 0 }}
                                    </span>
                                </a>
                            @empty
                                <p class="text-muted mb-0">
                                    Kategori belum tersedia.
                                </p>
                            @endforelse
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            .article-title {
                font-size: clamp(2rem, 4vw, 3.25rem);
                line-height: 1.15;
            }

            .article-excerpt {
                border-left: 4px solid rgba(0, 0, 0, .15);
            }

            .article-content {
                color: #2f2f2f;
                font-size: 1.05rem;
                line-height: 1.85;
            }

            .article-content h1,
            .article-content h2,
            .article-content h3,
            .article-content h4,
            .article-content h5,
            .article-content h6 {
                color: #1f1f1f;
                font-weight: 700;
                margin-top: 2rem;
                margin-bottom: 1rem;
            }

            .article-content p {
                margin-bottom: 1.25rem;
            }

            .article-content img {
                max-width: 100%;
                height: auto;
                border-radius: .75rem;
                margin: 1.25rem 0;
            }

            .article-content blockquote {
                background: #f8f9fa;
                border-left: 4px solid rgba(0, 0, 0, .15);
                border-radius: .5rem;
                padding: 1rem 1.25rem;
                font-style: italic;
            }

            .article-navigation .card,
            .related-posts .card,
            .sidebar-widget {
                transition: all .25s ease;
            }

            .article-navigation a.card:hover,
            .related-posts .card:hover {
                transform: translateY(-3px);
            }

            .card-title a:hover,
            .sidebar-widget a:hover {
                color: var(--bs-secondary) !important;
            }

            .blog-sidebar {
                position: sticky;
                top: 6.75rem;
            }

            @media (max-width: 991.98px) {
                .blog-sidebar {
                    position: static;
                    margin-top: 2rem;
                }
            }
        </style>
    @endpush

    @push('scripts')
    @endpush
</x-guest-layout>
