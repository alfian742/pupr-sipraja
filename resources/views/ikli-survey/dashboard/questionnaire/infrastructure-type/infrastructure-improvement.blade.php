<x-app-layout>
    @php $pageTitle = 'Survei - ' . $infrastructureType @endphp

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

                                <div class="d-flex justify-content-between mb-2" style="gap: 0.5rem;">
                                    <div class="d-flex" style="gap: 0.5rem;">
                                        <button class="btn btn-info" type="button" data-toggle="collapse"
                                            data-target="#filterWrapper" aria-expanded="false"
                                            aria-controls="filterWrapper"><i class="fa fa-filter"></i> <span
                                                class="d-none d-md-inline">Saring</span></button>

                                        <button class="btn btn-success" type="button" data-toggle="collapse"
                                            data-target="#exportWrapper" aria-expanded="false"
                                            aria-controls="exportWrapper"><i class="fa fa-file-excel-o"></i> <span
                                                class="d-none d-md-inline">Ekspor</span></button>
                                    </div>


                                    <form
                                        action="{{ route('ikli-survey.dashboard.questionnaire.respondent.mass-destroy') }}"
                                        method="POST" class="d-flex align-items-center" style="gap: 0.75rem">
                                        @csrf

                                        <p class="mb-0">
                                            Item terpilih: <span class="font-weight-bold" id="countItems">0</span>
                                        </p>

                                        <input type="hidden" name="infrastructure_type"
                                            value="{{ $infrastructureTypeKey }}">

                                        <button class="btn btn-danger" type="button" id="btnDelete"
                                            title="Hapus Data Terpilih" disabled>
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="row mb-2">
                                    @include('layouts.partials.ikli-survey-filter')
                                    @include('layouts.partials.ikli-survey-export')
                                </div>

                                <div class="table-responsive">
                                    <table class="table-striped table-bordered table-custom table-align-middle table">
                                        @php
                                            $column = [
                                                'Valid/Tidak Valid',
                                                'No.',
                                                'Tanggal Pengisian',
                                                'ID Responden',
                                                'Kecamatan',
                                                'Kelurahan/Desa',
                                                'Jenis Kelamin',
                                                'Usia (Tahun)',
                                                'Pendidikan',
                                                'Pekerjaan',
                                                'Disabilitas/Non-Disabilitas',
                                                'Jenis Infrastruktur Prioritas',
                                                'Aspek Prioritas',
                                            ];
                                        @endphp

                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="d-flex align-items-center justify-content-between"
                                                        style="gap: 1rem">
                                                        <span>Aksi</span>
                                                        <input type="checkbox" class="custom-form-check-input check-all"
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

        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/selects/select2.min.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
        <script src="{{ asset('app-assets/js/scripts/extensions/block-ui-custom.js') }}"></script>
        <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>

        <script>
            $(document).ready(function() {
                var table = $('.table-custom').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: {
                        url: "{{ $routeList->data }}",
                        data: function(d) {
                            d.start_date = $('#filter_start_date').val();
                            d.end_date = $('#filter_end_date').val();
                            d.is_valid = $('#filter_is_valid').val();
                            d.district = $('#filter_district').val();
                            d.village = $('#filter_village').val();
                        }
                    },
                    columns: [{
                            data: 'action',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'is_valid',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-right'
                        },
                        {
                            data: 'survey_date',
                            name: 'survey_date',
                            defaultContent: '-'
                        },
                        {
                            data: 'id',
                            name: 'id',
                            defaultContent: '-',
                            className: 'text-right'
                        },
                        {
                            data: 'district',
                            name: 'district',
                            defaultContent: '-'
                        },
                        {
                            data: 'village',
                            name: 'village',
                            defaultContent: '-'
                        },
                        {
                            data: 'gender',
                            name: 'gender',
                            defaultContent: '-'
                        },
                        {
                            data: 'age',
                            name: 'age',
                            defaultContent: '-'
                        },
                        {
                            data: 'education',
                            name: 'education',
                            defaultContent: '-'
                        },
                        {
                            data: 'occupation',
                            name: 'occupation',
                            defaultContent: '-'
                        },
                        {
                            data: 'disability_status',
                            name: 'disability_status',
                            defaultContent: '-'
                        },
                        {
                            data: 'priority_infrastructure',
                            name: 'priority_infrastructure',
                            defaultContent: '-',
                        },
                        {
                            data: 'priority_improvement',
                            name: 'priority_improvement',
                            defaultContent: '-',
                            className: '-'
                        },
                    ],
                    order: [
                        ['3', 'desc'],
                        ['4', 'desc'],
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
                // FILTER DATA
                // =============================
                // Filter
                $('#btnFilter').click(function() {
                    table.draw();
                });

                // Reset
                $('#btnReset').click(function() {
                    $('#filter_start_date').val('');
                    $('#filter_end_date').val('');
                    $('#filter_is_valid')
                        .val(null)
                        .trigger('change');

                    // reset select2 district
                    $('#filter_district')
                        .val(null)
                        .trigger('change');

                    table.draw();
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
            $(document).ready(function() {

                function toggleRange() {
                    let mode = $('#export_mode').val();

                    if (mode === 'all') {
                        $('.range-wrapper').hide();
                        $('#btnExport').prop('disabled', false);
                        clearValidation();
                    } else {
                        $('.range-wrapper').show();
                        validateRange();
                    }
                }

                function clearValidation() {
                    $('#export_start_date, #export_end_date')
                        .removeClass('is-invalid');
                    $('.invalid-feedback').remove();
                }

                function validateRange() {
                    let start = $('#export_start_date').val();
                    let end = $('#export_end_date').val();
                    let isValid = true;

                    clearValidation();

                    if (!start || !end) {
                        isValid = false;
                    }

                    if (start && end && start > end) {
                        isValid = false;

                        $('#export_start_date, #export_end_date')
                            .addClass('is-invalid');

                        $('#export_end_date').after(
                            '<div class="invalid-feedback">Tanggal selesai harus setelah atau sama dengan tanggal mulai.</div>'
                        );
                    }

                    $('#btnExport').prop('disabled', !isValid);
                }

                toggleRange();

                $('#export_mode').on('change', toggleRange);
                $('#export_start_date, #export_end_date').on('change keyup', function() {
                    if ($('#export_mode').val() === 'range') {
                        validateRange();
                    }
                });

                // KONFIRMASI SWEETALERT SEBELUM SUBMIT
                $('#exportForm').on('submit', function(e) {
                    e.preventDefault();

                    if ($('#btnExport').prop('disabled')) {
                        return;
                    }

                    swal({
                        title: 'Ekspor Data?',
                        text: 'Data akan di ekspor sesuai mode yang dipilih dengan format ".xlsx". Proses ini mungkin memakan waktu cukup lama. Mohon jangan menutup halaman hingga proses selesai.',
                        icon: 'info',
                        buttons: ["Batal", "Ya, Ekspor"],
                    }).then((willExport) => {
                        if (willExport) {
                            // blockWholePage("Proses ekspor sedang berlangsung...");
                            setTimeout(() => {
                                $('#exportForm')[0].submit();
                            }, 300);
                        }
                    });
                });

            });
        </script>

        <script>
            $(document).ready(function() {

                $('.select2').select2({
                    width: '100%',
                });

                const $filterDistrict = $('#filter_district');
                const $filterVillage = $('#filter_village');

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

                function resetVillage() {
                    $filterVillage
                        .html('<option value="">-- Pilih Kelurahan/Desa --</option>')
                        .prop('disabled', true);
                    refreshSelect($filterVillage);
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
                                .html('<option value="">-- Pilih Kecamatan --</option>')
                                .prop('disabled', false);

                            if (response.success) {

                                $.each(response.data, function(index, district) {

                                    $filterDistrict.append(
                                        `<option data-id="${district.id}" value="${district.district_name}">
                                            ${district.district_name}
                                        </option>`
                                    );
                                });
                            }

                            refreshSelect($filterDistrict);
                        },
                        error: function() {
                            $filterDistrict
                                .html('<option value="">Gagal memuat data</option>');
                            refreshSelect($filterDistrict);
                        }
                    });
                }

                // ==========================
                // LOAD VILLAGE FILTER
                // ==========================
                function loadFilterVillages(districtId) {

                    if (!districtId) {
                        resetVillage();
                        return;
                    }

                    showLoading($filterVillage, 'Memuat Kelurahan/Desa...');

                    $.ajax({
                        url: "{{ route('ikli-survey.region.villages') }}",
                        type: "GET",
                        data: {
                            district_id: districtId
                        },
                        success: function(response) {

                            $filterVillage
                                .html('<option value="">-- Pilih Kelurahan/Desa --</option>')
                                .prop('disabled', false);

                            if (response.success) {

                                $.each(response.data, function(index, village) {

                                    $filterVillage.append(
                                        `<option data-id="${village.id}" value="${village.village_name}">
                                            ${village.village_name}
                                        </option>`
                                    );
                                });
                            }

                            refreshSelect($filterVillage);
                        },
                        error: function() {
                            resetVillage();
                        }
                    });
                }

                // ==========================
                // EVENT CHANGE
                // ==========================
                $filterDistrict.on('change', function() {
                    // ambil data-id dari option terpilih
                    let districtId = $(this).find(':selected').data('id');

                    // reset desa
                    $filterVillage.val('').trigger('change');

                    loadFilterVillages(districtId);
                });

                // ==========================
                // INIT
                // ==========================
                loadFilterDistricts();
                resetVillage();

            });
        </script>
    @endpush
</x-app-layout>
