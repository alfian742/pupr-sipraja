<x-app-layout>
    @php $pageTitle = 'Rekap Survei - ' . $infrastructureType @endphp

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
                                    style="gap: 0.5rem">
                                    <div class="d-flex align-items-center" style="gap: 0.5rem">
                                        <a href="{{ $routeList->index }}" class="btn btn-secondary">
                                            <i class="fa fa-arrow-left"></i> <span
                                                class="d-none d-md-inline">Kembali</span>
                                        </a>

                                        <button class="btn btn-indigo" type="button" data-toggle="collapse"
                                            data-target="#formulaWrapper" aria-expanded="false"
                                            aria-controls="formulaWrapper"><i class="fa fa-calculator"></i> <span
                                                class="d-none d-md-inline">Rumus</span></button>
                                    </div>

                                    <div style="width: 15rem;">
                                        <select class="custom-select select2" name="district" id="filter_district"
                                            aria-label="district">
                                            <option value="">Semua Kecamatan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="multi-collapse collapse mb-2" id="formulaWrapper">
                                    <div
                                        class="bs-callout-indigo callout-border-left callout-bordered rounded-0 bg-transparent p-1">
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <h5 class="indigo font-weight-bold mb-2 text-center">
                                                    Nilai Rata-Rata (NRR) per Indikator
                                                </h5>
                                                <math xmlns="http://www.w3.org/1998/Math/MathML" display="block">
                                                    <mrow>
                                                        <mi>NRR</mi>
                                                        <mo>=</mo>
                                                        <mfrac>
                                                            <mrow>
                                                                <mo>&#8721;</mo>
                                                                <mi>Nilai&nbsp;Indikator</mi>
                                                            </mrow>
                                                            <mi>Jumlah&nbsp;Responden</mi>
                                                        </mfrac>
                                                    </mrow>
                                                </math>
                                                <p class="text-muted small text-center mt-2 mb-0">
                                                    Rata-rata skor untuk setiap indikator penilaian.
                                                </p>
                                            </div>

                                            <div class="col-md-4 mb-2">
                                                <h5 class="indigo font-weight-bold mb-2 text-center">
                                                    Nilai IKM Internal (Sebelum Konversi)
                                                </h5>
                                                <math xmlns="http://www.w3.org/1998/Math/MathML" display="block">
                                                    <mrow>
                                                        <mi>IKM</mi>
                                                        <mo>=</mo>
                                                        <mfrac>
                                                            <mrow>
                                                                <mo>&#8721;</mo>
                                                                <mi>NRR</mi>
                                                            </mrow>
                                                            <mi>Jumlah&nbsp;Indikator</mi>
                                                        </mfrac>
                                                    </mrow>
                                                </math>
                                                <p class="text-muted small text-center mt-2 mb-0">
                                                    Menggunakan rata-rata dari seluruh indikator yang dinilai dalam
                                                    sistem ini.
                                                </p>
                                            </div>

                                            <div class="col-md-4 mb-2">
                                                <h5 class="indigo font-weight-bold mb-2 text-center">
                                                    Nilai IKM Setelah Konversi (Skala 25–100)
                                                </h5>
                                                <math xmlns="http://www.w3.org/1998/Math/MathML" display="block">
                                                    <mrow>
                                                        <mi>IKM&nbsp;Konversi</mi>
                                                        <mo>=</mo>
                                                        <mo>(</mo>
                                                        <mfrac>
                                                            <mrow>
                                                                <mo>&#8721;</mo>
                                                                <mi>NRR</mi>
                                                            </mrow>
                                                            <mi>Jumlah&nbsp;Indikator</mi>
                                                        </mfrac>
                                                        <mo>)</mo>
                                                        <mo>&#x00D7;</mo>
                                                        <mn>25</mn>
                                                    </mrow>
                                                </math>
                                                <p class="text-muted small text-center mt-2 mb-0">
                                                    Konversi ke skala 25–100 mengacu pada Permen PAN &amp; RB No. 14
                                                    Tahun 2017.
                                                </p>
                                            </div>
                                            <div class="col-12">
                                                <hr>

                                                <div class="font-weight-bold">
                                                    Sumber Referensi:
                                                </div>
                                                <ul class="pl-2">
                                                    <li><a target="_blank"
                                                            href="https://jdih.tangerangkab.go.id/common/dokumen/r9uTHND0p15224zcpECRDFBXswtn0RXmJaltYd5A.pdf">Laporan
                                                            Akhir IKLI Kabupaten Tangerang Tahun 2024</a></li>
                                                    <li><a target="_blank"
                                                            href="https://peraturan.bpk.go.id/Details/132600/permen-pan-rb-no-14-tahun-2017">Permen
                                                            PAN & RB No. 14 Tahun 2017</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-7 mb-2">
                                        <div class="mb-2 p-2" style="border: 1px solid #e3ebf3;">
                                            <div class="font-weight-bold d-flex justify-content-between mb-2"
                                                style="gap: 0.5rem;">
                                                <div>Total Penduduk</div>
                                                <div><span id="totalResident">0</span> Orang</div>
                                            </div>
                                            <div class="font-weight-bold d-flex justify-content-between"
                                                style="gap: 0.5rem;">
                                                <div>Total Responden</div>
                                                <div><span id="totalRespondent">0</span> Orang</div>
                                            </div>
                                        </div>

                                        <div class="table-responsive mt-1">
                                            <table class="table-bordered table-striped table-align-middle w-100 table">
                                                <thead>
                                                    <tr class="bg-indigo text-white text-uppercase">
                                                        <th colspan="4">Indeks Kepuasan</th>
                                                    </tr>
                                                    <tr class="text-center">
                                                        <th>No.</th>
                                                        <th>Indikator</th>
                                                        <th>Rata-Rata</th>
                                                        <th>Nilai Indeks</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="recapIkliTableBody">
                                                    {{-- Diisi via AJAX --}}
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="table-responsive mt-1">
                                            <table class="table-bordered table-striped table-align-middle w-100 table">
                                                <thead>
                                                    <tr class="bg-indigo text-white text-uppercase">
                                                        <th colspan="4">Indeks Harapan</th>
                                                    </tr>
                                                    <tr class="text-center">
                                                        <th>No.</th>
                                                        <th>Indikator</th>
                                                        <th>Rata-Rata</th>
                                                        <th>Nilai Indeks</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="recapIhliTableBody">
                                                    {{-- Diisi via AJAX --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 mb-2">
                                        <div class="p-2" style="border: 1px solid #e3ebf3;">
                                            <h3 class="font-weight-bold mb-2 text-center">Nilai Indeks Kepuasan</h3>

                                            <div id="ikmValueContainer"
                                                class="display-3 font-weight-bold mb-1 text-center">
                                                <span id="ikmValue">0</span>
                                            </div>

                                            <div id="ikmLabelContainer" class="font-weight-bold mb-0 text-center">
                                                <span id="ikmQuality">-</span>
                                                (<span id="ikmLabel">-</span>)
                                            </div>
                                        </div>
                                        <div class="p-2" style="border: 1px solid #e3ebf3;">
                                            <h3 class="font-weight-bold mb-2 text-center">Nilai Indeks Harapan</h3>

                                            <div id="ihmValueContainer"
                                                class="display-3 font-weight-bold mb-1 text-center">
                                                <span id="ihmValue">0</span>
                                            </div>

                                            <div id="ihmLabelContainer" class="font-weight-bold mb-0 text-center">
                                                <span id="ihmQuality">-</span>
                                                (<span id="ihmLabel">-</span>)
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table-bordered table-striped table-align-middle w-100 table">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th colspan="3">Konversi Mutu
                                                            Pelayanan
                                                        </th>
                                                    </tr>
                                                    <tr class="text-center">
                                                        <th>Mutu</th>
                                                        <th>Rentang</th>
                                                        {{-- <th>Keterangan</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>A</td>
                                                        <td class="text-right">88.31–100</td>
                                                        {{-- <td>SANGAT BAIK</td> --}}
                                                    </tr>
                                                    <tr>
                                                        <td>B</td>
                                                        <td class="text-right">76.61–88.30</td>
                                                        {{-- <td>BAIK</td> --}}
                                                    </tr>
                                                    <tr>
                                                        <td>C</td>
                                                        <td class="text-right">65.00–76.60</td>
                                                        {{-- <td>KURANG BAIK</td> --}}
                                                    </tr>
                                                    <tr>
                                                        <td>D</td>
                                                        <td class="text-right">&lt;65.00</td>
                                                        {{-- <td>TIDAK BAIK</td> --}}
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
        </section>
    </div>

    @push('styles')
        <link rel="stylesheet" type="text/css"
            href="{{ asset('app-assets/vendors/css/forms/selects/select2.min.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>

        <script>
            $(document).ready(function() {

                $('.select2').select2({
                    width: '100%',
                });

                const $filterDistrict = $('#filter_district');
                let districtParam = "{{ request('district') ?? '' }}";

                let districtCache = []; // simpan data district untuk hitung total resident

                function refreshSelect($el) {
                    if ($el.hasClass('select2-hidden-accessible')) {
                        $el.trigger('change.select2');
                    } else {
                        $el.trigger('change');
                    }
                }

                function showLoading($el, text = 'Memuat...') {
                    $el.html(`<option value="">${text}</option>`)
                        .prop('disabled', true);
                    refreshSelect($el);
                }

                function formatNumber(value) {
                    return new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 2
                    }).format(parseFloat(value) || 0);
                }

                function getQualityTextClass(quality) {
                    let textClass = 'text-danger';

                    if (quality === 'A') textClass = 'text-success';
                    else if (quality === 'B') textClass = 'indigo';
                    else if (quality === 'C') textClass = 'text-warning';

                    return textClass;
                }

                // ==========================
                // LOAD RECAP
                // ==========================
                function loadRecap(district = '') {

                    $('#recapIkliTableBody').html(`
                <tr>
                    <td colspan="4" class="text-center">Memuat data...</td>
                </tr>
            `);

                    $('#recapIhliTableBody').html(`
                <tr>
                    <td colspan="4" class="text-center">Memuat data...</td>
                </tr>
            `);

                    $.ajax({
                        url: "{{ $routeList->recapData }}",
                        type: "GET",
                        data: {
                            district: district
                        },
                        success: function(response) {

                            if (!response.success) return;

                            const ikm = response.data.ikm || {};
                            const ihm = response.data.ihm || {};

                            const ikmIndicators = ikm.indicators || [];
                            const ihmIndicators = ihm.indicators || [];

                            const ikmSummary = ikm.summary || {};
                            const ihmSummary = ihm.summary || {};

                            let ikliTableBody = '';
                            let ihliTableBody = '';

                            if (ikmIndicators.length > 0) {
                                ikmIndicators.forEach(function(item, index) {
                                    ikliTableBody += `
                                <tr>
                                    <td class="text-right">${index + 1}</td>
                                    <td>${item.indicator}</td>
                                    <td class="text-right">${formatNumber(item.average)}</td>
                                    <td class="text-right">${formatNumber(item.score)}</td>
                                </tr>
                            `;
                                });
                            } else {
                                ikliTableBody += `
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            Data indeks kepuasan belum tersedia
                                        </td>
                                    </tr>
                                `;
                            }

                            if (ihmIndicators.length > 0) {
                                ihmIndicators.forEach(function(item, index) {
                                    ihliTableBody += `
                                        <tr>
                                            <td class="text-right">${index + 1}</td>
                                            <td>${item.indicator}</td>
                                            <td class="text-right">${formatNumber(item.average)}</td>
                                            <td class="text-right">${formatNumber(item.score)}</td>
                                        </tr>
                                    `;
                                });
                            } else {
                                ihliTableBody += `
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            Data indeks harapan belum tersedia
                                        </td>
                                    </tr>
                                `;
                            }

                            $('#recapIkliTableBody').html(ikliTableBody);
                            $('#recapIhliTableBody').html(ihliTableBody);

                            // summary responden
                            $('#totalRespondent').text(
                                new Intl.NumberFormat('id-ID').format(parseInt(ikmSummary
                                    .totalRespondent) || 0)
                            );

                            // summary IKM
                            $('#ikmValue').text(formatNumber(ikmSummary.value));
                            $('#ikmQuality').text(ikmSummary.quality || '-');
                            $('#ikmLabel').text(ikmSummary.label || '-');

                            let ikmTextClass = getQualityTextClass(ikmSummary.quality);

                            $('#ikmValueContainer')
                                .removeClass('text-success indigo text-warning text-danger')
                                .addClass(ikmTextClass);

                            $('#ikmLabelContainer')
                                .removeClass('text-success indigo text-warning text-danger')
                                .addClass(ikmTextClass);

                            // summary IHM
                            $('#ihmValue').text(formatNumber(ihmSummary.value));
                            $('#ihmQuality').text(ihmSummary.quality || '-');
                            $('#ihmLabel').text(ihmSummary.label || '-');

                            let ihmTextClass = 'text-dark';

                            $('#ihmValueContainer')
                                .removeClass('text-success indigo text-warning text-danger')
                                .addClass(ihmTextClass);

                            $('#ihmLabelContainer')
                                .removeClass('text-success indigo text-warning text-danger')
                                .addClass(ihmTextClass);
                        },
                        error: function() {
                            $('#recapIkliTableBody').html(`
                                <tr>
                                    <td colspan="4" class="text-center text-danger">
                                        Gagal memuat data
                                    </td>
                                </tr>
                            `);

                            $('#recapIhliTableBody').html(`
                                <tr>
                                    <td colspan="4" class="text-center text-danger">
                                        Gagal memuat data
                                    </td>
                                </tr>
                            `);
                        }
                    });
                }

                // ==========================
                // UPDATE TOTAL RESIDENT
                // ==========================
                function updateTotalResident(selectedDistrict = '') {

                    if (!districtCache.length) return;

                    let total = 0;

                    if (selectedDistrict === '' || selectedDistrict === null) {
                        // Semua kecamatan → jumlahkan semua
                        districtCache.forEach(function(d) {
                            total += parseInt(d.resident_count ?? 0);
                        });
                    } else {
                        // Kecamatan tertentu
                        let found = districtCache.find(d => d.district_name === selectedDistrict);
                        total = found ? parseInt(found.resident_count ?? 0) : 0;
                    }

                    $('#totalResident').text(total.toLocaleString('id-ID'));
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

                            if (response.success) {

                                districtCache = response.data; // simpan untuk resident_count

                                $.each(response.data, function(index, district) {

                                    $filterDistrict.append(
                                        `<option value="${district.district_name}" 
                                    ${district.district_name === districtParam ? 'selected' : ''}>
                                    ${district.district_name}
                                </option>`
                                    );
                                });
                            }

                            refreshSelect($filterDistrict);

                            // initial load
                            loadRecap(districtParam);
                            updateTotalResident(districtParam);
                        },
                        error: function() {
                            $filterDistrict
                                .html('<option value="">Gagal memuat data</option>');
                            refreshSelect($filterDistrict);
                        }
                    });
                }

                // ==========================
                // EVENT FILTER CHANGE
                // ==========================
                $filterDistrict.on('change', function() {
                    let selected = $(this).val();
                    loadRecap(selected);
                    updateTotalResident(selected);
                });

                // ==========================
                // INIT
                // ==========================
                loadFilterDistricts();

            });
        </script>
    @endpush
</x-app-layout>
