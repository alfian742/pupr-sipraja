<div class="col-12">
    <div class="multi-collapse collapse" id="exportWrapper">
        <div class="bs-callout-success callout-border-left callout-bordered rounded-0 bg-transparent p-1">
            <h4 class="success mb-2">Ekspor Data</h4>

            <form action="{{ route('dashboard.monev.finances.contracts.export') }}" method="GET" id="exportForm">
                <div class="row">
                    <div class="col-12 mb-2">
                        Data yang akan diekspor ditentukan berdasarkan <b>tanggal mulai</b> dan <b>tanggal berakhir
                            kontrak</b>.
                        Silakan masukkan rentang tanggal untuk memulai proses ekspor data.
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="small" for="export_start_date">Tanggal Mulai</label>
                        <input type="date" id="export_start_date" name="export_start_date"
                            value="{{ request('export_start_date') }}"
                            class="form-control @error('export_start_date') is-invalid @enderror">

                        @error('export_start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="small" for="export_end_date">Tanggal Berakhir</label>
                        <input type="date" id="export_end_date" name="export_end_date"
                            value="{{ request('export_end_date') }}"
                            class="form-control @error('export_end_date') is-invalid @enderror">

                        @error('export_end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="small" for="export_format">Format</label>
                        <select id="export_format" name="export_format"
                            class="custom-select @error('export_format') is-invalid @enderror" required>
                            {{-- <option value="" {{ request('export_format') ? '' : 'selected' }} disabled>
                                -- Pilih Format --
                            </option>
                            <option value="csv" {{ request('export_format') == 'csv' ? 'selected' : '' }}>
                                CSV
                            </option> --}}
                            <option value="xlsx" {{ request('export_format') == 'xlsx' ? 'selected' : '' }}>
                                XLSX
                            </option>
                        </select>
                        @error('export_format')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex" style="gap: 0.5rem;">
                    <button type="submit" id="btnExport" class="btn btn-success">
                        <i class="fa fa-download"></i>
                        Ekspor Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
