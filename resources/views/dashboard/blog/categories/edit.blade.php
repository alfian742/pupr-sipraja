<x-app-layout>
    @php $pageTitle = 'Edit Kategori' @endphp

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

                                <form class="form" action="{{ route('dashboard.blog.categories.update', $data->id) }}"
                                    method="POST" id="myForm">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="name">Nama Kategori <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="name"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        placeholder="Contoh: Web GIS" name="name"
                                                        value="{{ old('name', $data->name) }}">
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="description">Deskripsi Kategori</label>
                                                    <textarea rows="7" id="description" class="form-control @error('description') is-invalid @enderror"
                                                        placeholder="Contoh: Kategori artikel yang membahas Web GIS, pemetaan digital, dan sistem informasi geografis."
                                                        name="description">{{ old('description', $data->description) }}</textarea>
                                                    @error('description')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="sort_order">Urutan</label>
                                                    <input type="number" id="sort_order"
                                                        class="form-control @error('sort_order') is-invalid @enderror"
                                                        placeholder="Contoh: 1" name="sort_order"
                                                        value="{{ old('sort_order', $data->sort_order) }}"
                                                        min="0">
                                                    @error('sort_order')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">
                                                        Digunakan untuk mengatur urutan kategori pada tampilan.
                                                    </small>
                                                </div>

                                                <div class="form-group">
                                                    <label>Status Kategori</label>

                                                    <input type="hidden" name="is_active" value="0">

                                                    <div class="d-flex align-items-center mt-1" style="gap: 0.5rem">
                                                        <input type="checkbox"
                                                            class="custom-form-check-input @error('is_active') is-invalid @enderror"
                                                            id="is_active" name="is_active" value="1"
                                                            @checked(old('is_active', $data->is_active) == 1)>
                                                        <label for="is_active" class="mb-0">
                                                            Aktif
                                                        </label>
                                                    </div>

                                                    @error('is_active')
                                                        <small class="text-danger d-block">{{ $message }}</small>
                                                    @enderror

                                                    <small class="text-muted">
                                                        Jika aktif, kategori dapat digunakan dan ditampilkan pada blog.
                                                    </small>
                                                </div>

                                                <div class="form-group">
                                                    <label>Informasi Slug</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $data->slug }}" readonly>
                                                    <small class="text-muted">
                                                        Slug akan diperbarui otomatis berdasarkan nama kategori.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions right">
                                        <a href="{{ route('dashboard.blog.categories.index') }}"
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
    @endpush
</x-app-layout>
