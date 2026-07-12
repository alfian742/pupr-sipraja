<x-guest-layout>
    @php
        $pageTitle = $currentIndicator['label'];

        $progressMap = [
            'physical-availability' => 20,
            'quality' => 40,
            'suitability' => 60,
            'utilization' => 80,
            'expectation' => 100,
        ];

        $progress = $progressMap[$surveyIndicator] ?? 0;
    @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <x-slot name="brand">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('ikli-survey.home') }}">
            <x-application-logo height="30" />
        </a>
    </x-slot>

    <x-slot name="hideFooter">true</x-slot>

    <section class="content container">
        <h2 class="text-secondary text-uppercase mb-5 text-center">
            {{ $currentIndicator['label'] }}
        </h2>

        <div class="form-wizard mb-5">
            <div class="form-wizard-line"></div>

            <div class="form-wizard-step">
                <div class="form-wizard-circle">
                    <i class="fa fa-lg fa-user"></i>
                </div>
                <div class="form-wizard-title text-uppercase">Responden</div>
            </div>

            <div class="form-wizard-line"></div>

            <div class="form-wizard-step active">
                <div class="form-wizard-circle">
                    <i class="fa fa-lg fa-list"></i>
                </div>
                <div class="form-wizard-title text-uppercase">Kuesioner</div>
            </div>

            <div class="form-wizard-line"></div>
        </div>

        <div class="progress mb-5">
            <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ $progress }}%;"
                aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                {{ $progress }}%
            </div>
        </div>

        <form class="form" action="{{ $routeList->update }}" method="POST" id="myForm">
            @csrf
            @method('PUT')

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary">
                    <h4 class="fw-bold text-uppercase mb-0 text-white">
                        {{ $currentIndicator['label'] }}
                    </h4>
                </div>

                <div class="card-body">
                    <div
                        class="d-flex align-items-stretch border-secondary rounded-3 mb-4 overflow-hidden border bg-transparent">
                        <div class="d-flex align-items-center bg-secondary p-3 text-white">
                            <i class="fa fa-info-circle fa-lg white"></i>
                        </div>
                        <div class="p-3">
                            <h5 class="fw-bold text-secondary">Petunjuk Pengisian Kuesioner</h5>
                            <p class="text-muted mb-1">
                                Silakan pilih <strong>satu jawaban</strong> untuk setiap infrastruktur.
                            </p>
                        </div>
                    </div>

                    <div class="form-body">
                        @php
                            $generalQuestionMap = [
                                'physical-availability' =>
                                    'Bagaimana tingkat ketersediaan fisik prasana berikut di wilayah Anda saat ini?',
                                'quality' =>
                                    'Bagaimana tingkat kualitas prasarana berikut berdasarkan kondisi fisik dan fungsinya di wilayah Anda saat ini?',
                                'suitability' =>
                                    'Sejauh mana prasarana berikut sesuai dengan kebutuhan Anda di wilayah Anda?',
                                'utilization' =>
                                    'Dalam 1 bulan terakhir, seberapa sering Anda memanfaatkan prasarana berikut?',
                                'expectation' =>
                                    'Seberapa perlu prasarana berikut ditingkatkan di wilayah domisili Anda saat ini?',
                            ];
                        @endphp

                        <P class="fw-bold fs-5 text-dark">{{ $generalQuestionMap[$surveyIndicator] ?? '-' }}</P>

                        <table class="table table-borderless table-hover w-100 mb-3">
                            <tbody>
                                @foreach ($questions as $question)
                                    @php
                                        $infraKey = $question->infra_key;
                                        $selected = old("answers.$infraKey", $answers[$infraKey] ?? null);
                                    @endphp

                                    @if (!$infraKey)
                                        @continue
                                    @endif

                                    <tr>
                                        <td class="text-right align-top fw-bold text-secondary" style="width: 40px;">
                                            {{ $loop->iteration }}.
                                        </td>
                                        <td class="pl-0">
                                            <h5 class="fw-bold text-secondary mb-2">
                                                {{ $question->infrastructure_type }}
                                                <span class="text-danger">*</span>
                                            </h5>

                                            @if ($question->description)
                                                <p class="mb-3 text-muted">
                                                    {{ $question->description }}
                                                </p>
                                            @endif

                                            <div class="d-flex flex-column">
                                                @for ($i = 1; $i <= 4; $i++)
                                                    @php
                                                        $optionText = $question->{'option_' . $i};
                                                        $inputId = $infraKey . '_' . $i;
                                                    @endphp

                                                    <div class="form-check mb-3">
                                                        <input
                                                            class="form-check-input @error("answers.$infraKey") is-invalid @enderror"
                                                            type="radio" id="{{ $inputId }}"
                                                            name="answers[{{ $infraKey }}]"
                                                            value="{{ $i }}"
                                                            {{ (string) $selected === (string) $i ? 'checked' : '' }}
                                                            {{ $i === 1 ? 'required' : '' }}>

                                                        <label class="form-check-label ml-2" for="{{ $inputId }}">
                                                            {{ $optionText }}
                                                        </label>
                                                    </div>
                                                @endfor
                                            </div>

                                            @error("answers.$infraKey")
                                                <small class="text-danger d-block mb-2">{{ $message }}</small>
                                            @enderror

                                            <hr>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($surveyIndicator == 'expectation')
                            <div class="form-group mb-4">
                                <label class="form-label fw-bold fs-5 text-dark" for="priority_infrastructure">Menurut
                                    Anda, prasarana mana
                                    yang perlu
                                    diprioritaskan untuk ditingkatkan? <span class="text-danger">*</span></label>
                                <select id="priority_infrastructure"
                                    class="@error('priority_infrastructure') is-invalid @enderror form-select" required
                                    name="priority_infrastructure">
                                    <option value="" {{ old('priority_infrastructure') ? '' : 'selected' }}
                                        disabled>-- Pilih
                                        Infrastruktur --
                                    </option>
                                    <option value="PRASARANA TERMINAL"
                                        {{ old('priority_infrastructure', $respondent->priority_infrastructure) == 'PRASARANA TERMINAL' ? 'selected' : '' }}>
                                        PRASARANA TERMINAL</option>
                                    <option value="JARINGAN JALAN"
                                        {{ old('priority_infrastructure', $respondent->priority_infrastructure) == 'JARINGAN JALAN' ? 'selected' : '' }}>
                                        JARINGAN JALAN</option>
                                    <option value="JARINGAN IRIGASI"
                                        {{ old('priority_infrastructure', $respondent->priority_infrastructure) == 'JARINGAN IRIGASI' ? 'selected' : '' }}>
                                        JARINGAN IRIGASI</option>
                                    <option value="PRASARANA AIR MINUM"
                                        {{ old('priority_infrastructure', $respondent->priority_infrastructure) == 'PRASARANA AIR MINUM' ? 'selected' : '' }}>
                                        PRASARANA AIR MINUM</option>
                                    <option value="PRASARANA AIR LIMBAH"
                                        {{ old('priority_infrastructure', $respondent->priority_infrastructure) == 'PRASARANA AIR LIMBAH' ? 'selected' : '' }}>
                                        PRASARANA AIR LIMBAH</option>
                                    <option value="PRASARANA PERSAMPAHAN"
                                        {{ old('priority_infrastructure', $respondent->priority_infrastructure) == 'PRASARANA PERSAMPAHAN' ? 'selected' : '' }}>
                                        PRASARANA PERSAMPAHAN</option>
                                    <option value="JARINGAN LISTRIK"
                                        {{ old('priority_infrastructure', $respondent->priority_infrastructure) == 'JARINGAN LISTRIK' ? 'selected' : '' }}>
                                        JARINGAN LISTRIK</option>
                                    <option value="JARINGAN TELEKOMUNIKASI/INTERNET"
                                        {{ old('priority_infrastructure', $respondent->priority_infrastructure) == 'JARINGAN TELEKOMUNIKASI/INTERNET' ? 'selected' : '' }}>
                                        JARINGAN TELEKOMUNIKASI/INTERNET</option>
                                </select>
                                @error('education')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @php
                                $improvementOptions = ['KETERSEDIAAN FISIK', 'KUALITAS', 'KESESUAIAN', 'PEMANFAATAN'];

                                $value = old('priority_improvement', $respondent->priority_improvement);

                                $isCustom = $value && !in_array($value, $improvementOptions);
                            @endphp

                            <div class="form-group mb-3">
                                <label class="form-label fw-bold fs-5 text-dark" for="priority_improvement">Dari
                                    prasarana prioritas pilihan Anda, hal apa yang harus ditingkatkan? <span
                                        class="text-danger">*</span></label>

                                <select id="select_priority_improvement"
                                    class="@error('priority_improvement') is-invalid @enderror form-select">

                                    <option value="" disabled {{ !$value ? 'selected' : '' }}>
                                        -- Pilih --
                                    </option>

                                    @foreach ($improvementOptions as $option)
                                        <option value="{{ $option }}"
                                            {{ $value === $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach

                                    <option value="LAINNYA" {{ $isCustom ? 'selected' : '' }}>
                                        LAINNYA
                                    </option>
                                </select>

                                <input type="text" id="priority_improvement" name="priority_improvement" required
                                    class="form-control {{ $isCustom ? '' : 'd-none' }} @error('priority_improvement') is-invalid @enderror mt-3"
                                    placeholder="Isi jika memilih lainnya" value="{{ $isCustom ? $value : '' }}">

                                @error('priority_improvement')
                                    <div class="invalid-feedback">{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-wizard-action fixed-bottom bg-light">
                <div class="container pt-3">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-secondary w-100" id="btnPrevious">
                                <i class="fa fa-arrow-left"></i> Sebelumnya
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-secondary w-100" id="btnNext">
                                {!! $routeList->next
                                    ? 'Selanjutnya <i class="fa fa-arrow-right"></i>'
                                    : 'Selesai <i class="fa fa-circle-check"></i>' !!}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

    <div id="submitOverlay" class="submit-overlay d-none" aria-hidden="true">
        <div class="submit-overlay-box">
            <div class="spinner-border" role="status" aria-hidden="true"></div>
            <div class="fw-bold mt-3">Mengirim...</div>
            <div class="text-muted small mt-1">Mohon tunggu, jangan tutup halaman.</div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/sweetalert.css') }}">

        <style>
            .form-wizard {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                margin: 30px 0;
            }

            .form-wizard-line {
                flex: 1;
                height: 3px;
                background-color: #6c757d;
                opacity: 0.3;
            }

            .form-wizard-step {
                display: flex;
                flex-direction: column;
                align-items: center;
                position: relative;
                min-width: 110px;
            }

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

            .form-wizard-title {
                margin-top: 8px;
                font-size: 14px;
                font-weight: 500;
                color: #55595c;
                text-align: center;
                transition: 0.3s;
            }

            .form-wizard-step.active .form-wizard-circle {
                background-color: var(--secondary);
                color: #fff;
                box-shadow: 0 0 12px rgba(102, 16, 242, 0.4);
            }

            .form-wizard-step.active .form-wizard-title {
                color: var(--secondary);
                font-weight: 600;
            }

            .form-wizard-action {
                z-index: 1030;
                height: 4.5rem;
            }

            .form-check-input[type="radio"] {
                transform: scale(1.2);
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
        </style>
    @endpush

    @push('scripts')
        <script src="{{ asset('app-assets/vendors/js/extensions/sweetalert.min.js') }}"></script>

        <script>
            $(document).ready(function() {
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

                function showOverlay() {
                    if (overlay) overlay.classList.remove('d-none');
                    document.body.classList.add('overlay-open');
                }

                if (btnNext) {
                    btnNext.addEventListener("click", function() {
                        if (!validateForm()) {
                            swal({
                                title: "Kuesioner Belum Lengkap",
                                text: "Mohon lengkapi seluruh jawaban sebelum mengirim.",
                                icon: "warning",
                                button: "Baik"
                            });

                            return;
                        }

                        swal({
                            title: "{{ $routeList->next ? 'Lanjut ke Tahap Berikutnya?' : 'Akhiri Kuesioner' }}",
                            text: "Saya menyatakan bahwa jawaban diberikan sesuai dengan kondisi yang saya ketahui dan alami.",
                            icon: "info",
                            buttons: ["Periksa Lagi", "Ya, Simpan"],
                        }).then((willSubmit) => {
                            if (willSubmit) {
                                btnNext.disabled = true;
                                btnNext.innerHTML =
                                    '<span class="spinner-border spinner-border-sm mr-1"></span>Memproses...';

                                showOverlay();

                                setTimeout(() => {
                                    form.submit();
                                }, 300);
                            }
                        });
                    });
                }

                if (btnPrevious) {
                    btnPrevious.addEventListener("click", function() {
                        window.location.href = "{{ $routeList->prev }}";
                    });
                }
            });
        </script>

        @if ($surveyIndicator == 'expectation')
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
                    initSelectOther('#select_priority_improvement', '#priority_improvement');
                });
            </script>
        @endif
    @endpush
</x-guest-layout>
