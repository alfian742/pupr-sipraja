<x-guest-layout>
    @php $pageTitle = 'Profil Personel'; @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <section id="page-section">
        <div class="row justify-content-center">
            <div class="col-lg-7 mb-5 text-center">
                <p class="text-dark">
                    Kenali lebih dekat profil dan informasi lengkap mengenai personel
                    {{ config('app.subname', 'Laravel') }},
                    termasuk jabatan, tanggung jawab, dan peran dalam mendukung layanan kepada masyarakat.
                </p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-7">
                <form action="{{ route('organization-profiles.personnel-profiles') }}" method="GET">
                    <div class="input-group input-group-lg">
                        <input type="search" name="search" class="form-control rounded-start"
                            placeholder="Cari personel..." value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary rounded-end" type="submit">
                            <i class="fa fa-search me-2"></i> Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row justify-content-center g-4 mb-5 mt-3">
            @forelse ($personnelProfiles as $item)
                <div class="col-md-6">
                    <div class="card bg-light zoom-hover overflow-hidden rounded border-0 shadow-sm">
                        <div class="row g-0 align-items-center">
                            <div class="col-lg-4">
                                <img src="{{ $item->personnel_photo ? asset($item->personnel_photo) : asset('assets/images/avatar.png') }}"
                                    class="img-fluid w-100" style="object-fit: cover; aspect-ratio: 3/4;"
                                    alt="{{ $item->personnel_name ?? '' }}">
                            </div>
                            <div class="col-lg-8 d-flex">
                                <div class="card-body p-4">
                                    <h4 class="card-title">{{ $item->personnel_position ?? '' }}</h4>
                                    <h6 class="fw-normal fst-italic text-secondary mb-3">
                                        {{ $item->personnel_name ?? '' }}
                                    </h6>
                                    <p class="card-text">{{ $item->personnel_description ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-5 text-center">
                    <i class="fa fa-users fa-4x text-muted mb-4 opacity-50"></i>
                    <h4 class="text-muted fw-bold mb-2">{{ $pageTitle }} Belum Tersedia</h4>
                </div>
            @endforelse
        </div>

        {{ $personnelProfiles->links('pagination::bootstrap-5') }}
    </section>

    @push('styles')
    @endpush

    @push('scripts')
    @endpush
</x-guest-layout>
