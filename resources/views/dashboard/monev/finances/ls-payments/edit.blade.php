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
                                    action="{{ route('dashboard.monev.finances.ls-payments.update', $data->id) }}"
                                    method="POST" id="myForm">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-body">
                                        <div class="card border-indigo">
                                            <div class="card-header bg-indigo">
                                                <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                    SKPD
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="skpd_code">Kode SKPD</label>
                                                            <input type="text" id="skpd_code" name="skpd_code"
                                                                class="form-control @error('skpd_code') is-invalid @enderror"
                                                                value="{{ old('skpd_code', $data->skpd_code) }}"
                                                                placeholder="Contoh: 1.03.0.00.0.00.01.0000">
                                                            @error('skpd_code')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="skpd_name">Nama SKPD</label>
                                                            <input type="text" id="skpd_name" name="skpd_name"
                                                                class="form-control @error('skpd_name') is-invalid @enderror"
                                                                value="{{ old('skpd_name', $data->skpd_name) }}"
                                                                placeholder="Contoh: Dinas Pekerjaan Umum dan Penataan Ruang">
                                                            @error('skpd_name')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sub_skpd_code">Kode Sub SKPD</label>
                                                            <input type="text" id="sub_skpd_code"
                                                                name="sub_skpd_code"
                                                                class="form-control @error('sub_skpd_code') is-invalid @enderror"
                                                                value="{{ old('sub_skpd_code', $data->sub_skpd_code) }}"
                                                                placeholder="Contoh: 1.03.0.00.0.00.01.0000">
                                                            @error('sub_skpd_code')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sub_skpd_name">Nama Sub SKPD</label>
                                                            <input type="text" id="sub_skpd_name"
                                                                name="sub_skpd_name"
                                                                class="form-control @error('sub_skpd_name') is-invalid @enderror"
                                                                value="{{ old('sub_skpd_name', $data->sub_skpd_name) }}"
                                                                placeholder="Contoh: Dinas Pekerjaan Umum dan Penataan Ruang">
                                                            @error('sub_skpd_name')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card border-indigo">
                                            <div class="card-header bg-indigo">
                                                <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                    FUNGSI
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="function_code">Kode Fungsi</label>
                                                            <input type="text" id="function_code"
                                                                name="function_code"
                                                                class="form-control @error('function_code') is-invalid @enderror"
                                                                value="{{ old('function_code', $data->function_code) }}"
                                                                placeholder="Contoh: 04">
                                                            @error('function_code')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="function_name">Nama Fungsi</label>
                                                            <input type="text" id="function_name"
                                                                name="function_name"
                                                                class="form-control @error('function_name') is-invalid @enderror"
                                                                value="{{ old('function_name', $data->function_name) }}"
                                                                placeholder="Contoh: Ekonomi">
                                                            @error('function_name')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sub_function_code">Kode Sub Fungsi</label>
                                                            <input type="text" id="sub_function_code"
                                                                name="sub_function_code"
                                                                class="form-control @error('sub_function_code') is-invalid @enderror"
                                                                value="{{ old('sub_function_code', $data->sub_function_code) }}"
                                                                placeholder="Contoh: 07">
                                                            @error('sub_function_code')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sub_function_name">Nama Sub Fungsi</label>
                                                            <input type="text" id="sub_function_name"
                                                                name="sub_function_name"
                                                                class="form-control @error('sub_function_name') is-invalid @enderror"
                                                                value="{{ old('sub_function_name', $data->sub_function_name) }}"
                                                                placeholder="Contoh: Industri dan Konstruksi">
                                                            @error('sub_function_name')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card border-indigo">
                                            <div class="card-header bg-indigo">
                                                <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                    URUSAN
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="affair_code">Kode Urusan</label>
                                                            <input type="text" id="affair_code" name="affair_code"
                                                                class="form-control @error('affair_code') is-invalid @enderror"
                                                                value="{{ old('affair_code', $data->affair_code) }}"
                                                                placeholder="Contoh: 1">
                                                            @error('affair_code')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="affair_name">Nama Urusan</label>
                                                            <input type="text" id="affair_name" name="affair_name"
                                                                class="form-control @error('affair_name') is-invalid @enderror"
                                                                value="{{ old('affair_name', $data->affair_name) }}"
                                                                placeholder="Contoh: Urusan Pemerintahan Wajib Yang Berkaitan Dengan Pelayanan Dasar">
                                                            @error('affair_name')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="field_affair_code">Kode Bidang Urusan</label>
                                                            <input type="text" id="field_affair_code"
                                                                name="field_affair_code"
                                                                class="form-control @error('field_affair_code') is-invalid @enderror"
                                                                value="{{ old('field_affair_code', $data->field_affair_code) }}"
                                                                placeholder="Contoh: 1.03">
                                                            @error('field_affair_code')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="field_affair_name">Nama Bidang Urusan</label>
                                                            <input type="text" id="field_affair_name"
                                                                name="field_affair_name"
                                                                class="form-control @error('field_affair_name') is-invalid @enderror"
                                                                value="{{ old('field_affair_name', $data->field_affair_name) }}"
                                                                placeholder="Contoh: Urusan Pemerintahan Bidang Pekerjaan Umum Dan Penataan Ruang">
                                                            @error('field_affair_name')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card border-indigo">
                                            <div class="card-header bg-indigo">
                                                <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                    PROGRAM DAN KEGIATAN
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="program_code">Kode Program</label>
                                                            <input type="text" id="program_code"
                                                                name="program_code"
                                                                class="form-control @error('program_code') is-invalid @enderror"
                                                                value="{{ old('program_code', $data->program_code) }}"
                                                                placeholder="Contoh: 01.02.001">
                                                            @error('program_code')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="program_name">Nama Program</label>
                                                            <input type="text" id="program_name"
                                                                name="program_name"
                                                                class="form-control @error('program_name') is-invalid @enderror"
                                                                value="{{ old('program_name', $data->program_name) }}"
                                                                placeholder="Contoh: Program Penataan Bangunan Gedung">
                                                            @error('program_name')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="activity_code">Kode Kegiatan</label>
                                                            <input type="text" id="activity_code"
                                                                name="activity_code"
                                                                class="form-control @error('activity_code') is-invalid @enderror"
                                                                value="{{ old('activity_code', $data->activity_code) }}"
                                                                placeholder="Contoh: 1.03.08.2.01">
                                                            <small class="font-weight-bold">Digunakan untuk pencocokan
                                                                data.</small>
                                                            @error('activity_code')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="activity_name">Nama Kegiatan</label>
                                                            <input type="text" id="activity_name"
                                                                name="activity_name"
                                                                class="form-control @error('activity_name') is-invalid @enderror"
                                                                value="{{ old('activity_name', $data->activity_name) }}"
                                                                placeholder="Contoh: Penyelenggaraan Bangunan Gedung di Wilayah Daerah Kabupaten/Kota, Pemberian Izin Mendirikan Bangunan (IMB) dan Sertifikat Laik Fungsi Bangunan Gedung">
                                                            @error('activity_name')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sub_activity_code">Kode Sub Kegiatan</label>
                                                            <input type="text" id="sub_activity_code"
                                                                name="sub_activity_code"
                                                                class="form-control @error('sub_activity_code') is-invalid @enderror"
                                                                value="{{ old('sub_activity_code', $data->sub_activity_code) }}"
                                                                placeholder="Contoh: 1.03.08.2.01.0018">
                                                            @error('sub_activity_code')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sub_activity_name">Nama Sub Kegiatan</label>
                                                            <input type="text" id="sub_activity_name"
                                                                name="sub_activity_name"
                                                                class="form-control @error('sub_activity_name') is-invalid @enderror"
                                                                value="{{ old('sub_activity_name', $data->sub_activity_name) }}"
                                                                placeholder="Contoh: Pemeliharaan, Perawatan, dan Pemeriksaan Berkala Bangunan Gedung untuk Kepentingan Strategis Daerah Kabupaten/Kota">
                                                            @error('sub_activity_name')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card border-indigo">
                                            <div class="card-header bg-indigo">
                                                <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                    REKENING
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="account_code">Kode Rekening</label>
                                                            <input type="text" id="account_code"
                                                                name="account_code"
                                                                class="form-control @error('account_code') is-invalid @enderror"
                                                                value="{{ old('account_code', $data->account_code) }}"
                                                                placeholder="Contoh: 5.1.02.02.01.0030">
                                                            @error('account_code')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="account_name">Nama Rekening</label>
                                                            <input type="text" id="account_name"
                                                                name="account_name"
                                                                class="form-control @error('account_name') is-invalid @enderror"
                                                                value="{{ old('account_name', $data->account_name) }}"
                                                                placeholder="Contoh: Belanja Jasa Tenaga Kebersihan">
                                                            @error('account_name')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card border-indigo">
                                            <div class="card-header bg-indigo">
                                                <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                    DOKUMEN
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="document_number">Nomor Dokumen</label>
                                                            <input type="text" id="document_number"
                                                                name="document_number"
                                                                class="form-control @error('document_number') is-invalid @enderror"
                                                                value="{{ old('document_number', $data->document_number) }}"
                                                                placeholder="Contoh: 52.02/02.0/000008/LS/1.03.0.00.0.00.01.0000/M/2/2025">
                                                            @error('document_number')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="dpt_number">Nomor DPT</label>
                                                            <input type="text" id="dpt_number" name="dpt_number"
                                                                class="form-control @error('dpt_number') is-invalid @enderror"
                                                                value="{{ old('dpt_number', $data->dpt_number) }}"
                                                                placeholder="Contoh: DPT/3903">
                                                            @error('dpt_number')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="document_type">Jenis Dokumen</label>
                                                            <input type="text" id="document_type"
                                                                name="document_type"
                                                                class="form-control @error('document_type') is-invalid @enderror"
                                                                value="{{ old('document_type', $data->document_type) }}"
                                                                placeholder="Contoh: SPP">
                                                            @error('document_type')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="transaction_type">Jenis Transaksi</label>
                                                            <input type="text" id="transaction_type"
                                                                name="transaction_type"
                                                                class="form-control @error('transaction_type') is-invalid @enderror"
                                                                value="{{ old('transaction_type', $data->transaction_type) }}"
                                                                placeholder="Contoh: LS">
                                                            @error('transaction_type')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="document_date">Tanggal Dokumen</label>
                                                    <input type="date" id="document_date" name="document_date"
                                                        class="form-control @error('document_date') is-invalid @enderror"
                                                        value="{{ old('document_date', $data->document_date) }}">
                                                    @error('document_date')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="document_description">Keterangan Dokumen</label>
                                                    <textarea id="document_description" name="document_description"
                                                        class="form-control @error('document_description') is-invalid @enderror" rows="3"
                                                        placeholder="Contoh: Pembayaran UMK 30% (Tiga puluh persen) pada Pekerjaan Pengadaan Operasional dan CS (Cleaning Servis) Gedung Kantor An. PT. SAKRA JAYA UTAMA. DANA PAD.">{{ old('document_description', $data->document_description) }}</textarea>
                                                    @error('document_description')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card border-indigo">
                                            <div class="card-header bg-indigo">
                                                <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                    NILAI REALISASI, NILAI SETORAN, DAN PEGAWAI
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="realization_value">Nilai Realisasi</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"
                                                                        id="realization-addon">Rp</span>
                                                                </div>
                                                                <input type="number" id="realization_value"
                                                                    name="realization_value"
                                                                    class="form-control @error('realization_value') is-invalid @enderror"
                                                                    value="{{ old('realization_value', $data->realization_value) }}"
                                                                    placeholder="Contoh: 1000000 (tanpa simbol)"
                                                                    aria-describedby="realization-addon">
                                                                @error('realization_value')
                                                                    <div class="invalid-feedback d-block">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="deposit_value">Nilai Setoran</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"
                                                                        id="deposit-addon">Rp</span>
                                                                </div>
                                                                <input type="number" id="deposit_value"
                                                                    name="deposit_value"
                                                                    class="form-control @error('deposit_value') is-invalid @enderror"
                                                                    value="{{ old('deposit_value', $data->deposit_value) }}"
                                                                    placeholder="Contoh: 1000000 (tanpa simbol)"
                                                                    aria-describedby="deposit-addon">
                                                                @error('deposit_value')
                                                                    <div class="invalid-feedback d-block">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="nip">NIP</label>
                                                            <input type="number" id="nip" name="nip"
                                                                class="form-control @error('nip') is-invalid @enderror"
                                                                value="{{ old('nip', $data->nip) }}"
                                                                placeholder="Contoh: 198206042010012007">
                                                            @error('nip')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="personnel_name">Nama Pegawai</label>
                                                            <input type="text" id="personnel_name"
                                                                name="personnel_name"
                                                                class="form-control @error('personnel_name') is-invalid @enderror"
                                                                value="{{ old('personnel_name', $data->personnel_name) }}"
                                                                placeholder="Contoh: Hijriah">
                                                            @error('personnel_name')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="saved_date">Tanggal Simpan</label>
                                                            <input type="date" id="saved_date" name="saved_date"
                                                                class="form-control @error('saved_date') is-invalid @enderror"
                                                                value="{{ old('saved_date', $data->save_date) }}">
                                                            @error('saved_date')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card border-indigo">
                                            <div class="card-header bg-indigo">
                                                <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                    SPD
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="spd_number">Nomor SPD</label>
                                                    <input type="text" id="spd_number" name="spd_number"
                                                        class="form-control @error('spd_number') is-invalid @enderror"
                                                        value="{{ old('spd_number', $data->spd_number) }}"
                                                        placeholder="Contoh: 52.02/01.0/000001/1.03.0.00.0.00.01.0000/M/1/2025">
                                                    @error('spd_number')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="spd_period">Periode SPD</label>
                                                            <input type="text" id="spd_period" name="spd_period"
                                                                class="form-control @error('spd_period') is-invalid @enderror"
                                                                value="{{ old('spd_period', $data->spd_period) }}"
                                                                placeholder="Contoh: Triwulan 1">
                                                            @error('spd_period')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="spd_value">Nilai SPD</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"
                                                                        id="spd-addon">Rp</span>
                                                                </div>
                                                                <input type="number" id="spd_value" name="spd_value"
                                                                    class="form-control @error('spd_value') is-invalid @enderror"
                                                                    value="{{ old('spd_value', $data->spd_value) }}"
                                                                    placeholder="Contoh: 1000000 (tanpa simbol)"
                                                                    aria-describedby="spd-addon">
                                                                @error('spd_value')
                                                                    <div class="invalid-feedback d-block">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="spd_stage">Tahapan SPD</label>
                                                            <input type="text" id="spd_stage" name="spd_stage"
                                                                class="form-control @error('spd_stage') is-invalid @enderror"
                                                                value="{{ old('spd_stage', $data->spd_stage) }}"
                                                                placeholder="Penetapan APBD">
                                                            @error('spd_stage')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="sub_stage_name">Nama Sub Tahapan Jadwal</label>
                                                            <input type="text" id="sub_stage_name"
                                                                name="sub_stage_name"
                                                                class="form-control @error('sub_stage_name') is-invalid @enderror"
                                                                value="{{ old('sub_stage_name', $data->sub_stage_name) }}"
                                                                placeholder="Contoh: Penetapan APBD 2025">
                                                            @error('sub_stage_name')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="apbd_stage">Tahapan APBD</label>
                                                            <input type="text" id="apbd_stage" name="apbd_stage"
                                                                class="form-control @error('apbd_stage') is-invalid @enderror"
                                                                value="{{ old('apbd_stage', $data->apbd_stage) }}"
                                                                placeholder="Contoh: Murni">
                                                            @error('apbd_stage')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card border-indigo">
                                            <div class="card-header bg-indigo">
                                                <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                    SPP
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="spp_number">Nomor SPP</label>
                                                            <input type="text" id="spp_number" name="spp_number"
                                                                class="form-control @error('spp_number') is-invalid @enderror"
                                                                value="{{ old('spp_number', $data->spp_number) }}"
                                                                placeholder="Contoh: 52.02/02.0/000008/LS/1.03.0.00.0.00.01.0000/M/2/2025">
                                                            @error('spp_number')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="spp_date">Tanggal SPP</label>
                                                            <input type="date" id="spp_date" name="spp_date"
                                                                class="form-control @error('spp_date') is-invalid @enderror"
                                                                value="{{ old('spp_date', $data->spp_date) }}">
                                                            @error('spp_date')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card border-indigo">
                                            <div class="card-header bg-indigo">
                                                <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                    SPM
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="spm_number">Nomor SPM</label>
                                                            <input type="text" id="spm_number" name="spm_number"
                                                                class="form-control @error('spm_number') is-invalid @enderror"
                                                                value="{{ old('spm_number', $data->spm_number) }}"
                                                                placeholder="Contoh: 52.02/03.0/000008/LS/1.03.0.00.0.00.01.0000/M/2/2025">
                                                            @error('spm_number')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="spm_date">Tanggal SPM</label>
                                                            <input type="date" id="spm_date" name="spm_date"
                                                                class="form-control @error('spm_date') is-invalid @enderror"
                                                                value="{{ old('spm_date', $data->spm_date) }}">
                                                            @error('spm_date')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card border-indigo">
                                            <div class="card-header bg-indigo">
                                                <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                    SP2D
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sp2d_number">Nomor SP2D</label>
                                                            <input type="text" id="sp2d_number" name="sp2d_number"
                                                                class="form-control @error('sp2d_number') is-invalid @enderror"
                                                                value="{{ old('sp2d_number', $data->sp2d_number) }}"
                                                                placeholder="Contoh: 52.02/04.0/000009/LS/1.03.0.00.0.00.01.0000/M/2/2025">
                                                            @error('sp2d_number')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sp2d_date">Tanggal SP2D</label>
                                                            <input type="date" id="sp2d_date" name="sp2d_date"
                                                                class="form-control @error('sp2d_date') is-invalid @enderror"
                                                                value="{{ old('sp2d_date', $data->sp2d_date) }}">
                                                            @error('sp2d_date')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="transfer_date">Tanggal Transfer</label>
                                                            <input type="date" id="transfer_date"
                                                                name="transfer_date"
                                                                class="form-control @error('transfer_date') is-invalid @enderror"
                                                                value="{{ old('transfer_date', $data->transfer_date) }}">
                                                            @error('transfer_date')
                                                                <div class="invalid-feedback d-block">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sp2d_value">Nilai SP2D</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"
                                                                        id="sp2d-addon">Rp</span>
                                                                </div>
                                                                <input type="number" id="sp2d_value"
                                                                    name="sp2d_value"
                                                                    class="form-control @error('sp2d_value') is-invalid @enderror"
                                                                    value="{{ old('sp2d_value', $data->sp2d_value) }}"
                                                                    placeholder="Contoh: 1000000 (tanpa simbol)"
                                                                    aria-describedby="sp2d-addon">
                                                                @error('sp2d_value')
                                                                    <div class="invalid-feedback d-block">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions right">
                                        <a href="{{ route('dashboard.monev.finances.ls-payments.index') }}"
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
    @endpush

    @push('scripts')
        <script src="{{ asset('public/app-assets/js/scripts/extensions/block-ui-custom.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const numericColumns = @json($numericColumns);

                numericColumns.forEach(function(field) {
                    const input = document.getElementById(field);

                    if (input) {
                        let val = input.value;

                        if (val !== '') {
                            input.value = parseFloat(val);
                        }
                    }
                });

                const btnSubmit = document.getElementById("btnSubmit");
                const form = document.getElementById("myForm");

                btnSubmit.addEventListener('click', (e) => {
                    e.preventDefault();

                    // Blok UI terlebih dahulu
                    blockWholePage("Mohon tunggu...");

                    // Submit form setelah jeda kecil
                    setTimeout(() => {
                        form.submit();
                    }, 300);
                });
            });
        </script>
    @endpush
</x-app-layout>
