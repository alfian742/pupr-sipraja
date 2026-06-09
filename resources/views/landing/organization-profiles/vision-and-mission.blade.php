<x-guest-layout>
    @php $pageTitle = 'Visi dan Misi'; @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <section id="page-section">
        <div class="row justify-content-center">
            <div class="col-md-7 mb-5 text-center">
                <div class="text-dark">
                    {!! $organizationProfile->organization_summary ?? '' !!}
                </div>
            </div>
            <div class="col-md-7">
                <div class="text-dark mb-5">
                    <h3 class="mb-3">Visi</h3>

                    {!! $organizationProfile->organization_vision ?? '' !!}
                </div>

                <div class="text-dark">
                    <h3 class="mb-3">Misi</h3>

                    {!! $organizationProfile->organization_mission ?? '' !!}
                </div>
            </div>
        </div>
    </section>

    @push('styles')
    @endpush

    @push('scripts')
    @endpush
</x-guest-layout>
