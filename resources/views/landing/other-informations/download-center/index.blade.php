<x-guest-layout>
    @php $pageTitle = 'Pusat Unduhan'; @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <section id="page-section">
        <div class="row justify-content-center">
            <div class="col-lg-7 mb-5 text-center">
                <p class="text-dark">
                    Pusat Unduhan {{ config('app.subname', 'Laravel') }} menyajikan berbagai laporan,
                    dokumen resmi, dan materi publikasi sebagai wujud transparansi
                    serta keterbukaan informasi kepada masyarakat.
                </p>
            </div>
        </div>

        <form action="{{ route('other-informations.download-center.index') }}" method="GET">
            <div class="row justify-content-center justify-content-lg-between g-4 mb-4">
                <div class="col-lg-3">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text rounded-start">Tampilkan</span>
                        <select name="show" class="rounded-end form-select" aria-label="Tampilkan Data"
                            onchange="this.form.submit()">
                            <option value="10" {{ request('show') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('show') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('show') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('show') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="input-group input-group-lg">
                        <input type="search" name="search" class="form-control rounded-start"
                            placeholder="Cari nama dokumen..." value="{{ request('search') }}" aria-label="Cari">
                        <button class="btn btn-outline-secondary rounded-end" type="submit">
                            <i class="fa fa-search me-2"></i> Cari
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive mb-4">
            <table class="table-hover table-bordered table-align-middle table" style="white-space: nowrap;">
                @php
                    $column = ['No.', 'Tanggal Unggah', 'Judul Dokumen', 'Jenis Dokumen', 'Detail'];
                @endphp

                <thead class="bg-secondary text-white">
                    <tr class="text-center align-middle">
                        @foreach ($column as $col)
                            <th>{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($downloadCenter as $item)
                        <tr class="align-middle">
                            <td class="text-end">
                                {{ ($downloadCenter->currentPage() - 1) * $downloadCenter->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                {{ $item->created_at? \Carbon\Carbon::parse($item->created_at)->locale(app()->getLocale())->translatedFormat('d F Y H:i'): '-' }}
                            </td>
                            <td>
                                <div class="text-wrap">{{ $item->document_title ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="text-wrap">{{ $item->document_type ?? '-' }}</div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('other-informations.download-center.show', $item->slug) }}"
                                    class="btn btn-secondary rounded-pill">
                                    <i class="fa fa-info-circle me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr class="text-center">
                            <td colspan="6">Tidak ada data yang tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $downloadCenter->links('pagination::bootstrap-5') }}
    </section>

    @push('styles')
    @endpush

    @push('scripts')
    @endpush
</x-guest-layout>
