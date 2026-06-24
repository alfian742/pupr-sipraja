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
                                    action="{{ route('dashboard.monev.finances.contracts.update', $data->id) }}"
                                    method="POST" id="myForm">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="contract_start_date">Tanggal Mulai</label>
                                                    <input type="date" id="contract_start_date"
                                                        name="contract_start_date"
                                                        class="form-control @error('contract_start_date') is-invalid @enderror"
                                                        value="{{ old('contract_start_date', $data->contract_start_date) }}">
                                                    @error('contract_start_date')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="contract_end_date">Tanggal Berakhir</label>
                                                    <input type="date" id="contract_end_date"
                                                        name="contract_end_date"
                                                        class="form-control @error('contract_end_date') is-invalid @enderror"
                                                        value="{{ old('contract_end_date', $data->contract_end_date) }}">
                                                    @error('contract_end_date')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="contract_number">Nomor Kontrak</label>
                                                    <input type="text" id="contract_number" name="contract_number"
                                                        class="form-control @error('contract_number') is-invalid @enderror"
                                                        value="{{ old('contract_number', $data->contract_number) }}"
                                                        placeholder="Contoh: 03/PPK-CS/DPUPR/2025">
                                                    @error('contract_number')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="third_party_name">Nama Pihak III</label>
                                                    <input type="text" id="third_party_name" name="third_party_name"
                                                        class="form-control @error('third_party_name') is-invalid @enderror"
                                                        value="{{ old('third_party_name', $data->third_party_name) }}"
                                                        placeholder="Contoh:  PT. SAKRA JAYA UTAMA">
                                                    @error('third_party_name')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="activity_code">Kode Kegiatan</label>
                                                    <input type="text" id="activity_code" name="activity_code"
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
                                                    <label for="sub_account_code">Sub Rek</label>
                                                    <input type="text" id="sub_account_code" name="sub_account_code"
                                                        class="form-control @error('sub_account_code') is-invalid @enderror"
                                                        value="{{ old('sub_account_code', $data->sub_account_code) }}"
                                                        placeholder="Contoh: 5.1.02.02.01.0030">
                                                    <small class="font-weight-bold">Digunakan untuk pencocokan
                                                        data.</small>
                                                    @error('sub_account_code')
                                                        <div class="invalid-feedback d-block">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="activity_description">Uraian Kegiatan</label>
                                                    <textarea id="activity_description" name="activity_description"
                                                        class="form-control @error('activity_description') is-invalid @enderror" rows="3"
                                                        placeholder="Contoh: Pengadaan Operasional dan CS (Cleaning Servis) Gedung Kantor">{{ old('activity_description', $data->activity_description) }}</textarea>
                                                    @error('activity_description')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="department">Bidang</label>
                                                    <select id="department" name="department"
                                                        class="custom-select @error('department') is-invalid @enderror">
                                                        <option value="" disabled selected>-- Pilih Bidang --
                                                        </option>
                                                        <option value="BINA MARGA"
                                                            {{ old('department', $data->department) == 'BINA MARGA' ? 'selected' : '' }}>
                                                            BINA MARGA</option>
                                                        <option value="CIPTA KARYA"
                                                            {{ old('department', $data->department) == 'CIPTA KARYA' ? 'selected' : '' }}>
                                                            CIPTA KARYA</option>
                                                        <option value="SDA"
                                                            {{ old('department', $data->department) == 'SDA' ? 'selected' : '' }}>
                                                            SDA
                                                        </option>
                                                        <option value="SEK"
                                                            {{ old('department', $data->department) == 'SEK' ? 'selected' : '' }}>
                                                            SEK
                                                        </option>
                                                    </select>

                                                    @error('department')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="budget_value">Nilai Anggaran (Pagu)</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="budget-addon">Rp</span>
                                                        </div>
                                                        <input type="number" id="budget_value" name="budget_value"
                                                            class="form-control @error('budget_value') is-invalid @enderror"
                                                            value="{{ old('budget_value', $data->budget_value) }}"
                                                            placeholder="Contoh: 1000000 (tanpa simbol)"
                                                            aria-describedby="budget-addon">
                                                        @error('budget_value')
                                                            <div class="invalid-feedback d-block">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="contract_value">Nilai Kontrak</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"
                                                                id="contract-addon">Rp</span>
                                                        </div>
                                                        <input type="number" id="contract_value"
                                                            name="contract_value"
                                                            class="form-control @error('contract_value') is-invalid @enderror"
                                                            value="{{ old('contract_value', $data->contract_value) }}"
                                                            placeholder="Contoh: 1000000 (tanpa simbol)"
                                                            aria-describedby="contract-addon">
                                                        @error('contract_value')
                                                            <div class="invalid-feedback d-block">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="fund_source">Sumber Dana</label>
                                                    <input type="text" id="fund_source" name="fund_source"
                                                        class="form-control @error('fund_source') is-invalid @enderror"
                                                        value="{{ old('fund_source', $data->fund_source) }}"
                                                        placeholder="Contoh: DBHCHT">
                                                    @error('fund_source')
                                                        <div class="invalid-feedback d-block">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="bast_number">Nomor BAST</label>
                                                    <input type="text" id="bast_number" name="bast_number"
                                                        class="form-control @error('bast_number') is-invalid @enderror"
                                                        value="{{ old('bast_number', $data->bast_number) }}"
                                                        placeholder="Contoh: BAST/7213/2025">
                                                    @error('bast_number')
                                                        <div class="invalid-feedback d-block">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions right">
                                        <a href="{{ route('dashboard.monev.finances.contracts.index') }}"
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
        <script src="{{ asset('app-assets/js/scripts/extensions/block-ui-custom.js') }}"></script>

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
