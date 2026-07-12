<x-app-layout>
    @php $pageTitle = 'Monitoring Wilayah';    @endphp

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

                                {{-- Semua Data --}}
                                <div
                                    class="bs-callout-indigo callout-border-left callout-bordered rounded-0 bg-transparent p-1 mb-2">
                                    <div class="d-flex flex-lg-row justify-content-between align-items-center flex-wrap"
                                        style="gap: .5rem">
                                        <div>
                                            <h6 class="font-weight-bold text-dark text-uppercase mb-0">Semua Data</h6>
                                            <small class="text-muted">Total seluruh responden yang mengisi
                                                kuesioner.</small>
                                        </div>

                                        <h3 class="font-weight-bold text-dark mb-0">
                                            {{ number_format($countAll, 0, ',', '.') }} Responden
                                        </h3>
                                    </div>
                                </div>

                                {{-- Accordion Per Kecamatan --}}
                                <div class="row">
                                    <div class="col-md-12">

                                        <div id="accordionDistrict">

                                            @forelse ($countDistrict as $district)
                                                @php
                                                    $districtId = 'district-' . $loop->iteration;
                                                    $villages = $countVillage->get($district->district, collect());
                                                @endphp

                                                <div class="card mb-1">
                                                    <div class="card-header py-0 bg-light"
                                                        id="heading-{{ $districtId }}">
                                                        <h5 class="mb-0">
                                                            <button
                                                                class="btn btn-link btn-block text-left font-weight-bold text-dark text-decoration-none"
                                                                type="button" data-toggle="collapse"
                                                                data-target="#collapse-{{ $districtId }}"
                                                                aria-expanded="false"
                                                                aria-controls="collapse-{{ $districtId }}">
                                                                <div class="d-flex flex-lg-row justify-content-between align-items-center flex-wrap"
                                                                    style="gap: .5rem">
                                                                    <span class="text-uppercase">
                                                                        <i
                                                                            class="fa fa-chevron-right js-chevron mr-2"></i>
                                                                        <i class="fa fa-map-marker mr-1"></i>
                                                                        Kecamatan {{ $district->district }}
                                                                    </span>

                                                                    <span class="badge bg-indigo badge-pill">
                                                                        {{ number_format($district->total, 0, ',', '.') }}
                                                                        Responden
                                                                    </span>
                                                                </div>
                                                            </button>
                                                        </h5>
                                                    </div>

                                                    <div id="collapse-{{ $districtId }}"
                                                        class="collapse js-monitor-collapse"
                                                        aria-labelledby="heading-{{ $districtId }}">

                                                        <div class="card-body p-0">
                                                            <div class="table-responsive">
                                                                <table
                                                                    class="table-striped table-bordered table-custom table-align-middle table">
                                                                    <thead>
                                                                        <tr class="text-center">
                                                                            <th style="width: 60px;"
                                                                                class="text-center">No.</th>
                                                                            <th>Kelurahan/Desa</th>
                                                                            <th style="width: 160px;"
                                                                                class="text-center">
                                                                                Jumlah Responden
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @forelse ($villages as $village)
                                                                            <tr>
                                                                                <td class="text-right">
                                                                                    {{ $loop->iteration }}
                                                                                </td>
                                                                                <td class="text-uppercase">
                                                                                    {{ $village->village }}
                                                                                </td>
                                                                                <td class="text-right font-weight-bold">
                                                                                    {{ number_format($village->total, 0, ',', '.') }}
                                                                                </td>
                                                                            </tr>
                                                                        @empty
                                                                            <tr>
                                                                                <td colspan="3"
                                                                                    class="text-center text-muted">
                                                                                    Belum ada data kelurahan/desa.
                                                                                </td>
                                                                            </tr>
                                                                        @endforelse
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="alert alert-light border text-center">
                                                    Belum ada data monitoring.
                                                </div>
                                            @endforelse
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
        <script>
            $(document).on('shown.bs.collapse', '.js-monitor-collapse', function(e) {
                if (e.target !== this) {
                    return;
                }

                var targetId = this.id;

                $('[data-target="#' + targetId + '"], [href="#' + targetId + '"]')
                    .attr('aria-expanded', 'true')
                    .find('.js-chevron')
                    .removeClass('fa-chevron-right')
                    .addClass('fa-chevron-down');
            });

            $(document).on('hidden.bs.collapse', '.js-monitor-collapse', function(e) {
                if (e.target !== this) {
                    return;
                }

                var targetId = this.id;

                $('[data-target="#' + targetId + '"], [href="#' + targetId + '"]')
                    .attr('aria-expanded', 'false')
                    .find('.js-chevron')
                    .removeClass('fa-chevron-down')
                    .addClass('fa-chevron-right');
            });
        </script>
    @endpush
</x-app-layout>
