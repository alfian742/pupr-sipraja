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
        </div>
    </div>
</div>
