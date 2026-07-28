<x-guest-layout>
    @php $pageTitle = 'Kategori'; @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <section id="page-section">
        <div class="row justify-content-center">
            <div class="col-lg-7 mb-5 text-center">
                <p class="text-dark">
                    Akses berbagai kategori informasi publik yang disediakan oleh
                    {{ config('app.subname', 'Laravel') }}.
                </p>
            </div>
        </div>

        @if (isset($categories) && $categories->count())
            <div class="row g-4 justify-content-center">
                @foreach ($categories as $category)
                    @php
                        $isActive = request()->routeIs('blog.category') && request()->route('slug') === $category->slug;
                    @endphp

                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('blog.category', $category->slug) }}"
                            class="blog-category-card {{ $isActive ? 'active' : '' }}">

                            <div class="blog-category-card-content">
                                <div class="blog-category-card-header">
                                    <h3 class="blog-category-card-title">
                                        {{ $category->name }}
                                    </h3>

                                    <span class="blog-category-card-count">
                                        {{ $category->published_articles_count ?? 0 }}
                                    </span>
                                </div>

                                <p class="blog-category-card-description">
                                    {{ \Illuminate\Support\Str::limit(
                                        strip_tags($category->description ?: 'Temukan berbagai artikel dan informasi terbaru dalam kategori ini.'),
                                        120,
                                    ) }}
                                </p>

                                <span class="blog-category-card-action">
                                    Selengkapnya
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-5 text-center">
                <i class="fa fa-newspaper-o fa-4x text-muted mb-4 opacity-50"></i>
                <h4 class="text-muted fw-bold mb-2">{{ $pageTitle }} Belum Tersedia</h4>
                <p class="text-muted mb-0">
                    Belum ada kategori yang dipublikasikan.
                </p>
            </div>
        @endif
    </section>

    @push('styles')
    @endpush

    @push('scripts')
    @endpush
</x-guest-layout>
