<x-app-layout>
    @php $pageTitle = __('Dashboard') @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <div class="content-body">
        <div class="row">
            <div class="col-12">
                @include('layouts.partials.alert')
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-md-9">
                        <div class="card" style="background: linear-gradient(135deg, #06366d, #0d4d9c);">
                            <div class="card-body">
                                <div class="d-flex justify-content-center justify-content-md-between align-items-center flex-wrap"
                                    style="gap: 1.5rem">
                                    <!-- Title -->
                                    <h1
                                        class="text-uppercase font-weight-bold text-md-left mb-0 text-center text-white">
                                        {{ __('Dashboard') }}
                                    </h1>

                                    <!-- Time -->
                                    <div class="d-inline-block text-dark rounded bg-white p-1" id="server-time"
                                        data-servertime="{{ \Carbon\Carbon::now()->timestamp * 1000 }}">
                                        {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y | HH:mm:ss') . ' WITA' }}
                                    </div>
                                </div>

                                <hr class="my-2 border border-white">

                                @php
                                    $user = Auth::user();
                                    $userName = $user->name ?? '';
                                    $initName = $userName ? strtoupper(substr(trim($userName), 0, 1)) : '';
                                    $userRole = $user->role ?? '';
                                @endphp

                                <div class="d-flex justify-content-center justify-content-md-start align-items-center flex-wrap"
                                    style="gap: 1.5rem">
                                    <div class="d-flex justify-content-center align-items-center rounded-circle font-weight-bold bg-white p-2"
                                        style="height: 6rem; width: 6rem; font-size: 4rem; color: #06366d;">
                                        {{ $initName }}
                                    </div>
                                    <div class="text-md-left text-center">
                                        <h6 class="font-italic mb-0 text-white">Selamat Datang Kembali</h6>
                                        <h3 class="mb-1 text-white">{{ $userName }}</h3>
                                        <span
                                            class="text-uppercase badge badge-pill text-dark bg-white">{{ $userRole }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body pb-0">
                                <h5 class="font-weight-bold text-uppercase mb-2 text-center">
                                    Pengunjung <span>{{ config('app.name') }}</span>
                                </h5>

                                <ul class="list-group list-group-flush">
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-0 py-1">
                                        <span>
                                            <i class="fa fa-users mr-1"></i> Total:
                                        </span>
                                        {{ $visitorData->total ?? 0 }}
                                    </li>

                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-0 py-1">
                                        <span>
                                            <i class="fa fa-calendar mr-1"></i> Bulan Ini:
                                        </span>
                                        {{ $visitorData->monthly ?? 0 }}
                                    </li>

                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-0 py-1">
                                        <span>
                                            <i class="fa fa-calendar-check-o mr-1"></i> Hari Ini:
                                        </span>
                                        {{ $visitorData->today ?? 0 }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="font-weight-bold text-uppercase mb-0"><i class="icon-target mr-1"></i> Indikator
                            Kinerja
                            Utama (IKU)</h4>
                    </div>

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap" style="gap: 1rem">
                            <div>
                                <h5 class="font-weight-bold mb-1" id="main-indicator-title">
                                    Indikator
                                </h5>
                                <h6 class="mb-0" id="main-indicator-unit">Satuan: -</h6>
                            </div>

                            <div style="min-width: 280px;">
                                <select id="main-indicator-name-select" class="custom-select"
                                    aria-label="Nama Indikator">
                                    @foreach ($mainIndicatorNames as $mainIndicatorName)
                                        <option value="{{ $mainIndicatorName }}">
                                            {{ \Illuminate\Support\Str::limit($mainIndicatorName, 30, '...') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="main-indicator-chart-wrapper">
                            <div style="height: 300px;">
                                <canvas id="main-indicator-chart" class="h-100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap" style="gap: 1rem">
                            <h4 class="font-weight-bold text-uppercase mb-0"><i class="icon-wallet mr-1"></i> Realisasi
                                Keuangan
                            </h4>
                            <div style="min-width: 160px;">
                                <select id="department-select" class="custom-select" aria-label="Bidang">
                                    <option value="" selected>Semua Bidang</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-lg-7 mb-2">
                                <div class="table-responsive">
                                    <table class="table-borderless table-hover mb-0 table">
                                        <tbody>
                                            <tr>
                                                <td class="font-weight-bold" width="75">Bidang</td>
                                                <td width="5">:</td>
                                                <td id="summary-department-label">-</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Anggaran</td>
                                                <td>:</td>
                                                <td id="summary-budget-value">-</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Kontrak</td>
                                                <td>:</td>
                                                <td id="summary-contract-value">-</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Realisasi</td>
                                                <td>:</td>
                                                <td id="summary-realization-value">-</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Saldo</td>
                                                <td>:</td>
                                                <td id="summary-balance-value">-</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Persentase</td>
                                                <td>:</td>
                                                <td id="summary-percentage-value">-</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-5 mb-2">
                                <div id="realization-chart-wrapper">
                                    <div style="height: 240px;">
                                        <canvas id="realization-chart" class="h-100"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card" style="max-height: 414px;">
                    <div class="card-header">
                        <h4 class="font-weight-bold text-uppercase mb-0">
                            <i class="icon-clock mr-1"></i> Akses Sistem
                        </h4>
                    </div>

                    <div class="card-body" style="overflow-y: auto;">
                        @forelse($userActivities as $user)
                            <div class="user-activity d-flex align-items-center p-1"
                                style="gap: 1.25rem; @if (!$loop->last) border-bottom: 1px solid #b2b2b2; @endif">
                                <div class="d-flex justify-content-center align-items-center rounded-circle font-weight-bold bg-indigo text-white"
                                    style="height: 2.5rem; width: 2.5rem; font-size: 1.5rem; padding: 1rem;">
                                    {{ $user->name ? strtoupper(substr(trim($user->name), 0, 1)) : '' }}
                                </div>

                                <div class="flex-grow-1" style="min-width:0;">
                                    <h6 class="font-weight-bold text-clamp-lg w-100 mb-0">{{ $user->name }}</h6>
                                    <small class="text-muted d-flex flex-lg-column flex-row">
                                        <span style="margin-right: 0.25rem">Terakhir masuk</span>
                                        <span>{{ \Carbon\Carbon::parse($user->last_login_at)->locale(app()->getLocale())->diffForHumans() }}</span>
                                    </small>
                                </div>
                            </div>
                        @empty
                            <div class="text-muted py-5 text-center">
                                <i class="fas fa-user-slash fa-2x mb-2"></i>
                                <div>Tidak ada aktivitas</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" type="text/css"
            href="{{ asset('app-assets/vendors/css/forms/selects/select2.min.css') }}">

        <style>
            .user-activity {
                transition: all .3s ease;
            }

            .user-activity:hover {
                background: rgba(102, 16, 242, 0.05);
            }

            @media (min-width: 992px) {
                .text-clamp-lg {
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>

        <script src="{{ asset('app-assets/vendors/js/charts/chart.min.js') }}"></script>

        <script src="{{ asset('app-assets/vendors/js/charts/chart.min.js') }}"></script>

        <script>
            const MAIN_INDICATOR_CONFIG = {
                chartUrl: "{{ route('dashboard.performance-indicators.main-indicators.chart') }}",
                currentYear: @json(\Carbon\Carbon::now()->year)
            };

            let mainIndicatorChartInstances = [];

            function getChartWrapper() {
                const wrapper = document.getElementById(
                    'main-indicator-chart-wrapper'
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
                mainIndicatorChartInstances.forEach(function(chart) {
                    if (chart) {
                        chart.destroy();
                    }
                });

                mainIndicatorChartInstances = [];
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

                const loader = wrapper.querySelector(
                    '[data-loader="1"]'
                );

                if (loader) {
                    loader.remove();
                }
            }

            function updateHeader(data = null) {
                const headerElement = document.getElementById(
                    'main-indicator-header'
                );

                const titleElement = document.getElementById(
                    'main-indicator-title'
                );

                const unitElement = document.getElementById(
                    'main-indicator-unit'
                );

                if (!data) {
                    if (headerElement) {
                        headerElement.classList.add('d-none');
                    }

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

                if (headerElement) {
                    headerElement.classList.remove('d-none');
                }
            }

            function showChartMessage(message, type = 'info') {
                const wrapper = getChartWrapper();

                if (!wrapper) {
                    return;
                }

                destroyChartInstances();

                wrapper.innerHTML = '';

                const alert = document.createElement('div');

                alert.className =
                    `alert alert-${type} text-center mb-0`;

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

            function getSelectedYearChart(charts) {
                if (
                    !Array.isArray(charts) ||
                    charts.length === 0
                ) {
                    return null;
                }

                const currentYear = String(
                    MAIN_INDICATOR_CONFIG.currentYear
                );

                /*
                 * Prioritas pertama adalah data tahun berjalan.
                 */
                const currentYearChart = charts.find(
                    function(chartData) {
                        return String(chartData.year) === currentYear;
                    }
                );

                if (currentYearChart) {
                    return currentYearChart;
                }

                /*
                 * Jika data tahun berjalan tidak tersedia,
                 * ambil data dengan tahun paling baru.
                 */
                const sortedCharts = [...charts].sort(
                    function(firstChart, secondChart) {
                        return Number(secondChart.year) -
                            Number(firstChart.year);
                    }
                );

                return sortedCharts[0] || null;
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

                const selectedChart = getSelectedYearChart(charts);

                if (!selectedChart) {
                    showChartMessage(
                        'Data grafik untuk indikator tersebut tidak ditemukan.',
                        'warning'
                    );

                    return;
                }

                const colorTarget =
                    'rgba(85, 89, 92, 0.7)';

                const colorAchievement =
                    'rgba(55, 188, 155, 0.7)';

                const colorPerformance =
                    'rgba(59, 175, 218, 0.7)';

                const canvasWrapper =
                    document.createElement('div');

                canvasWrapper.style.position = 'relative';
                canvasWrapper.style.height = '300px';

                const canvas = document.createElement('canvas');

                canvas.className = 'h-100';

                canvasWrapper.appendChild(canvas);
                wrapper.appendChild(canvasWrapper);

                const context = canvas.getContext('2d');

                const chartInstance = new Chart(context, {
                    type: 'bar',

                    data: {
                        labels: selectedChart.labels || [],

                        datasets: [{
                                label: 'Target',
                                data: selectedChart.target || [],
                                backgroundColor: colorTarget,
                                borderColor: colorTarget,
                                borderWidth: 1
                            },
                            {
                                label: 'Capaian',
                                data: selectedChart.achievement || [],
                                backgroundColor: colorAchievement,
                                borderColor: colorAchievement,
                                borderWidth: 1
                            },
                            {
                                label: 'Kinerja',
                                data: selectedChart.performance || [],
                                backgroundColor: colorPerformance,
                                borderColor: colorPerformance,
                                borderWidth: 1
                            }
                        ]
                    },

                    options: {
                        responsive: true,
                        maintainAspectRatio: false,

                        title: {
                            display: true,
                            text: `Tahun ${selectedChart.year}`,
                            fontSize: 16,
                            fontStyle: 'bold'
                        },

                        legend: {
                            display: true,
                            position: 'top'
                        },

                        tooltips: {
                            callbacks: {
                                label: function(
                                    tooltipItem,
                                    chart
                                ) {
                                    const dataset =
                                        chart.datasets[
                                            tooltipItem.datasetIndex
                                        ];

                                    const value =
                                        dataset.data[
                                            tooltipItem.index
                                        ];

                                    const unit =
                                        data?.unit &&
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
                                        return formatNumber(
                                            value
                                        );
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

                mainIndicatorChartInstances.push(
                    chartInstance
                );
            }

            async function loadIndicatorChart(indicatorName) {
                const wrapper = getChartWrapper();

                if (!wrapper) {
                    return;
                }

                if (!indicatorName) {
                    updateHeader();

                    showChartMessage(
                        'Data nama indikator belum tersedia.',
                        'warning'
                    );

                    return;
                }

                const loader = showLoader(wrapper);

                try {
                    /*
                     * Tahun tidak dikirim ke controller.
                     * Controller tetap mengirim semua tahun,
                     * kemudian dashboard memilih tahun berjalan
                     * atau tahun paling baru melalui JavaScript.
                     */
                    const params = new URLSearchParams({
                        indicator_name: indicatorName
                    });

                    const response = await fetch(
                        `${MAIN_INDICATOR_CONFIG.chartUrl}?${params.toString()}`, {
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

            document.addEventListener(
                'DOMContentLoaded',
                function() {
                    const nameSelect =
                        document.getElementById(
                            'main-indicator-name-select'
                        );

                    if (!nameSelect) {
                        return;
                    }

                    /*
                     * Dashboard tidak mengosongkan select.
                     * Option pertama otomatis menjadi indikator awal.
                     */
                    if (
                        nameSelect.options.length > 0 &&
                        nameSelect.value
                    ) {
                        loadIndicatorChart(
                            nameSelect.value
                        );
                    } else {
                        updateHeader();

                        showChartMessage(
                            'Data nama indikator belum tersedia.',
                            'warning'
                        );
                    }

                    nameSelect.addEventListener(
                        'change',
                        function() {
                            loadIndicatorChart(
                                this.value
                            );
                        }
                    );
                }
            );
        </script>

        <script>
            let realizationChartInstance = null;
            let realizationChartPayload = null;

            function ensureRealizationWrapperRelative() {
                const wrapper = document.getElementById('realization-chart-wrapper');
                if (!wrapper) return null;

                if (getComputedStyle(wrapper).position === 'static') {
                    wrapper.style.position = 'relative';
                }

                return wrapper;
            }

            function showRealizationLoader(wrapper) {
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

            function removeRealizationLoader(wrapper) {
                const existing = wrapper.querySelector('[data-loader="1"]');
                if (existing) existing.remove();
            }

            function populateDepartmentOptions(rows) {
                const select = document.getElementById('department-select');
                if (!select) return;

                const currentValue = select.value;

                select.innerHTML = '<option value="">Semua Bidang</option>';

                rows.forEach((item) => {
                    const option = document.createElement('option');
                    option.value = item.department ?? '';
                    option.textContent = item.department_label ?? 'Tanpa Bidang';
                    select.appendChild(option);
                });

                if ([...select.options].some(option => option.value === currentValue)) {
                    select.value = currentValue;
                }
            }

            function getSelectedDepartmentData() {
                const select = document.getElementById('department-select');
                const selectedValue = select ? select.value : '';

                if (!realizationChartPayload) {
                    return null;
                }

                if (selectedValue === '') {
                    return realizationChartPayload.summary;
                }

                const selectedRow = realizationChartPayload.departments.find((item) => {
                    return (item.department ?? '') === selectedValue;
                });

                return selectedRow ?? realizationChartPayload.summary;
            }

            function updateSummary(data) {
                document.getElementById('summary-department-label').innerText = data.department_label ?? 'Semua Bidang';
                document.getElementById('summary-budget-value').innerText = data.total_budget_value_formatted ?? '-';
                document.getElementById('summary-contract-value').innerText = data.total_contract_value_formatted ?? '-';
                document.getElementById('summary-realization-value').innerText = data.total_realization_formatted ?? '-';
                document.getElementById('summary-balance-value').innerText = data.total_balance_formatted ?? '-';
                document.getElementById('summary-percentage-value').innerText = data.percentage_formatted ?? '-';
            }

            function renderOrUpdateRealizationChart(data) {
                const canvas = document.getElementById('realization-chart');
                if (!canvas) return;

                const ctx = canvas.getContext('2d');

                if (realizationChartInstance) {
                    realizationChartInstance.destroy();
                    realizationChartInstance = null;
                }

                realizationChartInstance = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Kontrak', 'Realisasi', 'Saldo'],
                        datasets: [{
                            data: [
                                Number(data.total_contract_value ?? 0),
                                Number(data.total_realization ?? 0),
                                Number(data.total_balance ?? 0),
                            ],
                            backgroundColor: [
                                'rgba(0, 123, 255, 0.75)',
                                'rgba(246, 187, 66, 0.75)',
                                'rgba(85, 89, 92, 0.75)'
                            ],
                            borderColor: [
                                'rgba(0, 123, 255, 1)',
                                'rgba(246, 187, 66, 1)',
                                'rgba(85, 89, 92, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutoutPercentage: 55,
                        legend: {
                            position: 'bottom'
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    const dataset = data.datasets[tooltipItem.datasetIndex];
                                    const value = dataset.data[tooltipItem.index] ?? 0;
                                    const label = data.labels[tooltipItem.index] ?? '';

                                    return `${label}: ${new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }).format(value)}`;
                                }
                            }
                        }
                    }
                });
            }

            function updateRealizationSection() {
                const data = getSelectedDepartmentData();
                if (!data) return;

                updateSummary(data);
                renderOrUpdateRealizationChart(data);
            }

            async function loadRealizationChart() {
                const wrapper = ensureRealizationWrapperRelative();
                if (!wrapper) return;

                removeRealizationLoader(wrapper);
                const loader = showRealizationLoader(wrapper);

                try {
                    const res = await fetch("{{ route('dashboard.monev.finances.contracts.data-chart') }}", {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    const json = await res.json();

                    if (json.status === 'success') {
                        realizationChartPayload = json.data;

                        populateDepartmentOptions(realizationChartPayload.departments ?? []);
                        updateRealizationSection();
                    } else {
                        console.error(json);
                        alert('Gagal memuat data chart realisasi.');
                    }
                } catch (error) {
                    console.error(error);
                    alert('Gagal memuat data chart realisasi.');
                } finally {
                    setTimeout(() => {
                        if (loader) loader.remove();
                    }, 300);
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const select = document.getElementById('department-select');

                loadRealizationChart();

                if (select) {
                    select.addEventListener('change', function() {
                        updateRealizationSection();
                    });
                }
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const timeEl = document.getElementById("server-time");
                if (!timeEl) return;

                const serverTimestamp = parseInt(timeEl.dataset.servertime);
                const timeDiff = serverTimestamp - Date.now();

                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                    'Oktober', 'November', 'Desember'
                ];

                function updateTime() {
                    const now = new Date(Date.now() + timeDiff);
                    const formatted =
                        `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()} | ` +
                        `${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}:${String(now.getSeconds()).padStart(2,'0')} WITA`;
                    timeEl.textContent = formatted;

                    // Panggil updateTime lagi di frame berikutnya
                    requestAnimationFrame(updateTime);
                }

                // Mulai update
                requestAnimationFrame(updateTime);
            });
        </script>
    @endpush
</x-app-layout>
