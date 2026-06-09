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
                    <label class="small" for="filter_name">Nama Responden</label>
                    <input type="text" id="filter_name" class="form-control" placeholder="Contoh: Jhon Doe">
                </div>

                <div class="col-md-4 mb-2">
                    <label class="small" for="filter_phone_number">No. HP/WA Responden</label>
                    <input type="text" id="filter_phone_number" class="form-control"
                        placeholder="Contoh: 08123456789">
                </div>

                <div class="col-md-4 mb-2">
                    <label class="small" for="filter_district">Kecamatan</label>
                    <select id="filter_district" class="custom-select select2">
                        <option value="">
                            -- Pilih Kecamatan --
                        </option>
                    </select>
                </div>

                @php
                    $frequencyOptions = [
                        'Drainase' => [
                            'label' => 'Frekuensi Masalah',
                            'options' => [
                                'SERING (TIAP HUJAN)',
                                'KADANG-KADANG',
                                'JARANG',
                                'TIDAK PERNAH MENGHADAPI MASALAH',
                            ],
                        ],
                        'Irigasi' => [
                            'label' => 'Frekuensi Akses',
                            'options' => ['HARIAN', 'MINGGUAN', 'MUSIMAN (MUSIM TANAM)', 'JARANG'],
                        ],
                        'Listrik' => [
                            'label' => 'Frekuensi Penggunaan',
                            'options' => ['HARIAN/RUTIN', 'KADANG-KADANG', 'JARANG'],
                        ],
                        'default' => [
                            'label' => 'Frekuensi Penggunaan',
                            'options' => ['HARIAN', 'MINGGUAN', 'BULANAN', 'JARANG'],
                        ],
                    ];

                    $frequency = $frequencyOptions[$infrastructureType] ?? $frequencyOptions['default'];
                @endphp

                <div class="col-md-4 mb-2">
                    <label class="small" for="filter_frequency">{{ $frequency['label'] }}</label>
                    <select id="filter_frequency" class="custom-select">
                        <option value="">
                            -- {{ $frequency['label'] }} --
                        </option>
                        @foreach ($frequency['options'] as $option)
                            <option value="{{ $option }}">
                                {{ $option }}
                            </option>
                        @endforeach
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
