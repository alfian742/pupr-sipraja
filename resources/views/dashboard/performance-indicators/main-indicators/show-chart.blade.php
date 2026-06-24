<x-app-layout>
    @php $pageTitle = 'Grafik' @endphp

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
                                @include('layouts.partials.alert')
                                <div class="d-flex flex-md-row justify-content-between align-items-center mb-2 flex-wrap"
                                    style="gap: 1rem">
                                    <a href="{{ $routeList->index }}" class="btn btn-secondary">
                                        <i class="fa fa-arrow-left"></i> <span class="d-none d-md-inline">Kembali</span>
                                    </a>
                                    <div style="min-width: 280px;">
                                        <select id="main-indicator-name-select" class="custom-select"
                                            aria-label="Nama Indikator">
                                            <option value="">-- Pilih Nama Indikator --</option>
                                            @foreach ($indicatorNames as $indicatorName)
                                                <option value="{{ $indicatorName }}">
                                                    {{ \Illuminate\Support\Str::limit($indicatorName, 30, '...') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <h5 class="font-weight-bold mb-1" id="main-indicator-title">
                                        Indikator
                                    </h5>
                                    <h6 class="mb-0" id="main-indicator-unit">Satuan: -</h6>
                                </div>

                                <div id="main-indicator-chart-wrapper">
                                    <div style="height: 300px;">
                                        <canvas id="main-indicator-chart" class="h-100"></canvas>
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
    @endpush

    @push('scripts')
        <script src="{{ asset('app-assets/vendors/js/charts/chart.min.js') }}"></script>

        <script>
            const MAIN_INDICATOR_CONFIG = {
                chartUrl: "{{ $routeList->chart }}"
            };

            let regionalChartInstance = null;

            function ensureWrapperRelative() {
                const wrapper = document.getElementById('main-indicator-chart-wrapper');
                if (!wrapper) return null;
                if (getComputedStyle(wrapper).position === 'static') wrapper.style.position = 'relative';
                return wrapper;
            }

            function showLoader(wrapper) {
                const loader = document.createElement('div');
                loader.setAttribute('data-loader', '1');
                loader.innerHTML = `<span class="ft-refresh-cw icon-spin"></span>&nbsp; Memuat data...`;

                Object.assign(loader.style, {
                    position: 'absolute',
                    top: '50%',
                    left: '50%',
                    transform: 'translate(-50%, -50%)',
                    zIndex: 9999,
                    padding: '10px 15px',
                    color: '#fff',
                    width: 'auto',
                    backgroundColor: '#333',
                    borderRadius: '4px',
                    cursor: 'wait',
                    textAlign: 'center',
                    opacity: 0.9,
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    gap: '5px',
                    fontWeight: '600',
                    fontSize: '14px'
                });

                wrapper.appendChild(loader);
                return loader;
            }

            function removeLoader(wrapper) {
                const existing = wrapper.querySelector('[data-loader="1"]');
                if (existing) existing.remove();
            }

            function updateHeader(data) {
                document.getElementById('main-indicator-title').innerText = data?.title ?? 'Indikator';
                document.getElementById('main-indicator-unit').innerText = `Satuan: ${data?.unit ?? '-'}`;
            }

            function renderOrUpdateChart(data) {
                const canvas = document.getElementById('main-indicator-chart');
                if (!canvas) return;

                const ctx = canvas.getContext('2d');

                if (regionalChartInstance) {
                    regionalChartInstance.destroy();
                    regionalChartInstance = null;
                }

                const colorTarget = "rgba(85, 89, 92, 0.7)";
                const colorAchievement = "rgba(55, 188, 155, 0.7)";
                const colorPerformance = "rgba(59, 175, 218, 0.7)";

                regionalChartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels ?? [],
                        datasets: [{
                                label: "Target",
                                data: data.target ?? [],
                                backgroundColor: colorTarget,
                                borderColor: colorTarget,
                                borderWidth: 1
                            },
                            {
                                label: "Capaian",
                                data: data.achievement ?? [],
                                backgroundColor: colorAchievement,
                                borderColor: colorAchievement,
                                borderWidth: 1
                            },
                            {
                                label: "Kinerja",
                                data: data.performance ?? [],
                                backgroundColor: colorPerformance,
                                borderColor: colorPerformance,
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            xAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Tahun'
                                },
                                ticks: {
                                    fontSize: 12
                                },
                                gridLines: {
                                    display: false
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontSize: 12
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Nilai'
                                }
                            }]
                        }
                    }
                });
            }

            async function loadIndicatorChart(indicatorName) {
                const wrapper = ensureWrapperRelative();
                if (!wrapper) return;

                removeLoader(wrapper);
                const loader = showLoader(wrapper);

                try {
                    const params = new URLSearchParams();

                    if (indicatorName) {
                        params.append('indicator_name', indicatorName);
                    }

                    const res = await fetch(`${MAIN_INDICATOR_CONFIG.chartUrl}?${params.toString()}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    const json = await res.json();

                    if (json.status === 'success') {
                        updateHeader(json.data);
                        renderOrUpdateChart(json.data);
                    } else {
                        console.error(json);
                        alert('Gagal memuat data chart.');
                    }
                } catch (e) {
                    console.error(e);
                    alert('Gagal memuat data chart.');
                } finally {
                    setTimeout(() => {
                        if (loader) loader.remove();
                    }, 300);
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const nameSelect = document.getElementById('main-indicator-name-select');

                if (nameSelect && nameSelect.options.length > 1) {
                    nameSelect.selectedIndex = 1;
                    loadIndicatorChart(nameSelect.value);
                } else {
                    loadIndicatorChart('');
                }

                nameSelect.addEventListener('change', function() {
                    loadIndicatorChart(this.value);
                });
            });
        </script>
    @endpush
</x-app-layout>
