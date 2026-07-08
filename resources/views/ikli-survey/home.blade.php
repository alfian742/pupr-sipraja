<x-guest-layout>
    @php $pageTitle = 'SURVEI IKLI 2026'; @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <x-slot name="brand">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('ikli-survey.home') }}">
            <x-application-logo height="30" />
        </a>
    </x-slot>

    <section class="hero d-flex align-items-center min-vh-100 py-5">
        <div class="container hero-content">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                    <div class="hero-card text-center mx-auto">
                        <h1 class="hero-title text-uppercase fw-bold mb-3">
                            Kuesioner <br> Indeks Kepuasan Layanan Infrastruktur (IKLI)
                        </h1>

                        <div class="hero-badge mb-3">
                            Kabupaten Lombok Tengah 2026
                        </div>

                        <p class="hero-text mb-4 fst-italic">
                            3 menit Anda menjadi momentum penting keberlangsungan infrastruktur Lombok Tengah.
                        </p>

                        <button type="button" class="btn btn-warning hero-cta text-white fw-semibold px-4 py-3"
                            data-bs-toggle="modal" data-bs-target="#surveyModal">
                            <i class="fa-solid fa-pen-to-square me-2"></i>Isi Kuesioner Sekarang
                        </button>

                        <small class="d-block text-muted mb-0 mt-4">
                            Pelajari lebih lanjut tentang IKLI. <a href="#"
                                style="color: blue; text-decoration: underline;" data-bs-toggle="modal"
                                data-bs-target="#ikliModal">
                                Klik di sini
                            </a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="surveyModal" tabindex="-1" aria-labelledby="surveyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-sm" style="border-radius: 1rem;">
                <div class="modal-body p-md-4 p-3">
                    <div class="text-center mb-3">
                        <div class="rounded-circle d-flex justify-content-center align-items-center mx-auto p-3"
                            style="height: 60px; width: 60px; background-color: rgba(53, 79, 142, 0.10) ">
                            <p class="text-secondary mb-0 text-center"> <i class="fa-solid fa-clipboard-list fa-2x"></i>
                            </p>
                        </div>
                        <h5 class="mt-3 mb-1 fw-bold text-secondary" id="surveyModalLabel">Persetujuan Responden</h5>
                    </div>

                    <div class="bg-light p-3 mb-3" style="border-radius: 1rem;">
                        <div>
                            <h6 class="fw-bold text-secondary mb-2"> <i class="fa-solid fa-bullseye me-2"></i>
                                Tujuan Survei</h6>
                            <p class="text-dark mb-0">
                                Mengukur kepuasan masyarakat terhadap layanan infrastruktur di Kabupaten Lombok Tengah
                                untuk meningkatkan mutu pelayanan publik.
                            </p>
                        </div>

                        <hr class="my-3">

                        <div>
                            <h6 class="fw-bold text-secondary mb-2"> <i class="fa-solid fa-clock me-2"></i>
                                Estimasi Waktu</h6>
                            <p class="text-dark mb-0">
                                Pengisian kuesioner membutuhkan waktu sekitar <strong>±3–5 menit</strong>.
                            </p>
                        </div>

                        <hr class="my-3">

                        <div>
                            <h6 class="fw-bold text-secondary mb-2"><i class="fa-solid fa-road-bridge me-2"></i>
                                Infrastruktur yang akan Dinilai</h6>
                            <ul class="mb-0 ps-3 text-dark">
                                <li>Prasarana Terminal</li>
                                <li>Jaringan Jalan</li>
                                <li>Jaringan Irigasi</li>
                                <li>Prasarana Air Minum</li>
                                <li>Prasarana Air Limbah</li>
                                <li>Prasarana Persampahan</li>
                                <li>Jaringan Listrik</li>
                                <li>Jaringan Telekomunikasi/Internet</li>
                            </ul>
                        </div>
                    </div>

                    <div>
                        <h6 class="fw-bold text-secondary mb-2">Pernyataan <span class="text-danger">*</span></h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="consentCheck">
                            <label class="form-check-label text-secondary" for="consentCheck">
                                Saya bersedia mengisi kuesioner ini sesuai dengan kondisi yang saya ketahui dan
                                alami.
                            </label>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill" style="width: 130px;"
                            data-bs-dismiss="modal">
                            <i class="fa-solid fa-times me-1"></i> Batal
                        </button>

                        <a href="{{ route('ikli-survey.survey.create') }}" id="startSurveyBtn"
                            class="btn btn-primary rounded-pill disabled"
                            style="width: 130px; pointer-events: none; opacity: 0.65;" aria-disabled="true"
                            tabindex="-1">
                            <i class="fa-solid fa-edit me-1"></i> Mulai
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ikliModal" tabindex="-1" aria-labelledby="ikliModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-sm" style="border-radius: 1rem;">
                <div class="modal-body p-md-4 p-3">
                    <div class="text-center mb-3">
                        <div class="rounded-circle d-flex justify-content-center align-items-center mx-auto p-3"
                            style="height: 60px; width: 60px; background-color: rgba(53, 79, 142, 0.10) ">
                            <p class="text-secondary mb-0 text-center"> <i class="fa-solid fa-road-bridge fa-2x"></i>
                            </p>
                        </div>
                        <h5 class="mt-3 mb-1 fw-bold text-secondary" id="ikliModalLabel">Indeks Kepuasan Layanan
                            Infrastruktur (IKLI)</h5>
                    </div>

                    <div class="bg-light p-3 mb-3" style="border-radius: 1rem;">
                        <div>
                            <h6 class="fw-bold text-secondary mb-2">
                                <i class="fa-solid fa-lightbulb me-2"></i>
                                Pengertian
                            </h6>
                            <p class="text-dark mb-2">
                                <strong>Indeks Kepuasan Layanan Infrastruktur (IKLI) adalah ukuran untuk mengetahui
                                    tingkat kepuasan masyarakat</strong>
                                terhadap pembangunan atau layanan infrastruktur yang disediakan oleh pemerintah, baik
                                pemerintah pusat maupun
                                pemerintah daerah.
                            </p>
                            <small class="text-muted d-block">
                                Sumber:
                                <a href="https://sirusa.web.bps.go.id/metadata/indikator/30960" target="_blank"
                                    class="fst-italic" style="color: blue; text-decoration: underline;">
                                    Metadata Statistik BPS – Indeks Kepuasan Layanan Infrastruktur (IKLI)
                                </a>
                            </small>
                        </div>

                        <hr class="my-3">

                        <div>
                            <h6 class="fw-bold text-secondary mb-2">
                                <i class="fa-solid fa-chart-line me-2"></i>
                                Tujuan
                            </h6>
                            <p class="text-dark mb-2">
                                IKLI bertujuan untuk <strong>memberikan gambaran tingkat kepuasan masyarakat secara
                                    obyektif, komprehensif, dan kredibel</strong>
                                terhadap layanan infrastruktur. Hasilnya dapat digunakan sebagai <strong>bahan evaluasi
                                    untuk meningkatkan mutu pelayanan publik</strong>
                                dan mendukung perbaikan pembangunan infrastruktur.
                            </p>
                            <small class="text-muted d-block">
                                Sumber:
                                <a href="https://sirusa.web.bps.go.id/metadata/kegiatan/34800" target="_blank"
                                    class="fst-italic" style="color: blue; text-decoration: underline;">
                                    Metadata Kegiatan Statistik BPS – Pengukuran IKLI
                                </a>,
                                <a href="https://peraturan.bpk.go.id/Details/132600/permen-pan-rb-no-14-tahun-2017"
                                    target="_blank" class="fst-italic"
                                    style="color: blue; text-decoration: underline;">
                                    Permen PANRB Nomor 14 Tahun 2017
                                </a>
                            </small>
                        </div>

                        <hr class="my-3">

                        <div>
                            <h6 class="fw-bold text-secondary mb-2">
                                <i class="fa-solid fa-users me-2"></i>
                                Sasaran
                            </h6>
                            <p class="text-dark mb-2">
                                <strong>Sasaran IKLI adalah masyarakat sebagai pengguna langsung layanan
                                    infrastruktur.</strong>
                                Pendapat masyarakat menjadi penting karena pelayanan publik pada dasarnya
                                diselenggarakan untuk memenuhi
                                kebutuhan warga negara, sehingga <strong>tingkat kepuasan masyarakat dapat menjadi salah
                                    satu bahan penilaian kualitas layanan</strong>
                                yang diberikan.
                            </p>
                            <small class="text-muted d-block">
                                Sumber:
                                <a href="https://peraturan.bpk.go.id/Details/38748/uu-no-25-tahun-2009"
                                    target="_blank" class="fst-italic"
                                    style="color: blue; text-decoration: underline;">
                                    Undang-Undang Nomor 25 Tahun 2009 tentang Pelayanan Publik
                                </a>,
                                <a href="https://peraturan.bpk.go.id/Details/132600/permen-pan-rb-no-14-tahun-2017"
                                    target="_blank" class="fst-italic"
                                    style="color: blue; text-decoration: underline;">
                                    Permen PANRB Nomor 14 Tahun 2017
                                </a>
                            </small>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill" style="width: 130px;"
                            data-bs-dismiss="modal">
                            <i class="fa-solid fa-times me-1"></i> Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

            .hero-title {
                font-size: clamp(1.25rem, 4vw, 1.5rem);
                line-height: 1.2;
                color: var(--dark);
                letter-spacing: 0.02em;
            }

            .hero-badge {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.6rem 1rem;
                border-radius: 50rem;
                background-color: rgba(53, 79, 142, 0.10);
                color: var(--secondary);
                font-size: 0.9rem;
                font-weight: 600;
                line-height: 1.4;
            }

            .hero-text {
                max-width: 560px;
                margin-left: auto;
                margin-right: auto;
                font-size: 1rem;
                line-height: 1.75;
                color: #495057;
            }

            .hero-cta {
                min-width: 220px;
                border-radius: 50rem;
                box-shadow: 0 0.75rem 1.75rem rgba(255, 193, 7, 0.28);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
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

                .hero-title {
                    font-size: 1.5rem;
                }

                .hero-badge {
                    font-size: 0.8rem;
                    padding: 0.5rem 0.85rem;
                }

                .hero-text {
                    font-size: 0.95rem;
                    line-height: 1.65;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const consentCheck = document.getElementById('consentCheck');
                const startSurveyBtn = document.getElementById('startSurveyBtn');

                consentCheck.addEventListener('change', function() {
                    if (this.checked) {
                        startSurveyBtn.classList.remove('disabled');
                        startSurveyBtn.style.pointerEvents = 'auto';
                        startSurveyBtn.style.opacity = '1';
                        startSurveyBtn.setAttribute('aria-disabled', 'false');
                        startSurveyBtn.removeAttribute('tabindex');
                    } else {
                        startSurveyBtn.classList.add('disabled');
                        startSurveyBtn.style.pointerEvents = 'none';
                        startSurveyBtn.style.opacity = '0.65';
                        startSurveyBtn.setAttribute('aria-disabled', 'true');
                        startSurveyBtn.setAttribute('tabindex', '-1');
                    }
                });
            });
        </script>
    @endpush
</x-guest-layout>
