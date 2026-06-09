<x-app-layout>
    @php $pageTitle = 'Tambah' @endphp

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

                                <form class="form"
                                    action="{{ route('dashboard.organization-profiles.departments.store') }}"
                                    method="POST" id="myForm" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="department_name">Nama Bidang <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="department_name"
                                                        class="form-control @error('department_name') is-invalid @enderror"
                                                        placeholder="Contoh: Bidang Cipta Karya" name="department_name"
                                                        value="{{ old('department_name') }}">
                                                    @error('department_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="description">Deskripsi Bidang</label>
                                                    <textarea rows="7" id="description" class="form-control @error('description') is-invalid @enderror"
                                                        placeholder="Contoh: Bidang yang menangani urusan infrastruktur permukiman, bangunan gedung, dan penataan lingkungan."
                                                        name="description">{{ old('description') }}</textarea>
                                                    @error('description')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-2">
                                                    <img id="previewImage"
                                                        src="{{ asset('public/assets/images/placeholder.svg') }}"
                                                        class="d-block mx-auto rounded shadow-sm"
                                                        style="height:260px; width:260px; object-fit: cover;">
                                                </div>

                                                <div class="form-group">
                                                    <label for="logo">Logo Bidang</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" name="logo" id="logo"
                                                                class="custom-file-input @error('logo') is-invalid @enderror"
                                                                accept=".jpg,.jpeg,.png">
                                                            <label class="custom-file-label" for="logo">
                                                                Pilih Berkas
                                                            </label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-danger d-none"
                                                                id="btnRemoveLogo" type="button">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @error('logo')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                    <small class="text-danger d-none" id="frontendLogoError">
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions right">
                                        <a href="{{ route('dashboard.organization-profiles.departments.index') }}"
                                            class="btn btn-secondary mr-1">
                                            <i class="ft-x"></i> Batal
                                        </a>
                                        <button type="submit" class="btn btn-indigo" id="btnSubmit">
                                            <i class="fa fa-check-square-o"></i> Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
        <script src="{{ asset('public/app-assets/js/scripts/extensions/block-ui-custom.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const btnSubmit = document.getElementById("btnSubmit");
                const form = document.getElementById("myForm");

                btnSubmit.addEventListener('click', (e) => {
                    e.preventDefault();

                    // Blok UI terlebih dahulu
                    blockWholePage("Mohon tunggu...");

                    // Submit form setelah jeda kecil
                    setTimeout(() => {
                        form.submit();
                    }, 300);
                });
            });
        </script>

        <script>
            $(document).ready(function() {

                // =========================
                // VALIDASI & PREVIEW LOGO
                // =========================
                const allowedExtensions = ['jpg', 'jpeg', 'png'];
                const maxSizeKB = 1024;

                const $inputLogo = $('#logo');
                const $btnRemove = $('#btnRemoveLogo');
                const $previewImage = $('#previewImage');
                const $frontendError = $('#frontendLogoError');

                function resetInput() {
                    $inputLogo.val("");
                    $inputLogo.removeClass('is-invalid');
                    $inputLogo.next().text("Pilih Berkas");
                    $frontendError.addClass('d-none').text('');
                    $btnRemove.addClass('d-none');
                    $previewImage.attr('src', "{{ asset('public/assets/images/placeholder.svg') }}");
                }

                $inputLogo.on('change', function(e) {

                    const file = this.files[0];

                    if (!file) {
                        resetInput();
                        return;
                    }

                    const fileExt = file.name.split('.').pop().toLowerCase();
                    const fileSizeKB = file.size / 1024;

                    // Validasi ekstensi
                    if ($.inArray(fileExt, allowedExtensions) === -1) {
                        $inputLogo.addClass('is-invalid');
                        $frontendError
                            .text('Format yang didukung JPG, JPEG, atau PNG.')
                            .removeClass('d-none');
                        $inputLogo.val("");
                        return;
                    }

                    // Validasi ukuran
                    if (fileSizeKB > maxSizeKB) {
                        $inputLogo.addClass('is-invalid');
                        $frontendError
                            .text('Ukuran logo maksimal 1 MB.')
                            .removeClass('d-none');
                        $inputLogo.val("");
                        return;
                    }

                    // Jika valid
                    $inputLogo.next().text(file.name);
                    $btnRemove.removeClass('d-none');
                    $inputLogo.removeClass('is-invalid');
                    $frontendError.addClass('d-none');

                    const reader = new FileReader();
                    reader.onload = function(event) {
                        $previewImage.attr('src', event.target.result);
                    };
                    reader.readAsDataURL(file);
                });

                $btnRemove.on('click', function() {
                    resetInput();
                });

            });
        </script>
    @endpush
</x-app-layout>
