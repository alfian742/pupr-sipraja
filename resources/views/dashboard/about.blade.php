<x-app-layout>
    @php $pageTitle = 'Tentang' @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <div class="content-body">
        <section id="dom">
            <div class="row">
                <div class="col-12">
                    @include('layouts.partials.alert')
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="font-weight-bold text-uppercase mb-0">{{ $pageTitle }}</h3>
                            <a class="heading-elements-toggle">
                                <i class="fa fa-ellipsis-v font-medium-3"></i>
                            </a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li>
                                        <a data-action="collapse">
                                            <i class="ft-minus"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-action="expand">
                                            <i class="ft-maximize"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card-content show collapse">
                            <div class="card-body card-dashboard about-page-body">
                                <div class="about-content-wrapper">
                                    <div class="row align-items-center">
                                        <div class="col-xl-4 col-lg-5 col-md-12">
                                            <div class="about-brand-wrapper">
                                                <x-application-logo height="115"
                                                    class="d-block mx-auto mb-2 about-logo" />

                                                <h2 class="about-title text-uppercase mb-0 text-center">
                                                    <span class="d-block">Sistem Informasi</span>
                                                    Pengukuran Kinerja
                                                </h2>
                                            </div>
                                        </div>

                                        <div class="col-xl-8 col-lg-7 col-md-12">
                                            <div class="about-info-wrapper">
                                                <div class="bs-callout-indigo callout-transparent mb-2 about-callout">
                                                    <div class="media align-items-stretch">
                                                        <div
                                                            class="media-left d-flex align-items-center justify-content-center bg-indigo position-relative callout-arrow-left about-icon-box">
                                                            <i class="fa fa-desktop fa-lg white font-medium-5"></i>
                                                        </div>

                                                        <div class="media-body about-callout-body">
                                                            <strong class="text-dark">
                                                                {{ config('app.name', 'Laravel') }}
                                                            </strong>
                                                            adalah aplikasi dashboard resmi
                                                            <span class="font-weight-bold text-dark">
                                                                DPUPR Kabupaten Lombok Tengah
                                                            </span>
                                                            yang dirancang untuk menghadirkan monitoring kinerja
                                                            infrastruktur yang cepat, akurat, dan terintegrasi.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="bs-callout-indigo callout-transparent mb-2 about-callout">
                                                    <div class="media align-items-stretch">
                                                        <div
                                                            class="media-left d-flex align-items-center justify-content-center bg-indigo position-relative callout-arrow-left about-icon-box">
                                                            <i class="fa fa-list fa-lg white font-medium-5"></i>
                                                        </div>

                                                        <div class="media-body about-callout-body">
                                                            Melalui platform ini, seluruh unsur
                                                            <span class="font-italic">
                                                                —perencanaan, pelaksanaan, evaluasi, hingga pelaporan—
                                                            </span>
                                                            dapat diamati secara
                                                            <span class="font-weight-bold text-dark">
                                                                real time, transparan, dan berbasis data
                                                            </span>.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="bs-callout-indigo callout-transparent about-callout">
                                                    <div class="media align-items-stretch">
                                                        <div
                                                            class="media-left d-flex align-items-center justify-content-center bg-indigo position-relative callout-arrow-left about-icon-box">
                                                            <i class="fa fa-dashboard fa-lg white font-medium-5"></i>
                                                        </div>

                                                        <div class="media-body about-callout-body">
                                                            <strong class="text-dark">
                                                                {{ config('app.name', 'Laravel') }}
                                                            </strong>
                                                            —
                                                            Arah Baru Manajemen Kinerja Infrastruktur Lombok Tengah,
                                                            satu data, satu dashboard, satu komitmen untuk pembangunan
                                                            yang lebih baik.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('styles')
        <style>
            .about-page-body {
                padding: 2rem 2.5rem;
            }

            .about-content-wrapper {
                width: 100%;
                max-width: 1180px;
                margin: 0 auto;
                padding: 1.5rem 0;
            }

            .about-brand-wrapper {
                width: 100%;
                max-width: 420px;
                margin: 0 auto;
            }

            .about-logo {
                border-radius: 8px;
            }

            .about-title {
                color: #06366d;
                font-family: Arial, sans-serif;
                font-size: 1.55rem;
                font-weight: 800;
                line-height: 1.15;
                letter-spacing: .4px;
            }

            .about-title span {
                color: #f3b80c;
            }

            .about-info-wrapper {
                width: 100%;
                max-width: 720px;
                margin: 0 auto;
            }

            .about-callout {
                border-left: 0;
            }

            .about-icon-box {
                width: 70px;
                min-width: 70px;
                min-height: 86px;
                padding: 1rem;
            }

            .about-callout-body {
                padding: .95rem 1rem .95rem 1.25rem;
                font-size: .92rem;
                line-height: 1.45;
            }

            @media (max-width: 1199.98px) {
                .about-content-wrapper {
                    max-width: 1000px;
                }

                .about-info-wrapper {
                    max-width: 650px;
                }
            }

            @media (max-width: 991.98px) {
                .about-brand-wrapper {
                    margin-bottom: 2rem;
                }

                .about-info-wrapper {
                    max-width: 100%;
                }
            }

            @media (max-width: 575.98px) {
                .about-page-body {
                    padding: 1.25rem;
                }

                .about-content-wrapper {
                    padding: .75rem 0;
                }

                .about-title {
                    font-size: 1.3rem;
                }

                .about-icon-box {
                    width: 58px;
                    min-width: 58px;
                    min-height: 76px;
                }

                .about-callout-body {
                    padding: .85rem .85rem .85rem 1rem;
                    font-size: .88rem;
                }
            }
        </style>
    @endpush

    @push('scripts')
    @endpush
</x-app-layout>
