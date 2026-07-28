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
                <div class="vision-mission-content">
                    <div class="vision-mission-item vision-item">
                        <div class="vision-mission-icon">
                            <i class="fa fa-eye text-white"></i>
                        </div>

                        <div class="vision-mission-text-area">
                            <h3 class="vision-mission-heading mb-3">Visi</h3>

                            <div class="vision-mission-text">
                                {!! $organizationProfile->organization_vision ?? '' !!}
                            </div>
                        </div>
                    </div>

                    <div class="vision-mission-item mission-item">
                        <div class="vision-mission-icon">
                            <i class="fa fa-bullseye text-white"></i>
                        </div>

                        <div class="vision-mission-text-area">
                            <h3 class="vision-mission-heading mb-3">Misi</h3>

                            <div class="vision-mission-text">
                                {!! $organizationProfile->organization_mission ?? '' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
    @endpush

    @push('scripts')
    @endpush
</x-guest-layout>
