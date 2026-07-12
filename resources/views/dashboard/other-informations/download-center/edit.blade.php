<x-app-layout>
    @php $pageTitle = 'Edit Data Dokumen' @endphp

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
                                    action="{{ route('dashboard.other-informations.download-center.update', $data->id) }}"
                                    method="POST" id="myForm">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-body">
                                        <div class="form-group">
                                            <label for="document_title">
                                                Judul Dokumen <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="document_title" id="document_title"
                                                class="form-control @error('document_title') is-invalid @enderror"
                                                placeholder="Masukkan judul dokumen"
                                                value="{{ old('document_title', $data->document_title) }}">
                                            @error('document_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="document_type">
                                                Jenis Dokumen <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="document_type" id="document_type"
                                                class="form-control @error('document_type') is-invalid @enderror"
                                                placeholder="Masukkan jenis dokumen"
                                                value="{{ old('document_type', $data->document_type) }}">
                                            @error('document_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="document_url">Link Dokumen <span
                                                    class="text-danger">*</span></label>
                                            <input type="url" name="document_url" id="document_url"
                                                class="form-control @error('document_url') is-invalid @enderror"
                                                placeholder="Contoh: https://drive.google.com/file/d/1JlQJ05zoYfBWzRLEc2lAesm9AUQ9pA25/preview"
                                                value="{{ old('document_url', $data->document_url) }}">
                                            @error('document_url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="description">
                                                Deskripsi Dokumen <span class="text-danger">*</span>
                                            </label>
                                            <textarea id="description" class="form-control ckeditor @error('description') is-invalid @enderror"
                                                placeholder="Masukkan deskripsi" name="description">{{ old('description', $data->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="status">Status <span class="text-danger">*</span></label>
                                            <select id="status"
                                                class="custom-select @error('status') is-invalid @enderror"
                                                name="status">
                                                <option disabled selected>-- Pilih Status --</option>
                                                <option value="draft"
                                                    {{ old('status', $data->status) == 'draft' ? 'selected' : '' }}>
                                                    Draf</option>
                                                <option value="publish"
                                                    {{ old('status', $data->status) == 'publish' ? 'selected' : '' }}>
                                                    Terbit</option>
                                                <option value="archive"
                                                    {{ old('status', $data->status) == 'archive' ? 'selected' : '' }}>
                                                    Arsip</option>
                                                <option value="internal"
                                                    {{ old('status', $data->status) == 'internal' ? 'selected' : '' }}>
                                                    Dokumen Internal</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-actions right">
                                        <a href="{{ route('dashboard.other-informations.faqs.index') }}"
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
    @endpush

    @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
        <script src="{{ asset('app-assets/js/scripts/ckeditor-config.js') }}"></script>

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
    @endpush
</x-app-layout>
