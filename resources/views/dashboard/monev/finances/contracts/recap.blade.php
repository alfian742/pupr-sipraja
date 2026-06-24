<x-app-layout>
    @php $pageTitle = 'Rekap' @endphp

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

                                <div class="card border-indigo">
                                    <div class="card-header bg-indigo">
                                        <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                            BIDANG
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table
                                                class="table-striped table-bordered table-custom table-align-middle table">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>No.</th>
                                                        <th>Bidang</th>
                                                        <th>Jumlah Kontrak</th>
                                                        <th>Anggaran</th>
                                                        <th>Nilai Kontrak</th>
                                                        <th>Realisasi</th>
                                                        <th>Saldo</th>
                                                        <th>%</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @forelse ($recapDepartments as $item)
                                                        <tr>
                                                            <td class="text-center">{{ $loop->iteration }}</td>
                                                            <td>
                                                                <div class="text-wrap-scroll">
                                                                    {{ $item->department_label }}
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $item->total_contracts_formatted }}</td>
                                                            <td class="text-right">
                                                                {{ $item->total_budget_value_formatted }}</td>
                                                            <td class="text-right">
                                                                {{ $item->total_contract_value_formatted }}</td>
                                                            <td class="text-right">
                                                                {{ $item->total_realization_formatted }}</td>
                                                            <td class="text-right">{{ $item->total_balance_formatted }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $item->realization_percentage_formatted }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="8" class="text-center">Tidak ada data yang
                                                                tersedia.</td>
                                                        </tr>
                                                    @endforelse

                                                    @if ($recapDepartments->count() > 0)
                                                        <tr class="font-weight-bold">
                                                            <td colspan="2" class="text-center">Total</td>
                                                            <td class="text-center">
                                                                {{ $departmentSummary->total_contracts_formatted }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $departmentSummary->total_budget_value_formatted }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $departmentSummary->total_contract_value_formatted }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $departmentSummary->total_realization_formatted }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $departmentSummary->total_balance_formatted }}</td>
                                                            <td class="text-right">
                                                                {{ $departmentSummary->realization_percentage_formatted }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border-indigo">
                                    <div class="card-header bg-indigo">
                                        <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                            SUMBER DANA
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table
                                                class="table-striped table-bordered table-custom table-align-middle table">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>No.</th>
                                                        <th>Sumber Dana</th>
                                                        <th>Jumlah Kontrak</th>
                                                        <th>Anggaran</th>
                                                        <th>Nilai Kontrak</th>
                                                        <th>Realisasi</th>
                                                        <th>Saldo</th>
                                                        <th>%</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @forelse ($recapFundSources as $item)
                                                        <tr>
                                                            <td class="text-center">{{ $loop->iteration }}</td>
                                                            <td>
                                                                <div class="text-wrap-scroll">
                                                                    {{ $item->fund_source_label }}
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $item->total_contracts_formatted }}</td>
                                                            <td class="text-right">
                                                                {{ $item->total_budget_value_formatted }}</td>
                                                            <td class="text-right">
                                                                {{ $item->total_contract_value_formatted }}</td>
                                                            <td class="text-right">
                                                                {{ $item->total_realization_formatted }}</td>
                                                            <td class="text-right">{{ $item->total_balance_formatted }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $item->realization_percentage_formatted }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="8" class="text-center">Tidak ada data yang
                                                                tersedia.</td>
                                                        </tr>
                                                    @endforelse

                                                    @if ($recapFundSources->count() > 0)
                                                        <tr class="font-weight-bold">
                                                            <td colspan="2" class="text-center">Total</td>
                                                            <td class="text-center">
                                                                {{ $fundSourceSummary->total_contracts_formatted }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $fundSourceSummary->total_budget_value_formatted }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $fundSourceSummary->total_contract_value_formatted }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $fundSourceSummary->total_realization_formatted }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $fundSourceSummary->total_balance_formatted }}</td>
                                                            <td class="text-right">
                                                                {{ $fundSourceSummary->realization_percentage_formatted }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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
