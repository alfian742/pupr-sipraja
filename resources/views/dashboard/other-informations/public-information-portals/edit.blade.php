<x-app-layout>
    @php
        $pageTitle = 'Edit Portal Informasi Publik';
        $defaultLogo = asset('assets/images/logo-loteng-square.png');
        $currentLogo = $data->logo ? asset('storage/' . \Illuminate\Support\Str::replaceStart('storage/', '', \Illuminate\Support\Str::replaceStart('uploads/', '', $data->logo))) : $defaultLogo;
    @endphp

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
                                    action="{{ route('dashboard.other-informations.public-information-portals.update', $data->id) }}"
                                    method="POST" id="myForm" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="portal_name">Nama Portal<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="portal_name"
                                                        class="form-control @error('portal_name') is-invalid @enderror"
                                                        placeholder="Contoh: Website Resmi Pemerintah Kabupaten Lombok Tengah"
                                                        name="portal_name"
                                                        value="{{ old('portal_name', $data->portal_name) }}">
                                                    @error('portal_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="description">Deskripsi</label>
                                                    <textarea rows="7" id="description" class="form-control @error('description') is-invalid @enderror"
                                                        placeholder="Contoh: Portal utama Pemerintah Kabupaten Lombok Tengah yang menyajikan informasi pemerintahan, berita daerah, pengumuman, layanan publik, serta tautan menuju kanal informasi resmi pemerintah daerah."
                                                        name="description">{{ old('description', $data->description) }}</textarea>
                                                    @error('description')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="website_url">Link Website</label>
                                                    <input type="text" id="website_url"
                                                        class="form-control @error('website_url') is-invalid @enderror"
                                                        placeholder="Contoh: https://dpupr.lomboktengahkab.go.id/"
                                                        name="website_url"
                                                        value="{{ old('website_url', $data->website_url) }}">
                                                    @error('website_url')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-2">
                                                    <img id="previewImage" src="{{ $currentLogo }}"
                                                        class="d-block mx-auto rounded shadow-sm"
                                                        style="height:260px; width:260px; object-fit: cover;">
                                                </div>

                                                <div class="form-group">
                                                    <label for="logo">Logo</label>
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
                                                            <button
                                                                class="btn btn-outline-danger {{ $data->logo ? '' : 'd-none' }}"
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

                                    <input type="hidden" name="remove_logo" id="remove_logo" value="0">

                                    <div class="form-actions right">
                                        <a href="{{ route('dashboard.other-informations.public-information-portals.index') }}"
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
        <script src="{{ asset('app-assets/js/scripts/extensions/block-ui-custom.js') }}"></script>

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

                const defaultLogo = "{{ $defaultLogo }}";
                const currentLogo = "{{ $currentLogo }}";
                const hasCurrentLogo = "{{ $data->logo ? '1' : '0' }}";

                const $inputLogo = $('#logo');
                const $btnRemove = $('#btnRemoveLogo');
                const $previewImage = $('#previewImage');
                const $frontendError = $('#frontendLogoError');
                const $removeLogo = $('#remove_logo');

                function resetInput() {
                    $inputLogo.val("");
                    $inputLogo.removeClass('is-invalid');
                    $inputLogo.next().text("Pilih Berkas");
                    $frontendError.addClass('d-none').text('');
                    $removeLogo.val("0");

                    if (hasCurrentLogo === "1") {
                        $btnRemove.removeClass('d-none');
                        $previewImage.attr('src', currentLogo);
                    } else {
                        $btnRemove.addClass('d-none');
                        $previewImage.attr('src', defaultLogo);
                    }
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
                    $removeLogo.val("0");

                    const reader = new FileReader();
                    reader.onload = function(event) {
                        $previewImage.attr('src', event.target.result);
                    };
                    reader.readAsDataURL(file);
                });

                $btnRemove.on('click', function() {
                    $inputLogo.val("");
                    $inputLogo.removeClass('is-invalid');
                    $inputLogo.next().text("Pilih Berkas");
                    $frontendError.addClass('d-none').text('');
                    $btnRemove.addClass('d-none');
                    $previewImage.attr('src', defaultLogo);
                    $removeLogo.val("1");
                });
            });
        </script>
    @endpush
</x-app-layout>
