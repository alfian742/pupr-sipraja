<x-app-layout>
    @php $pageTitle = 'Pembayaran Langsung (LS)' @endphp

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
                                        <a href="{{ route('dashboard.monev.finances.ls-payments.create') }}"
                                            class="btn btn-indigo">
                                            <i class="fa fa-plus"></i> <span class="d-none d-md-inline">Tambah</span>
                                        </a>

                                        <button class="btn btn-primary" type="button" data-toggle="collapse"
                                            data-target="#importWrapper" aria-expanded="false"
                                            aria-controls="importWrapper"><i class="fa fa-upload"></i> <span
                                                class="d-none d-md-inline">Impor</span></button>

                                        <button class="btn btn-success" type="button" data-toggle="collapse"
                                            data-target="#exportWrapper" aria-expanded="false"
                                            aria-controls="exportWrapper"><i class="fa fa-download"></i> <span
                                                class="d-none d-md-inline">Ekspor</span></button>

                                        <button type="button" class="btn btn-outline-secondary" id="reloadAllData"
                                            title="Muat ulang semua data"><i class="fa fa-refresh"></i></button>
                                    </div>

                                    @if (Auth::user()->role === 'superadmin' ||
                                            Auth::user()->role === 'admin' ||
                                            Auth::user()->role === 'head_of_department' ||
                                            Auth::user()->role === 'operator')
                                        <div class="d-flex align-items-center" style="gap: 0.75rem">
                                            <p class="mb-0">
                                                Item terpilih: <span class="font-weight-bold" id="countItems">0</span>
                                            </p>
                                            <form
                                                action="{{ route('dashboard.monev.finances.ls-payments.mass-add-to-realization') }}"
                                                method="POST" id="massAddToRealizationForm">
                                                @csrf

                                                <button class="btn btn-success" type="button" id="btnAddToRealization"
                                                    title="Tambahkan Data Terpilih ke Realisasi" disabled>
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </form>
                                            @if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin')
                                                <form
                                                    action="{{ route('dashboard.monev.finances.ls-payments.mass-destroy') }}"
                                                    method="POST" id="massDestroyForm">
                                                    @csrf

                                                    <button class="btn btn-danger" type="button" id="btnDelete"
                                                        title="Hapus Data Terpilih" disabled>
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="row mb-2">
                                    @include('dashboard.monev.finances.ls-payments.partials.import')
                                    @include('dashboard.monev.finances.ls-payments.partials.export')
                                </div>

                                <div class="table-responsive">
                                    <table class="table-striped table-bordered table-custom table-align-middle table">
                                        <thead>
                                            <tr>
                                                @foreach ($columnMaps as $column)
                                                    @if (in_array($column['field'], ['action', 'history']))
                                                        <th rowspan="2" class="text-center align-middle">
                                                            @if ($column['field'] === 'action')
                                                                <div class="d-flex align-items-center justify-content-between"
                                                                    style="gap: 1rem">
                                                                    <span>{{ $column['label'] }}</span>
                                                                    @if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin')
                                                                        <input type="checkbox"
                                                                            class="custom-form-check-input check-all"
                                                                            aria-label="Pilih Semua"
                                                                            title="Pilih Semua">
                                                                    @endif
                                                                </div>
                                                            @else
                                                                {{ $column['label'] }}
                                                            @endif
                                                        </th>
                                                    @else
                                                        <th>{{ $column['label'] }}</th>
                                                    @endif
                                                @endforeach
                                            </tr>

                                            <tr>
                                                @foreach ($columnMaps as $column)
                                                    @continue(in_array($column['field'], ['action', 'history']))

                                                    <th>
                                                        @if (($column['type'] ?? 'text') === 'date')
                                                            <input type="date"
                                                                class="form-control form-control-sm column-search"
                                                                data-field="{{ $column['field'] }}"
                                                                placeholder="Cari {{ $column['label'] }}">
                                                        @else
                                                            <input type="text"
                                                                class="form-control form-control-sm column-search"
                                                                data-field="{{ $column['field'] }}"
                                                                placeholder="Cari {{ $column['label'] }}">
                                                        @endif
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>

                                        <tbody>
                                            {{-- Server Side Rendering --}}
                                        </tbody>
                                    </table>
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
            href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
        <script src="{{ asset('app-assets/js/scripts/extensions/block-ui-custom.js') }}"></script>

        <script>
            $(document).ready(function() {
                const columnMaps = @json($columnMaps);

                const dtColumns = columnMaps.map((column) => {
                    const isAction = column.field === 'action';
                    const isHistory = column.field === 'history';

                    return {
                        data: column.field,
                        name: column.field,
                        defaultContent: '-',
                        orderable: !isAction && !isHistory,
                        searchable: !isAction && !isHistory,
                    };
                });

                const table = $('.table-custom').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: true,
                    ajax: {
                        url: "{{ route('dashboard.monev.finances.ls-payments.data') }}",
                        type: "POST",
                        data: function(d) {
                            d._token = "{{ csrf_token() }}";
                        }
                    },
                    columns: dtColumns,
                    order: [],
                    orderCellsTop: true,
                    autoWidth: false,
                    scrollX: true,
                    fixedColumns: {
                        leftColumns: 1
                    },
                    language: {
                        url: "{{ asset('app-assets/data/dataTableLangId.json') }}"
                    },
                    initComplete: function() {
                        const api = this.api();

                        $('.table-custom thead .column-search').each(function() {
                            const field = $(this).data('field');
                            const columnIndex = columnMaps.findIndex((column) => column.field ===
                                field);

                            if (columnIndex === -1) {
                                return;
                            }

                            $(this).on('keyup change clear', function() {
                                const value = this.value ?? '';

                                if (api.column(columnIndex).search() !== value) {
                                    api.column(columnIndex).search(value).draw();
                                }
                            });
                        });
                    },
                });

                $('#reloadAllData').on('click', function() {
                    $('.table-custom thead .column-search').val('');
                    table.search('');
                    table.columns().search('');
                    table.order([]);
                    table.ajax.reload(null, false);
                });

                @if (Auth::user()->role === 'superadmin' ||
                        Auth::user()->role === 'admin' ||
                        Auth::user()->role === 'head_of_department' ||
                        Auth::user()->role === 'operator')

                    // =============================
                    // REMOVE / ADD SELECTED ITEM
                    // =============================
                    const $checkAll = $('.check-all');
                    const $countItems = $('#countItems');
                    const $btnDelete = $('#btnDelete');
                    const $deleteForm = $('#massDestroyForm');
                    const $btnAddToRealization = $('#btnAddToRealization');
                    const $addToRealizationForm = $('#massAddToRealizationForm');

                    function updateSelectedCount() {

                        const $realCheckboxes = $('.dataTables_scrollBody .check-item');
                        const $checked = $realCheckboxes.filter(':checked');

                        const totalChecked = $checked.length;
                        const totalVisible = $realCheckboxes.length;

                        $countItems.text(totalChecked);
                        $btnDelete.prop('disabled', totalChecked === 0);
                        $btnAddToRealization.prop('disabled', totalChecked === 0);

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

                    @if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin')
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
                                    $deleteForm.find('input[name="ids[]"]').remove();

                                    $checkedItems.each(function() {
                                        $deleteForm.append(
                                            `<input type="hidden" name="ids[]" value="${$(this).val()}">`
                                        );
                                    });

                                    if (typeof blockWholePage === "function") {
                                        blockWholePage("Mohon tunggu...");
                                    }

                                    setTimeout(() => $deleteForm.trigger('submit'), 300);
                                }
                            });
                        });
                    @endif

                    // =============================
                    // ADD TO REALIZATION ACTION
                    // =============================
                    $btnAddToRealization.on('click', function(e) {
                        e.preventDefault();

                        const $checkedItems = $('.dataTables_scrollBody .check-item:checked');

                        if ($checkedItems.length === 0) {
                            swal("Tidak ada data yang dipilih", "Silakan pilih minimal 1 data.", "info");
                            return;
                        }

                        swal({
                            title: 'Tambahkan Data ke Realisasi?',
                            text: `Anda akan menambahkan ${$checkedItems.length} data yang dipilih.`,
                            icon: 'warning',
                            buttons: ["Batal", "Ya, tambahkan!"],
                            dangerMode: false,
                        }).then((willAddToRealization) => {
                            if (willAddToRealization) {
                                $addToRealizationForm.find('input[name="ids[]"]').remove();

                                $checkedItems.each(function() {
                                    $addToRealizationForm.append(
                                        `<input type="hidden" name="ids[]" value="${$(this).val()}">`
                                    );
                                });

                                if (typeof blockWholePage === "function") {
                                    blockWholePage("Mohon tunggu...");
                                }

                                setTimeout(() => $addToRealizationForm.trigger('submit'), 300);
                            }
                        });
                    });
                @endif
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                /*
                |--------------------------------------------------------------------------
                | IMPORT LS PAYMENT (TOKEN + POLLING)
                |--------------------------------------------------------------------------
                */

                const form = document.getElementById('importForm');
                const fileInput = document.getElementById('excelFile');
                const fileLabel = document.querySelector('label[for="excelFile"]');
                const btnImport = document.getElementById('btnImport');
                const btnDownloadTemplate = document.getElementById('btnDownloadTemplate');

                const checkImportUrl = "{{ route('dashboard.monev.finances.ls-payments.check-import') }}";

                const allowedExtensions = ['xlsx', 'csv'];
                const maxFileSize = 10 * 1024 * 1024; // 10 MB

                function resetFileInput() {
                    if (fileInput) fileInput.value = '';
                    if (fileLabel) fileLabel.textContent = 'Pilih Berkas...';
                    if (btnImport) btnImport.disabled = true;
                }

                function startImportPolling(token) {
                    const startedAt = Date.now();
                    const maxWaitMs = 10 * 60 * 1000;

                    const interval = setInterval(() => {
                        if (Date.now() - startedAt > maxWaitMs) {
                            clearInterval(interval);
                            unblockWholePage();

                            swal({
                                title: 'Impor masih diproses',
                                text: 'Data cukup besar. Silakan cek kembali beberapa saat lagi.',
                                icon: 'warning',
                            });

                            return;
                        }

                        fetch(`${checkImportUrl}?token=${encodeURIComponent(token)}`, {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            })
                            .then(r => r.json())
                            .then(res => {
                                if (res && res.ready) {
                                    clearInterval(interval);
                                    unblockWholePage();

                                    swal({
                                        title: 'Impor Berhasil',
                                        text: 'Data LS berhasil diimpor ke sistem.',
                                        icon: 'success',
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                }

                                if (res && res.status === 'not_found') {
                                    clearInterval(interval);
                                    unblockWholePage();

                                    swal({
                                        title: 'Status impor tidak ditemukan',
                                        text: 'Token impor tidak valid atau sudah kedaluwarsa.',
                                        icon: 'error',
                                    });
                                }
                            })
                            .catch(() => {
                                // silent fail agar polling tetap berjalan
                            });
                    }, 2000);

                    return interval;
                }

                if (form && fileInput && fileLabel && btnImport) {
                    btnImport.disabled = true;

                    fileInput.addEventListener('change', () => {
                        const file = fileInput.files[0];

                        if (!file) {
                            resetFileInput();
                            return;
                        }

                        const fileExt = file.name.split('.').pop().toLowerCase();

                        if (!allowedExtensions.includes(fileExt)) {
                            swal({
                                title: 'Format Tidak Valid',
                                text: 'Harap unggah berkas dengan format ".xlsx" atau ".csv".',
                                icon: 'error',
                                button: 'OK'
                            });

                            resetFileInput();
                            return;
                        }

                        if (file.size > maxFileSize) {
                            swal({
                                title: 'Ukuran Terlalu Besar',
                                text: 'Ukuran berkas terlalu besar. Maksimum 10 MB.',
                                icon: 'error',
                                button: 'OK'
                            });

                            resetFileInput();
                            return;
                        }

                        const lastDotIndex = file.name.lastIndexOf('.');
                        const baseName = lastDotIndex > -1 ?
                            file.name.substring(0, lastDotIndex) :
                            file.name;

                        const truncatedName = baseName.length > 14 ?
                            baseName.substring(0, 14) + '...' :
                            baseName;

                        fileLabel.textContent = `${truncatedName}.${fileExt}`;
                        btnImport.disabled = false;
                    });

                    btnImport.addEventListener('click', (e) => {
                        e.preventDefault();

                        if (btnImport.disabled) return;

                        swal({
                            title: 'Impor Data?',
                            text: 'Data LS akan diproses di server. Setelah selesai, halaman akan diperbarui.',
                            icon: 'warning',
                            buttons: ['Batal', 'Ya, Impor!'],
                        }).then((willImport) => {
                            if (!willImport) return;

                            blockWholePage('Proses impor sedang disiapkan...');

                            const formData = new FormData(form);

                            fetch(form.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'Accept': 'application/json',
                                    }
                                })
                                .then(async (response) => {
                                    const data = await response.json();

                                    if (!response.ok) {
                                        throw data;
                                    }

                                    return data;
                                })
                                .then((res) => {
                                    if (!res.token) {
                                        throw new Error('Token impor tidak ditemukan.');
                                    }

                                    blockWholePage('Impor sedang diproses di server...');
                                    startImportPolling(res.token);
                                })
                                .catch((error) => {
                                    unblockWholePage();

                                    let message = 'Terjadi kesalahan saat memulai proses impor.';

                                    if (error && error.message) {
                                        message = error.message;
                                    }

                                    if (error && error.errors) {
                                        const firstKey = Object.keys(error.errors)[0];

                                        if (firstKey) {
                                            message = error.errors[firstKey][0];
                                        }
                                    }

                                    swal({
                                        title: 'Gagal Memulai Impor',
                                        text: message,
                                        icon: 'error',
                                    });
                                });
                        });
                    });
                }

                // Unduh Template (jaga-jaga elemen tidak ada)
                if (btnDownloadTemplate) {

                    btnDownloadTemplate.addEventListener('click', function(e) {

                        e.preventDefault();

                        const downloadUrl = this.href;

                        setTimeout(() => {

                            blockWholePage("Mengunduh templat...");

                            // mulai download
                            window.location.href = downloadUrl;

                            // unblock singkat (download tidak memberi callback)
                            setTimeout(() => {
                                unblockWholePage();
                            }, 1000);

                        }, 300);

                    });

                }


                /*
                |--------------------------------------------------------------------------
                | EXPORT LS PAYMENT (TOKEN + POLLING)
                |--------------------------------------------------------------------------
                */

                const exportForm = document.getElementById('exportForm');
                const btnExport = document.getElementById('btnExport');
                const startInput = document.getElementById('export_start_date');
                const endInput = document.getElementById('export_end_date');
                const formatInput = document.getElementById('export_format');

                const checkUrl = "{{ route('dashboard.monev.finances.ls-payments.check-export') }}";

                function validateDateRange() {
                    const start = startInput ? startInput.value : '';
                    const end = endInput ? endInput.value : '';
                    const format = formatInput ? formatInput.value : '';

                    if (!start || !end) {
                        swal({
                            title: 'Tanggal belum lengkap',
                            text: 'Silakan isi Tanggal Mulai dan Tanggal Berakhir.',
                            icon: 'warning',
                        });
                        return false;
                    }

                    if (end < start) {
                        swal({
                            title: 'Rentang tanggal tidak valid',
                            text: 'Tanggal Berakhir harus lebih besar atau sama dengan Tanggal Mulai.',
                            icon: 'warning',
                        });
                        return false;
                    }

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
                            unblockWholePage();

                            swal({
                                title: 'Ekspor masih diproses',
                                text: 'Data cukup besar. Silakan coba cek kembali beberapa saat lagi.',
                                icon: 'warning',
                            });

                            return;
                        }

                        fetch(`${checkUrl}?token=${encodeURIComponent(token)}`, {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            })
                            .then(r => r.json())
                            .then(res => {
                                if (res && res.ready && res.download_url) {
                                    clearInterval(interval);
                                    unblockWholePage();

                                    window.location.href = res.download_url;
                                }
                            })
                            .catch(() => {
                                // silent fail
                            });
                    }, 2000);

                    return interval;
                }

                if (exportForm && btnExport) {
                    btnExport.addEventListener('click', (e) => {
                        e.preventDefault();

                        if (!validateDateRange()) return;

                        const selectedFormat = formatInput ? formatInput.value.toUpperCase() : 'XLSX';

                        swal({
                            title: 'Ekspor Data?',
                            text: `Data LS akan diproses di server dalam format ${selectedFormat} berdasarkan rentang tanggal input. Setelah selesai, file dapat diunduh.`,
                            icon: 'info',
                            buttons: ['Batal', 'Ya, Ekspor!'],
                        }).then((willExport) => {
                            if (!willExport) return;

                            blockWholePage('Proses ekspor sedang disiapkan...');

                            const params = new URLSearchParams(new FormData(exportForm));

                            fetch(`${exportForm.action}?${params.toString()}`, {
                                    headers: {
                                        'Accept': 'application/json',
                                    }
                                })
                                .then(r => r.json())
                                .then(res => {
                                    if (!res.token) {
                                        throw new Error('Token ekspor tidak ditemukan.');
                                    }

                                    blockWholePage('Ekspor sedang diproses di server...');
                                    startPolling(res.token);
                                })
                                .catch(() => {
                                    unblockWholePage();

                                    swal({
                                        title: 'Gagal memulai ekspor',
                                        text: 'Terjadi kesalahan saat memulai proses ekspor.',
                                        icon: 'error',
                                    });
                                });
                        });
                    });
                }

            });
        </script>
    @endpush
</x-app-layout>
