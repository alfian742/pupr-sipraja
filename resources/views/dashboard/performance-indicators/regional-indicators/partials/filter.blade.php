<div class="col-12">
    <div class="multi-collapse collapse mb-2 {{ $filterApplied ? 'show' : '' }}" id="filterWrapper">
        <div class="bs-callout-info callout-border-left callout-bordered rounded-0 bg-transparent p-1">
            <h4 class="info mb-2">Filter Data</h4>

            <form action="{{ $routeList->index }}" method="GET" id="filterForm">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="measurement_year">Tahun</label>
                            <select id="measurement_year"
                                class="custom-select select2 @error('measurement_year') is-invalid @enderror"
                                name="measurement_year">
                                <option value=""
                                    {{ request('measurement_year') === null || request('measurement_year') === '' ? 'selected' : '' }}>
                                    Semua Tahun
                                </option>
                                @foreach ($measurementYears as $year)
                                    <option value="{{ $year->measurement_year }}"
                                        {{ (string) request('measurement_year') === (string) $year->measurement_year ? 'selected' : '' }}>
                                        {{ $year->measurement_year }}
                                    </option>
                                @endforeach
                            </select>
                            @error('measurement_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="period">Periode</label>
                            <select id="period" class="custom-select select2 @error('period') is-invalid @enderror"
                                name="period">
                                <option value=""
                                    {{ request('period') === null || request('period') === '' ? 'selected' : '' }}>
                                    Semua Periode
                                </option>
                                <option value="Triwulan I" {{ request('period') == 'Triwulan I' ? 'selected' : '' }}>
                                    Triwulan I</option>
                                <option value="Triwulan II" {{ request('period') == 'Triwulan II' ? 'selected' : '' }}>
                                    Triwulan II</option>
                                <option value="Triwulan III"
                                    {{ request('period') == 'Triwulan III' ? 'selected' : '' }}>
                                    Triwulan III</option>
                                <option value="Triwulan IV" {{ request('period') == 'Triwulan IV' ? 'selected' : '' }}>
                                    Triwulan IV</option>
                            </select>
                            @error('period')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex" style="gap: 0.5rem;">
                    <button type="submit" id="btnFilter" class="btn btn-info">
                        <i class="fa fa-filter"></i>
                        Terapkan
                    </button>
                    <a href="{{ $routeList->index }}" id="btnReset" class="btn btn-outline-secondary">
                        <i class="fa fa-undo"></i>
                        Reset
                    </a>
                </div>
            </form>

            @if ($filterApplied)
                <hr class="my-2">

                <form action="{{ $routeList->export }}" method="GET" id="exportForm">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="success mb-2">Ekspor Data</h4>

                            <p class="mb-2">
                                Data yang akan diekspor ditentukan berdasarkan hasil filter.
                                Silakan pilih format ekspor untuk memulai proses ekspor data.
                            </p>
                        </div>
                        <div class="col-12">
                            <input type="hidden" name="measurement_year" value="{{ request('measurement_year') }}">
                            <input type="hidden" name="period" value="{{ request('period') }}">
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="input-group">
                                <select id="export_format" name="export_format"
                                    class="custom-select @error('export_format') is-invalid @enderror" required
                                    aria-label="Format Ekspor Data">
                                    {{-- <option value="csv" {{ request('export_format') == 'csv' ? 'selected' : '' }}>
                                        CSV
                                    </option> --}}
                                    <option value="xlsx" {{ request('export_format') == 'xlsx' ? 'selected' : '' }}>
                                        XLSX
                                    </option>
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-success" type="button" id="btnExport">Ekspor</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
