<x-app-layout>
    @php $pageTitle = 'Detail Kontrak' @endphp

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
                                <div class="d-flex flex-md-row justify-content-between align-items-center mb-2 flex-wrap"
                                    style="gap: 1rem">
                                    <a href="{{ route('dashboard.monev.finances.contracts.index') }}"
                                        class="btn btn-secondary">
                                        <i class="fa fa-arrow-left"></i> <span class="d-none d-md-inline">Kembali</span>
                                    </a>
                                </div>

                                <div
                                    class="bs-callout-secondary callout-border-left callout-bordered rounded-0 mb-2 bg-transparent">
                                    <div class="table-responsive">
                                        <table class="table-borderless table-custom table-align-middle mb-0 table">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 150px">Nomor Kontrak</td>
                                                    <td>:</td>
                                                    <td class="font-weight-bold">{{ $data->contract_number ?? '-' }}
                                                    </td>
                                                    <td style="width: 150px">Persentase</td>
                                                    <td>:</td>
                                                    <td class="font-weight-bold">
                                                        {{ $data->realization_percentage ?? '0,00' }} %</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px">Nilai Kontrak</td>
                                                    <td>:</td>
                                                    <td class="font-weight-bold">
                                                        {{ $data->contract_value_formatted ?? '-' }}</td>
                                                    <td style="width: 150px">Retensi (Nilai Kontrak ✖ 5,00 %)</td>
                                                    <td>:</td>
                                                    <td class="font-weight-bold">
                                                        {{ $data->retention_value_formatted ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px">Saldo</td>
                                                    <td>:</td>
                                                    <td class="font-weight-bold">
                                                        {{ $data->balance_value_formatted ?? '-' }}
                                                    </td>
                                                    <td style="width: 150px">Total Realisasi</td>
                                                    <td>:</td>
                                                    <td class="font-weight-bold">
                                                        {{ $data->total_realization_formatted ?? '-' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table-striped table-bordered table-custom table-align-middle table">
                                        <thead>
                                            <tr class="text-center">
                                                <th>No.</th>
                                                <th>Tanggal SP2D</th>
                                                <th>Nomor Kontrak</th>
                                                <th>Nomor SP2D</th>
                                                <th>Uraian Pekerjaan</th>
                                                <th>Realisasi</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($realizations as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->sp2d_date }}</td>
                                                    <td>
                                                        <div class="text-wrap-scroll">
                                                            {{ $item->contract_number }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-wrap-scroll">
                                                            {{ $item->sp2d_number }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-wrap-scroll">
                                                            {{ $item->document_description }}
                                                        </div>
                                                    </td>
                                                    <td class="text-right">{{ $item->sp2d_value_formatted }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">Tidak ada data yang tersedia.
                                                    </td>
                                                </tr>
                                            @endforelse
                                            <tr>
                                                <td colspan="5" class="font-weight-bold text-center">Total</td>
                                                <td class="text-right">{{ $data->total_realization_formatted ?? '-' }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
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
    @endpush
</x-app-layout>
