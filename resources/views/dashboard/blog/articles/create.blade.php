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

                                <form class="form" action="{{ route('dashboard.blog.articles.store') }}"
                                    method="POST" id="myForm" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-8">

                                                <div class="card border-indigo">
                                                    <div class="card-header bg-indigo">
                                                        <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                            Informasi Artikel
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">

                                                        <div class="form-group">
                                                            <label for="title">Judul Artikel <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" id="title"
                                                                class="form-control @error('title') is-invalid @enderror"
                                                                placeholder="Contoh: Manfaat Web GIS untuk Monitoring Infrastruktur"
                                                                name="title" value="{{ old('title') }}">
                                                            @error('title')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="blog_category_id">Kategori <span
                                                                    class="text-danger">*</span></label>
                                                            <select id="blog_category_id" name="blog_category_id"
                                                                class="form-control select2 @error('blog_category_id') is-invalid @enderror">
                                                                <option value="">-- Pilih Kategori --</option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}"
                                                                        @selected(old('blog_category_id') == $category->id)>
                                                                        {{ $category->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('blog_category_id')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group mb-0">
                                                            <label for="excerpt">Ringkasan Artikel</label>
                                                            <textarea rows="4" id="excerpt" class="form-control @error('excerpt') is-invalid @enderror"
                                                                placeholder="Contoh: Artikel ini membahas pemanfaatan Web GIS untuk mendukung pengelolaan dan monitoring infrastruktur secara digital."
                                                                name="excerpt">{{ old('excerpt') }}</textarea>
                                                            @error('excerpt')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                            <small class="text-muted">
                                                                Ringkasan singkat untuk tampilan daftar artikel dan meta
                                                                informasi.
                                                            </small>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="card border-indigo">
                                                    <div class="card-header bg-indigo">
                                                        <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                            Konten Artikel
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">

                                                        <div class="form-group mb-0">
                                                            <label for="content">Konten Artikel <span
                                                                    class="text-danger">*</span></label>
                                                            <textarea rows="14" id="content" class="form-control ckeditor @error('content') is-invalid @enderror"
                                                                placeholder="Tulis konten artikel di sini." name="content">{{ old('content') }}</textarea>
                                                            @error('content')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="card border-indigo">
                                                    <div class="card-header bg-indigo">
                                                        <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                            SEO
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">

                                                        <div class="form-group">
                                                            <label for="meta_title">Meta Title</label>
                                                            <input type="text" id="meta_title"
                                                                class="form-control @error('meta_title') is-invalid @enderror"
                                                                placeholder="Contoh: Manfaat Web GIS untuk Monitoring Infrastruktur"
                                                                name="meta_title" value="{{ old('meta_title') }}">
                                                            @error('meta_title')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="meta_description">Meta Description</label>
                                                            <textarea rows="4" id="meta_description" class="form-control @error('meta_description') is-invalid @enderror"
                                                                placeholder="Contoh: Pelajari bagaimana Web GIS membantu monitoring infrastruktur secara lebih efektif, akurat, dan mudah diakses."
                                                                name="meta_description">{{ old('meta_description') }}</textarea>
                                                            @error('meta_description')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group mb-0">
                                                            <label for="meta_keywords">Meta Keywords</label>
                                                            <input type="text" id="meta_keywords"
                                                                class="form-control @error('meta_keywords') is-invalid @enderror"
                                                                placeholder="Contoh: Web GIS, Infrastruktur, Dashboard, Pemetaan"
                                                                name="meta_keywords"
                                                                value="{{ old('meta_keywords') }}">
                                                            @error('meta_keywords')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                            <small class="text-muted">
                                                                Pisahkan setiap kata kunci dengan koma.
                                                            </small>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">

                                                <div class="card border-indigo">
                                                    <div class="card-header bg-indigo">
                                                        <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                            Thumbnail Artikel
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">

                                                        <div class="mb-2">
                                                            <img id="previewImage"
                                                                src="{{ asset('public/assets/images/placeholder.svg') }}"
                                                                class="d-block mx-auto rounded shadow-sm"
                                                                style="height:260px; width:260px; object-fit: cover;">
                                                        </div>

                                                        <div class="form-group mb-0">
                                                            <label for="thumbnail">Thumbnail Artikel</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input type="file" name="thumbnail"
                                                                        id="thumbnail"
                                                                        class="custom-file-input @error('thumbnail') is-invalid @enderror"
                                                                        accept=".jpg,.jpeg,.png,.webp">
                                                                    <label class="custom-file-label" for="thumbnail">
                                                                        Pilih Berkas
                                                                    </label>
                                                                </div>
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-outline-danger d-none"
                                                                        id="btnRemoveThumbnail" type="button">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            @error('thumbnail')
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                            <small class="text-danger d-none"
                                                                id="frontendThumbnailError">
                                                            </small>
                                                            <small class="text-muted d-block mt-1">
                                                                Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                                                            </small>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="card border-indigo">
                                                    <div class="card-header bg-indigo">
                                                        <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                            Pengaturan Publikasi
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">

                                                        <div class="form-group">
                                                            <label for="user_id">Penulis</label>
                                                            <select id="user_id" name="user_id"
                                                                class="form-control select2 @error('user_id') is-invalid @enderror">
                                                                <option value="">-- Gunakan User Login --
                                                                </option>
                                                                @foreach ($users as $user)
                                                                    <option value="{{ $user->id }}"
                                                                        @selected(old('user_id', Auth::id()) == $user->id)>
                                                                        {{ $user->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('user_id')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="status">Status Artikel <span
                                                                    class="text-danger">*</span></label>
                                                            <select id="status" name="status"
                                                                class="form-control select2 @error('status') is-invalid @enderror">
                                                                <option value="draft" @selected(old('status', 'draft') == 'draft')>
                                                                    Draft
                                                                </option>
                                                                <option value="published" @selected(old('status') == 'published')>
                                                                    Published
                                                                </option>
                                                                <option value="archived" @selected(old('status') == 'archived')>
                                                                    Archived
                                                                </option>
                                                            </select>
                                                            @error('status')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="published_at">Tanggal Publikasi</label>
                                                            <input type="datetime-local" id="published_at"
                                                                class="form-control @error('published_at') is-invalid @enderror"
                                                                name="published_at"
                                                                value="{{ old('published_at') }}">
                                                            @error('published_at')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                            <small class="text-muted">
                                                                Jika status Published dan tanggal kosong, sistem akan
                                                                memakai
                                                                waktu saat ini.
                                                            </small>
                                                        </div>

                                                        <div class="form-group mb-0">
                                                            <label>Artikel Unggulan</label>

                                                            <input type="hidden" name="is_featured" value="0">

                                                            <div class="d-flex align-items-center mt-1"
                                                                style="gap: 0.5rem">
                                                                <input type="checkbox"
                                                                    class="custom-form-check-input @error('is_featured') is-invalid @enderror"
                                                                    id="is_featured" name="is_featured"
                                                                    value="1" @checked(old('is_featured') == 1)>
                                                                <label for="is_featured" class="mb-0">
                                                                    Jadikan artikel unggulan
                                                                </label>
                                                            </div>

                                                            @error('is_featured')
                                                                <small
                                                                    class="text-danger d-block">{{ $message }}</small>
                                                            @enderror

                                                            <small class="text-muted">
                                                                Artikel unggulan dapat ditampilkan pada bagian utama
                                                                halaman blog.
                                                            </small>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions right">
                                        <a href="{{ route('dashboard.blog.articles.index') }}"
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
        <link rel="stylesheet" type="text/css"
            href="{{ asset('public/app-assets/vendors/css/forms/selects/select2.min.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('public/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
        <script src="{{ asset('public/app-assets/js/scripts/ckeditor-blog-config.js') }}"></script>
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
                // INIT SELECT2
                // =========================
                $('.select2').select2({
                    width: '100%',
                });

                // =========================
                // VALIDASI & PREVIEW THUMBNAIL
                // =========================
                const allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                const maxSizeKB = 2048;

                const $inputThumbnail = $('#thumbnail');
                const $btnRemove = $('#btnRemoveThumbnail');
                const $previewImage = $('#previewImage');
                const $frontendError = $('#frontendThumbnailError');

                function resetInput() {
                    $inputThumbnail.val("");
                    $inputThumbnail.removeClass('is-invalid');
                    $inputThumbnail.next().text("Pilih Berkas");
                    $frontendError.addClass('d-none').text('');
                    $btnRemove.addClass('d-none');
                    $previewImage.attr('src', "{{ asset('public/assets/images/placeholder.svg') }}");
                }

                $inputThumbnail.on('change', function(e) {

                    const file = this.files[0];

                    if (!file) {
                        resetInput();
                        return;
                    }

                    const fileExt = file.name.split('.').pop().toLowerCase();
                    const fileSizeKB = file.size / 1024;

                    // Validasi ekstensi
                    if ($.inArray(fileExt, allowedExtensions) === -1) {
                        $inputThumbnail.addClass('is-invalid');
                        $frontendError
                            .text('Format yang didukung JPG, JPEG, PNG, atau WEBP.')
                            .removeClass('d-none');
                        $inputThumbnail.val("");
                        return;
                    }

                    // Validasi ukuran
                    if (fileSizeKB > maxSizeKB) {
                        $inputThumbnail.addClass('is-invalid');
                        $frontendError
                            .text('Ukuran thumbnail maksimal 2 MB.')
                            .removeClass('d-none');
                        $inputThumbnail.val("");
                        return;
                    }

                    // Jika valid
                    $inputThumbnail.next().text(file.name);
                    $btnRemove.removeClass('d-none');
                    $inputThumbnail.removeClass('is-invalid');
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
