<x-guest-layout>
    @php $pageTitle = 'Kontak'; @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <section id="page-section">
        <div class="row justify-content-center">
            <div class="col-lg-7 mb-5 text-center">
                <p class="text-dark">
                    Hubungi kami untuk memperoleh informasi lebih lanjut,
                    menyampaikan pertanyaan, masukan, pengaduan, maupun kebutuhan layanan lainnya.
                    Kami siap membantu Anda.
                </p>
            </div>
        </div>

        <div class="row justify-content-center g-5">
            <!-- Informasi Kontak -->
            <div class="col-lg-6">
                <h3 class="text-primary fw-bold mb-4">Kontak Kami</h3>

                <div class="list-group list-group-flush">

                    <!-- Email -->
                    @if ($contact->email || $contact->email_alternative)
                        <div class="list-group-item d-flex align-items-center gap-3 py-3">
                            <span class="badge bg-secondary rounded-circle badge-custom p-4">
                                <i class="fas fa-envelope fa-lg text-white"></i>
                            </span>
                            <div>
                                <div class="fw-bold">Email</div>
                                @if ($contact->email)
                                    <small><a class="link-secondary"
                                            href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></small><br>
                                @endif
                                @if ($contact->email_alternative)
                                    <small><a class="link-secondary"
                                            href="mailto:{{ $contact->email_alternative }}">{{ $contact->email_alternative }}</a></small>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Telepon -->
                    @if ($contact->phone_number || $contact->phone_number_alternative)
                        <div class="list-group-item d-flex align-items-center gap-3 py-3">
                            <span class="badge bg-info rounded-circle badge-custom p-4">
                                <i class="fas fa-phone fa-lg text-white"></i>
                            </span>
                            <div>
                                <div class="fw-bold">Telepon</div>
                                @if ($contact->phone_number)
                                    <small><a class="link-secondary"
                                            href="tel:{{ $contact->phone_number }}">{{ $contact->phone_number }}</a></small><br>
                                @endif
                                @if ($contact->phone_number_alternative)
                                    <small><a class="link-secondary"
                                            href="tel:{{ $contact->phone_number_alternative }}">{{ $contact->phone_number_alternative }}</a></small>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- WhatsApp -->
                    @if ($contact->whatsapp_number || $contact->whatsapp_number_alternative)
                        <div class="list-group-item d-flex align-items-center gap-3 py-3">
                            <span class="badge bg-success rounded-circle badge-custom p-4">
                                <i class="fab fa-whatsapp fa-lg text-white"></i>
                            </span>
                            <div>
                                <div class="fw-bold">WhatsApp</div>
                                @if ($contact->whatsapp_number)
                                    <small>
                                        <a class="link-secondary"
                                            href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/\D/', '', $contact->whatsapp_number)) }}"
                                            target="_blank">
                                            {{ $contact->whatsapp_number }}
                                        </a>
                                    </small><br>
                                @endif
                                @if ($contact->whatsapp_number_alternative)
                                    <small>
                                        <a class="link-secondary"
                                            href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/\D/', '', $contact->whatsapp_number_alternative)) }}"
                                            target="_blank">
                                            {{ $contact->whatsapp_number_alternative }}
                                        </a>
                                    </small>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Alamat -->
                    @if ($contact->address)
                        <div class="list-group-item d-flex align-items-center gap-3 py-3">
                            <span class="badge bg-danger rounded-circle badge-custom p-4">
                                <i class="fas fa-map-marker-alt fa-lg text-white"></i>
                            </span>
                            <div>
                                <div class="fw-bold">Alamat</div>
                                <small>{{ $contact->address }}</small>
                            </div>
                        </div>
                    @endif

                    <!-- Waktu Operasional -->
                    @if ($contact->operational_time)
                        <div class="list-group-item d-flex align-items-center gap-3 py-3">
                            <span class="badge bg-warning rounded-circle badge-custom p-4">
                                <i class="fas fa-clock fa-lg text-white"></i>
                            </span>
                            <div>
                                <div class="fw-bold">Waktu Operasional</div>
                                <small>{{ $contact->operational_time }}</small>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Maps & Social Media -->
            <div class="col-lg-6">
                @if ($contact->google_maps_embed)
                    <div class="mb-4">
                        <h3 class="text-primary mb-4">Lokasi Kami</h3>
                        <div class="ratio ratio-16x9">
                            {!! $contact->google_maps_embed !!}
                        </div>
                    </div>
                @endif

                <div>
                    <h3 class="text-primary mb-4">Media Sosial</h3>
                    <div class="d-flex flex-wrap gap-3">
                        @if ($contact->instagram_url)
                            <a href="{{ $contact->instagram_url }}" target="_blank"
                                class="btn btn-outline-danger rounded-pill">
                                <i class="fab fa-instagram me-1"></i> Instagram
                            </a>
                        @endif
                        @if ($contact->facebook_url)
                            <a href="{{ $contact->facebook_url }}" target="_blank"
                                class="btn btn-outline-secondary rounded-pill">
                                <i class="fab fa-facebook-f me-1"></i> Facebook
                            </a>
                        @endif
                        @if ($contact->twitter_url)
                            <a href="{{ $contact->twitter_url }}" target="_blank"
                                class="btn btn-outline-dark rounded-pill">
                                <i class="fab fa-x-twitter me-1"></i> X/Twitter
                            </a>
                        @endif
                        @if ($contact->tiktok_url)
                            <a href="{{ $contact->tiktok_url }}" target="_blank"
                                class="btn btn-outline-dark rounded-pill">
                                <i class="fab fa-tiktok me-1"></i> TikTok
                            </a>
                        @endif
                        @if ($contact->youtube_url)
                            <a href="{{ $contact->youtube_url }}" target="_blank"
                                class="btn btn-outline-danger rounded-pill">
                                <i class="fab fa-youtube me-1"></i> YouTube
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            .badge-custom {
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
            }

            .badge-custom i {
                display: block;
                width: auto;
                height: auto;
                line-height: 1;
                text-align: center;
            }
        </style>
    @endpush

    @push('scripts')
    @endpush
</x-guest-layout>
