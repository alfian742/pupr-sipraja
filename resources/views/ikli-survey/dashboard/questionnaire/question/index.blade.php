<x-app-layout>
    @php $pageTitle = 'Daftar Pertanyaan' @endphp

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

                                @if (Auth::user()->role === 'superadmin')
                                    <div class="d-flex flex-md-row justify-content-between align-items-center mb-2 flex-wrap"
                                        style="gap: 0.5rem">
                                        <div class="d-flex align-items-center" style="gap: 0.5rem">
                                            <a href="{{ route('ikli-survey.dashboard.questionnaire.question.template') }}"
                                                class="btn btn-outline-primary" id="btnDownloadTemplate">
                                                <i class="fa fa-file"></i> <span
                                                    class="d-none d-md-inline">Templat</span></button>
                                            </a>

                                            <form
                                                action="{{ route('ikli-survey.dashboard.questionnaire.question.import') }}"
                                                method="POST" enctype="multipart/form-data"
                                                class="d-flex align-items-center" id="formImport">
                                                @csrf

                                                <fieldset class="form-group mb-0" style="margin-right: 0.5rem">
                                                    <div class="custom-file">
                                                        <input type="file"
                                                            class="custom-file-input @error('file') is-invalid @enderror"
                                                            id="excelFile" name="file" accept=".xlsx,.xls,.csv">
                                                        <label class="custom-file-label" for="excelFile">Pilih
                                                            Berkas...</label>
                                                    </div>
                                                </fieldset>

                                                <button type="button" class="btn btn-primary" id="btnImport" disabled>
                                                    <i class="fa fa-upload"></i> <span
                                                        class="d-none d-md-inline">Impor</span></button>
                                                </button>
                                            </form>
                                        </div>
                                        <button class="btn btn-info" type="button" data-toggle="collapse"
                                            data-target="#guideWrapper" aria-expanded="false"
                                            aria-controls="guideWrapper"><i class="fa fa-info-circle"></i> <span
                                                class="d-none d-md-inline">Panduan</span></button>
                                    </div>

                                    <div class="multi-collapse collapse mb-2" id="guideWrapper">
                                        <div
                                            class="bs-callout-info callout-border-left callout-bordered rounded-0 bg-transparent p-1">
                                            <h4 class="info mb-2">Panduan</h4>

                                            <ol class="mb-0">
                                                <li>Silakan unduh templat yang tersedia, kemudian edit sesuai dengan
                                                    format
                                                    yang telah ditentukan.</li>
                                                <li>Impor templat yang telah diedit. Proses ini akan menimpa data lama.
                                                </li>
                                                <li>Tunggu hingga proses selesai dan sistem akan memperbarui kuesioner
                                                    secara otomatis.</li>
                                            </ol>
                                        </div>
                                    </div>
                                @endif

                                <div class="table-responsive">
                                    <table class="table-striped table-bordered table-custom table-align-middle table">
                                        @php
                                            $column = [
                                                'No.',
                                                'Indikator (IKLI & IHLI)',
                                                'Jenis Infrastruktur',
                                                'Keterangan',
                                                'Opsi 1',
                                                'Opsi 2',
                                                'Opsi 3',
                                                'Opsi 4',
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
            href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.min.css') }}">

        <style>
            .text-wrap-scroll {
                white-space: normal !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
                min-width: 200px !important;
                max-width: 300px !important;
                max-height: 200px !important;
                overflow-y: auto !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
        <script src="{{ asset('app-assets/js/scripts/extensions/block-ui-custom.js') }}"></script>

        <script>
            $(document).ready(function() {
                $('.table-custom').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('ikli-survey.dashboard.questionnaire.question.data') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-right'
                        },
                        {
                            data: 'survey_indicator',
                            name: 'survey_indicator',
                            defaultContent: '-'
                        },
                        {
                            data: 'infrastructure_type',
                            name: 'infrastructure_type',
                            defaultContent: '-'
                        },
                        {
                            data: 'description',
                            name: 'description',
                            defaultContent: '-',
                            className: 'text-wrap-scroll'
                        },
                        {
                            data: 'option_1',
                            name: 'option_1',
                            defaultContent: '-',
                            className: 'text-wrap-scroll'
                        },
                        {
                            data: 'option_2',
                            name: 'option_2',
                            defaultContent: '-',
                            className: 'text-wrap-scroll'
                        },
                        {
                            data: 'option_3',
                            name: 'option_3',
                            defaultContent: '-',
                            className: 'text-wrap-scroll'
                        },
                        {
                            data: 'option_4',
                            name: 'option_4',
                            defaultContent: '-',
                            className: 'text-wrap-scroll'
                        },
                    ],
                    order: [],
                    autoWidth: false,
                    scrollX: true,
                    fixedColumns: {
                        leftColumns: 1
                    },
                    language: {
                        url: "{{ asset('app-assets/data/dataTableLangId.json') }}"
                    }
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('formImport');
                const fileInput = document.getElementById('excelFile');
                const fileLabel = document.querySelector('label[for="excelFile"]');
                const btnImport = document.getElementById('btnImport');
                const btnDownloadTemplate = document.getElementById('btnDownloadTemplate');

                const allowedExtensions = ['xlsx', 'xls', 'csv'];
                const maxFileSize = 10 * 1024 * 1024; // 10 MB dalam byte

                // Ubah label & validasi file saat dipilih
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
                            text: `Harap unggah berkas dengan format ".xlsx", ".xls", atau ".csv".`,
                            icon: 'error',
                            button: 'OK'
                        });
                        resetFileInput();
                        return;
                    }

                    // 🔹 Validasi ukuran file (maks 10 MB)
                    if (file.size > maxFileSize) {
                        swal({
                            title: 'Ukuran Terlalu Besar',
                            text: `Ukuran berkas terlalu besar. Maksimum 10 MB.`,
                            icon: 'error',
                            button: 'OK'
                        });
                        resetFileInput();
                        return;
                    }

                    // Potong nama file jika terlalu panjang
                    const baseName = file.name.substring(0, file.name.lastIndexOf('.'));
                    const truncatedName = baseName.length > 14 ? baseName.substring(0, 14) + '...' : baseName;
                    fileLabel.textContent = `${truncatedName}.${fileExt}`;
                    btnImport.disabled = false;
                });

                // Konfirmasi sebelum impor
                btnImport.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (btnImport.disabled) return;

                    swal({
                        title: 'Impor Data?',
                        text: 'Data akan diimpor dari berkas yang dipilih, dan seluruh data sebelumnya akan dihapus. Proses ini mungkin memakan waktu cukup lama. Mohon jangan menutup halaman hingga proses selesai.',
                        icon: 'warning',
                        buttons: ["Batal", "Ya, Impor!"],
                    }).then((willImport) => {
                        if (willImport) {
                            blockWholePage("Proses impor sedang berlangsung...");
                            setTimeout(() => form.submit(), 300);
                        }
                    });
                });

                // Unduh Templat
                btnDownloadTemplate.addEventListener('click', function(e) {
                    e.preventDefault();

                    const downloadUrl = this.href;

                    // Beri sedikit jeda agar UI siap
                    setTimeout(() => {

                        // 🔥 Blok seluruh halaman
                        blockWholePage("Mengunduh templat...");

                        // Mulai download
                        window.location.href = downloadUrl;

                        setTimeout(() => {
                            $.unblockUI();
                        }, 1000);

                    }, 300);
                });

                // Fungsi bantu untuk reset input file
                function resetFileInput() {
                    fileInput.value = '';
                    fileLabel.textContent = 'Pilih Berkas...';
                    btnImport.disabled = true;
                }
            });

            function confirmDelete(id) {
                swal({
                    title: 'Hapus Data?',
                    text: 'Data pada baris ini akan dihapus secara permanen.',
                    icon: 'warning',
                    buttons: ["Batal", "Ya, hapus!"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        blockWholePage("Mohon tunggu...");
                        setTimeout(() => document.getElementById('delete-form-' + id).submit(), 300); // 🔹 beri delay
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
