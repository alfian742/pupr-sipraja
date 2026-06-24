<x-app-layout>
    @php
        $pageTitle = 'Edit Carousel';
        $defaultImage = asset('assets/images/placeholder.svg');
        $currentImage = $data->image_path ? asset($data->image_path) : $defaultImage;
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

                                <form class="form" action="{{ route('dashboard.hero-carousels.update', $data->id) }}"
                                    method="POST" id="myForm" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="title">Judul Carousel <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="title"
                                                        class="form-control @error('title') is-invalid @enderror"
                                                        placeholder="Contoh: Gedung DPUPR Kabupaten Lombok Tengah"
                                                        name="title" value="{{ old('title', $data->title) }}">
                                                    @error('title')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="description">Deskripsi Singkat</label>
                                                    <textarea rows="7" id="description" class="form-control @error('description') is-invalid @enderror"
                                                        placeholder="Contoh: Dokumentasi gedung dan lingkungan DPUPR Kabupaten Lombok Tengah." name="description">{{ old('description', $data->description) }}</textarea>
                                                    @error('description')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sort_order">Urutan Carousel <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="number" id="sort_order"
                                                                class="form-control @error('sort_order') is-invalid @enderror"
                                                                placeholder="Contoh: 1" name="sort_order"
                                                                value="{{ old('sort_order', $data->sort_order) }}"
                                                                min="0">
                                                            @error('sort_order')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Status Carousel</label>
                                                            <div class="d-flex align-items-center mt-1"
                                                                style="gap: 0.5rem">
                                                                <input type="checkbox"
                                                                    class="custom-form-check-input @error('is_active') is-invalid @enderror"
                                                                    id="is_active" name="is_active" value="1"
                                                                    {{ old('is_active', $data->is_active) == 1 ? 'checked' : '' }}>
                                                                <label for="is_active" class="mb-0">Aktif</label>
                                                            </div>
                                                            @error('is_active')
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-2">
                                                    <img id="previewImage" src="{{ $currentImage }}"
                                                        class="d-block mx-auto rounded shadow-sm"
                                                        style="height:260px; aspect-ratio: 3 / 4; object-fit: cover;">
                                                </div>

                                                <div class="form-group">
                                                    <label for="image_path">Gambar Carousel</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" name="image_path" id="image_path"
                                                                class="custom-file-input @error('image_path') is-invalid @enderror"
                                                                accept=".jpg,.jpeg,.png">
                                                            <label class="custom-file-label" for="image_path">
                                                                Pilih Berkas
                                                            </label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <button
                                                                class="btn btn-outline-danger {{ $data->image_path ? '' : 'd-none' }}"
                                                                id="btnRemoveImage" type="button">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @error('image_path')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                    <small class="text-danger d-none" id="frontendImageError">
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="remove_image" id="remove_image" value="0">

                                    <div class="form-actions right">
                                        <a href="{{ route('dashboard.hero-carousels.index') }}"
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
                // VALIDASI & PREVIEW GAMBAR
                // =========================
                const allowedExtensions = ['jpg', 'jpeg', 'png'];
                const maxSizeKB = 2048;

                const defaultImage = "{{ $defaultImage }}";
                const currentImage = "{{ $currentImage }}";
                const hasCurrentImage = "{{ $data->image_path ? '1' : '0' }}";

                const $inputImage = $('#image_path');
                const $btnRemove = $('#btnRemoveImage');
                const $previewImage = $('#previewImage');
                const $frontendError = $('#frontendImageError');
                const $removeImage = $('#remove_image');

                function resetInput() {
                    $inputImage.val("");
                    $inputImage.removeClass('is-invalid');
                    $inputImage.next().text("Pilih Berkas");
                    $frontendError.addClass('d-none').text('');
                    $removeImage.val("0");

                    if (hasCurrentImage === "1") {
                        $btnRemove.removeClass('d-none');
                        $previewImage.attr('src', currentImage);
                    } else {
                        $btnRemove.addClass('d-none');
                        $previewImage.attr('src', defaultImage);
                    }
                }

                $inputImage.on('change', function(e) {

                    const file = this.files[0];

                    if (!file) {
                        resetInput();
                        return;
                    }

                    const fileExt = file.name.split('.').pop().toLowerCase();
                    const fileSizeKB = file.size / 1024;

                    // Validasi ekstensi
                    if ($.inArray(fileExt, allowedExtensions) === -1) {
                        $inputImage.addClass('is-invalid');
                        $frontendError
                            .text('Format yang didukung JPG, JPEG, atau PNG.')
                            .removeClass('d-none');
                        $inputImage.val("");
                        return;
                    }

                    // Validasi ukuran
                    if (fileSizeKB > maxSizeKB) {
                        $inputImage.addClass('is-invalid');
                        $frontendError
                            .text('Ukuran gambar maksimal 2 MB.')
                            .removeClass('d-none');
                        $inputImage.val("");
                        return;
                    }

                    // Jika valid
                    $inputImage.next().text(file.name);
                    $btnRemove.removeClass('d-none');
                    $inputImage.removeClass('is-invalid');
                    $frontendError.addClass('d-none');
                    $removeImage.val("0");

                    const reader = new FileReader();
                    reader.onload = function(event) {
                        $previewImage.attr('src', event.target.result);
                    };
                    reader.readAsDataURL(file);
                });

                $btnRemove.on('click', function() {
                    $inputImage.val("");
                    $inputImage.removeClass('is-invalid');
                    $inputImage.next().text("Pilih Berkas");
                    $frontendError.addClass('d-none').text('');
                    $btnRemove.addClass('d-none');
                    $previewImage.attr('src', defaultImage);
                    $removeImage.val("1");
                });
            });
        </script>
    @endpush
</x-app-layout>
