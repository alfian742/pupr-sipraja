<div class="col-12">
    <div class="multi-collapse collapse" id="exportWrapper">
        <div class="bs-callout-success callout-border-left callout-bordered rounded-0 bg-transparent p-1">
            <h4 class="success mb-2">Ekspor Data</h4>

            <form action="{{ $routeList->export }}" method="POST" id="exportForm">
                @csrf

                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label class="small" for="export_mode">Mode Ekspor</label>
                        <select id="export_mode" name="export_mode" class="custom-select select2">
                            <option value="all">Semua Data</option>
                            <option value="range">Rentang Waktu</option>
                        </select>
                    </div>

                    <div class="col-md-4 range-wrapper mb-2">
                        <label class="small" for="export_start_date">Tanggal Mulai</label>
                        <input type="date" id="export_start_date" name="export_start_date"
                            class="form-control @error('export_start_date') is-invalid @enderror">
                        @error('export_start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 range-wrapper mb-2">
                        <label class="small" for="export_end_date">Tanggal Selesai</label>
                        <input type="date" id="export_end_date" name="export_end_date"
                            class="form-control @error('export_start_date') is-invalid @enderror">
                        @error('export_end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex" style="gap: 0.5rem;">
                    <button type="submit" id="btnExport" class="btn btn-success">
                        <i class="fa fa-download"></i>
                        Ekspor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
