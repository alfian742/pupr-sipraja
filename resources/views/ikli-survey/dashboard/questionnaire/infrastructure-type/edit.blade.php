<x-app-layout>
    @php
        $pageTitle = 'Edit Survei - ' . $data->id;

        // key => label
        $scoreOptions = [
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
        ];
    @endphp

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

                                <div class="d-flex flex-md-row justify-content-between align-items-center mb-3 flex-wrap"
                                    style="gap: 0.5rem">
                                    <a href="{{ $back }}" class="btn btn-secondary">
                                        <i class="fa fa-arrow-left"></i> Data Survei
                                    </a>

                                    <form
                                        action="{{ route('ikli-survey.dashboard.questionnaire.respondent.destroy', ['id' => $data->id, 'infrastructureType' => $infrastructureType]) }}"
                                        method="POST" id="delete-form-{{ $data->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger"
                                            onclick="confirmDelete({{ $data->id }})" title="Hapus">
                                            <i class="fa fa-trash"></i>
                                            <span class="d-none d-md-inline">Hapus</span>
                                        </button>
                                    </form>
                                </div>

                                <form
                                    action="{{ route('ikli-survey.dashboard.questionnaire.respondent.update', ['id' => $data->id, 'infrastructureType' => $infrastructureType]) }}"
                                    method="POST" id="myForm">
                                    @csrf
                                    @method('PUT')

                                    <div class="card border-indigo">
                                        <div class="card-header bg-indigo">
                                            <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                Identitas Responden
                                            </h5>
                                        </div>

                                        <div class="card-body">
                                            <div class="form-body row">
                                                <div class="form-group col-md-6 mb-2">
                                                    <label class="form-label" for="survey_date">Tanggal Pengisian <span
                                                            class="text-danger">*</span></label>
                                                    <input type="datetime-local" id="survey_date" name="survey_date"
                                                        class="form-control bg-light @error('survey_date') is-invalid @enderror"
                                                        required
                                                        value="{{ \Carbon\Carbon::parse($data->survey_date)->format('Y-m-d\TH:i') }}"
                                                        disabled>
                                                    @error('survey_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-6 mb-2">
                                                    <label class="form-label" for="respondent_id">ID Responden
                                                        <span class="text-danger">*</span></label>
                                                    <input type="text" id="respondent_id" name="respondent_id"
                                                        class="form-control bg-light @error('respondent_id') is-invalid @enderror"
                                                        required value="{{ $data->id }}" disabled>
                                                    @error('respondent_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-6 mb-2">
                                                    <label class="form-label" for="gender">Jenis Kelamin <span
                                                            class="text-danger">*</span></label>
                                                    <select id="gender"
                                                        class="@error('gender') is-invalid @enderror select2 custom-select"
                                                        required name="gender">
                                                        <option value=""
                                                            {{ old('gender', $data->gender) ? '' : 'selected' }}
                                                            disabled>--
                                                            Pilih Jenis
                                                            Kelamin
                                                            --</option>
                                                        <option value="LAKI-LAKI"
                                                            {{ old('gender', $data->gender) == 'LAKI-LAKI' ? 'selected' : '' }}>
                                                            LAKI-LAKI</option>
                                                        <option value="PEREMPUAN"
                                                            {{ old('gender', $data->gender) == 'PEREMPUAN' ? 'selected' : '' }}>
                                                            PEREMPUAN</option>
                                                    </select>
                                                    @error('gender')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-6 mb-2">
                                                    <label class="form-label" for="age">Usia (Tahun) <span
                                                            class="text-danger">*</span></label>
                                                    <select id="age"
                                                        class="@error('age') is-invalid @enderror select2 custom-select"
                                                        required name="age">
                                                        <option value=""
                                                            {{ old('age', $data->age) ? '' : 'selected' }} disabled>--
                                                            Pilih
                                                            Rentang
                                                            Usia
                                                            --</option>
                                                        <option value="<20"
                                                            {{ old('age', $data->age) == '<20' ? 'selected' : '' }}>
                                                            &lt;20</option>
                                                        <option value="20-30"
                                                            {{ old('age', $data->age) == '20-30' ? 'selected' : '' }}>
                                                            20-30</option>
                                                        <option value="31-40"
                                                            {{ old('age', $data->age) == '31-40' ? 'selected' : '' }}>
                                                            31-40</option>
                                                        <option value="41-50"
                                                            {{ old('age', $data->age) == '41-50' ? 'selected' : '' }}>
                                                            41-50</option>
                                                        <option value=">50"
                                                            {{ old('age', $data->age) == '>50' ? 'selected' : '' }}>
                                                            &gt;50</option>
                                                    </select>
                                                    @error('age')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-6 mb-2">
                                                    <label class="form-label" for="education">Pendidikan Terakhir <span
                                                            class="text-danger">*</span></label>
                                                    <select id="education"
                                                        class="@error('education') is-invalid @enderror select2 custom-select"
                                                        required name="education">
                                                        <option value=""
                                                            {{ old('education', $data->education) ? '' : 'selected' }}
                                                            disabled>-- Pilih
                                                            Pendidikan
                                                            Terakhir --
                                                        </option>
                                                        <option value="TIDAK SEKOLAH"
                                                            {{ old('education', $data->education) == 'TIDAK SEKOLAH' ? 'selected' : '' }}>
                                                            TIDAK
                                                            SEKOLAH</option>
                                                        <option value="SD/SEDERAJAT"
                                                            {{ old('education', $data->education) == 'SD/SEDERAJAT' ? 'selected' : '' }}>
                                                            SD/SEDERAJAT</option>
                                                        <option value="SMP/SEDERAJAT"
                                                            {{ old('education', $data->education) == 'SMP/SEDERAJAT' ? 'selected' : '' }}>
                                                            SMP/SEDERAJAT</option>
                                                        <option value="SMA/SEDERAJAT"
                                                            {{ old('education', $data->education) == 'SMA/SEDERAJAT' ? 'selected' : '' }}>
                                                            SMA/SEDERAJAT</option>
                                                        <option value="D-I/D-II/D-III"
                                                            {{ old('education', $data->education) == 'D-I/D-II/D-III' ? 'selected' : '' }}>
                                                            D-I/D-II/D-III</option>
                                                        <option value="S1/D-IV"
                                                            {{ old('education', $data->education) == 'S1/D-IV' ? 'selected' : '' }}>
                                                            S1/D-IV
                                                        </option>
                                                        <option value=">S1"
                                                            {{ old('education', $data->education) == '>S1' ? 'selected' : '' }}>
                                                            &gt;S1
                                                        </option>
                                                    </select>
                                                    @error('education')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-6 mb-2">
                                                    <label class="form-label"
                                                        for="disability_status">Disabilitas/Non-Disabilitas <span
                                                            class="text-danger">*</span></label>
                                                    <select id="disability_status"
                                                        class="@error('disability_status') is-invalid @enderror select2 custom-select"
                                                        required name="disability_status">
                                                        <option value=""
                                                            {{ old('disability_status', $data->disability_status) ? '' : 'selected' }}
                                                            disabled>
                                                            -- Pilih --</option>
                                                        <option value="DISABILITAS"
                                                            {{ old('disability_status', $data->disability_status) == 'DISABILITAS' ? 'selected' : '' }}>
                                                            DISABILITAS</option>
                                                        <option value="NON-DISABILITAS"
                                                            {{ old('disability_status', $data->disability_status) == 'NON-DISABILITAS' ? 'selected' : '' }}>
                                                            NON-DISABILITAS</option>
                                                    </select>
                                                    @error('disability_status')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-6 mb-2">
                                                    <label class="form-label" for="district">Kecamatan sesuai Domisili
                                                        <span class="text-danger">*</span></label>
                                                    <select id="district"
                                                        class="@error('district') is-invalid @enderror select2 custom-select"
                                                        name="district" required>
                                                        <option value="">-- Pilih Kecamatan --</option>
                                                    </select>
                                                    @error('district')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-6 mb-2">
                                                    <label class="form-label" for="village">Kelurahan/Desa sesuai
                                                        Domisili <span class="text-danger">*</span></label>
                                                    <select id="village"
                                                        class="@error('village') is-invalid @enderror select2 custom-select"
                                                        name="village" required>
                                                        <option value="">-- Pilih Kelurahan/Desa --</option>
                                                    </select>
                                                    @error('village')
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

                                                    $value = old('occupation', $data->occupation);

                                                    $isCustom = $value && !in_array($value, $occupationOptions);
                                                @endphp

                                                <div class="form-group col-md-6 mb-2">
                                                    <label class="form-label" for="occupation">Pekerjaan <span
                                                            class="text-danger">*</span></label>

                                                    <select id="select_occupation"
                                                        class="@error('occupation') is-invalid @enderror select2 custom-select">

                                                        <option value="" disabled
                                                            {{ !$value ? 'selected' : '' }}>
                                                            -- Pilih Pekerjaan --
                                                        </option>

                                                        @foreach ($occupationOptions as $option)
                                                            <option value="{{ $option }}"
                                                                {{ $value === $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach

                                                        <option value="LAINNYA" {{ $isCustom ? 'selected' : '' }}>
                                                            LAINNYA
                                                        </option>
                                                    </select>

                                                    <input type="text" id="occupation" name="occupation" required
                                                        class="form-control {{ $isCustom ? '' : 'd-none' }} @error('occupation') is-invalid @enderror mt-1"
                                                        placeholder="Isi jika memilih lainnya"
                                                        value="{{ $isCustom ? $value : '' }}">

                                                    @error('occupation')
                                                        <div class="invalid-feedback">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-6 mb-2">
                                                    <label class="form-label" for="address">Alamat (Opsional)</label>
                                                    <textarea id="address" class="form-control @error('address') is-invalid @enderror"
                                                        placeholder="Contoh: Jalan Merdeka No. 10, RT 001, RW 002" name="address">{{ old('address', $data->address) }}</textarea>
                                                    @error('address')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card border-indigo">
                                        <div class="card-header bg-indigo">
                                            <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                Penilaian
                                            </h5>
                                        </div>

                                        <div class="card-body">
                                            <div class="form-body">
                                                <div class="table-responsive">
                                                    <table
                                                        class="table-bordered table-striped table-align-middle w-100 table">
                                                        <thead>
                                                            <tr class="text-center">
                                                                <th>No.</th>
                                                                <th>Jenis Infrastruktur</th>
                                                                <th style="width: 200px">Ketersediaan Fisik</th>
                                                                <th style="width: 200px">Kualitas</th>
                                                                <th style="width: 200px">Kesesuaian</th>
                                                                <th style="width: 200px">Pemanfaatan</th>
                                                                <th style="width: 200px">Harapan</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach ($infrastructureOptions as $key => $label)
                                                                @php
                                                                    $questionnaireAnswer =
                                                                        $questionnaireAnswers[$key] ?? null;
                                                                @endphp

                                                                <tr>
                                                                    <td class="text-right">{{ $loop->iteration }}</td>

                                                                    <td>
                                                                        {{ $label }}
                                                                    </td>

                                                                    <td>
                                                                        <select
                                                                            name="answers[{{ $key }}][physical_availability_score]"
                                                                            class="select2 custom-select @error('answers.' . $key . '.physical_availability_score') is-invalid @enderror"
                                                                            required>
                                                                            <option value="">Pilih Nilai</option>
                                                                            @foreach ($scoreOptions as $score => $optionLabel)
                                                                                <option value="{{ $score }}"
                                                                                    @selected(old('answers.' . $key . '.physical_availability_score', $questionnaireAnswer->physical_availability_score ?? null) == $score)>
                                                                                    {{ $optionLabel }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>

                                                                        @error('answers.' . $key .
                                                                            '.physical_availability_score')
                                                                            <div class="invalid-feedback">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </td>

                                                                    <td>
                                                                        <select
                                                                            name="answers[{{ $key }}][quality_score]"
                                                                            class="select2 custom-select @error('answers.' . $key . '.quality_score') is-invalid @enderror"
                                                                            required>
                                                                            <option value="">Pilih Nilai</option>
                                                                            @foreach ($scoreOptions as $score => $optionLabel)
                                                                                <option value="{{ $score }}"
                                                                                    @selected(old('answers.' . $key . '.quality_score', $questionnaireAnswer->quality_score ?? null) == $score)>
                                                                                    {{ $optionLabel }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>

                                                                        @error('answers.' . $key . '.quality_score')
                                                                            <div class="invalid-feedback">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </td>

                                                                    <td>
                                                                        <select
                                                                            name="answers[{{ $key }}][suitability_score]"
                                                                            class="select2 custom-select @error('answers.' . $key . '.suitability_score') is-invalid @enderror"
                                                                            required>
                                                                            <option value="">Pilih Nilai</option>
                                                                            @foreach ($scoreOptions as $score => $optionLabel)
                                                                                <option value="{{ $score }}"
                                                                                    @selected(old('answers.' . $key . '.suitability_score', $questionnaireAnswer->suitability_score ?? null) == $score)>
                                                                                    {{ $optionLabel }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>

                                                                        @error('answers.' . $key . '.suitability_score')
                                                                            <div class="invalid-feedback">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </td>

                                                                    <td>
                                                                        <select
                                                                            name="answers[{{ $key }}][utilization_score]"
                                                                            class="select2 custom-select @error('answers.' . $key . '.utilization_score') is-invalid @enderror"
                                                                            required>
                                                                            <option value="">Pilih Nilai</option>
                                                                            @foreach ($scoreOptions as $score => $optionLabel)
                                                                                <option value="{{ $score }}"
                                                                                    @selected(old('answers.' . $key . '.utilization_score', $questionnaireAnswer->utilization_score ?? null) == $score)>
                                                                                    {{ $optionLabel }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>

                                                                        @error('answers.' . $key . '.utilization_score')
                                                                            <div class="invalid-feedback">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </td>

                                                                    <td>
                                                                        <select
                                                                            name="answers[{{ $key }}][expectation_score]"
                                                                            class="select2 custom-select @error('answers.' . $key . '.expectation_score') is-invalid @enderror"
                                                                            required>
                                                                            <option value="">Pilih Nilai</option>
                                                                            @foreach ($scoreOptions as $score => $optionLabel)
                                                                                <option value="{{ $score }}"
                                                                                    @selected(old('answers.' . $key . '.expectation_score', $questionnaireAnswer->expectation_score ?? null) == $score)>
                                                                                    {{ $optionLabel }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>

                                                                        @error('answers.' . $key . '.expectation_score')
                                                                            <div class="invalid-feedback">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card border-indigo">
                                        <div class="card-header bg-indigo">
                                            <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                Prioritas Peningkatan
                                            </h5>
                                        </div>

                                        <div class="card-body">
                                            <div class="form-body row">
                                                <div class="form-group col-md-6 mb-2">
                                                    <label class="form-label fw-bold fs-5 text-dark"
                                                        for="priority_infrastructure">Infrastruktur Prioritas <span
                                                            class="text-danger">*</span></label>
                                                    <select id="priority_infrastructure"
                                                        class="@error('priority_infrastructure') is-invalid @enderror select2 custom-select"
                                                        required name="priority_infrastructure">
                                                        <option value=""
                                                            {{ old('priority_infrastructure', $data->priority_infrastructure) ? '' : 'selected' }}
                                                            disabled>-- Pilih
                                                            Infrastruktur --
                                                        </option>
                                                        <option value="PRASARANA TERMINAL"
                                                            {{ old('priority_infrastructure', $data->priority_infrastructure) == 'PRASARANA TERMINAL' ? 'selected' : '' }}>
                                                            PRASARANA TERMINAL</option>
                                                        <option value="JARINGAN JALAN"
                                                            {{ old('priority_infrastructure', $data->priority_infrastructure) == 'JARINGAN JALAN' ? 'selected' : '' }}>
                                                            JARINGAN JALAN</option>
                                                        <option value="JARINGAN IRIGASI"
                                                            {{ old('priority_infrastructure', $data->priority_infrastructure) == 'JARINGAN IRIGASI' ? 'selected' : '' }}>
                                                            JARINGAN IRIGASI</option>
                                                        <option value="PRASARANA AIR MINUM"
                                                            {{ old('priority_infrastructure', $data->priority_infrastructure) == 'PRASARANA AIR MINUM' ? 'selected' : '' }}>
                                                            PRASARANA AIR MINUM</option>
                                                        <option value="PRASARANA AIR LIMBAH"
                                                            {{ old('priority_infrastructure', $data->priority_infrastructure) == 'PRASARANA AIR LIMBAH' ? 'selected' : '' }}>
                                                            PRASARANA AIR LIMBAH</option>
                                                        <option value="PRASARANA PERSAMPAHAN"
                                                            {{ old('priority_infrastructure', $data->priority_infrastructure) == 'PRASARANA PERSAMPAHAN' ? 'selected' : '' }}>
                                                            PRASARANA PERSAMPAHAN</option>
                                                        <option value="JARINGAN LISTRIK"
                                                            {{ old('priority_infrastructure', $data->priority_infrastructure) == 'JARINGAN LISTRIK' ? 'selected' : '' }}>
                                                            JARINGAN LISTRIK</option>
                                                        <option value="JARINGAN TELEKOMUNIKASI/INTERNET"
                                                            {{ old('priority_infrastructure', $data->priority_infrastructure) == 'JARINGAN TELEKOMUNIKASI/INTERNET' ? 'selected' : '' }}>
                                                            JARINGAN TELEKOMUNIKASI/INTERNET</option>
                                                    </select>
                                                    @error('education')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                @php
                                                    $improvementOptions = [
                                                        'KETERSEDIAAN FISIK',
                                                        'KUALITAS',
                                                        'KESESUAIAN',
                                                        'PEMANFAATAN',
                                                    ];

                                                    $value = old('priority_improvement', $data->priority_improvement);

                                                    $isCustom = $value && !in_array($value, $improvementOptions);
                                                @endphp

                                                <div class="form-group col-md-6 mb-2">
                                                    <label class="form-label fw-bold fs-5 text-dark"
                                                        for="priority_improvement">Aspek Prioritas <span
                                                            class="text-danger">*</span></label>

                                                    <select id="select_priority_improvement"
                                                        class="@error('priority_improvement') is-invalid @enderror select2 custom-select">

                                                        <option value="" disabled
                                                            {{ !$value ? 'selected' : '' }}>
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

                                                    <input type="text" id="priority_improvement"
                                                        name="priority_improvement" required
                                                        class="form-control {{ $isCustom ? '' : 'd-none' }} @error('priority_improvement') is-invalid @enderror mt-1"
                                                        placeholder="Isi jika memilih lainnya"
                                                        value="{{ $isCustom ? $value : '' }}">

                                                    @error('priority_improvement')
                                                        <div class="invalid-feedback">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-4 mb-md-0 mb-1">
                                            @if ($previous)
                                                <a href="{{ $previous }}"
                                                    class="btn btn-outline-indigo btn-block">
                                                    <i class="fa fa-arrow-left"></i> Sebelumnya
                                                </a>
                                            @endif
                                        </div>

                                        <div class="col-md-4 mb-md-0 mb-1">
                                            <button type="submit" class="btn btn-indigo btn-block" id="btnSubmit">
                                                <i class="fa fa-save"></i> Simpan Perubahan
                                            </button>
                                        </div>

                                        <div class="col-md-4">
                                            @if ($next)
                                                <a href="{{ $next }}"
                                                    class="btn btn-outline-indigo btn-block">
                                                    Berikutnya <i class="fa fa-arrow-right"></i>
                                                </a>
                                            @endif
                                        </div>
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
            href="{{ asset('app-assets/vendors/css/forms/selects/select2.min.css') }}">

        <style>
            /* ============================= */
            /* Custom Style */
            /* ============================= */
            .select2-selection.is-invalid {
                border: 1px solid #da4453 !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="{{ asset('app-assets/js/scripts/extensions/block-ui-custom.js') }}"></script>
        <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>

        <script>
            function confirmDelete(id) {
                swal({
                    title: 'Hapus Data?',
                    text: 'Data survei ID {{ $data->id }} akan dihapus secara permanen.',
                    icon: 'warning',
                    buttons: ["Batal", "Ya, hapus!"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        blockWholePage("Mohon tunggu...");
                        setTimeout(() => document.getElementById('delete-form-' + id).submit(),
                            300);
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                const btnSubmit = document.getElementById("btnSubmit");
                const form = document.getElementById("myForm");

                btnSubmit.addEventListener('click', (e) => {
                    e.preventDefault();

                    swal({
                        title: 'Perbarui Data?',
                        text: "Data survei ID {{ $data->id }} akan diperbarui.",
                        icon: 'warning',
                        buttons: ["Batal", "Ya, Perbarui!"],
                    }).then((willUpdate) => {
                        if (willUpdate) {
                            blockWholePage("Mohon tunggu...");
                            setTimeout(() => form.submit(), 300);
                        }
                    });
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                // ================= INIT SELECT2 =================
                $('.select2').select2({
                    width: '100%',
                });

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
                initSelectOther('#select_priority_improvement', '#priority_improvement');
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
</x-app-layout>
