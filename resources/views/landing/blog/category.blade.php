<x-guest-layout>
    @php $pageTitle = 'Kategori: ' . $category->name; @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <section id="page-section">
        <div class="row justify-content-center">
            <div class="col-lg-7 mb-5 text-center">
                <h2 class="fw-bold mb-3">{{ $category->name }}</h2>

                <p class="text-dark">
                    {{ $category->description ?: 'Temukan artikel yang termasuk dalam kategori ' . $category->name . ' pada ' . config('app.subname', 'Laravel') . '.' }}
                </p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-7">
                <form action="{{ route('blog.category', $category->slug) }}" method="GET">
                    <div class="input-group input-group-lg">
                        <input type="search" name="search" class="form-control rounded-start"
                            placeholder="Cari artikel pada kategori ini..." value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary rounded-end" type="submit">
                            <i class="fa fa-search me-2"></i> Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if (isset($categories) && $categories->count())
            <div class="row justify-content-center mt-4">
                <div class="col-lg-10">
                    <div class="d-flex justify-content-center flex-wrap" style="gap: .75rem">
                        <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary rounded-pill">
                            Semua Artikel
                        </a>

                        @foreach ($categories as $item)
                            <a href="{{ route('blog.category', $item->slug) }}"
                                class="btn {{ $category->id === $item->id ? 'btn-secondary' : 'btn-outline-secondary' }} rounded-pill">
                                {{ $item->name }}
                                <span class="badge bg-light text-dark ms-1">
                                    {{ $item->published_articles_count ?? 0 }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="row justify-content-center g-4 mb-5 mt-3">
            @forelse ($articles as $item)
                <div class="col-md-6 col-lg-4">
                    <article class="card h-100 zoom-hover overflow-hidden rounded border-0 shadow-sm">
                        <a href="{{ route('blog.show', $item->slug) }}" class="text-decoration-none">
                            <img src="{{ $item->thumbnail ? asset('storage/' . \Illuminate\Support\Str::replaceStart('storage/', '', \Illuminate\Support\Str::replaceStart('uploads/', '', $item->thumbnail))) : asset('assets/images/placeholder.svg') }}"
                                class="card-img-top" style="aspect-ratio: 4/3; object-fit: cover;"
                                alt="{{ $item->title ?? 'Artikel' }}">
                        </a>

                        <div class="card-body d-flex flex-column p-4">
                            <div class="mb-3">
                                <span class="badge bg-secondary">
                                    {{ $item->category->name ?? 'Tak Berkategori' }}
                                </span>

                                @if ($item->is_featured)
                                    <span class="badge bg-secondary text-white">
                                        Unggulan
                                    </span>
                                @endif
                            </div>

                            <h4 class="card-title mb-3">
                                <a href="{{ route('blog.show', $item->slug) }}" class="text-dark text-decoration-none">
                                    {{ $item->title ?? '' }}
                                </a>
                            </h4>

                            <div class="d-flex flex-wrap text-secondary small mb-3" style="gap: .75rem">
                                <span>
                                    <i class="fa fa-calendar-o me-1"></i>
                                    {{ $item->published_at ? $item->published_at->translatedFormat('d F Y') : '-' }}
                                </span>
                                <span>
                                    <i class="fa fa-user-o me-1"></i>
                                    {{ $item->author->name ?? 'Admin' }}
                                </span>
                                <span>
                                    <i class="fa fa-eye me-1"></i>
                                    {{ number_format($item->views_count ?? 0, 0, ',', '.') }}
                                </span>
                            </div>

                            <p class="card-text text-secondary mb-4">
                                {{ \Illuminate\Support\Str::limit(strip_tags($item->excerpt ?: $item->content), 130) }}
                            </p>

                            <div class="mt-auto">
                                <a href="{{ route('blog.show', $item->slug) }}"
                                    class="btn btn-outline-secondary rounded-pill">
                                    Baca Artikel
                                    <i class="fa fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <div class="py-5 text-center">
                    <i class="fa fa-newspaper-o fa-4x text-muted mb-4 opacity-50"></i>
                    <h4 class="text-muted fw-bold mb-2">Artikel Belum Tersedia</h4>

                    @if (request('search'))
                        <p class="text-muted mb-0">
                            Tidak ada artikel dalam kategori
                            <span class="fw-bold">{{ $category->name }}</span>
                            yang sesuai dengan kata kunci
                            <span class="fw-bold">"{{ request('search') }}"</span>.
                        </p>
                    @else
                        <p class="text-muted mb-0">
                            Belum ada artikel yang dipublikasikan pada kategori ini.
                        </p>
                    @endif
                </div>
            @endforelse
        </div>

        {{ $articles->links('pagination::bootstrap-5') }}
    </section>

    @push('styles')
        <style>
            .card-title a:hover {
                color: var(--bs-secondary) !important;
            }

            .card-text {
                line-height: 1.7;
            }
        </style>
    @endpush

    @push('scripts')
    @endpush
</x-guest-layout>
