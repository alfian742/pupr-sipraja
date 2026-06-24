<x-app-layout>
    @php $pageTitle = 'Tambah Pengguna' @endphp

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

                                <form class="form" action="{{ route('dashboard.users.store') }}" method="POST"
                                    id="myForm">
                                    @csrf
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label for="name">Nama <span class="text-danger">*</span></label>
                                            <input type="text" id="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="Contoh: Jhon Doe" name="name"
                                                value="{{ old('name') }}">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Email <span class="text-danger">*</span></label>
                                            <input type="email" id="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="Contoh: jhon.doe@gmail.com" name="email"
                                                value="{{ old('email') }}">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="role">Peran <span class="text-danger">*</span></label>
                                            <select id="role"
                                                class="custom-select @error('role') is-invalid @enderror"
                                                name="role">
                                                <option disabled selected>-- Pilih Peran Pengguna --</option>
                                                @if (Auth::user()->role === 'superadmin')
                                                    <option value="superadmin"
                                                        {{ old('role') == 'superadmin' ? 'selected' : '' }}>
                                                        Super Admin</option>
                                                @endif
                                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                                    Admin</option>
                                                <option value="operator"
                                                    {{ old('role') == 'operator' ? 'selected' : '' }}>
                                                    Operator</option>
                                                <option value="head_of_department"
                                                    {{ old('role') == 'head_of_department' ? 'selected' : '' }}>
                                                    Pimpinan</option>
                                            </select>
                                            @error('role')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-actions right">
                                        <a href="{{ route('dashboard.users.index') }}" class="btn btn-secondary mr-1">
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
