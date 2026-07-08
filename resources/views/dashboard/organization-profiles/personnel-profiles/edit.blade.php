<x-app-layout>
    @php $pageTitle = 'Edit Profil Personel' @endphp

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
                                    action="{{ route('dashboard.organization-profiles.personnel-profiles.update', $data->id) }}"
                                    method="POST" id="myForm" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="personnel_name">Nama Personel <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="personnel_name"
                                                        class="form-control @error('personnel_name') is-invalid @enderror"
                                                        placeholder="Contoh: John Doe" name="personnel_name"
                                                        value="{{ old('personnel_name', $data->personnel_name) }}">
                                                    @error('personnel_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                @php
                                                    $personnelPositionOptions = [
                                                        'Kepala Dinas',
                                                        'Sekretaris',
                                                        'Bendahara',
                                                        'Kepala Bidang Bina Marga',
                                                        'Kepala Bidang Cipta Karya',
                                                        'Kepala Bidang Sumber Daya Air (SDA)',
                                                        'Kepala Bidang Penataan Ruang',
                                                        'Anggota Bidang Bina Marga',
                                                        'Anggota Bidang Cipta Karya',
                                                        'Anggota Bidang Sumber Daya Air (SDA)',
                                                        'Anggota Bidang Penataan Ruang',
                                                    ];

                                                    $value = old('personnel_position', $data->personnel_position);

                                                    $isCustom = $value && !in_array($value, $personnelPositionOptions);
                                                @endphp

                                                <div class="form-group">
                                                    <label for="personnel_position">Jabatan <span
                                                            class="text-danger">*</span></label>

                                                    <select id="select_personnel_position"
                                                        class="custom-select select2 @error('personnel_position') is-invalid @enderror">

                                                        <option value="" disabled {{ !$value ? 'selected' : '' }}>
                                                            -- Pilih Jabatan --
                                                        </option>

                                                        @foreach ($personnelPositionOptions as $option)
                                                            <option value="{{ $option }}"
                                                                {{ $value === $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach

                                                        <option value="Lainnya" {{ $isCustom ? 'selected' : '' }}>
                                                            Lainnya
                                                        </option>
                                                    </select>

                                                    <input type="text" id="personnel_position"
                                                        name="personnel_position"
                                                        class="form-control {{ $isCustom ? '' : 'd-none' }} @error('personnel_position') is-invalid @enderror mt-2"
                                                        placeholder="Isi jika memilih Lainnya" required
                                                        value="{{ $isCustom ? $value : '' }}">

                                                    @error('personnel_position')
                                                        <div class="invalid-feedback">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="personnel_description">Deskripsi Personel <span
                                                            class="text-danger">*</span></label>
                                                    <textarea rows="7" id="personnel_description"
                                                        class="form-control @error('personnel_description') is-invalid @enderror"
                                                        placeholder="Contoh: Tenaga kerja ahli yang menjunjung profesionalisme dalam pekerjaan dan peduli terhadap sesama."
                                                        name="personnel_description">{{ old('personnel_description', $data->personnel_description) }}</textarea>
                                                    @error('personnel_description')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-2">
                                                    <img id="previewImage"
                                                        src="{{ $data->personnel_photo ? asset('storage/' . \Illuminate\Support\Str::replaceStart('storage/', '', \Illuminate\Support\Str::replaceStart('uploads/', '', $data->personnel_photo))) : asset('assets/images/avatar.png') }}"
                                                        class="d-block mx-auto rounded shadow-sm"
                                                        style="height:260px; aspect-ratio:3/4; object-fit: cover;">
                                                </div>

                                                <div class="form-group">
                                                    <label for="personnel_photo">Foto Personel</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" name="personnel_photo"
                                                                id="personnel_photo"
                                                                class="custom-file-input @error('personnel_photo') is-invalid @enderror"
                                                                accept=".jpg,.jpeg,.png">
                                                            <label class="custom-file-label" for="personnel_photo">
                                                                Pilih Berkas
                                                            </label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-danger d-none"
                                                                id="btnRemovePhoto" type="button">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @error('personnel_photo')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                    <small class="text-danger d-none" id="frontendPhotoError">
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="remove_photo" id="remove_photo" value="0">

                                    <div class="form-actions right">
                                        <a href="{{ route('dashboard.organization-profiles.personnel-profiles.index') }}"
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

    @push('styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/selects/select2.min.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>

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
                // INIT SELECT2
                // =========================
                $('.select2').select2({
                    width: '100%',
                });

                // =========================
                // LOGIC JABATAN "LAINNYA"
                // =========================
                function initOtherSelect(selectSelector, inputSelector) {

                    const $select = $(selectSelector);
                    const $input = $(inputSelector);

                    function toggle() {

                        const originalValue = $select.val() || '';
                        const normalizedValue = originalValue.trim().toLowerCase();

                        if (normalizedValue === 'lainnya') {

                            // Tampilkan input custom
                            $input
                                .removeClass('d-none')
                                .prop('required', true);

                            // Validasi jika kosong
                            if (!$input.val() || !$input.val().trim()) {
                                $input.addClass('is-invalid');
                            } else {
                                $input.removeClass('is-invalid');
                            }

                        } else {

                            // Sembunyikan input
                            $input
                                .addClass('d-none')
                                .prop('required', false)
                                .removeClass('is-invalid');

                            // Set value dari select (tanpa ubah case)
                            if (originalValue) {
                                $input.val(originalValue);
                            }
                        }
                    }

                    // Jalankan saat pertama load (create & edit)
                    toggle();

                    // Saat select berubah
                    $select.on('change', function() {
                        toggle();
                    });

                    // Saat user mengetik di input
                    $input.on('input', function() {

                        const selectedValue = ($select.val() || '').trim().toLowerCase();

                        if (selectedValue === 'lainnya') {

                            if ($(this).val().trim()) {
                                $(this).removeClass('is-invalid');
                            } else {
                                $(this).addClass('is-invalid');
                            }
                        }
                    });
                }

                // Panggil function (bisa ditambah kalau ada field lain)
                initOtherSelect('#select_personnel_position', '#personnel_position');

                // =========================
                // VALIDASI & PREVIEW FOTO
                // =========================
                const allowedExtensions = ['jpg', 'jpeg', 'png'];
                const maxSizeKB = 1024;

                const $inputPhoto = $('#personnel_photo');
                const $btnRemove = $('#btnRemovePhoto');
                const $previewImage = $('#previewImage');
                const $frontendError = $('#frontendPhotoError');

                function resetInput() {
                    $inputPhoto.val("");
                    $inputPhoto.removeClass('is-invalid');
                    $inputPhoto.next().text("Pilih Berkas");
                    $frontendError.addClass('d-none').text('');
                    $btnRemove.addClass('d-none');
                    $previewImage.attr('src',
                        "{{ $data->personnel_photo ? asset('storage/' . \Illuminate\Support\Str::replaceStart('storage/', '', \Illuminate\Support\Str::replaceStart('uploads/', '', $data->personnel_photo))) : asset('assets/images/avatar.png') }}"
                    );
                }

                $inputPhoto.on('change', function(e) {

                    const file = this.files[0];

                    if (!file) {
                        resetInput();
                        return;
                    }

                    const fileExt = file.name.split('.').pop().toLowerCase();
                    const fileSizeKB = file.size / 1024;

                    // Validasi ekstensi
                    if ($.inArray(fileExt, allowedExtensions) === -1) {
                        $inputPhoto.addClass('is-invalid');
                        $frontendError
                            .text('Format yang didukung JPG, JPEG, atau PNG.')
                            .removeClass('d-none');
                        $inputPhoto.val("");
                        return;
                    }

                    // Validasi ukuran
                    if (fileSizeKB > maxSizeKB) {
                        $inputPhoto.addClass('is-invalid');
                        $frontendError
                            .text('Ukuran foto maksimal 1 MB.')
                            .removeClass('d-none');
                        $inputPhoto.val("");
                        return;
                    }

                    // Jika valid
                    $inputPhoto.next().text(file.name);
                    $btnRemove.removeClass('d-none');
                    $inputPhoto.removeClass('is-invalid');
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
