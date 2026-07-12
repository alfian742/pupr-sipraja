<x-app-layout>
    @php $pageTitle = 'Bidang' @endphp

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
                                        <a href="{{ route('dashboard.organization-profiles.departments.create') }}"
                                            class="btn btn-indigo">
                                            <i class="fa fa-plus"></i> Tambah
                                        </a>
                                    </div>

                                    <form
                                        action="{{ route('dashboard.organization-profiles.departments.mass-destroy') }}"
                                        method="POST" class="d-flex align-items-center" style="gap: 0.75rem">
                                        @csrf

                                        <p class="mb-0">
                                            Item terpilih: <span class="font-weight-bold" id="countItems">0</span>
                                        </p>

                                        <button class="btn btn-danger" type="button" id="btnDelete"
                                            title="Hapus Data Terpilih" disabled>
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="table-responsive">
                                    <table class="table-striped table-bordered table-custom table-align-middle table">
                                        @php
                                            $column = ['Logo', 'Nama Bidang', 'Deskripsi', 'Riwayat'];
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
    @endpush

    @push('scripts')
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
        <script src="{{ asset('app-assets/js/scripts/extensions/block-ui-custom.js') }}"></script>

        <script>
            $(document).ready(function() {
                const table = $('.table-custom').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('dashboard.organization-profiles.departments.data') }}",
                    columns: [{
                            data: 'action',
                            orderable: false,
                            searchable: false,
                        },
                        {
                            data: 'logo',
                            name: 'logo',
                            defaultContent: '-',
                            orderable: false,
                            searchable: false,
                        },
                        {
                            data: 'department_name',
                            name: 'department_name',
                            defaultContent: '-'
                        },
                        {
                            data: 'description',
                            name: 'description',
                            defaultContent: '-'
                        },
                        {
                            data: 'history',
                            name: 'history',
                            defaultContent: '-'
                        },
                    ],
                    order: ['2', 'asc'],
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
    @endpush
</x-app-layout>
