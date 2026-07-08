<x-app-layout>
    @php $pageTitle = __('Dashboard') . ' Survei IKLI' @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <div class="content-body">
        <div class="row">
            <div class="col-12">
                @include('layouts.partials.alert')
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center justify-content-sm-between align-items-center flex-wrap"
                            style="gap: 1.5rem">
                            <h3 class="font-weight-bold text-uppercase mb-0">{{ $pageTitle }}</h3>
                            <h5 class="mb-0" id="server-time"
                                data-servertime="{{ \Carbon\Carbon::now()->timestamp * 1000 }}">
                                {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y | HH:mm:ss') . ' WITA' }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card" style="height: 380px">
                            <div class="card-body d-flex justify-content-center align-items-center text-center">
                                <div class="d-flex flex-column">
                                    @php
                                        $user = Auth::user();
                                        $userName = $user->name ?? '';
                                        $initName = $userName ? strtoupper(substr(trim($userName), 0, 1)) : '';
                                        $userRole = $user->role ?? '';
                                    @endphp
                                    <h4 class="mb-2">Selamat Datang</h4>
                                    <div class="d-block mx-auto mb-2">
                                        <div class="d-flex justify-content-center align-items-center rounded-circle font-weight-bold bg-indigo p-2 text-white"
                                            style="height: 8rem; width: 8rem; font-size: 6rem; ">
                                            {{ $initName }}
                                        </div>
                                    </div>
                                    <h5 class="mb-0">{{ $userName }}</h5>
                                    <span class="text-uppercase mb-0">({{ $userRole }})</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="font-weight-bold indigo text-uppercase mb-2">
                                    Monitoring Pengisian Kuesioner
                                </h4>

                                <div class="position-relative" id="questionnaireStatisticWrapper"
                                    style="height: 240px;">
                                    <canvas id="questionnaireStatisticChart"></canvas>
                                </div>

                                <div class="mt-2">
                                    <p class="text-center">Total Bulan Ini:
                                        <strong><span id="totalQuestionnaire">0</span></strong> Responden
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="font-weight-bold indigo text-uppercase mb-2">
                                    Monitoring Per Kecamatan
                                </h4>

                                <div class="row align-items-center">
                                    <div class="col-lg-7">
                                        <div class="position-relative" id="districtStatisticWrapper">
                                            <canvas id="districtStatisticChart"></canvas>
                                        </div>

                                        <div class="mt-2 mb-3 mb-lg-0">
                                            <p class="text-center">Total Keseluruhan:
                                                <strong><span id="totalDistrict">0</span></strong> Responden
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="table-responsive">
                                            <table
                                                class="table-striped table-bordered table-custom table-align-middle table">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th rowspan="2" class="align-middle">No.</th>
                                                        <th rowspan="2" class="align-middle">Kecamatan</th>
                                                        <th colspan="2">Responden</th>
                                                    </tr>
                                                    <tr class="text-center">
                                                        <th>Minimal</th>
                                                        <th>Jumlah</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="districtStatisticTableBody">
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">
                                                            Memuat data...
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="mb-2">
                                <h4 class="font-weight-bold indigo text-uppercase mb-1">
                                    Analisis Keseluruhan
                                </h4>
                                <div>
                                    Total Penduduk:
                                    <span id="totalResident" class="font-weight-bold">0</span>
                                </div>
                            </div>

                            <div style="width: 15rem;" class="mb-2">
                                <select class="custom-select select2" id="filter_district">
                                    <option value="">Semua Kecamatan</option>
                                </select>
                            </div>
                        </div>

                        <div class="card border-indigo">
                            <div class="card-header bg-indigo">
                                <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                    Indeks Kepuasan
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table-bordered table-sm table-striped table-align-middle w-100 table">
                                        <thead>
                                            <tr class="text-center">
                                                <th style="width: 60px;">No.</th>
                                                <th>Jenis Infrastruktur</th>
                                                <th style="width: 200px;">Total Responden</th>
                                                <th style="width: 200px;">Nilai Indeks</th>
                                                <th style="width: 200px;">Mutu</th>
                                                <th style="width: 300px;">Keterangan</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($infrastructureType as $key => $label)
                                                <tr>
                                                    <td class="text-right">{{ $loop->iteration }}</td>

                                                    <!-- Jenis Infrastruktur -->
                                                    <td>{{ $label }}</td>

                                                    <!-- Total Responden -->
                                                    <td class="text-right">
                                                        <span id="total-respondent-{{ $key }}">0</span>
                                                    </td>

                                                    <!-- Nilai IKM -->
                                                    <td class="text-right">
                                                        <span id="ikm-value-{{ $key }}">0.00</span>
                                                    </td>

                                                    <!-- Mutu -->
                                                    <td class="text-center">
                                                        <span id="ikm-quality-{{ $key }}">-</span>
                                                    </td>

                                                    <!-- Keterangan -->
                                                    <td class="text-center">
                                                        <span id="ikm-label-{{ $key }}">-</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr class="font-weight-bold bg-light">
                                                <td colspan="3" class="text-center">Nilai Indeks Kepuasan Keseluruhan
                                                </td>
                                                <td class="text-right">
                                                    <span id="ikm-total-value">0.00</span>
                                                </td>
                                                <td class="text-center">
                                                    <span id="ikm-total-quality">-</span>
                                                </td>
                                                <td class="text-center">
                                                    <span id="ikm-total-label">-</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card border-indigo">
                            <div class="card-header bg-indigo">
                                <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                    Indeks Harapan
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table
                                        class="table-bordered table-sm table-striped table-align-middle w-100 table">
                                        <thead>
                                            <tr class="text-center">
                                                <th style="width: 60px;">No.</th>
                                                <th>Jenis Infrastruktur</th>
                                                <th style="width: 200px;">Total Responden</th>
                                                <th style="width: 200px;">Nilai Indeks</th>
                                                <th style="width: 200px;">Mutu</th>
                                                <th style="width: 300px;">Keterangan</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($infrastructureType as $key => $label)
                                                <tr>
                                                    <td class="text-right">{{ $loop->iteration }}</td>

                                                    <!-- Jenis Infrastruktur -->
                                                    <td>{{ $label }}</td>

                                                    <!-- Total Responden -->
                                                    <td class="text-right">
                                                        <span id="ihm-total-respondent-{{ $key }}">0</span>
                                                    </td>

                                                    <!-- Nilai IHM -->
                                                    <td class="text-right">
                                                        <span id="ihm-value-{{ $key }}">0.00</span>
                                                    </td>

                                                    <!-- Mutu -->
                                                    <td class="text-center">
                                                        <span id="ihm-quality-{{ $key }}">-</span>
                                                    </td>

                                                    <!-- Keterangan -->
                                                    <td class="text-center">
                                                        <span id="ihm-label-{{ $key }}">-</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr class="font-weight-bold bg-light">
                                                <td colspan="3" class="text-center">Nilai Indeks Harapan
                                                    Keseluruhan
                                                </td>
                                                <td class="text-right">
                                                    <span id="ihm-total-value">0.00</span>
                                                </td>
                                                <td class="text-center">
                                                    <span id="ihm-total-quality">-</span>
                                                </td>
                                                <td class="text-center">
                                                    <span id="ihm-total-label">-</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card border-indigo">
                            <div class="card-header bg-indigo">
                                <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                    Prioritas Peningkatan
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-lg-7">
                                        <div class="position-relative" id="priorityStatisticWrapper"
                                            style="height: 320px;">
                                            <canvas id="priorityStatisticChart"></canvas>
                                        </div>

                                        <div class="mt-2 mb-3 mb-lg-0">
                                            <p class="text-center">Total Keseluruhan:
                                                <strong><span id="totalPriorityInfrastructur">0</span></strong>
                                                Responden
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-lg-5">
                                        {{-- Accordion Prioritas Infrastruktur --}}
                                        <div id="priorityStatisticAccordion">
                                            <div class="card mb-1">
                                                <div class="card-body text-center text-muted">
                                                    Memuat data...
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
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="font-weight-bold indigo text-uppercase mb-2">
                                    Jenis Kelamin
                                </h4>

                                <div class="position-relative" id="genderStatisticWrapper" style="height: 240px;">
                                    <canvas id="genderStatisticChart"></canvas>
                                </div>

                                <div class="mt-2">
                                    <p class="text-center">Total:
                                        <strong><span id="totalGender">0</span></strong> Responden
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="font-weight-bold indigo text-uppercase mb-2">
                                    Disabilitas/Non-Disabilitas
                                </h4>

                                <div class="position-relative" id="disabilityStatusStatisticWrapper"
                                    style="height: 240px;">
                                    <canvas id="disabilityStatusStatisticChart"></canvas>
                                </div>

                                <div class="mt-2">
                                    <p class="text-center">Total:
                                        <strong><span id="totalDisabilityStatus">0</span></strong> Responden
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="font-weight-bold indigo text-uppercase mb-2">
                                    Usia
                                </h4>

                                <div class="position-relative" id="ageStatisticWrapper" style="height: 240px;">
                                    <canvas id="ageStatisticChart"></canvas>
                                </div>

                                <div class="mt-2">
                                    <p class="text-center">Total:
                                        <strong><span id="totalAge">0</span></strong> Responden
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="font-weight-bold indigo text-uppercase mb-2">
                                    Pendidikan Terakhir
                                </h4>

                                <div class="position-relative" id="educationStatisticWrapper" style="height: 240px;">
                                    <canvas id="educationStatisticChart"></canvas>
                                </div>

                                <div class="mt-2">
                                    <p class="text-center">Total:
                                        <strong><span id="totalEducation">0</span></strong> Responden
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="font-weight-bold indigo text-uppercase mb-2">
                                    Pekerjaan
                                </h4>

                                <div class="position-relative" id="occupationStatisticWrapper"
                                    style="height: 240px;">
                                    <canvas id="occupationStatisticChart"></canvas>
                                </div>

                                <div class="mt-2">
                                    <p class="text-center">Total:
                                        <strong><span id="totalOccupation">0</span></strong> Responden
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" type="text/css"
            href="{{ asset('app-assets/vendors/css/forms/selects/select2.min.css') }}">

        <style>
            #occupationStatisticWrapper {
                height: 500px !important;
            }

            #districtStatisticWrapper {
                height: 240px;
            }

            /* md (≥768px) */
            @media (min-width: 768px) {
                #districtStatisticWrapper {
                    height: 340px;
                }
            }

            /* lg (≥992px) */
            @media (min-width: 992px) {
                #districtStatisticWrapper {
                    height: 400px;
                }
            }

            /* xl (≥1200px) */
            @media (min-width: 1200px) {
                #districtStatisticWrapper {
                    height: 440px;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
        <script src="{{ asset('app-assets/vendors/js/charts/chart.min.js') }}"></script>

        <script>
            // ===============================
            // Helper Statistik Dashboard
            // ===============================
            const DashboardStatisticHelper = {
                createLoader: function(wrapper) {
                    const loader = document.createElement('div');

                    loader.innerHTML = `<span class="ft-refresh-cw icon-spin"></span>&nbsp; Memuat data...`;

                    Object.assign(loader.style, {
                        position: 'absolute',
                        top: '50%',
                        left: '50%',
                        transform: 'translate(-50%, -50%)',
                        zIndex: 9999,
                        padding: '10px 15px',
                        color: '#fff',
                        backgroundColor: '#333',
                        borderRadius: '4px',
                        display: 'none',
                        fontSize: '14px'
                    });

                    wrapper.style.position = 'relative';
                    wrapper.appendChild(loader);

                    return loader;
                },

                showLoader: function(loader) {
                    loader.style.display = 'block';
                },

                hideLoader: function(loader) {
                    loader.style.display = 'none';
                },

                formatNumber: function(value) {
                    return new Intl.NumberFormat('id-ID').format(parseInt(value) || 0);
                },

                normalizeDistrictName: function(text) {
                    return (text || '')
                        .toString()
                        .trim()
                        .toUpperCase()
                        .replace(/^KECAMATAN\s+/i, '')
                        .replace(/\s+/g, ' ');
                },

                getStableRandomColor: function(text, opacity) {
                    let hash = 0;

                    for (let i = 0; i < text.length; i++) {
                        hash = text.charCodeAt(i) + ((hash << 5) - hash);
                    }

                    const r = Math.abs((hash >> 0) & 255);
                    const g = Math.abs((hash >> 8) & 255);
                    const b = Math.abs((hash >> 16) & 255);

                    return `rgba(${r}, ${g}, ${b}, ${opacity})`;
                },

                getMonthName: function(month) {
                    const monthIndonesia = [
                        "", "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                    ];

                    return monthIndonesia[parseInt(month)] || '';
                },

                getDayName: function(day) {
                    const days = [
                        'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
                    ];

                    return days[parseInt(day)] || '';
                },

                padZero: function(value) {
                    return String(value).padStart(2, '0');
                },

                formatDateTimeIndonesia: function(date, timezoneLabel) {
                    return `${this.getDayName(date.getDay())}, ${date.getDate()} ${this.getMonthName(date.getMonth() + 1)} ${date.getFullYear()} | ` +
                        `${this.padZero(date.getHours())}:${this.padZero(date.getMinutes())}:${this.padZero(date.getSeconds())} ${timezoneLabel || ''}`;
                },

                startServerTime: function(elementId, timezoneLabel) {
                    const timeEl = document.getElementById(elementId);

                    if (!timeEl) {
                        return;
                    }

                    const serverTimestamp = parseInt(timeEl.dataset.servertime) || Date.now();
                    const timeDiff = serverTimestamp - Date.now();

                    function updateTime() {
                        const now = new Date(Date.now() + timeDiff);

                        timeEl.textContent = DashboardStatisticHelper.formatDateTimeIndonesia(
                            now,
                            timezoneLabel || 'WITA'
                        );
                    }

                    updateTime();

                    setInterval(updateTime, 1000);
                }
            };
        </script>

        <script>
            const ctxQuestionnaire = document.getElementById('questionnaireStatisticChart').getContext('2d');
            const totalQuestionnaireEl = document.getElementById('totalQuestionnaire');
            const questionnaireWrapper = document.getElementById('questionnaireStatisticWrapper');

            let questionnaireChart = null;

            const questionnaireLoader = DashboardStatisticHelper.createLoader(questionnaireWrapper);

            // ===============================
            // Load Statistik Bulan Ini
            // ===============================
            function loadQuestionnaireStatistic() {

                const today = new Date();
                const year = today.getFullYear();
                const month = today.getMonth() + 1; // 0-based → +1

                DashboardStatisticHelper.showLoader(questionnaireLoader);

                fetch(`{{ route('ikli-survey.dashboard.questionnaire.statistic') }}?year=${year}&month=${month}`)
                    .then(res => res.json())
                    .then(res => {

                        DashboardStatisticHelper.hideLoader(questionnaireLoader);

                        if (res.status !== 'success') {
                            console.error(res);
                            return;
                        }

                        totalQuestionnaireEl.innerText = res.total_in_month || 0;

                        const monthName = DashboardStatisticHelper.getMonthName(res.month);

                        const labels = res.per_day.map(item => {
                            const date = new Date(item.date);
                            return date.getDate() + " " + monthName;
                        });

                        const data = res.per_day.map(item => item.total);

                        if (questionnaireChart) {
                            questionnaireChart.destroy();
                        }

                        questionnaireChart = new Chart(ctxQuestionnaire, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: `Jumlah Responden ${monthName} ${res.year}`,
                                    data: data,
                                    backgroundColor: 'rgba(135, 206, 235, 0.3)',
                                    borderColor: 'rgba(0, 0, 139, 1)',
                                    borderWidth: 2,
                                    fill: true,
                                    tension: 0.3,
                                    pointRadius: 3
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                legend: {
                                    labels: {
                                        fontColor: '#373a3c'
                                    }
                                },
                                tooltips: {
                                    callbacks: {
                                        title: function(tooltipItems) {
                                            const index = tooltipItems[0].index;
                                            const item = res.per_day[index];
                                            const date = new Date(item.date);

                                            return date.getDate() + " " + monthName + " " + res.year;
                                        },
                                        label: function(tooltipItem) {
                                            return "Jumlah: " + tooltipItem.yLabel + " responden";
                                        }
                                    }
                                },
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        },
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Jumlah Responden'
                                        }
                                    }],
                                    xAxes: [{
                                        ticks: {
                                            autoSkip: false
                                        }
                                    }]
                                }
                            }
                        });

                    })
                    .catch(err => {
                        DashboardStatisticHelper.hideLoader(questionnaireLoader);
                        console.error(err);
                    });
            }
        </script>

        <script>
            const ctxDistrict = document.getElementById('districtStatisticChart').getContext('2d');
            const totalDistrictEl = document.getElementById('totalDistrict');
            const districtWrapper = document.getElementById('districtStatisticWrapper');

            let districtStatisticChart = null;

            const districtLoader = DashboardStatisticHelper.createLoader(districtWrapper);

            // ===============================
            // Load Statistik Per Kecamatan
            // ===============================
            function loadDistrictStatistic() {

                const minimumSampleUrl = `{{ route('ikli-survey.dashboard.questionnaire.respondent.krejcie-morgan') }}`;
                const districtStatisticUrl = `{{ route('ikli-survey.dashboard.questionnaire.district-statistic') }}`;

                DashboardStatisticHelper.showLoader(districtLoader);

                Promise.all([
                        fetch(minimumSampleUrl).then(res => res.json()),
                        fetch(districtStatisticUrl).then(res => res.json())
                    ])
                    .then(([minimumSampleRes, districtStatisticRes]) => {

                        DashboardStatisticHelper.hideLoader(districtLoader);

                        if (districtStatisticRes.status !== 'success') {
                            console.error(districtStatisticRes);
                            return;
                        }

                        const minimumDistribution = minimumSampleRes.distribution || [];
                        const statisticData = districtStatisticRes.data || [];

                        const respondentMap = {};
                        const minimumSampleMap = {};

                        statisticData.forEach(function(item) {
                            respondentMap[DashboardStatisticHelper.normalizeDistrictName(item.district)] = parseInt(
                                item.total) || 0;
                        });

                        minimumDistribution.forEach(function(item) {
                            const districtName = item.district || item.district_name || item.name || '';
                            const minimumSample = item.sample || item.minimum_sample || item.target_sample || 0;

                            minimumSampleMap[DashboardStatisticHelper.normalizeDistrictName(districtName)] = {
                                district: districtName,
                                minimum_sample: parseInt(minimumSample) || 0
                            };
                        });

                        let tableData = [];

                        minimumDistribution.forEach(function(item) {
                            const districtName = item.district || item.district_name || item.name || '';
                            const districtKey = DashboardStatisticHelper.normalizeDistrictName(districtName);
                            const minimumSample = item.sample || item.minimum_sample || item.target_sample || 0;
                            const totalRespondent = respondentMap[districtKey] || 0;

                            tableData.push({
                                district: districtName,
                                minimum_sample: parseInt(minimumSample) || 0,
                                total: totalRespondent
                            });
                        });

                        statisticData.forEach(function(item) {
                            const districtKey = DashboardStatisticHelper.normalizeDistrictName(item.district);

                            if (!minimumSampleMap[districtKey]) {
                                tableData.push({
                                    district: item.district,
                                    minimum_sample: 0,
                                    total: parseInt(item.total) || 0
                                });
                            }
                        });

                        tableData.sort(function(a, b) {
                            return DashboardStatisticHelper.normalizeDistrictName(a.district).localeCompare(
                                DashboardStatisticHelper.normalizeDistrictName(b.district),
                                'id', {
                                    sensitivity: 'base'
                                }
                            );
                        });

                        const totalMinimumSample = tableData.reduce(function(total, item) {
                            return total + (parseInt(item.minimum_sample) || 0);
                        }, 0);

                        const totalRespondent = tableData.reduce(function(total, item) {
                            return total + (parseInt(item.total) || 0);
                        }, 0);

                        totalDistrictEl.innerText = DashboardStatisticHelper.formatNumber(totalRespondent);

                        const labels = tableData.map(item => item.district);
                        const data = tableData.map(item => parseInt(item.total) || 0);

                        const districtStatisticTableBody = document.getElementById('districtStatisticTableBody');

                        districtStatisticTableBody.innerHTML = '';

                        if (tableData.length > 0) {

                            let tableRows = '';

                            tableData.forEach(function(item, index) {
                                const minimumSample = parseInt(item.minimum_sample) || 0;
                                const total = parseInt(item.total) || 0;
                                const isCompleted = total >= minimumSample;

                                tableRows += `
                                    <tr>
                                        <td class="text-right">
                                            ${index + 1}
                                        </td>
                                        <td class="text-uppercase">
                                            ${item.district}
                                        </td>
                                        <td class="text-right font-weight-bold">
                                            ${DashboardStatisticHelper.formatNumber(minimumSample)}
                                        </td>
                                        <td class="text-right font-weight-bold ${isCompleted ? 'text-success' : 'text-danger'}">
                                            ${DashboardStatisticHelper.formatNumber(total)}
                                        </td>
                                    </tr>
                                `;
                            });

                            tableRows += `
                                <tr>
                                    <td colspan="2" class="text-center font-weight-bold">
                                        Total Keseluruhan
                                    </td>
                                    <td class="text-right font-weight-bold">
                                        ${DashboardStatisticHelper.formatNumber(totalMinimumSample)}
                                    </td>
                                    <td class="text-right font-weight-bold">
                                        ${DashboardStatisticHelper.formatNumber(totalRespondent)}
                                    </td>
                                </tr>
                            `;

                            districtStatisticTableBody.innerHTML = tableRows;

                        } else {
                            districtStatisticTableBody.innerHTML = `
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Belum ada data.
                                    </td>
                                </tr>
                            `;
                        }

                        const backgroundColors = labels.map(label => {
                            return DashboardStatisticHelper.getStableRandomColor(label, 0.7);
                        });

                        const borderColors = labels.map(label => {
                            return DashboardStatisticHelper.getStableRandomColor(label, 1);
                        });

                        if (districtStatisticChart) {
                            districtStatisticChart.destroy();
                        }

                        districtStatisticChart = new Chart(ctxDistrict, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Jumlah Responden Per Kecamatan',
                                    data: data,
                                    backgroundColor: backgroundColors,
                                    borderColor: borderColors,
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                legend: {
                                    labels: {
                                        fontColor: '#373a3c',
                                        boxWidth: 0
                                    }
                                },
                                tooltips: {
                                    callbacks: {
                                        title: function(tooltipItems) {
                                            return 'KECAMATAN ' + tooltipItems[0].xLabel;
                                        },
                                        label: function(tooltipItem) {
                                            return 'Jumlah: ' +
                                                DashboardStatisticHelper.formatNumber(tooltipItem.yLabel) +
                                                ' responden';
                                        }
                                    }
                                },
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true,
                                            precision: 0
                                        },
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Jumlah Responden'
                                        }
                                    }],
                                    xAxes: [{
                                        ticks: {
                                            autoSkip: false,
                                            maxRotation: 45,
                                            minRotation: 0
                                        },
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Kecamatan'
                                        }
                                    }]
                                }
                            }
                        });

                    })
                    .catch(err => {
                        DashboardStatisticHelper.hideLoader(districtLoader);
                        console.error(err);
                    });
            }
        </script>

        <script>
            const respondentStatisticCharts = {};
            const respondentStatisticLoaders = {};

            const respondentStatisticConfig = {
                gender: {
                    type: 'pie',
                    chartId: 'genderStatisticChart',
                    wrapperId: 'genderStatisticWrapper',
                    totalId: 'totalGender',
                    datasetLabel: 'Jenis Kelamin',
                    dataOrder: ['LAKI-LAKI', 'PEREMPUAN']
                },
                age: {
                    type: 'horizontalBar',
                    chartId: 'ageStatisticChart',
                    wrapperId: 'ageStatisticWrapper',
                    totalId: 'totalAge',
                    datasetLabel: 'Usia',
                    dataOrder: ['<20', '20-30', '31-40', '41-50', '>50']
                },
                disability_status: {
                    type: 'pie',
                    chartId: 'disabilityStatusStatisticChart',
                    wrapperId: 'disabilityStatusStatisticWrapper',
                    totalId: 'totalDisabilityStatus',
                    datasetLabel: 'Disabilitas/Non-Disabilitas',
                    dataOrder: ['DISABILITAS', 'NON-DISABILITAS']
                },
                education: {
                    type: 'horizontalBar',
                    chartId: 'educationStatisticChart',
                    wrapperId: 'educationStatisticWrapper',
                    totalId: 'totalEducation',
                    datasetLabel: 'Pendidikan Terakhir',
                    dataOrder: ['TIDAK SEKOLAH', 'SD/SEDERAJAT', 'SMP/SEDERAJAT', 'SMA/SEDERAJAT', 'D-I/D-II/D-III',
                        'S1/D-IV', '>S1'
                    ]
                },
                occupation: {
                    type: 'horizontalBar',
                    chartId: 'occupationStatisticChart',
                    wrapperId: 'occupationStatisticWrapper',
                    totalId: 'totalOccupation',
                    datasetLabel: 'Pekerjaan'
                }
            };

            Object.keys(respondentStatisticConfig).forEach(function(key) {
                const wrapper = document.getElementById(respondentStatisticConfig[key].wrapperId);

                if (wrapper) {
                    respondentStatisticLoaders[key] = DashboardStatisticHelper.createLoader(wrapper);
                }
            });

            // ===============================
            // Ambil Nilai Filter Kecamatan
            // ===============================
            function getSelectedDistrictFilterValue() {
                const districtEl = document.getElementById('filter_district');

                return districtEl ? districtEl.value : '';
            }

            // ===============================
            // Format Label Statistik Responden
            // ===============================
            function formatRespondentStatisticLabel(label, total) {
                const formattedLabel = (label || '-').toString().trim().toUpperCase();
                const formattedTotal = DashboardStatisticHelper.formatNumber(parseInt(total) || 0);

                return formattedLabel + ' (' + formattedTotal + ' Responden)';
            }

            // ===============================
            // Urutkan Data Statistik Responden
            // ===============================
            function sortRespondentStatisticData(data, dataOrder) {
                const orderedLabels = Array.isArray(dataOrder) ? dataOrder.map(function(label) {
                    return (label || '').toString().trim().toUpperCase();
                }) : [];

                return (data || []).slice().sort(function(a, b) {
                    const labelA = (a.label || '-').toString().trim().toUpperCase();
                    const labelB = (b.label || '-').toString().trim().toUpperCase();

                    const indexA = orderedLabels.indexOf(labelA);
                    const indexB = orderedLabels.indexOf(labelB);

                    if (indexA !== -1 && indexB !== -1) {
                        return indexA - indexB;
                    }

                    if (indexA !== -1) {
                        return -1;
                    }

                    if (indexB !== -1) {
                        return 1;
                    }

                    return labelA.localeCompare(labelB);
                });
            }

            // ===============================
            // Ambil URL Statistik Responden
            // ===============================
            function getRespondentStatisticUrl() {
                const params = new URLSearchParams();

                const district = getSelectedDistrictFilterValue();

                if (district) {
                    params.append('district', district);
                }

                const queryString = params.toString();

                return `{{ route('ikli-survey.dashboard.questionnaire.respondent-statistic') }}${queryString ? '?' + queryString : ''}`;
            }

            // ===============================
            // Render Chart Statistik Responden
            // ===============================
            function renderRespondentStatisticChart(key, statistic) {
                const config = respondentStatisticConfig[key];

                if (!config) {
                    return;
                }

                const canvas = document.getElementById(config.chartId);
                const totalEl = document.getElementById(config.totalId);

                if (!canvas) {
                    return;
                }

                const ctx = canvas.getContext('2d');
                const chartData = sortRespondentStatisticData(statistic.data || [], config.dataOrder);

                const rawLabels = chartData.map(function(item) {
                    return (item.label || '-').toString().trim().toUpperCase();
                });

                const labels = chartData.map(function(item) {
                    return formatRespondentStatisticLabel(item.label, item.total);
                });

                const data = chartData.map(function(item) {
                    return parseInt(item.total) || 0;
                });

                const backgroundColors = rawLabels.map(function(label) {
                    return DashboardStatisticHelper.getStableRandomColor(label, 0.7);
                });

                const borderColors = rawLabels.map(function(label) {
                    return DashboardStatisticHelper.getStableRandomColor(label, 1);
                });

                if (totalEl) {
                    totalEl.innerText = DashboardStatisticHelper.formatNumber(statistic.total_all || 0);
                }

                if (respondentStatisticCharts[key]) {
                    respondentStatisticCharts[key].destroy();
                }

                respondentStatisticCharts[key] = new Chart(ctx, {
                    type: config.type,
                    data: {
                        labels: labels,
                        datasets: [{
                            label: config.datasetLabel,
                            data: data,
                            backgroundColor: backgroundColors,
                            borderColor: borderColors,
                            borderWidth: 1
                        }]
                    },
                    options: getRespondentStatisticChartOptions(config, statistic, rawLabels)
                });
            }

            // ===============================
            // Options Chart Statistik Responden
            // ===============================
            function getRespondentStatisticChartOptions(config, statistic, rawLabels) {
                const baseOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        labels: {
                            fontColor: '#373a3c'
                        }
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                const dataset = data.datasets[tooltipItem.datasetIndex];
                                const value = dataset.data[tooltipItem.index] || 0;
                                const label = rawLabels[tooltipItem.index] || '-';
                                const total = statistic.total_all || 0;
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(2) : 0;

                                return label + ': ' +
                                    DashboardStatisticHelper.formatNumber(value) +
                                    ' responden (' + percentage + '%)';
                            }
                        }
                    }
                };

                if (config.type === 'horizontalBar') {
                    baseOptions.legend = {
                        display: false
                    };

                    baseOptions.tooltips = {
                        callbacks: {
                            title: function(tooltipItems) {
                                return rawLabels[tooltipItems[0].index] || '-';
                            },
                            label: function(tooltipItem, data) {
                                const dataset = data.datasets[tooltipItem.datasetIndex];
                                const value = dataset.data[tooltipItem.index] || 0;
                                const total = statistic.total_all || 0;
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(2) : 0;

                                return 'Jumlah: ' +
                                    DashboardStatisticHelper.formatNumber(value) +
                                    ' responden (' + percentage + '%)';
                            }
                        }
                    };

                    baseOptions.scales = {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true,
                                precision: 0
                            },
                            scaleLabel: {
                                display: false,
                                labelString: 'Jumlah Responden'
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                autoSkip: false,
                                maxRotation: 0,
                                minRotation: 0
                            }
                        }]
                    };
                }

                return baseOptions;
            }

            // ===============================
            // Load Statistik Responden
            // ===============================
            function loadRespondentStatistic() {
                Object.keys(respondentStatisticLoaders).forEach(function(key) {
                    DashboardStatisticHelper.showLoader(respondentStatisticLoaders[key]);
                });

                fetch(getRespondentStatisticUrl())
                    .then(res => res.json())
                    .then(res => {
                        Object.keys(respondentStatisticLoaders).forEach(function(key) {
                            DashboardStatisticHelper.hideLoader(respondentStatisticLoaders[key]);
                        });

                        if (res.status !== 'success') {
                            console.error(res);
                            return;
                        }

                        Object.keys(respondentStatisticConfig).forEach(function(key) {
                            renderRespondentStatisticChart(key, res.data[key] || {
                                total_all: 0,
                                data: []
                            });
                        });
                    })
                    .catch(err => {
                        Object.keys(respondentStatisticLoaders).forEach(function(key) {
                            DashboardStatisticHelper.hideLoader(respondentStatisticLoaders[key]);
                        });

                        console.error(err);
                    });
            }
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                DashboardStatisticHelper.startServerTime('server-time', 'WITA');

                // ===============================
                // Init
                // ===============================
                loadQuestionnaireStatistic();
                loadDistrictStatistic();
            });
        </script>

        <script>
            let priorityStatisticChart = null;
            let priorityStatisticLoader = null;

            const priorityStatisticWrapper = document.getElementById(
                'priorityStatisticWrapper'
            );

            if (priorityStatisticWrapper) {
                priorityStatisticLoader = DashboardStatisticHelper.createLoader(
                    priorityStatisticWrapper
                );
            }

            // ===============================
            // Event Accordion
            // ===============================
            $(document).on('shown.bs.collapse', '.js-monitor-collapse', function(e) {
                if (e.target !== this) {
                    return;
                }

                var targetId = this.id;

                $('[data-target="#' + targetId + '"], [href="#' + targetId + '"]')
                    .attr('aria-expanded', 'true')
                    .find('.js-chevron')
                    .removeClass('fa-chevron-right')
                    .addClass('fa-chevron-down');
            });

            $(document).on('hidden.bs.collapse', '.js-monitor-collapse', function(e) {
                if (e.target !== this) {
                    return;
                }

                var targetId = this.id;

                $('[data-target="#' + targetId + '"], [href="#' + targetId + '"]')
                    .attr('aria-expanded', 'false')
                    .find('.js-chevron')
                    .removeClass('fa-chevron-down')
                    .addClass('fa-chevron-right');
            });

            // ===============================
            // Ambil Nilai Filter Kecamatan
            // ===============================
            function getSelectedDistrictFilterValue() {
                const districtEl = document.getElementById('filter_district');

                return districtEl ? districtEl.value : '';
            }

            // ===============================
            // Ambil URL Statistik Prioritas Infrastruktur
            // ===============================
            function getPriorityStatisticUrl() {
                const params = new URLSearchParams();

                const district = getSelectedDistrictFilterValue();

                if (district) {
                    params.append('district', district);
                }

                const queryString = params.toString();

                return `{{ route('ikli-survey.dashboard.questionnaire.priority-statistic') }}${queryString ? '?' + queryString : ''}`;
            }

            // ===============================
            // Escape HTML
            // ===============================
            function escapePriorityStatisticHtml(value) {
                return (value || '-')
                    .toString()
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            // ===============================
            // Format Label Prioritas Infrastruktur
            // ===============================
            function formatPriorityStatisticLabel(label, total) {
                const formattedLabel = (label || '-').toString().trim().toUpperCase();
                const formattedTotal = DashboardStatisticHelper.formatNumber(parseInt(total) || 0);

                return formattedLabel + ' (' + formattedTotal + ' Responden)';
            }

            // ===============================
            // Urutkan Data Prioritas Infrastruktur
            // ===============================
            function sortPriorityStatisticData(data) {
                return (data || []).slice().sort(function(a, b) {
                    const totalA = parseInt(a.total) || 0;
                    const totalB = parseInt(b.total) || 0;

                    if (totalA !== totalB) {
                        return totalB - totalA;
                    }

                    const labelA = (a.priority_infrastructure || '-').toString().trim().toUpperCase();
                    const labelB = (b.priority_infrastructure || '-').toString().trim().toUpperCase();

                    return labelA.localeCompare(labelB);
                });
            }

            // ===============================
            // Render Chart Prioritas Infrastruktur
            // ===============================
            function renderPriorityStatisticChart(statistic) {
                const canvas = document.getElementById('priorityStatisticChart');
                const totalEl = document.getElementById('totalPriorityInfrastructur');

                if (!canvas) {
                    return;
                }

                const ctx = canvas.getContext('2d');
                const chartData = sortPriorityStatisticData(statistic.data || []);

                const rawLabels = chartData.map(function(item) {
                    return (item.priority_infrastructure || '-').toString().trim().toUpperCase();
                });

                const labels = chartData.map(function(item) {
                    return formatPriorityStatisticLabel(
                        item.priority_infrastructure,
                        item.total
                    );
                });

                const data = chartData.map(function(item) {
                    return parseInt(item.total) || 0;
                });

                const backgroundColors = rawLabels.map(function(label) {
                    return DashboardStatisticHelper.getStableRandomColor(label, 0.7);
                });

                const borderColors = rawLabels.map(function(label) {
                    return DashboardStatisticHelper.getStableRandomColor(label, 1);
                });

                if (totalEl) {
                    totalEl.innerText = DashboardStatisticHelper.formatNumber(statistic.total_all || 0);
                }

                if (priorityStatisticChart) {
                    priorityStatisticChart.destroy();
                }

                priorityStatisticChart = new Chart(ctx, {
                    type: 'horizontalBar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Prioritas Infrastruktur',
                            data: data,
                            backgroundColor: backgroundColors,
                            borderColor: borderColors,
                            borderWidth: 1
                        }]
                    },
                    options: getPriorityStatisticChartOptions(statistic, rawLabels)
                });
            }

            // ===============================
            // Options Chart Prioritas Infrastruktur
            // ===============================
            function getPriorityStatisticChartOptions(statistic, rawLabels) {
                return {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    tooltips: {
                        callbacks: {
                            title: function(tooltipItems) {
                                return rawLabels[tooltipItems[0].index] || '-';
                            },
                            label: function(tooltipItem, data) {
                                const dataset = data.datasets[tooltipItem.datasetIndex];
                                const value = dataset.data[tooltipItem.index] || 0;
                                const total = statistic.total_all || 0;
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(2) : 0;

                                return 'Jumlah: ' +
                                    DashboardStatisticHelper.formatNumber(value) +
                                    ' responden (' + percentage + '%)';
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true,
                                precision: 0
                            },
                            scaleLabel: {
                                display: false,
                                labelString: 'Jumlah Responden'
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                autoSkip: false,
                                maxRotation: 0,
                                minRotation: 0
                            }
                        }]
                    }
                };
            }

            // ===============================
            // Render Accordion Prioritas Infrastruktur
            // ===============================
            function renderPriorityStatisticTable(statistic) {
                const accordion = document.getElementById('priorityStatisticAccordion');

                if (!accordion) {
                    return;
                }

                const data = sortPriorityStatisticData(statistic.data || []);

                let html = '';

                if (data.length > 0) {
                    data.forEach(function(item, index) {
                        const improvements = item.improvements || [];
                        const priorityId = 'priority-' + (index + 1);
                        const headingId = 'heading-' + priorityId;
                        const collapseId = 'collapse-' + priorityId;

                        let improvementRows = '';

                        if (improvements.length > 0) {
                            improvements.forEach(function(improvement, improvementIndex) {
                                improvementRows += `
                                    <tr>
                                        <td class="text-right">
                                            ${improvementIndex + 1}
                                        </td>
                                        <td class="text-uppercase">
                                            ${escapePriorityStatisticHtml(improvement.priority_improvement || 'TIDAK DIISI')}
                                        </td>
                                        <td class="text-right font-weight-bold">
                                            ${DashboardStatisticHelper.formatNumber(parseInt(improvement.total) || 0)}
                                        </td>
                                    </tr>
                                `;
                            });
                        } else {
                            improvementRows = `
                                <tr>
                                    <td class="text-right">
                                        1
                                    </td>
                                    <td class="text-uppercase">
                                        TIDAK DIISI
                                    </td>
                                    <td class="text-right font-weight-bold">
                                        ${DashboardStatisticHelper.formatNumber(parseInt(item.total) || 0)}
                                    </td>
                                </tr>
                            `;
                        }

                        html += `
                            <div class="card mb-1">
                                <div class="card-header py-0 bg-light" id="${headingId}">
                                    <h5 class="mb-0">
                                        <button
                                            class="btn btn-link btn-block text-left font-weight-bold text-dark text-decoration-none"
                                            type="button"
                                            data-toggle="collapse"
                                            data-target="#${collapseId}"
                                            aria-expanded="false"
                                            aria-controls="${collapseId}">
                                            <div class="d-flex flex-lg-row justify-content-between align-items-center flex-wrap"
                                                style="gap: .5rem">
                                                <span class="text-uppercase">
                                                    <i class="fa fa-chevron-right js-chevron mr-2"></i>
                                                    ${index + 1}. ${escapePriorityStatisticHtml(item.priority_infrastructure || '-')}
                                                </span>

                                                <span class="badge bg-indigo badge-pill">
                                                    ${DashboardStatisticHelper.formatNumber(parseInt(item.total) || 0)}
                                                    Responden
                                                </span>
                                            </div>
                                        </button>
                                    </h5>
                                </div>

                                <div id="${collapseId}"
                                    class="collapse js-monitor-collapse"
                                    aria-labelledby="${headingId}">

                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table-striped table-bordered table-custom table-align-middle table">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th style="width: 60px;" class="text-center">No.</th>
                                                        <th>Aspek Prioritas</th>
                                                        <th style="width: 160px;" class="text-center">
                                                            Jumlah Responden
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${improvementRows}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = `
                        <div class="alert alert-light border text-center">
                            Data prioritas infrastruktur belum tersedia.
                        </div>
                    `;
                }

                accordion.innerHTML = html;
            }

            // ===============================
            // Set Loading Accordion Prioritas Infrastruktur
            // ===============================
            function setPriorityStatisticAccordionLoading() {
                const accordion = document.getElementById('priorityStatisticAccordion');

                if (!accordion) {
                    return;
                }

                accordion.innerHTML = `
                    <div class="card mb-1">
                        <div class="card-body text-center text-muted">
                            Memuat data...
                        </div>
                    </div>
                `;
            }

            // ===============================
            // Set Error Accordion Prioritas Infrastruktur
            // ===============================
            function setPriorityStatisticAccordionError() {
                const accordion = document.getElementById('priorityStatisticAccordion');

                if (!accordion) {
                    return;
                }

                accordion.innerHTML = `
                    <div class="alert alert-light border text-center text-danger">
                        Gagal memuat data prioritas infrastruktur.
                    </div>
                `;
            }

            // ===============================
            // Load Statistik Prioritas Infrastruktur
            // ===============================
            function loadPriorityStatistic() {
                if (priorityStatisticLoader) {
                    DashboardStatisticHelper.showLoader(priorityStatisticLoader);
                }

                setPriorityStatisticAccordionLoading();

                fetch(getPriorityStatisticUrl())
                    .then(res => res.json())
                    .then(res => {
                        if (priorityStatisticLoader) {
                            DashboardStatisticHelper.hideLoader(priorityStatisticLoader);
                        }

                        if (res.status !== 'success') {
                            console.error(res);
                            setPriorityStatisticAccordionError();
                            return;
                        }

                        renderPriorityStatisticChart({
                            total_all: res.total_all || 0,
                            data: res.data || []
                        });

                        renderPriorityStatisticTable({
                            total_all: res.total_all || 0,
                            data: res.data || []
                        });
                    })
                    .catch(err => {
                        if (priorityStatisticLoader) {
                            DashboardStatisticHelper.hideLoader(priorityStatisticLoader);
                        }

                        console.error(err);
                        setPriorityStatisticAccordionError();
                    });
            }
        </script>

        <script>
            $(document).ready(function() {

                $('.select2').select2({
                    width: '100%'
                });

                const $filterDistrict = $('#filter_district');
                let districtParam = "{{ request('district') ?? '' }}";
                let districtCache = [];

                // ==========================
                // ENDPOINT PER INFRASTRUKTUR
                // ==========================
                const infrastructureTypeEndpoints = {
                    'transportation-terminal': "{{ route('ikli-survey.dashboard.questionnaire.transportation-terminal.recap-data') }}",
                    'road': "{{ route('ikli-survey.dashboard.questionnaire.road.recap-data') }}",
                    'irrigation': "{{ route('ikli-survey.dashboard.questionnaire.irrigation.recap-data') }}",
                    'wastewater-management-system': "{{ route('ikli-survey.dashboard.questionnaire.wastewater-management-system.recap-data') }}",
                    'waste-management-system': "{{ route('ikli-survey.dashboard.questionnaire.waste-management-system.recap-data') }}",
                    'water-supply-system': "{{ route('ikli-survey.dashboard.questionnaire.water-supply-system.recap-data') }}",
                    'internet-network': "{{ route('ikli-survey.dashboard.questionnaire.internet-network.recap-data') }}",
                    'power-grid': "{{ route('ikli-survey.dashboard.questionnaire.power-grid.recap-data') }}",
                };

                function refreshSelect($el) {
                    if ($el.hasClass('select2-hidden-accessible')) {
                        $el.trigger('change.select2');
                    } else {
                        $el.trigger('change');
                    }
                }

                function showLoading($el, text = 'Memuat...') {
                    $el.html(`<option value="">${text}</option>`).prop('disabled', true);
                    refreshSelect($el);
                }

                function getQualityClass(quality) {
                    switch (quality) {
                        case 'A':
                            return 'text-success font-weight-bold';
                        case 'B':
                            return 'indigo font-weight-bold';
                        case 'C':
                            return 'text-warning font-weight-bold';
                        case 'D':
                            return 'text-danger font-weight-bold';
                        default:
                            return 'text-secondary';
                    }
                }

                function formatNumber(value) {
                    return new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(parseFloat(value) || 0);
                }

                // ==========================
                // LOAD RINGKASAN PER INFRASTRUKTUR
                // ==========================
                function loadInfrastructureTypeRecap(district = '') {

                    const totalEndpoint = Object.keys(infrastructureTypeEndpoints).length;

                    let totalIKMAccumulator = 0;
                    let totalIHMAccumulator = 0;

                    // ==========================
                    // SET LOADING PER INFRA
                    // ==========================
                    $.each(infrastructureTypeEndpoints, function(key) {
                        const loader = '<span>...</span>';

                        $('#total-respondent-' + key).html(loader);
                        $('#ikm-value-' + key).html(loader);
                        $('#ikm-quality-' + key).html(loader);
                        $('#ikm-label-' + key).html(loader);

                        $('#ihm-total-respondent-' + key).html(loader);
                        $('#ihm-value-' + key).html(loader);
                        $('#ihm-quality-' + key).html(loader);
                        $('#ihm-label-' + key).html(loader);
                    });

                    // ==========================
                    // SET LOADING TOTAL IKM
                    // ==========================
                    const totalLoader = '<span>...</span>';

                    $('#ikm-total-value').html(totalLoader);
                    $('#ikm-total-quality').html(totalLoader);
                    $('#ikm-total-label').html(totalLoader);

                    // ==========================
                    // SET LOADING TOTAL IHM
                    // ==========================
                    $('#ihm-total-value').html(totalLoader);
                    $('#ihm-total-quality').html(totalLoader);
                    $('#ihm-total-label').html(totalLoader);

                    // ==========================
                    // KUMPULKAN SEMUA AJAX
                    // ==========================
                    let requests = [];

                    $.each(infrastructureTypeEndpoints, function(key, url) {

                        let request = $.ajax({
                            url: url,
                            type: "GET",
                            data: {
                                district: district
                            }
                        }).done(function(response) {

                            if (!response.success) return;

                            const ikm = response.data.ikm || {};
                            const ihm = response.data.ihm || {};

                            const ikmSummary = ikm.summary || {};
                            const ihmSummary = ihm.summary || {};

                            let ikmValue = parseFloat(ikmSummary.value ?? 0);
                            let ihmValue = parseFloat(ihmSummary.value ?? 0);

                            let totalRespondent = parseInt(ikmSummary.totalRespondent ?? 0);

                            totalIKMAccumulator += ikmValue;
                            totalIHMAccumulator += ihmValue;

                            // ==========================
                            // RENDER IKM PER INFRASTRUKTUR
                            // ==========================
                            $('#total-respondent-' + key)
                                .text(totalRespondent.toLocaleString('id-ID'));

                            $('#ikm-value-' + key).html(`
                                <span class="${getQualityClass(ikmSummary.quality)}">
                                    ${formatNumber(ikmValue)}
                                </span>
                            `);

                            $('#ikm-quality-' + key).html(`
                                <span class="${getQualityClass(ikmSummary.quality)}">
                                    ${ikmSummary.quality ?? '-'}
                                </span>
                            `);

                            $('#ikm-label-' + key).html(`
                                <span class="${getQualityClass(ikmSummary.quality)}">
                                    ${ikmSummary.label ?? '-'}
                                </span>
                            `);

                            // ==========================
                            // RENDER IHM PER INFRASTRUKTUR
                            // ==========================
                            $('#ihm-total-respondent-' + key)
                                .text(totalRespondent.toLocaleString('id-ID'));

                            $('#ihm-value-' + key).html(`
                                <span class="font-weight-bold">
                                    ${formatNumber(ihmValue)}
                                </span>
                            `);

                            $('#ihm-quality-' + key).html(`
                                <span class="font-weight-bold">
                                    ${ihmSummary.quality ?? '-'}
                                </span>
                            `);

                            $('#ihm-label-' + key).html(`
                                <span class="font-weight-bold">
                                    ${ihmSummary.label ?? '-'}
                                </span>
                            `);

                        }).fail(function() {
                            // Jika gagal tetap dianggap 0
                        });

                        requests.push(request);
                    });

                    // ==========================
                    // TUNGGU SEMUA REQUEST SELESAI
                    // ==========================
                    $.when.apply($, requests).always(function() {

                        let ikmTotal = totalEndpoint > 0 ?
                            totalIKMAccumulator / totalEndpoint :
                            0;

                        let ihmTotal = totalEndpoint > 0 ?
                            totalIHMAccumulator / totalEndpoint :
                            0;

                        renderTotalIKM(ikmTotal);
                        renderTotalIHM(ihmTotal);
                    });
                }

                // ==========================
                // RENDER NILAI IKM KESELURUHAN
                // ==========================
                function renderTotalIKM(ikmTotal) {

                    let quality = '-';
                    let label = '-';

                    if (ikmTotal >= 88.31) {
                        quality = 'A';
                        label = 'SANGAT BAIK';
                    } else if (ikmTotal >= 76.61) {
                        quality = 'B';
                        label = 'BAIK';
                    } else if (ikmTotal >= 65.00) {
                        quality = 'C';
                        label = 'KURANG BAIK';
                    } else {
                        quality = 'D';
                        label = 'TIDAK BAIK';
                    }

                    $('#ikm-total-value').html(`
                        <span class="${getQualityClass(quality)}">
                            ${formatNumber(ikmTotal)}
                        </span>
                    `);

                    $('#ikm-total-quality').html(`
                        <span class="${getQualityClass(quality)}">
                            ${quality}
                        </span>
                    `);

                    $('#ikm-total-label').html(`
                        <span class="${getQualityClass(quality)}">
                            ${label}
                        </span>
                    `);
                }

                // ==========================
                // RENDER NILAI IHM KESELURUHAN
                // ==========================
                function renderTotalIHM(ihmTotal) {

                    let quality = '-';
                    let label = '-';

                    if (ihmTotal >= 88.31) {
                        quality = 'A';
                        label = 'SANGAT PENTING';
                    } else if (ihmTotal >= 76.61) {
                        quality = 'B';
                        label = 'PENTING';
                    } else if (ihmTotal >= 65.00) {
                        quality = 'C';
                        label = 'KURANG PENTING';
                    } else {
                        quality = 'D';
                        label = 'TIDAK PENTING';
                    }

                    $('#ihm-total-value').html(`
                        <span class="font-weight-bold">
                            ${formatNumber(ihmTotal)}
                        </span>
                    `);

                    $('#ihm-total-quality').html(`
                        <span class="font-weight-bold">
                            ${quality}
                        </span>
                    `);

                    $('#ihm-total-label').html(`
                        <span class="font-weight-bold">
                            ${label}
                        </span>
                    `);
                }

                // ==========================
                // TOTAL PENDUDUK
                // ==========================
                function updateTotalResident(selectedDistrict = '') {

                    if (!districtCache.length) {
                        $('#totalResident').text('0');
                        return;
                    }

                    let total = 0;

                    if (!selectedDistrict) {
                        // Semua Kecamatan → SUM semua resident_count
                        total = districtCache.reduce((sum, district) => {
                            return sum + parseInt(district.resident_count ?? 0);
                        }, 0);
                    } else {
                        // Per Kecamatan
                        let found = districtCache.find(d =>
                            d.district_name === selectedDistrict
                        );

                        total = found ? parseInt(found.resident_count ?? 0) : 0;
                    }

                    $('#totalResident').text(
                        total.toLocaleString('id-ID')
                    );
                }

                // ==========================
                // LOAD DISTRICT FILTER
                // ==========================
                function loadFilterDistricts() {

                    showLoading($filterDistrict, 'Memuat Kecamatan...');

                    $.ajax({
                        url: "{{ route('ikli-survey.region.districts') }}",
                        type: "GET",
                        success: function(response) {

                            $filterDistrict
                                .html('<option value="">Semua Kecamatan</option>')
                                .prop('disabled', false);

                            if (response.success && response.data) {

                                districtCache = response.data;

                                response.data.forEach(function(district) {
                                    $filterDistrict.append(`
                                <option value="${district.district_name}"
                                    ${district.district_name === districtParam ? 'selected' : ''}>
                                    ${district.district_name}
                                </option>
                            `);
                                });
                            }

                            refreshSelect($filterDistrict);

                            // Initial load
                            loadInfrastructureTypeRecap(districtParam);
                            updateTotalResident(districtParam);

                            if (typeof loadRespondentStatistic === 'function') {
                                loadRespondentStatistic();
                            }

                            if (typeof loadPriorityStatistic === 'function') {
                                loadPriorityStatistic();
                            }
                        },
                        error: function() {
                            $('#totalResident').text('0');
                        }
                    });
                }

                // ==========================
                // EVENT FILTER CHANGE
                // ==========================
                $filterDistrict.on('change', function() {

                    let selected = $(this).val();

                    loadInfrastructureTypeRecap(selected);
                    updateTotalResident(selected);

                    if (typeof loadRespondentStatistic === 'function') {
                        loadRespondentStatistic();
                    }

                    if (typeof loadPriorityStatistic === 'function') {
                        loadPriorityStatistic();
                    }
                });

                // ==========================
                // INIT
                // ==========================
                loadFilterDistricts();

            });
        </script>
    @endpush
</x-app-layout>
