<div class="col-12">
    <div class="multi-collapse collapse" id="filterWrapper">
        <div class="bs-callout-info callout-border-left callout-bordered rounded-0 bg-transparent p-1">
            <h4 class="info mb-2">Saring Data</h4>

            <div class="row">
                <div class="col-md-4 mb-2">
                    <label class="small" for="filter_start_date">Tanggal Mulai</label>
                    <input type="date" id="filter_start_date" class="form-control">
                </div>

                <div class="col-md-4 mb-2">
                    <label class="small" for="filter_end_date">Tanggal Selesai</label>
                    <input type="date" id="filter_end_date" class="form-control">
                </div>

                <div class="col-md-4 mb-2">
                    <label class="small" for="filter_is_valid">Valid/Tidak Valid</label>
                    <select id="filter_is_valid" class="custom-select select2">
                        <option value="">
                            -- Pilih --
                        </option>
                        <option value="valid">Valid</option>
                        <option value="invalid">Tidak Valid</option>
                    </select>
                </div>

                <div class="col-md-6 mb-2">
                    <label class="small" for="filter_district">Kecamatan</label>
                    <select id="filter_district" class="custom-select select2">
                        <option value="">
                            -- Pilih Kecamatan --
                        </option>
                    </select>
                </div>

                <div class="col-md-6 mb-2">
                    <label class="small" for="filter_village">Kelurahan/Desa</label>
                    <select id="filter_village" class="custom-select select2">
                        <option value="">
                            -- Pilih Kelurahan/Desa --
                        </option>
                    </select>
                </div>
            </div>

            <div class="d-flex" style="gap: 0.5rem;">
                <button id="btnFilter" class="btn btn-info">
                    <i class="fa fa-filter"></i>
                    Terapkan
                </button>
                <button id="btnReset" class="btn btn-outline-secondary">
                    <i class="fa fa-undo"></i>
                    Reset
                </button>
            </div>
        </div>
    </div>
</div>
