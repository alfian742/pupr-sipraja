<x-guest-layout>
    @php $pageTitle = 'SURVEI IKLI 2026'; @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <x-slot name="brand">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <x-application-logo height="30" />
        </a>
    </x-slot>

    <section class="hero d-flex align-items-center min-vh-100 py-5">
        <div class="container hero-content">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                    <div class="hero-card text-center mx-auto">
                        <img src="{{ asset('landing/img/people-saying-thank-you.webp') }}" alt="Terima Kasih"
                            class="hero-image mb-2">
                        <p class="hero-text mb-4">
                            <span class="fs-4 fw-bold">Kuesioner ini telah ditutup.</span> <br> <span
                                class="fst-italic">Kami
                                mengucapkan terima kasih atas waktu dan partisipasi Anda yang
                                sangat berarti bagi kami.</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            .hero {
                position: relative;
                overflow: hidden;
                background: url('{{ asset('landing/img/ikli-survey-hero.png') }}') center center / cover no-repeat;
            }

            .hero::before {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg,
                        rgba(29, 42, 77, 0.68),
                        rgba(53, 79, 142, 0.30));
                z-index: 1;
            }

            .hero-content {
                position: relative;
                z-index: 2;
            }

            .hero-card {
                max-width: 720px;
                background-color: rgba(255, 255, 255, 0.90);
                border: 1px solid rgba(255, 255, 255, 0.45);
                border-radius: 1.5rem;
                box-shadow: 0 1rem 2.5rem rgba(29, 42, 77, 0.18);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                padding: 2rem 1.5rem;
            }

            .hero-image {
                height: auto;
                width: 80%;
            }

            .hero-text {
                max-width: 560px;
                margin-left: auto;
                margin-right: auto;
                font-size: 1rem;
                line-height: 1.75;
                color: #495057;
            }

            .hero-cta:hover,
            .hero-cta:focus {
                transform: translateY(-2px);
                box-shadow: 0 1rem 2rem rgba(255, 193, 7, 0.35);
            }

            @media (max-width: 991.98px) {
                .hero {
                    background-position: center;
                }

                .hero-card {
                    padding: 1.75rem 1.25rem;
                }
            }

            @media (max-width: 575.98px) {
                .hero {
                    min-height: auto;
                    padding-top: 4rem;
                    padding-bottom: 4rem;
                }

                .hero-card {
                    border-radius: 1.25rem;
                    padding: 1.5rem 1rem;
                }

                .hero-text {
                    font-size: 0.95rem;
                    line-height: 1.65;
                }
            }
        </style>
    @endpush

    @push('scripts')
    @endpush
</x-guest-layout>
