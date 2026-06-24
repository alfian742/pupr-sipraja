<div class="col-12">
    <div class="multi-collapse collapse" id="importWrapper">
        <div class="bs-callout-primary callout-border-left callout-bordered rounded-0 bg-transparent p-1">
            <h4 class="primary mb-2">Import Data</h4>

            <div class="d-flex flex-md-row justify-content-start align-items-center mb-2 flex-wrap" style="gap: 1rem">
                <a href="{{ route('dashboard.monev.finances.contracts.download-template') }}"
                    class="btn btn-outline-primary" id="btnDownloadTemplate">
                    <i class="fa fa-file"></i> Templat
                </a>

                <form action="{{ route('dashboard.monev.finances.contracts.import') }}" method="POST"
                    enctype="multipart/form-data" class="d-flex align-items-center" id="importForm">
                    @csrf

                    <fieldset class="form-group mb-0" style="margin-right: 0.75rem">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('file') is-invalid @enderror"
                                id="excelFile" name="file" accept=".xlsx,.csv">
                            <label class="custom-file-label" for="excelFile">Pilih
                                Berkas...</label>
                        </div>
                    </fieldset>

                    <button type="button" class="btn btn-primary" id="btnImport" disabled>
                        <i class="fa fa-upload"></i> Impor Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
