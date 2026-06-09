<x-app-layout>
    @php $pageTitle = 'Kelola Pengguna' @endphp

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
                                    <a href="{{ route('dashboard.users.create') }}" class="btn btn-indigo">
                                        <i class="fa fa-plus"></i> Tambah
                                    </a>
                                </div>

                                <div class="table-responsive">
                                    <table class="table-striped table-bordered table-custom table-align-middle table">
                                        @php
                                            $column = [
                                                'Aksi',
                                                'No.',
                                                'Terakhir Masuk',
                                                'Nama',
                                                'Email',
                                                'Status',
                                                'Peran',
                                                'Tanggal Verifikasi',
                                            ];
                                        @endphp

                                        <thead>
                                            <tr>
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
            href="{{ asset('public/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('public/app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
        <script src="{{ asset('public/app-assets/js/scripts/extensions/block-ui-custom.js') }}"></script>

        <script>
            $(document).ready(function() {
                $('.table-custom').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('dashboard.users.data') }}",
                    columns: [{
                            data: 'action',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-right'
                        },
                        {
                            data: 'last_login_at',
                            name: 'last_login_at',
                            defaultContent: '-'
                        },
                        {
                            data: 'name',
                            name: 'name',
                            defaultContent: '-'
                        },
                        {
                            data: 'email',
                            name: 'email',
                            defaultContent: '-'
                        },
                        {
                            data: 'is_active',
                            name: 'is_active',
                            className: 'text-center',
                            defaultContent: '-'
                        },
                        {
                            data: 'role',
                            name: 'role',
                            className: 'text-center',
                            defaultContent: '-'
                        },
                        {
                            data: 'email_verified_at',
                            name: 'email_verified_at',
                            defaultContent: '-'
                        },
                    ],
                    order: [],
                    autoWidth: false,
                    scrollX: true,
                    fixedColumns: {
                        leftColumns: 1
                    },
                    language: {
                        url: "{{ asset('public/app-assets/data/dataTableLangId.json') }}"
                    }
                });
            });
        </script>

        <script>
            function confirmSetStatus(id, isActive) {
                const formId = `set-status-form-${id}`;
                const willProceedAction = !isActive;

                const title = willProceedAction ? 'Aktifkan Akun?' : 'Nonaktifkan Akun?';
                const text = willProceedAction ?
                    'Akun pengguna ini akan diaktifkan.' :
                    'Akun pengguna ini akan dinonaktifkan.';
                const confirmButtonText = willProceedAction ?
                    'Ya, aktifkan!' :
                    'Ya, nonaktifkan!';
                const loadingText = willProceedAction ?
                    'Mengaktifkan akun...' :
                    'Menonaktifkan akun...';

                swal({
                    title: title,
                    text: text,
                    icon: 'warning',
                    buttons: ["Batal", confirmButtonText],
                    dangerMode: true,
                }).then((willProceed) => {
                    if (!willProceed) return;

                    const form = document.getElementById(formId);
                    if (!form) return;

                    blockWholePage(loadingText);
                    setTimeout(() => form.submit(), 300);
                });
            }

            function confirmResetPassword(id) {
                const formId = `reset-password-form-${id}`;
                const title = 'Reset Kata Sandi?';
                const text = 'Kata sandi pengguna ini akan direset.';
                const confirmButtonText = 'Ya, reset!';
                const loadingText = 'Mereset kata sandi...';

                swal({
                    title: title,
                    text: text,
                    icon: 'warning',
                    buttons: ["Batal", confirmButtonText],
                    dangerMode: true,
                }).then((willProceed) => {
                    if (!willProceed) return;

                    const form = document.getElementById(formId);
                    if (!form) return;

                    blockWholePage(loadingText);
                    setTimeout(() => form.submit(), 300);
                });
            }

            function confirmDelete(id) {
                const formId = `delete-form-${id}`;
                const title = 'Hapus Pengguna?';
                const text = 'Pengguna akan dihapus secara permanen.';
                const confirmButtonText = 'Ya, hapus!';
                const loadingText = 'Menghapus pengguna...';

                swal({
                    title: title,
                    text: text,
                    icon: 'warning',
                    buttons: ["Batal", confirmButtonText],
                    dangerMode: true,
                }).then((willProceed) => {
                    if (!willProceed) return;

                    const form = document.getElementById(formId);
                    if (!form) return;

                    blockWholePage(loadingText);
                    setTimeout(() => form.submit(), 300);
                });
            }
        </script>
    @endpush
</x-app-layout>
