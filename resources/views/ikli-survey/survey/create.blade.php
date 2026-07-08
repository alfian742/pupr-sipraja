<x-guest-layout>
    @php $pageTitle = 'Responden' @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <x-slot name="brand">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('ikli-survey.home') }}">
            <x-application-logo height="30" />
        </a>
    </x-slot>

    {{-- sembunyikan footer karena halaman wizard ada fixed action bottom --}}
    <x-slot name="hideFooter">true</x-slot>

    <section class="content container">
        <h2 class="text-secondary text-uppercase mb-5 text-center">{{ $pageTitle }}</h2>

        <div class="form-wizard mb-5">
            <div class="form-wizard-line"></div>

            <!-- Step 1 -->
            <div class="form-wizard-step active">
                <div class="form-wizard-circle">
                    <i class="fa fa-lg fa-user"></i>
                </div>
                <div class="form-wizard-title text-uppercase">Responden</div>
            </div>

            <div class="form-wizard-line"></div>

            <!-- Step 2 -->
            <div class="form-wizard-step">
                <div class="form-wizard-circle">
                    <i class="fa fa-lg fa-list"></i>
                </div>
                <div class="form-wizard-title text-uppercase">Kuesioner</div>
            </div>

            <div class="form-wizard-line"></div>
        </div>

        <div class="progress mb-5">
            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <form class="form" action="{{ route('ikli-survey.survey.store') }}" method="POST" id="myForm">
            @csrf

            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-secondary">
                    <h4 class="fw-bold text-uppercase mb-0 text-white">
                        IDENTITAS RESPONDEN
                    </h4>
                </div>
                <div class="card-body">
                    <div
                        class="d-flex align-items-stretch border-secondary rounded-3 mb-4 overflow-hidden border bg-transparent">
                        <div class="d-flex align-items-center bg-secondary p-3 text-white">
                            <i class="fa fa-info-circle fa-lg white"></i>
                        </div>
                        <div class="p-3">
                            <h5 class="fw-bold text-secondary">Petunjuk Pengisian Identitas
                                Responden</h5>
                            <p class="text-muted mb-1">
                                Mohon untuk mengisi formulir identitas responden secara
                                lengkap dan
                                sesuai
                                dengan data diri yang sebenarnya.
                            </p>
                            <small class="text-muted">
                                Tanda bintang (<span class="text-danger">*</span>) pada form menandakan kolom wajib
                                diisi.
                            </small>
                        </div>
                    </div>

                    <div class="form-body">
                        <div class="form-group mb-3">
                            <label class="form-label" for="survey_date">Tanggal Pengisian <span
                                    class="text-danger">*</span></label>
                            <input type="datetime-local" id="survey_date" name="survey_date"
                                class="form-control bg-light @error('survey_date') is-invalid @enderror" required
                                value="{{ \Carbon\Carbon::parse(now())->format('Y-m-d\TH:i') }}" disabled>
                            @error('survey_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="gender">Jenis Kelamin <span
                                    class="text-danger">*</span></label>
                            <select id="gender" class="@error('gender') is-invalid @enderror form-select" required
                                name="gender">
                                <option value="" {{ old('gender') ? '' : 'selected' }} disabled>-- Pilih Jenis
                                    Kelamin
                                    --</option>
                                <option value="LAKI-LAKI" {{ old('gender') == 'LAKI-LAKI' ? 'selected' : '' }}>
                                    LAKI-LAKI</option>
                                <option value="PEREMPUAN" {{ old('gender') == 'PEREMPUAN' ? 'selected' : '' }}>
                                    PEREMPUAN</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="age">Usia (Tahun) <span
                                    class="text-danger">*</span></label>
                            <select id="age" class="@error('age') is-invalid @enderror form-select" required
                                name="age">
                                <option value="" {{ old('age') ? '' : 'selected' }} disabled>-- Pilih Rentang
                                    Usia
                                    --</option>
                                <option value="<20" {{ old('age') == '<20' ? 'selected' : '' }}>
                                    &lt;20</option>
                                <option value="20-30" {{ old('age') == '20-30' ? 'selected' : '' }}>
                                    20-30</option>
                                <option value="31-40" {{ old('age') == '31-40' ? 'selected' : '' }}>
                                    31-40</option>
                                <option value="41-50" {{ old('age') == '41-50' ? 'selected' : '' }}>
                                    41-50</option>
                                <option value=">50" {{ old('age') == '>50' ? 'selected' : '' }}>
                                    &gt;50</option>
                            </select>
                            @error('age')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="education">Pendidikan Terakhir <span
                                    class="text-danger">*</span></label>
                            <select id="education" class="@error('education') is-invalid @enderror form-select" required
                                name="education">
                                <option value="" {{ old('education') ? '' : 'selected' }} disabled>-- Pilih
                                    Pendidikan
                                    Terakhir --
                                </option>
                                <option value="TIDAK SEKOLAH"
                                    {{ old('education') == 'TIDAK SEKOLAH' ? 'selected' : '' }}>
                                    TIDAK
                                    SEKOLAH</option>
                                <option value="SD/SEDERAJAT"
                                    {{ old('education') == 'SD/SEDERAJAT' ? 'selected' : '' }}>
                                    SD/SEDERAJAT</option>
                                <option value="SMP/SEDERAJAT"
                                    {{ old('education') == 'SMP/SEDERAJAT' ? 'selected' : '' }}>
                                    SMP/SEDERAJAT</option>
                                <option value="SMA/SEDERAJAT"
                                    {{ old('education') == 'SMA/SEDERAJAT' ? 'selected' : '' }}>
                                    SMA/SEDERAJAT</option>
                                <option value="D-I/D-II/D-III"
                                    {{ old('education') == 'D-I/D-II/D-III' ? 'selected' : '' }}>
                                    D-I/D-II/D-III</option>
                                <option value="S1/D-IV" {{ old('education') == 'S1/D-IV' ? 'selected' : '' }}>
                                    S1/D-IV
                                </option>
                                <option value=">S1" {{ old('education') == '>S1' ? 'selected' : '' }}>&gt;S1
                                </option>
                            </select>
                            @error('education')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @php
                            $occupationOptions = [
                                'PELAJAR/MAHASISWA',
                                'PETANI/PEKEBUN',
                                'NELAYAN/PETERNAK',
                                'BURUH/TUKANG/PEKERJA HARIAN',
                                'TNI/POLRI',
                                'PNS PEMERINTAH PUSAT',
                                'PNS PEMERINTAH DAERAH',
                                'PERANGKAT DESA',
                                'PPPK',
                                'PEGAWAI SWASTA',
                                'WIRASWASTA/PERDAGANGAN/USAHA SENDIRI',
                                'IBU RUMAH TANGGA',
                                'TIDAK BEKERJA/PENSIUNAN',
                            ];

                            $value = old('occupation');

                            $isCustom = $value && !in_array($value, $occupationOptions);
                        @endphp

                        <div class="form-group mb-3">
                            <label class="form-label" for="occupation">Pekerjaan <span
                                    class="text-danger">*</span></label>

                            <select id="select_occupation"
                                class="@error('occupation') is-invalid @enderror form-select">

                                <option value="" disabled {{ !$value ? 'selected' : '' }}>
                                    -- Pilih Pekerjaan --
                                </option>

                                @foreach ($occupationOptions as $option)
                                    <option value="{{ $option }}" {{ $value === $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach

                                <option value="LAINNYA" {{ $isCustom ? 'selected' : '' }}>
                                    LAINNYA
                                </option>
                            </select>

                            <input type="text" id="occupation" name="occupation" required
                                class="form-control {{ $isCustom ? '' : 'd-none' }} @error('occupation') is-invalid @enderror mt-3"
                                placeholder="Isi jika memilih lainnya" value="{{ $isCustom ? $value : '' }}">

                            @error('occupation')
                                <div class="invalid-feedback">{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="disability_status">Disabilitas/Non-Disabilitas <span
                                    class="text-danger">*</span></label>
                            <select id="disability_status"
                                class="@error('disability_status') is-invalid @enderror form-select" required
                                name="disability_status">
                                <option value="" {{ old('disability_status') ? '' : 'selected' }} disabled>--
                                    Pilih --
                                </option>
                                <option value="DISABILITAS"
                                    {{ old('disability_status') == 'DISABILITAS' ? 'selected' : '' }}>
                                    DISABILITAS</option>
                                <option value="NON-DISABILITAS"
                                    {{ old('disability_status') == 'NON-DISABILITAS' ? 'selected' : '' }}>
                                    NON-DISABILITAS</option>
                            </select>
                            @error('disability_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="district">Kecamatan sesuai Domisili <span
                                    class="text-danger">*</span></label>
                            <select id="district" class="@error('district') is-invalid @enderror form-select"
                                name="district" required>
                                <option value="">-- Pilih Kecamatan --</option>
                            </select>
                            @error('district')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="village">Kelurahan/Desa sesuai Domisili <span
                                    class="text-danger">*</span></label>
                            <select id="village" class="@error('village') is-invalid @enderror form-select"
                                name="village" required disabled>
                                <option value="">-- Pilih Kelurahan/Desa --</option>
                            </select>
                            @error('village')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="address">Alamat (Opsional)</label>
                            <textarea id="address" class="form-control @error('address') is-invalid @enderror"
                                placeholder="Jalan Merdeka No. 10, RT 001, RW 002" name="address">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            @if (config('services.recaptcha.enabled'))
                <input type="hidden" name="g-recaptcha-response" id="recaptcha_token">
            @endif

            <div class="form-wizard-action fixed-bottom bg-light">
                <div class="container pt-3">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-secondary w-100" id="btnPrevious">
                                <i class="fa fa-times"></i> Batal
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-secondary w-100" id="btnNext">
                                Berikutnya <i class="fa fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

    {{-- FULLSCREEN OVERLAY LOADING --}}
    <div id="submitOverlay" class="submit-overlay d-none" aria-hidden="true">
        <div class="submit-overlay-box">
            <div class="spinner-border" role="status" aria-hidden="true"></div>
            <div class="fw-semibold mt-3">Mengirim...</div>
            <div class="text-muted small mt-1">Mohon tunggu, jangan tutup halaman.</div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" type="text/css"
            href="{{ asset('app-assets/vendors/css/forms/selects/select2.min.css') }}">

        <style>
            /* ============================= */
            /* FORM WIZARD CONTAINER */
            /* ============================= */
            .form-wizard {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                margin: 30px 0;
            }

            /* ============================= */
            /* LINE CONNECTOR */
            /* ============================= */
            .form-wizard-line {
                flex: 1;
                height: 3px;
                background-color: #6c757d;
                opacity: 0.3;
            }

            /* ============================= */
            /* STEP */
            /* ============================= */
            .form-wizard-step {
                display: flex;
                flex-direction: column;
                align-items: center;
                position: relative;
                min-width: 110px;
            }

            /* ============================= */
            /* CIRCLE */
            /* ============================= */
            .form-wizard-circle {
                width: 52px;
                height: 52px;
                border-radius: 50%;
                background-color: #6c757d;
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
                transition: all 0.3s ease;
                z-index: 2;
            }

            /* ============================= */
            /* TITLE */
            /* ============================= */
            .form-wizard-title {
                margin-top: 8px;
                font-size: 14px;
                font-weight: 500;
                color: #55595c;
                text-align: center;
                transition: 0.3s;
            }

            /* ============================= */
            /* ACTIVE STEP */
            /* ============================= */
            .form-wizard-step.active .form-wizard-circle {
                background-color: var(--secondary);
                color: #fff;
                box-shadow: 0 0 12px rgba(102, 16, 242, 0.4);
            }

            .form-wizard-step.active .form-wizard-title {
                color: var(--secondary);
                font-weight: 600;
            }

            /* ============================= */
            /* ACTION BUTTON */
            /* ============================= */
            .form-wizard-action {
                z-index: 1030;
                height: 4.5rem;
            }

            /* ============================= */
            /* MOBILE RESPONSIVE */
            /* ============================= */
            @media (max-width: 768px) {

                .form-wizard {
                    gap: 10px;
                }

                .form-wizard-title {
                    display: none;
                }

                .form-wizard-circle {
                    width: 42px;
                    height: 42px;
                    font-size: 16px;
                }

                .form-wizard-line {
                    height: 2px;
                }
            }

            /* ============================= */
            /* Custom Style */
            /* ============================= */
            .select2-selection.is-invalid {
                border: 1px solid #da4453 !important;
            }

            .table-question tbody tr td:first-child {
                width: 1rem;
            }

            .form-check-input[type="radio"] {
                transform: scale(1.5);
            }

            .submit-overlay {
                position: fixed;
                inset: 0;
                z-index: 2000;
                background: rgba(0, 0, 0, .45);
                backdrop-filter: blur(2px);
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
            }

            .submit-overlay-box {
                background: #fff;
                border-radius: 1rem;
                padding: 1.25rem 1.5rem;
                min-width: 260px;
                max-width: 420px;
                text-align: center;
                box-shadow: 0 10px 30px rgba(0, 0, 0, .2);
            }

            body.overlay-open {
                overflow: hidden;
            }

            /* ============================= */
            /* Google recaptcha */
            /* ============================= */
            .grecaptcha-badge {
                bottom: auto !important;
                top: 50% !important;
                transform: translateY(-50%) !important;
            }

            @media (max-width: 768px) {
                .grecaptcha-badge {
                    bottom: auto !important;
                    top: 1.6rem !important;
                    z-index: 1080;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>

        <script src="{{ asset('app-assets/vendors/js/extensions/sweetalert.min.js') }}"></script>

        @if (config('services.recaptcha.enabled'))
            <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>

            <script>
                function executeRecaptchaAndSubmit() {
                    grecaptcha.ready(function() {
                        grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {
                            action: 'survey'
                        }).then(function(token) {
                            document.getElementById('recaptcha_token').value = token;
                            document.getElementById("myForm").submit();
                        });
                    });
                }
            </script>
        @else
            <script>
                function executeRecaptchaAndSubmit() {
                    document.getElementById("myForm").submit();
                }
            </script>
        @endif

        <script>
            $(document).ready(function() {

                // ================= INIT SELECT2 =================
                $('.select2').select2({
                    width: '100%',
                });

                const form = document.getElementById("myForm");
                const btnNext = document.getElementById("btnNext");
                const btnPrevious = document.getElementById("btnPrevious");
                const overlay = document.getElementById("submitOverlay");

                if (!form) return;

                form.setAttribute("novalidate", true);

                // ================= VALIDATION =================
                function validateForm() {

                    let isValid = true;

                    const requiredFields = form.querySelectorAll(
                        "input[required], select[required], textarea[required]"
                    );

                    const radioGroups = new Set();

                    requiredFields.forEach(field => {

                        // ================= RADIO =================
                        if (field.type === "radio") {

                            if (radioGroups.has(field.name)) return;
                            radioGroups.add(field.name);

                            const checked = form.querySelector(
                                `input[name="${field.name}"]:checked`
                            );

                            if (!checked) {
                                isValid = false;
                            }
                        }

                        // ================= SELECT =================
                        else if (field.tagName === "SELECT") {

                            const $field = $(field);

                            if (!$field.val()) {

                                field.classList.add("is-invalid");

                                if ($field.hasClass("select2-hidden-accessible")) {
                                    $field.next('.select2-container')
                                        .find('.select2-selection')
                                        .addClass('is-invalid');
                                }

                                isValid = false;

                            } else {

                                field.classList.remove("is-invalid");

                                if ($field.hasClass("select2-hidden-accessible")) {
                                    $field.next('.select2-container')
                                        .find('.select2-selection')
                                        .removeClass('is-invalid');
                                }
                            }
                        }

                        // ================= INPUT & TEXTAREA =================
                        else {

                            if (!field.value.trim()) {
                                field.classList.add("is-invalid");
                                isValid = false;
                            } else {
                                field.classList.remove("is-invalid");
                            }
                        }
                    });

                    return isValid;
                }

                // ================= OVERLAY =================
                function showOverlay() {
                    if (overlay) overlay.classList.remove('d-none');
                    document.body.classList.add('overlay-open');
                }

                // ================= SUBMIT =================
                if (btnNext) {
                    btnNext.addEventListener("click", function() {

                        if (!validateForm()) {

                            swal({
                                title: "Data Belum Lengkap",
                                text: "Mohon lengkapi seluruh data terlebih dahulu.",
                                icon: "warning",
                                button: "Baik"
                            });

                            return;
                        }

                        swal({
                            title: "Lanjut ke Tahap Berikutnya?",
                            text: "Pastikan data sudah benar sebelum melanjutkan.",
                            icon: "info",
                            buttons: ["Periksa Lagi", "Ya, Lanjut"],
                        }).then((willSubmit) => {

                            if (willSubmit) {

                                btnNext.disabled = true;
                                btnNext.innerHTML =
                                    '<span class="spinner-border spinner-border-sm me-1"></span>Memproses...';

                                showOverlay();

                                // setTimeout(() => {
                                //     form.submit();
                                // }, 300);

                                executeRecaptchaAndSubmit();
                            }
                        });
                    });
                }

                // ================= BATAL =================
                if (btnPrevious) {
                    btnPrevious.addEventListener("click", function() {

                        swal({
                            title: "Batalkan Pengisian?",
                            text: "Semua data yang telah diisi akan hilang.",
                            icon: "warning",
                            buttons: ["Lanjutkan Isi", "Ya, Batalkan"],
                            dangerMode: true,
                        }).then((willCancel) => {

                            if (willCancel) {
                                window.location.href = "{{ route('ikli-survey.home') }}";
                            }
                        });
                    });
                }

                // ================= REMOVE INVALID =================
                $(document).on("change keyup", "select, input, textarea", function() {

                    const $this = $(this);

                    $this.removeClass("is-invalid");

                    if ($this.hasClass("select2-hidden-accessible")) {
                        $this.next(".select2-container")
                            .find(".select2-selection")
                            .removeClass("is-invalid");
                    }
                });

            });
        </script>

        <script>
            $(document).ready(function() {
                function initSelectOther(selectId, inputId) {

                    const $select = $(selectId);
                    const $input = $(inputId);

                    function toggleInput() {

                        const selectedValue = $select.val();

                        if (selectedValue && selectedValue.toLowerCase() === 'lainnya') {

                            $input.removeClass('d-none');

                            // kalau input kosong, kosongkan
                            if (!$input.val()) {
                                $input.val('');
                            }

                        } else {

                            $input.addClass('d-none');
                            $input.val(selectedValue);
                        }
                    }

                    // saat change
                    $select.on('change', function() {
                        toggleInput();
                    });

                    // jalankan saat pertama load (edit / old validation)
                    toggleInput();
                }

                // panggil
                initSelectOther('#select_occupation', '#occupation');
            });
        </script>

        <script>
            $(document).ready(function() {

                function refreshSelect($el) {
                    if ($el.hasClass('select2-hidden-accessible')) {
                        $el.trigger('change.select2');
                    } else {
                        $el.trigger('change');
                    }
                }

                function showLoading($el, text = 'Memuat...') {
                    $el
                        .html(`<option value="" selected disabled>${text}</option>`)
                        .prop('disabled', true);

                    refreshSelect($el);
                }

                function resetVillage() {
                    $('#village')
                        .html('<option value="" selected disabled>-- Pilih Kelurahan/Desa --</option>')
                        .prop('disabled', true);

                    refreshSelect($('#village'));
                }

                function loadDistricts(selectedDistrictName = null, selectedVillageName = null) {

                    showLoading($('#district'), 'Memuat Kecamatan...');

                    $.ajax({
                        url: "{{ route('ikli-survey.region.districts') }}",
                        type: "GET",
                        success: function(response) {

                            let placeholder =
                                '<option value="" disabled selected>-- Pilih Kecamatan --</option>';
                            $('#district').html(placeholder).prop('disabled', false);

                            if (response.success) {

                                $.each(response.data, function(index, district) {

                                    let selected = (selectedDistrictName === district
                                        .district_name) ? 'selected' : '';

                                    $('#district').append(
                                        `<option value="${district.district_name}" 
                                     data-id="${district.id}" ${selected}>
                                ${district.district_name}
                            </option>`
                                    );
                                });

                                refreshSelect($('#district'));

                                // Kalau mode edit, langsung load village
                                if (selectedDistrictName) {
                                    let districtId = $('#district').find(':selected').data('id');
                                    loadVillages(districtId, selectedVillageName);
                                }
                            }
                        },
                        error: function() {
                            $('#district')
                                .html('<option value="" selected disabled>Gagal memuat data</option>');
                            refreshSelect($('#district'));
                        }
                    });
                }

                function loadVillages(districtId, selectedVillageName = null) {

                    if (!districtId) {
                        resetVillage();
                        return;
                    }

                    showLoading($('#village'), 'Memuat Kelurahan/Desa...');

                    $.ajax({
                        url: "{{ route('ikli-survey.region.villages') }}",
                        type: "GET",
                        data: {
                            district_id: districtId
                        },
                        success: function(response) {

                            let placeholder =
                                '<option value="" disabled selected>-- Pilih Kelurahan/Desa --</option>';
                            $('#village').html(placeholder).prop('disabled', false);

                            if (response.success) {

                                $.each(response.data, function(index, village) {

                                    let selected = (selectedVillageName === village.village_name) ?
                                        'selected' : '';

                                    $('#village').append(
                                        `<option value="${village.village_name}" 
                                     data-id="${village.id}" ${selected}>
                                ${village.village_name}
                            </option>`
                                    );
                                });
                            }

                            refreshSelect($('#village'));
                        },
                        error: function() {
                            $('#village')
                                .html('<option value="" selected disabled>Gagal memuat data</option>');
                            refreshSelect($('#village'));
                        }
                    });
                }

                // =====================================
                // EVENT CHANGE DISTRICT
                // =====================================
                $('#district').on('change', function() {
                    let districtId = $(this).find(':selected').data('id');
                    loadVillages(districtId);
                });

                // =====================================
                // LOAD AWAL (CREATE & EDIT)
                // =====================================
                let oldDistrict = "{{ old('district', $data->district ?? '') }}";
                let oldVillage = "{{ old('village', $data->village ?? '') }}";

                loadDistricts(oldDistrict, oldVillage);

            });
        </script>
    @endpush
</x-guest-layout>
