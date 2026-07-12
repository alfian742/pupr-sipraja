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

                                <form class="form" action="{{ $routeList->store }}" method="POST" id="myForm">
                                    @csrf
                                    <div class="form-body">
                                        <input type="hidden" name="indicator_type" value="{{ $type }}">

                                        <div class="form-group">
                                            <label for="indicator_code">Kode Indikator <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="indicator_code"
                                                class="form-control @error('indicator_code') is-invalid @enderror"
                                                placeholder="Contoh: 01.001.0001" name="indicator_code"
                                                value="{{ old('indicator_code') }}">
                                            @error('indicator_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="indicator_name">Nama Indikator <span
                                                    class="text-danger">*</span></label>
                                            <textarea id="indicator_name" class="form-control @error('indicator_name') is-invalid @enderror"
                                                placeholder="Contoh: Indeks Kepuasan Layanan Infrastruktur (IKLI)" name="indicator_name">{{ old('indicator_name') }}</textarea>
                                            @error('indicator_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="indicator_unit">Satuan Indikator <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="indicator_unit"
                                                class="form-control @error('indicator_unit') is-invalid @enderror"
                                                placeholder="Contoh: Indeks, %, Angka, dan lain-lain"
                                                name="indicator_unit" value="{{ old('indicator_unit') }}">
                                            @error('indicator_unit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="baseline_year">Tahun Baseline <span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" id="baseline_year"
                                                        class="form-control @error('baseline_year') is-invalid @enderror"
                                                        placeholder="Contoh: 2020" name="baseline_year"
                                                        value="{{ old('baseline_year') }}">
                                                    @error('baseline_year')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="baseline_value">Nilai Baseline</label>
                                                    <input type="number" step="0.01" id="baseline_value"
                                                        class="form-control @error('baseline_value') is-invalid @enderror"
                                                        placeholder="Contoh: 75.50" name="baseline_value"
                                                        value="{{ old('baseline_value') }}">
                                                    @error('baseline_value')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="measurement_year">Tahun Pengukuran <span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" id="measurement_year"
                                                        class="form-control @error('measurement_year') is-invalid @enderror"
                                                        placeholder="Contoh: 2024" name="measurement_year"
                                                        value="{{ old('measurement_year') }}">
                                                    @error('measurement_year')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="target_value">Target <span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" step="0.01" id="target_value"
                                                        class="form-control @error('target_value') is-invalid @enderror"
                                                        placeholder="Contoh: 80.00" name="target_value"
                                                        value="{{ old('target_value') }}">
                                                    @error('target_value')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="achievement_value">Capaian</label>
                                                    <input type="number" step="0.01" id="achievement_value"
                                                        class="form-control @error('achievement_value') is-invalid @enderror"
                                                        placeholder="Contoh: 78.50" name="achievement_value"
                                                        value="{{ old('achievement_value') }}">
                                                    @error('achievement_value')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="performance_value">Kinerja</label>
                                                    <input type="number" step="0.01" id="performance_value"
                                                        class="form-control @error('performance_value') is-invalid @enderror"
                                                        placeholder="Contoh: 98.12" name="performance_value"
                                                        value="{{ old('performance_value') }}">
                                                    @error('performance_value')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="document_url">Link Dokumen Pendukung</label>
                                            <input type="url" name="document_url" id="document_url"
                                                class="form-control @error('document_url') is-invalid @enderror"
                                                placeholder="Contoh: https://drive.google.com/file/d/1JlQJ05zoYfBWzRLEc2lAesm9AUQ9pA25/preview"
                                                value="{{ old('document_url') }}">
                                            @error('document_url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-actions right">
                                        <a href="{{ $routeList->index }}" class="btn btn-secondary mr-1">
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
