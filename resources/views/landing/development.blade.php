<x-guest-layout>
    @php
        // Ambil parameter di URL, contoh: ?page=Struktur%20Organisasi
        $parameter = request('page');

        // Decode agar spasi dan karakter khusus tampil normal di title
        $title = $parameter ? urldecode($parameter) : null;
    @endphp

    <x-slot name="title">{{ $title ?? 'Halaman Dalam Pengembangan' }}</x-slot>

    <section id="page-section">
        <div class="card border-0">
            <div class="card-body">
                <div class="py-5 text-center">
                    <i class="fa fa-code fa-4x text-muted mb-4 opacity-50"></i>
                    <h4 class="text-muted fw-bold mb-2">Halaman Dalam Pengembangan</h4>
                    <p class="text-muted fst-italic">
                        Maaf, halaman <span class="fw-bold">{{ $title ?? '' }}</span> masih dalam
                        proses pengembangan. Silakan kembali lagi nanti.
                    </p>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
    @endpush

    @push('scripts')
    @endpush
</x-guest-layout>
