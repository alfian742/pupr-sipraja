<x-app-layout>
    @php $pageTitle = 'Grafik Indikator Kinerja Daerah' @endphp

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
                                        <i class="fa fa-arrow-left"></i>
                                        <span class="d-none d-md-inline">Kembali</span>
                                    </a>

                                    <div style="min-width: 280px;">
                                        <select id="regional-indicator-name-select" class="custom-select"
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

                                <div id="regional-indicator-header" class="d-none">
                                    <h5 class="font-weight-bold mb-1" id="regional-indicator-title">
                                        Indikator
                                    </h5>

                                    <h6 class="mb-0" id="regional-indicator-unit">
                                        Satuan: -
                                    </h6>
                                </div>

                                <div id="regional-indicator-chart-wrapper" class="mt-2">
                                    <div class="alert alert-info text-center mb-0">
                                        Silakan pilih nama indikator untuk menampilkan grafik.
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
            const REGIONAL_INDICATOR_CONFIG = {
                chartUrl: "{{ $routeList->chart }}"
            };

            let regionalIndicatorChartInstances = [];

            function getChartWrapper() {
                const wrapper = document.getElementById(
                    'regional-indicator-chart-wrapper'
                );

                if (!wrapper) {
                    return null;
                }

                if (getComputedStyle(wrapper).position === 'static') {
                    wrapper.style.position = 'relative';
                }

                return wrapper;
            }

            function destroyChartInstances() {
                regionalIndicatorChartInstances.forEach(function(chart) {
                    if (chart) {
                        chart.destroy();
                    }
                });

                regionalIndicatorChartInstances = [];
            }

            function showLoader(wrapper) {
                removeLoader(wrapper);

                const loader = document.createElement('div');

                loader.setAttribute('data-loader', '1');

                loader.innerHTML = `
                    <span class="ft-refresh-cw icon-spin"></span>
                    <span>Memuat data...</span>
                `;

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
                if (!wrapper) {
                    return;
                }

                const loader = wrapper.querySelector('[data-loader="1"]');

                if (loader) {
                    loader.remove();
                }
            }

            function updateHeader(data = null) {
                const headerElement = document.getElementById(
                    'regional-indicator-header'
                );

                const titleElement = document.getElementById(
                    'regional-indicator-title'
                );

                const unitElement = document.getElementById(
                    'regional-indicator-unit'
                );

                if (!headerElement) {
                    return;
                }

                if (!data) {
                    headerElement.classList.add('d-none');

                    if (titleElement) {
                        titleElement.textContent = 'Indikator';
                    }

                    if (unitElement) {
                        unitElement.textContent = 'Satuan: -';
                    }

                    return;
                }

                if (titleElement) {
                    titleElement.textContent =
                        data.title || 'Indikator';
                }

                if (unitElement) {
                    unitElement.textContent =
                        `Satuan: ${data.unit || '-'}`;
                }

                headerElement.classList.remove('d-none');
            }

            function showChartMessage(message, type = 'info') {
                const wrapper = getChartWrapper();

                if (!wrapper) {
                    return;
                }

                destroyChartInstances();

                wrapper.innerHTML = '';

                const alert = document.createElement('div');

                alert.className = `alert alert-${type} text-center mb-0`;
                alert.textContent = message;

                wrapper.appendChild(alert);
            }

            function formatNumber(value) {
                if (
                    value === null ||
                    value === undefined ||
                    value === ''
                ) {
                    return '-';
                }

                const number = Number(value);

                if (Number.isNaN(number)) {
                    return value;
                }

                return number.toLocaleString('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                });
            }

            function renderCharts(data) {
                const wrapper = getChartWrapper();

                if (!wrapper) {
                    return;
                }

                destroyChartInstances();

                wrapper.innerHTML = '';

                const charts = Array.isArray(data?.charts) ?
                    data.charts : [];

                if (charts.length === 0) {
                    showChartMessage(
                        'Data grafik untuk indikator tersebut tidak ditemukan.',
                        'warning'
                    );

                    return;
                }

                const colorTarget = 'rgba(85, 89, 92, 0.7)';
                const colorAchievement = 'rgba(55, 188, 155, 0.7)';
                const colorPerformance = 'rgba(59, 175, 218, 0.7)';

                charts.forEach(function(chartData) {
                    const card = document.createElement('div');

                    card.className = 'card border mb-3';

                    const cardHeader = document.createElement('div');

                    cardHeader.className = 'card-header';

                    const cardTitle = document.createElement('h4');

                    cardTitle.className = 'font-weight-bold mb-0';
                    cardTitle.textContent = `Tahun ${chartData.year}`;

                    cardHeader.appendChild(cardTitle);

                    const cardBody = document.createElement('div');

                    cardBody.className = 'card-body';

                    const canvasWrapper = document.createElement('div');

                    canvasWrapper.style.position = 'relative';
                    canvasWrapper.style.height = '350px';

                    const canvas = document.createElement('canvas');

                    canvasWrapper.appendChild(canvas);
                    cardBody.appendChild(canvasWrapper);

                    card.appendChild(cardHeader);
                    card.appendChild(cardBody);

                    wrapper.appendChild(card);

                    const context = canvas.getContext('2d');

                    const chartInstance = new Chart(context, {
                        type: 'bar',

                        data: {
                            labels: chartData.labels || [],

                            datasets: [{
                                    label: 'Target',
                                    data: chartData.target || [],
                                    backgroundColor: colorTarget,
                                    borderColor: colorTarget,
                                    borderWidth: 1
                                },
                                {
                                    label: 'Capaian',
                                    data: chartData.achievement || [],
                                    backgroundColor: colorAchievement,
                                    borderColor: colorAchievement,
                                    borderWidth: 1
                                },
                                {
                                    label: 'Kinerja',
                                    data: chartData.performance || [],
                                    backgroundColor: colorPerformance,
                                    borderColor: colorPerformance,
                                    borderWidth: 1
                                }
                            ]
                        },

                        options: {
                            responsive: true,
                            maintainAspectRatio: false,

                            legend: {
                                display: true,
                                position: 'top'
                            },

                            tooltips: {
                                callbacks: {
                                    label: function(tooltipItem, chart) {
                                        const dataset =
                                            chart.datasets[
                                                tooltipItem.datasetIndex
                                            ];

                                        const value =
                                            dataset.data[
                                                tooltipItem.index
                                            ];

                                        const unit = data?.unit &&
                                            data.unit !== '-' ?
                                            ` ${data.unit}` :
                                            '';

                                        return `${dataset.label}: ${formatNumber(value)}${unit}`;
                                    }
                                }
                            },

                            scales: {
                                xAxes: [{
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Periode'
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
                                        fontSize: 12,

                                        callback: function(value) {
                                            return formatNumber(value);
                                        }
                                    },

                                    scaleLabel: {
                                        display: true,
                                        labelString: data?.unit &&
                                            data.unit !== '-' ?
                                            `Nilai (${data.unit})` : 'Nilai'
                                    }
                                }]
                            }
                        }
                    });

                    regionalIndicatorChartInstances.push(chartInstance);
                });
            }

            async function loadIndicatorChart(indicatorName) {
                const wrapper = getChartWrapper();

                if (!wrapper) {
                    return;
                }

                if (!indicatorName) {
                    updateHeader();

                    showChartMessage(
                        'Silakan pilih nama indikator untuk menampilkan grafik.',
                        'info'
                    );

                    return;
                }

                const loader = showLoader(wrapper);

                try {
                    const params = new URLSearchParams({
                        indicator_name: indicatorName
                    });

                    const response = await fetch(
                        `${REGIONAL_INDICATOR_CONFIG.chartUrl}?${params.toString()}`, {
                            method: 'GET',

                            headers: {
                                'Accept': 'application/json'
                            }
                        }
                    );

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(
                            result.message ||
                            'Gagal mengambil data grafik.'
                        );
                    }

                    if (
                        result.status !== 'success' ||
                        !result.data
                    ) {
                        throw new Error(
                            result.message ||
                            'Respons data grafik tidak valid.'
                        );
                    }

                    updateHeader(result.data);
                    renderCharts(result.data);
                } catch (error) {
                    console.error(error);

                    updateHeader();

                    showChartMessage(
                        error.message ||
                        'Terjadi kesalahan saat memuat grafik.',
                        'danger'
                    );
                } finally {
                    removeLoader(wrapper);
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const nameSelect = document.getElementById(
                    'regional-indicator-name-select'
                );

                if (!nameSelect) {
                    return;
                }

                nameSelect.value = '';

                updateHeader();

                if (nameSelect.options.length > 1) {
                    showChartMessage(
                        'Silakan pilih nama indikator untuk menampilkan grafik.',
                        'info'
                    );
                } else {
                    showChartMessage(
                        'Data nama indikator belum tersedia.',
                        'warning'
                    );
                }

                nameSelect.addEventListener('change', function() {
                    loadIndicatorChart(this.value);
                });
            });
        </script>
    @endpush
</x-app-layout>
