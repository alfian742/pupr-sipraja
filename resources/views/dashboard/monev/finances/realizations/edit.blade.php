<x-app-layout>
    @php $pageTitle = 'Edit' @endphp

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
                                    action="{{ route('dashboard.monev.finances.realizations.update', $data->id) }}"
                                    method="POST" id="myForm">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-body">
                                        <div class="card border-indigo">
                                            <div class="card-header bg-indigo">
                                                <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                    KONTRAK
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="contract_id">Nomor Kontrak <span
                                                                    class="text-danger">*</span></label>
                                                            <select id="contract_id" name="contract_id"
                                                                class="custom-select select2 @error('contract_id') is-invalid @enderror"
                                                                required>

                                                                <option value="" disabled
                                                                    {{ $selectedContract ? '' : 'selected' }}>
                                                                    -- Pilih Nomor Kontrak --
                                                                </option>

                                                                @if ($selectedContract)
                                                                    <option value="{{ $selectedContract->id }}" selected
                                                                        data-contract-number="{{ $selectedContract->contract_number }}"
                                                                        data-sub-account-code="{{ $selectedContract->sub_account_code }}"
                                                                        data-activity-code="{{ $selectedContract->activity_code }}"
                                                                        data-activity-description="{{ $selectedContract->activity_description }}">
                                                                        {{ $selectedContract->contract_number }} |
                                                                        {{ $selectedContract->sub_account_code }} |
                                                                        {{ $selectedContract->activity_code }} |
                                                                        {{ $selectedContract->activity_description }}
                                                                    </option>
                                                                @endif
                                                            </select>

                                                            <small class="font-italic"><span
                                                                    class="text-danger">*</span> Nomor Kontrak | Sub
                                                                Rek
                                                                | Kode Kegiatan | Uraian Kegiatan.</small>
                                                            @error('contract_id')
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <hr class="my-2">
                                                        <h4 class="font-weight-bold indigo mb-2">Detail</h4>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="contract_number">Nomor Kontrak</label>
                                                            <input type="text" id="contract_number"
                                                                name="contract_number" class="form-control"
                                                                value="" disabled
                                                                placeholder="Contoh: 03/PPK-CS/DPUPR/2025">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="sub_account_code">Sub Rek</label>
                                                            <input type="text" id="sub_account_code"
                                                                name="sub_account_code" class="form-control"
                                                                value="" disabled
                                                                placeholder="Contoh: 5.1.02.02.01.0030">
                                                            <small class="font-weight-bold">Digunakan untuk pencocokan
                                                                data.</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="contract_activity_code">Kode Kegiatan</label>
                                                            <input type="text" id="contract_activity_code"
                                                                name="contract_activity_code" class="form-control"
                                                                value="" disabled
                                                                placeholder="Contoh: 1.03.08.2.01">
                                                            <small class="font-weight-bold">Digunakan untuk pencocokan
                                                                data.</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="activity_description">Uraian Kegiatan</label>
                                                            <textarea id="activity_description" name="activity_description" class="form-control" rows="3" disabled
                                                                placeholder="Contoh: Pengadaan Operasional dan CS (Cleaning Servis) Gedung Kantor"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card border-indigo">
                                            <div class="card-header bg-indigo">
                                                <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                    PEMBAYARAN LANGSUNG (LS)
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="ls_payment_id">Nomor SPM <span
                                                                    class="text-danger">*</span></label>
                                                            <select id="ls_payment_id" name="ls_payment_id"
                                                                class="custom-select select2 @error('ls_payment_id') is-invalid @enderror"
                                                                required>

                                                                <option value="" disabled
                                                                    {{ $selectedLsPayment ? '' : 'selected' }}>
                                                                    -- Pilih Nomor SPM --
                                                                </option>

                                                                @if ($selectedLsPayment)
                                                                    <option value="{{ $selectedLsPayment->id }}"
                                                                        selected
                                                                        data-spm-number="{{ $selectedLsPayment->spm_number }}"
                                                                        data-account-code="{{ $selectedLsPayment->account_code }}"
                                                                        data-sub-activity-code="{{ $selectedLsPayment->sub_activity_code }}"
                                                                        data-document-description="{{ $selectedLsPayment->document_description }}">
                                                                        {{ $selectedLsPayment->spm_number }} |
                                                                        {{ $selectedLsPayment->account_code }} |
                                                                        {{ $selectedLsPayment->sub_activity_code }} |
                                                                        {{ $selectedLsPayment->document_description }}
                                                                    </option>
                                                                @endif
                                                            </select>

                                                            <small class="font-italic"><span
                                                                    class="text-danger">*</span> Nomor SPM | Kode
                                                                Rekening | Kode Sub Kegiatan | Keterangan
                                                                Dokumen.</small>
                                                            @error('ls_payment_id')
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <hr class="my-2">
                                                        <h4 class="font-weight-bold indigo mb-2">Detail</h4>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="spm_number">Nomor SPM</label>
                                                            <input type="text" id="spm_number" name="spm_number"
                                                                class="form-control" value="" disabled
                                                                placeholder="Contoh: 52.02/03.0/000008/LS/1.03.0.00.0.00.01.0000/M/2/2025">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="account_code">Kode Rekening</label>
                                                            <input type="text" id="account_code"
                                                                name="account_code" class="form-control"
                                                                value="" disabled
                                                                placeholder="Contoh: 5.1.02.02.01.0030">
                                                            <small class="font-weight-bold">Digunakan untuk pencocokan
                                                                data.</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="ls_payment_sub_activity_code">Kode
                                                                Sub Kegiatan</label>
                                                            <input type="text" id="ls_payment_sub_activity_code"
                                                                name="ls_payment_sub_activity_code"
                                                                class="form-control" value="" disabled
                                                                placeholder="Contoh: 1.03.08.2.01">
                                                            <small class="font-weight-bold">Digunakan untuk pencocokan
                                                                data.</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="document_description">Keterangan
                                                                Dokumen</label>
                                                            <textarea id="document_description" name="document_description" class="form-control" rows="3" disabled
                                                                placeholder="Contoh: Pembayaran UMK 30% (Tiga puluh persen) pada Pekerjaan Pengadaan Operasional dan CS (Cleaning Servis) Gedung Kantor An. PT. SAKRA JAYA UTAMA. DANA PAD."></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card border-indigo">
                                            <div class="card-header bg-indigo">
                                                <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                    Status Kecocokan
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="match_status_preview">Status Kecocokan <span
                                                            class="text-danger">*</span></label>

                                                    <select id="match_status_preview" class="custom-select" disabled>
                                                        <option value="">-- Terisi Otomatis --</option>
                                                        <option value="SAMA">SAMA</option>
                                                        <option value="BEDA">BEDA</option>
                                                    </select>

                                                    <input type="hidden" name="match_status" id="match_status"
                                                        value="{{ old('match_status', $data->math_status) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions right">
                                        <a href="{{ route('dashboard.monev.finances.realizations.index') }}"
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
        <link rel="stylesheet" type="text/css"
            href="{{ asset('public/app-assets/vendors/css/forms/selects/select2.min.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('public/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>

        <script src="{{ asset('public/app-assets/js/scripts/extensions/block-ui-custom.js') }}"></script>

        <script>
            $(document).ready(function() {

                // =========================
                // INIT SELECT2
                // =========================
                $('.select2').select2({
                    width: '100%',
                });

                // =========================
                // ELEMENT FORM
                // =========================
                const $form = $('#myForm');
                const $btnSubmit = $('#btnSubmit');

                // =========================
                // SELECT UTAMA
                // =========================
                const $lsPaymentSelect = $('#ls_payment_id');
                const $contractSelect = $('#contract_id');

                // =========================
                // PREVIEW LS PAYMENT
                // =========================
                const $spmNumber = $('#spm_number');
                const $accountCode = $('#account_code');
                const $lsPaymentSubActivityCode = $('#ls_payment_sub_activity_code');
                const $documentDescription = $('#document_description');

                // =========================
                // PREVIEW CONTRACT
                // =========================
                const $contractNumber = $('#contract_number');
                const $subAccountCode = $('#sub_account_code');
                const $contractActivityCode = $('#contract_activity_code');
                const $activityDescription = $('#activity_description');

                // =========================
                // MATCH STATUS
                // =========================
                const $matchStatusPreview = $('#match_status_preview');
                const $matchStatus = $('#match_status');

                // =========================
                // HELPER: NORMALIZE TEXT
                // =========================
                function normalizeText(value) {
                    return String(value || '')
                        .trim()
                        .replace(/\s+/g, '')
                        .toUpperCase();
                }

                // =========================
                // HELPER: SET VALUE
                // =========================
                function setPreviewValue($element, value) {
                    $element.val(value ?? '');
                }

                // =========================
                // HELPER: RESET CLASS
                // =========================
                function resetValidationClass($element) {
                    $element.removeClass('is-valid is-invalid');
                }

                // =========================
                // HELPER: SET VALID / INVALID
                // mode:
                // - 'valid'
                // - 'invalid'
                // - 'reset'
                // =========================
                function setValidationClass($element, mode = 'reset') {
                    resetValidationClass($element);

                    if (mode === 'valid') {
                        $element.addClass('is-valid');
                    } else if (mode === 'invalid') {
                        $element.addClass('is-invalid');
                    }
                }

                // =========================
                // HELPER: SET VALIDATION PER PAIR
                // - null  => reset
                // - true  => valid
                // - false => invalid
                // =========================
                function setFieldValidation($field1, $field2, isSame) {
                    if (isSame === null) {
                        setValidationClass($field1, 'reset');
                        setValidationClass($field2, 'reset');
                        return;
                    }

                    if (isSame === true) {
                        setValidationClass($field1, 'valid');
                        setValidationClass($field2, 'valid');
                    } else {
                        setValidationClass($field1, 'invalid');
                        setValidationClass($field2, 'invalid');
                    }
                }

                // =========================
                // HELPER: MATCH STATUS CLASS
                // =========================
                function setMatchStatusClass(status) {
                    resetValidationClass($matchStatusPreview);

                    if (status === 'SAMA') {
                        $matchStatusPreview.addClass('is-valid');
                    } else if (status === 'BEDA') {
                        $matchStatusPreview.addClass('is-invalid');
                    }
                }

                // =========================
                // HELPER: CHECK STATUS
                // =========================
                function checkMatchStatus() {
                    const isSame = $matchStatus.val() === 'SAMA';
                    $btnSubmit.prop('disabled', !isSame);
                }

                // =========================
                // HELPER: GET SELECT2 SELECTED DATA
                // PRIORITAS:
                // 1. data hasil select2 ajax jika lengkap
                // 2. fallback ke data-* option selected
                // =========================
                function getSelectedData($select, type) {
                    const selectedData = $select.select2('data');
                    const $selectedOption = $select.find(':selected');

                    if (selectedData && selectedData.length > 0 && selectedData[0].id) {
                        const item = selectedData[0];

                        if (type === 'contract') {
                            const hasCompleteData =
                                item.contract_number !== undefined &&
                                item.sub_account_code !== undefined &&
                                item.activity_code !== undefined &&
                                item.activity_description !== undefined;

                            if (hasCompleteData) {
                                return {
                                    id: item.id,
                                    text: item.text ?? '',
                                    contract_number: item.contract_number ?? '',
                                    sub_account_code: item.sub_account_code ?? '',
                                    activity_code: item.activity_code ?? '',
                                    activity_description: item.activity_description ?? '',
                                };
                            }
                        }

                        if (type === 'ls_payment') {
                            const hasCompleteData =
                                item.spm_number !== undefined &&
                                item.account_code !== undefined &&
                                item.sub_activity_code !== undefined &&
                                item.document_description !== undefined;

                            if (hasCompleteData) {
                                return {
                                    id: item.id,
                                    text: item.text ?? '',
                                    spm_number: item.spm_number ?? '',
                                    account_code: item.account_code ?? '',
                                    sub_activity_code: item.sub_activity_code ?? '',
                                    document_description: item.document_description ?? '',
                                };
                            }
                        }
                    }

                    if (!$selectedOption.length || !$select.val()) {
                        return null;
                    }

                    if (type === 'contract') {
                        return {
                            id: $selectedOption.val(),
                            text: $selectedOption.text() ?? '',
                            contract_number: $selectedOption.attr('data-contract-number') ?? '',
                            sub_account_code: $selectedOption.attr('data-sub-account-code') ?? '',
                            activity_code: $selectedOption.attr('data-activity-code') ?? '',
                            activity_description: $selectedOption.attr('data-activity-description') ?? '',
                        };
                    }

                    if (type === 'ls_payment') {
                        return {
                            id: $selectedOption.val(),
                            text: $selectedOption.text() ?? '',
                            spm_number: $selectedOption.attr('data-spm-number') ?? '',
                            account_code: $selectedOption.attr('data-account-code') ?? '',
                            sub_activity_code: $selectedOption.attr('data-sub-activity-code') ?? '',
                            document_description: $selectedOption.attr('data-document-description') ?? '',
                        };
                    }

                    return null;
                }

                // =========================
                // INIT SELECT2 CONTRACT
                // =========================
                $contractSelect.select2({
                    width: '100%',
                    ajax: {
                        url: "{{ route('dashboard.monev.finances.contracts.list') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                search: params.term || ''
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.results || []
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 0
                });

                // =========================
                // INIT SELECT2 LS PAYMENT
                // =========================
                $lsPaymentSelect.select2({
                    width: '100%',
                    ajax: {
                        url: "{{ route('dashboard.monev.finances.ls-payments.list') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                search: params.term || ''
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.results || []
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 0
                });

                // =========================
                // LOAD PREVIEW LS PAYMENT
                // =========================
                function loadLsPaymentPreview() {
                    const selected = getSelectedData($lsPaymentSelect, 'ls_payment');

                    if (!selected || !$lsPaymentSelect.val()) {
                        clearLsPaymentPreview();
                        updateMatchStatus();
                        return;
                    }

                    setPreviewValue($spmNumber, selected.spm_number);
                    setPreviewValue($accountCode, selected.account_code);
                    setPreviewValue($lsPaymentSubActivityCode, selected.sub_activity_code);
                    setPreviewValue($documentDescription, selected.document_description);

                    updateMatchStatus();
                }

                // =========================
                // LOAD PREVIEW CONTRACT
                // =========================
                function loadContractPreview() {
                    const selected = getSelectedData($contractSelect, 'contract');

                    if (!selected || !$contractSelect.val()) {
                        clearContractPreview();
                        updateMatchStatus();
                        return;
                    }

                    setPreviewValue($contractNumber, selected.contract_number);
                    setPreviewValue($subAccountCode, selected.sub_account_code);
                    setPreviewValue($contractActivityCode, selected.activity_code);
                    setPreviewValue($activityDescription, selected.activity_description);

                    updateMatchStatus();
                }

                // =========================
                // CLEAR PREVIEW LS PAYMENT
                // =========================
                function clearLsPaymentPreview() {
                    setPreviewValue($spmNumber, '');
                    setPreviewValue($accountCode, '');
                    setPreviewValue($lsPaymentSubActivityCode, '');
                    setPreviewValue($documentDescription, '');

                    setValidationClass($accountCode, 'reset');
                    setValidationClass($lsPaymentSubActivityCode, 'reset');
                }

                // =========================
                // CLEAR PREVIEW CONTRACT
                // =========================
                function clearContractPreview() {
                    setPreviewValue($contractNumber, '');
                    setPreviewValue($subAccountCode, '');
                    setPreviewValue($contractActivityCode, '');
                    setPreviewValue($activityDescription, '');

                    setValidationClass($subAccountCode, 'reset');
                    setValidationClass($contractActivityCode, 'reset');
                }

                // =========================
                // CLEAR MATCH STATUS
                // =========================
                function clearMatchStatus() {
                    setPreviewValue($matchStatusPreview, '');
                    setPreviewValue($matchStatus, '');

                    setValidationClass($matchStatusPreview, 'reset');
                    checkMatchStatus();
                }

                // =========================
                // UPDATE MATCH STATUS
                // LOGIKA:
                // 1. account_code == sub_account_code
                // 2. ls_payment_sub_activity_code == contract_activity_code
                // 3. jika keduanya sama => SAMA
                // 4. selain itu => BEDA
                // =========================
                function updateMatchStatus() {
                    const hasLsPayment = !!$lsPaymentSelect.val();
                    const hasContract = !!$contractSelect.val();

                    const accountCodeValue = normalizeText($accountCode.val());
                    const subAccountCodeValue = normalizeText($subAccountCode.val());

                    const lsActivityCodeValue = normalizeText($lsPaymentSubActivityCode.val());
                    const contractActivityCodeValue = normalizeText($contractActivityCode.val());

                    // jika salah satu select belum dipilih
                    if (!hasLsPayment || !hasContract) {
                        setFieldValidation($accountCode, $subAccountCode, null);
                        setFieldValidation($lsPaymentSubActivityCode, $contractActivityCode, null);
                        clearMatchStatus();
                        return;
                    }

                    // =========================
                    // MATCH KODE REKENING vs SUB REK
                    // jika salah satu kosong setelah dipilih => invalid
                    // =========================
                    let accountMatch = false;

                    if (accountCodeValue !== '' && subAccountCodeValue !== '') {
                        accountMatch = accountCodeValue === subAccountCodeValue;
                        setFieldValidation($accountCode, $subAccountCode, accountMatch);
                    } else {
                        setFieldValidation($accountCode, $subAccountCode, false);
                    }

                    // =========================
                    // MATCH KODE KEGIATAN
                    // jika salah satu kosong setelah dipilih => invalid
                    // =========================
                    let activityMatch = false;

                    if (lsActivityCodeValue !== '' && contractActivityCodeValue !== '') {
                        activityMatch = lsActivityCodeValue === contractActivityCodeValue;
                        setFieldValidation($lsPaymentSubActivityCode, $contractActivityCode, activityMatch);
                    } else {
                        setFieldValidation($lsPaymentSubActivityCode, $contractActivityCode, false);
                    }

                    // =========================
                    // STATUS GLOBAL
                    // =========================
                    const isSame = accountMatch && activityMatch;
                    const status = isSame ? 'SAMA' : 'BEDA';

                    setPreviewValue($matchStatusPreview, status);
                    setPreviewValue($matchStatus, status);

                    setMatchStatusClass(status);
                    checkMatchStatus();
                }

                // =========================
                // EVENT CHANGE
                // =========================
                $lsPaymentSelect.on('change', function() {
                    loadLsPaymentPreview();
                });

                $contractSelect.on('change', function() {
                    loadContractPreview();
                });

                $matchStatus.on('change', function() {
                    checkMatchStatus();
                });

                // =========================
                // SUBMIT BUTTON
                // =========================
                $btnSubmit.on('click', function(e) {

                    if ($matchStatus.val() !== 'SAMA') {
                        e.preventDefault();
                        return;
                    }

                    e.preventDefault();

                    $btnSubmit.prop('disabled', true);

                    if (typeof blockWholePage === 'function') {
                        blockWholePage('Mohon tunggu...');
                    }

                    setTimeout(function() {
                        $form.trigger('submit');
                    }, 300);
                });

                // =========================
                // SUBMIT FORM
                // =========================
                $form.on('submit', function() {
                    $btnSubmit.prop('disabled', true);
                });

                // =========================
                // LOAD AWAL
                // =========================
                loadLsPaymentPreview();
                loadContractPreview();
                checkMatchStatus();
            });
        </script>
    @endpush
</x-app-layout>
