<x-app-layout>
    @php $pageTitle = 'Indikator Kinerja Utama' @endphp

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
                                    <div class="d-flex align-items-center" style="gap: 0.75rem">
                                        <a href="{{ $routeList->create }}" class="btn btn-indigo">
                                            <i class="fa fa-plus"></i> Tambah
                                        </a>

                                        <a href="{{ $routeList->showChart }}" class="btn btn-secondary">
                                            <i class="fa fa-bar-chart"></i> Grafik
                                        </a>

                                        <button class="btn btn-info" type="button" data-toggle="collapse"
                                            data-target="#filterWrapper" aria-expanded="false"
                                            aria-controls="filterWrapper"><i class="fa fa-filter"></i> <span
                                                class="d-none d-md-inline">Filter</span></button>
                                    </div>

                                    @if ($filterApplied)
                                        <form action="{{ $routeList->massDestroy }}" method="POST"
                                            class="d-flex align-items-center form-delete-selected" style="gap: 0.75rem">
                                            @csrf

                                            <p class="mb-0">
                                                Item terpilih: <span class="font-weight-bold" id="countItems">0</span>
                                            </p>

                                            <button class="btn btn-danger" type="button" id="btnDelete"
                                                title="Hapus Data Terpilih" disabled>
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <div class="row">
                                    @include('dashboard.performance-indicators.main-indicators.partials.filter')
                                </div>

                                @if ($filterApplied)
                                    <div class="table-responsive">
                                        <table
                                            class="table-striped table-bordered table-custom table-align-middle table table-hover">
                                            @php
                                                $column = [
                                                    'Kode Indikator',
                                                    'Nama Indikator',
                                                    'Satuan',
                                                    'Tahun Baseline',
                                                    'Nilai Baseline',
                                                    'Tahun',
                                                    'Periode',
                                                    'Target',
                                                    'Capaian',
                                                    'Kinerja',
                                                    'Dokumen',
                                                    'Riwayat',
                                                ];
                                            @endphp

                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="d-flex align-items-center justify-content-between"
                                                            style="gap: 1rem">
                                                            <span>Aksi</span>
                                                            <input type="checkbox"
                                                                class="custom-form-check-input check-all"
                                                                aria-label="Pilih Semua" title="Pilih Semua">
                                                        </div>
                                                    </th>

                                                    @foreach ($column as $col)
                                                        <th>{{ $col }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>

                                            <tbody>
                                                {{-- Server Side Rendering --}}
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info d-flex align-items-center justify-content-center"
                                        role="alert">
                                        <p class="mb-0 d-flex align-items-center" style="gap: 0.5rem">
                                            <i class="fa fa-filter"></i>
                                            <span>Silakan terapkan filter untuk menampilkan data.</span>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/selects/select2.min.css') }}">
        <link rel="stylesheet" type="text/css"
            href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
        <script src="{{ asset('app-assets/js/scripts/extensions/block-ui-custom.js') }}"></script>

        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    width: '100%',
                });
            });
        </script>

        @if ($filterApplied)
            <script>
                $(document).ready(function() {
                    const table = $('.table-custom').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ $routeList->data }}",
                            data: function(d) {
                                d.measurement_year = $('#measurement_year').val();
                                d.period = $('#period').val();
                            }
                        },
                        columns: [{
                                data: 'action',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'indicator_code',
                                name: 'indicator_code',
                                defaultContent: '-'
                            },
                            {
                                data: 'indicator_name',
                                name: 'indicator_name',
                                defaultContent: '-'
                            },
                            {
                                data: 'indicator_unit',
                                name: 'indicator_unit',
                                defaultContent: '-'
                            },
                            {
                                data: 'baseline_year',
                                name: 'baseline_year',
                                className: 'text-right',
                                defaultContent: '-'
                            },
                            {
                                data: 'baseline_value',
                                name: 'baseline_value',
                                className: 'text-right',
                                defaultContent: '-'
                            },
                            {
                                data: 'measurement_year',
                                name: 'measurement_year',
                                className: 'text-right',
                                defaultContent: '-'
                            },
                            {
                                data: 'period',
                                name: 'period',
                                defaultContent: '-'
                            },
                            {
                                data: 'target_value',
                                name: 'target_value',
                                className: 'text-right',
                                defaultContent: '-'
                            },
                            {
                                data: 'achievement_value',
                                name: 'achievement_value',
                                className: 'text-right',
                                defaultContent: '-'
                            },
                            {
                                data: 'performance_value',
                                name: 'performance_value',
                                className: 'text-right',
                                defaultContent: '-'
                            },
                            {
                                data: 'document_url',
                                name: 'document_url',
                                className: 'text-center',
                                orderable: false,
                                searchable: false,
                                defaultContent: '-'
                            },
                            {
                                data: 'history',
                                name: 'history',
                                orderable: false,
                                searchable: false,
                                defaultContent: '-'
                            },
                        ],
                        order: [
                            [1, 'asc'],
                            [6, 'desc'],
                            [7, 'asc']
                        ],
                        autoWidth: false,
                        scrollX: true,
                        fixedColumns: {
                            leftColumns: 1
                        },
                        language: {
                            url: "{{ asset('app-assets/data/dataTableLangId.json') }}"
                        }
                    });

                    // =============================
                    // REMOVE SELECTED ITEM
                    // =============================
                    const $checkAll = $('.check-all');
                    const $btnDelete = $('#btnDelete');
                    const $countItems = $('#countItems');
                    const $form = $btnDelete.closest('form');

                    function updateSelectedCount() {

                        const $realCheckboxes = $('.dataTables_scrollBody .check-item');
                        const $checked = $realCheckboxes.filter(':checked');

                        const totalChecked = $checked.length;
                        const totalVisible = $realCheckboxes.length;

                        $countItems.text(totalChecked);
                        $btnDelete.prop('disabled', totalChecked === 0);

                        $checkAll.prop('checked', totalVisible > 0 && totalChecked === totalVisible);
                    }

                    // =============================
                    // EVENT: CLICK CHECK-ALL
                    // =============================
                    $(document).on('change', '.check-all', function() {

                        const isChecked = $(this).is(':checked');

                        const $realCheckboxes = $('.dataTables_scrollBody .check-item');

                        $realCheckboxes.each(function() {
                            const id = $(this).val();

                            $('.check-item[value="' + id + '"]').prop('checked', isChecked);
                        });

                        updateSelectedCount();
                    });


                    // =============================
                    // EVENT: CLICK CHECK-ITEM
                    // =============================
                    $(document).on('change', '.check-item', function() {
                        const id = $(this).val();
                        const isChecked = $(this).is(':checked');

                        $('.check-item[value="' + id + '"]').prop('checked', isChecked);

                        updateSelectedCount();
                    });


                    // =============================
                    // EVENT: ON TABLE DRAW (Paging/Filter/Sort)
                    // =============================
                    table.on('draw.dt', function() {
                        $checkAll.prop('checked', false);

                        updateSelectedCount();
                    });

                    // =============================
                    // DELETE ACTION
                    // =============================
                    $btnDelete.on('click', function(e) {
                        e.preventDefault();

                        const $checkedItems = $('.dataTables_scrollBody .check-item:checked');

                        if ($checkedItems.length === 0) {
                            swal("Tidak ada data yang dipilih", "Silakan pilih minimal 1 data.", "info");
                            return;
                        }

                        swal({
                            title: 'Hapus Data?',
                            text: `Anda akan menghapus ${$checkedItems.length} data yang dipilih.`,
                            icon: 'warning',
                            buttons: ["Batal", "Ya, hapus!"],
                            dangerMode: true,
                        }).then((willDelete) => {
                            if (willDelete) {
                                $form.find('input[name="ids[]"]').remove();

                                $checkedItems.each(function() {
                                    $form.append(
                                        `<input type="hidden" name="ids[]" value="${$(this).val()}">`
                                    );
                                });

                                if (typeof blockWholePage === "function") {
                                    blockWholePage("Mohon tunggu...");
                                }

                                setTimeout(() => $form.trigger('submit'), 300);
                            }
                        });
                    });
                });
            </script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    /*
                    |--------------------------------------------------------------------------
                    | EXPORT MAIN PERFORMANCE INDICATOR (TOKEN + POLLING)
                    |--------------------------------------------------------------------------
                    */

                    const exportForm = document.getElementById('exportForm');
                    const btnExport = document.getElementById('btnExport');

                    const measurementYearInput = document.getElementById(
                        'export_measurement_year'
                    );

                    const periodInput = document.getElementById('export_period');
                    const formatInput = document.getElementById('export_format');

                    const checkUrl =
                        "{{ route('dashboard.performance-indicators.main-indicators.check-export') }}";

                    function validateExport() {
                        const format = formatInput ? formatInput.value : '';

                        if (!format) {
                            swal({
                                title: 'Format belum dipilih',
                                text: 'Silakan pilih format file XLSX atau CSV.',
                                icon: 'warning',
                            });

                            return false;
                        }

                        if (!['xlsx', 'csv'].includes(format)) {
                            swal({
                                title: 'Format tidak valid',
                                text: 'Format ekspor hanya mendukung XLSX atau CSV.',
                                icon: 'warning',
                            });

                            return false;
                        }

                        return true;
                    }

                    function startPolling(token) {
                        const startedAt = Date.now();
                        const maxWaitMs = 10 * 60 * 1000;

                        const interval = setInterval(() => {
                            if (Date.now() - startedAt > maxWaitMs) {
                                clearInterval(interval);

                                if (typeof unblockWholePage === 'function') {
                                    unblockWholePage();
                                }

                                swal({
                                    title: 'Ekspor masih diproses',
                                    text: 'Data cukup besar. Silakan coba kembali beberapa saat lagi.',
                                    icon: 'warning',
                                });

                                return;
                            }

                            fetch(
                                    `${checkUrl}?token=${encodeURIComponent(token)}`, {
                                        method: 'GET',
                                        headers: {
                                            'Accept': 'application/json',
                                        },
                                    }
                                )
                                .then(async response => {
                                    const result = await response.json();

                                    if (!response.ok) {
                                        throw new Error(
                                            result.message ||
                                            'Gagal memeriksa status ekspor.'
                                        );
                                    }

                                    return result;
                                })
                                .then(result => {
                                    if (
                                        result &&
                                        result.ready &&
                                        result.download_url
                                    ) {
                                        clearInterval(interval);

                                        if (typeof unblockWholePage === 'function') {
                                            unblockWholePage();
                                        }

                                        window.location.href = result.download_url;
                                        return;
                                    }

                                    if (
                                        result && ['failed', 'not_found'].includes(result.status)
                                    ) {
                                        clearInterval(interval);

                                        if (typeof unblockWholePage === 'function') {
                                            unblockWholePage();
                                        }

                                        swal({
                                            title: 'Ekspor gagal',
                                            text: result.status === 'not_found' ?
                                                'Status ekspor tidak ditemukan atau sudah kedaluwarsa.' :
                                                'Terjadi kesalahan saat membuat file ekspor.',
                                            icon: 'error',
                                        });
                                    }
                                })
                                .catch(() => {
                                    // Polling berikutnya tetap dilanjutkan.
                                });
                        }, 2000);

                        return interval;
                    }

                    if (exportForm && btnExport) {
                        btnExport.addEventListener('click', function(event) {
                            event.preventDefault();

                            if (!validateExport()) {
                                return;
                            }

                            const measurementYear = measurementYearInput ?
                                measurementYearInput.value :
                                '';

                            const period = periodInput ?
                                periodInput.value :
                                '';

                            const selectedFormat = formatInput ?
                                formatInput.value.toUpperCase() :
                                'XLSX';

                            const yearLabel = measurementYear || 'Semua Tahun';
                            const periodLabel = period || 'Semua Periode';

                            swal({
                                title: 'Ekspor Data?',
                                text: `Data indikator kinerja utama akan diekspor dalam format ${selectedFormat} dengan filter ${yearLabel} dan ${periodLabel}.`,
                                icon: 'info',
                                buttons: ['Batal', 'Ya, Ekspor!'],
                            }).then(willExport => {
                                if (!willExport) {
                                    return;
                                }

                                if (typeof blockWholePage === 'function') {
                                    blockWholePage(
                                        'Proses ekspor sedang disiapkan...'
                                    );
                                }

                                const params = new URLSearchParams(
                                    new FormData(exportForm)
                                );

                                fetch(
                                        `${exportForm.action}?${params.toString()}`, {
                                            method: 'GET',
                                            headers: {
                                                'Accept': 'application/json',
                                            },
                                        }
                                    )
                                    .then(async response => {
                                        const result = await response.json();

                                        if (!response.ok) {
                                            throw new Error(
                                                result.message ||
                                                'Gagal memulai proses ekspor.'
                                            );
                                        }

                                        return result;
                                    })
                                    .then(result => {
                                        if (!result.token) {
                                            throw new Error(
                                                'Token ekspor tidak ditemukan.'
                                            );
                                        }

                                        if (typeof blockWholePage === 'function') {
                                            blockWholePage(
                                                'Ekspor sedang diproses di server...'
                                            );
                                        }

                                        startPolling(result.token);
                                    })
                                    .catch(error => {
                                        if (typeof unblockWholePage === 'function') {
                                            unblockWholePage();
                                        }

                                        swal({
                                            title: 'Gagal memulai ekspor',
                                            text: error.message ||
                                                'Terjadi kesalahan saat memulai proses ekspor.',
                                            icon: 'error',
                                        });
                                    });
                            });
                        });
                    }
                });
            </script>
        @endif
    @endpush
</x-app-layout>
