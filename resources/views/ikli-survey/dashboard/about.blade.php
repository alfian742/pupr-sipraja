<x-app-layout>
    @php $pageTitle = 'Tentang' @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <div class="content-body">
        <section id="dom">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="font-weight-bold text-uppercase mb-0">{{ $pageTitle }}</h3>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content show collapse">
                            <div class="card-body card-dashboard">
                                <!-- BRAND INTRO -->
                                <div class="brand-intro mb-3">

                                    <div class="brand-logos">
                                        <img src="{{ asset('assets/images/logo-loteng-square.png') }}"
                                            alt="Logo Kabupaten Lombok Tengah" height="100"
                                            class="border-indigo rounded p-1">

                                        <img src="{{ asset('assets/images/logo-ngk-square.png') }}"
                                            alt="Logo CV. Nuansa GIS Konsultan" height="100"
                                            class="border-indigo rounded p-1">
                                    </div>

                                    <p class="text-secondary mb-0" style="line-height: 1.75;">
                                        Survei Indeks Kepuasan Layanan Infrastruktur Dinas PUPR Kabupaten Lombok Tengah
                                        merupakan instrumen untuk mengukur tingkat kepuasan masyarakat terhadap
                                        pelayanan infrastruktur yang diberikan.

                                        Survei ini bertujuan menghimpun
                                        masukan masyarakat terkait kualitas, ketepatan waktu, dan efektivitas layanan,
                                        sekaligus menjadi dasar evaluasi dan perbaikan berkelanjutan guna meningkatkan
                                        kualitas pelayanan publik.
                                    </p>
                                </div>

                                <!-- ROLE CARDS -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="role-card">
                                            <div class="role-header">
                                                <span class="role-icon bg-warning">
                                                    <i class="fa fa-mobile-phone fa-2x"></i>
                                                </span>
                                                <div class="text-center">
                                                    <h4 class="role-title">Enumerator</h4>
                                                    <small class="text-muted">Survei Lapangan</small>
                                                </div>
                                            </div>

                                            <div class="role-body">
                                                Bertugas melakukan pencatatan di lapangan dan membantu masyarakat dalam
                                                pengisian kuesioner sesuai
                                                instrumen yang ditetapkan.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="role-card">
                                            <div class="role-header">
                                                <span class="role-icon bg-indigo">
                                                    <i class="fa fa-desktop fa-2x"></i>
                                                </span>
                                                <div class="text-center">
                                                    <h4 class="role-title">Admin</h4>
                                                    <small class="text-muted">Verifikasi &amp; Validasi</small>
                                                </div>
                                            </div>

                                            <div class="role-body">
                                                Bertanggung jawab melakukan pemeriksaan, verifikasi, dan validasi data
                                                untuk
                                                memastikan kelengkapan,
                                                konsistensi, dan kualitas informasi sebelum digunakan sebagai dasar
                                                pelaporan dan pengambilan keputusan.
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
            .brand-intro {
                position: relative;
                border: 1px solid rgba(0, 0, 0, .10);
                border-radius: 4px;
                background: #fff;
                padding: 1rem;
                margin-bottom: 1rem;
                overflow: hidden;
            }

            .brand-logos {
                display: flex;
                align-items: center;
                justify-content: center;
                flex-wrap: wrap;
                gap: .75rem;
                margin: .25rem 0 .9rem;
            }

            @media (max-width: 575.98px) {
                .brand-intro {
                    padding: .9rem;
                }
            }

            .role-card {
                border: 1px solid rgba(0, 0, 0, .10);
                border-radius: 4px;
                background: #fff;
                height: 100%;
                overflow: hidden;
                transition: transform .15s ease, border-color .15s ease;
            }

            .role-card:hover {
                transform: translateY(-2px);
                border-color: rgba(0, 0, 0, .18);
            }

            .role-header {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: .75rem;
                padding: 1rem 1rem .85rem;
                background: rgba(0, 0, 0, .02);
                border-bottom: 1px dashed rgba(0, 0, 0, .15);
                text-align: center;
            }

            .role-icon {
                width: 56px;
                height: 56px;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                flex: 0 0 auto;
            }

            .role-title {
                font-weight: 800;
                margin: 0;
                line-height: 1.1;
                color: #212529;
            }

            .role-body {
                padding: 1rem 1rem 1.1rem;
                color: #6c757d;
                line-height: 1.7;
            }
        </style>
    @endpush

    @push('scripts')
    @endpush
</x-app-layout>
