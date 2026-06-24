<x-guest-layout>
    @php $pageTitle = 'Struktur Organisasi'; @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <section id="page-section">
        <div class="row justify-content-center">
            <div class="col-lg-7 mb-5 text-center">
                <p class="text-dark">
                    Gambaran struktur {{ config('app.subname', 'Laravel') }}
                    yang menunjukkan susunan jabatan, peran, dan tanggung jawab dalam mendukung
                    pelaksanaan tugas dan fungsi secara profesional.
                </p>
            </div>
        </div>

        @if ($organizationStructure)
            <div class="ratio ratio-16x9 rounded border">
                <iframe src="{{ $organizationStructure }}" style="border:0;" allow="autoplay; fullscreen" allowfullscreen>
                </iframe>
            </div>
        @else
            <div class="py-5 text-center">
                <i class="fa fa-sitemap fa-4x text-muted mb-4 opacity-50"></i>
                <h4 class="text-muted fw-bold mb-2">{{ $pageTitle }} Belum Tersedia</h4>
            </div>
        @endif
    </section>

    @push('styles')
    @endpush

    @push('scripts')
    @endpush
</x-guest-layout>
