<x-app-layout>
    @php $pageTitle = 'Sampel Responden' @endphp

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
                                    <div class="font-weight-bold">
                                        Sumber Data: <a target="_blank"
                                            href="https://lomboktengahkab.bps.go.id/id/publication/2026/02/27/ddca45c85e684fc3e3efedb2/lombok-tengah-regency-in-figures-2026.html">BPS
                                            Kabupaten Lombok Tengah</a>
                                    </div>

                                    <div style="width: 15rem;">
                                        <select class="custom-select" name="formula" id="filter_formula"
                                            aria-label="formula">
                                            <option value="slovin">Rumus Slovin</option>
                                            <option value="krejcie-morgan">Rumus Krejcie & Morgan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 mb-2">
                                        <div id="slovinFormulaWrapper" class="p-2"
                                            style="border: 1px solid #e3ebf3;">
                                            <h2 class="font-weight-bold indigo text-uppercase mb-2 text-center">Rumus
                                                Slovin</h2>
                                            <math xmlns="http://www.w3.org/1998/Math/MathML" display="block">
                                                <mi>n</mi>
                                                <mo>=</mo>
                                                <mfrac>
                                                    <mi>N</mi>
                                                    <mrow>
                                                        <mn>1</mn>
                                                        <mo>+</mo>
                                                        <mi>N</mi>
                                                        <mo>&#x22C5;</mo>
                                                        <msup>
                                                            <mi>e</mi>
                                                            <mn>2</mn>
                                                        </msup>
                                                    </mrow>
                                                </mfrac>
                                            </math>
                                            <ul class="mt-2">
                                                <li><strong>n</strong> = ukuran sampel</li>
                                                <li><strong>N</strong> = ukuran populasi</li>
                                                <li><strong>e</strong> = margin of error (misal 0.05)</li>
                                            </ul>

                                            <hr class="my-2">

                                            <ul>
                                                <li><strong>N</strong> =
                                                    {{ number_format($totalPopulation, 0, ',', '.') }}</li>
                                                <li><strong>e</strong> = <span id="value_e">0.05</span></li>
                                            </ul>
                                        </div>
                                        <div id="krejcieMorganFormulaWrapper" class="p-2"
                                            style="border: 1px solid #e3ebf3;">
                                            <h2 class="font-weight-bold indigo text-uppercase mb-2 text-center">Rumus
                                                Krejcie &
                                                Morgan</h2>
                                            <math xmlns="http://www.w3.org/1998/Math/MathML" display="block">
                                                <mi>s</mi>
                                                <mo>=</mo>
                                                <mfrac>
                                                    <mrow>
                                                        <msup>
                                                            <mi>X</mi>
                                                            <mn>2</mn>
                                                        </msup>
                                                        <mo>&#x22C5;</mo>
                                                        <mi>N</mi>
                                                        <mo>&#x22C5;</mo>
                                                        <mi>P</mi>
                                                        <mo>&#x22C5;</mo>
                                                        <mrow>
                                                            <mn>1</mn>
                                                            <mo>-</mo>
                                                            <mi>P</mi>
                                                        </mrow>
                                                    </mrow>
                                                    <mrow>
                                                        <msup>
                                                            <mi>d</mi>
                                                            <mn>2</mn>
                                                        </msup>
                                                        <mo>&#x22C5;</mo>
                                                        <mrow>
                                                            <mi>N</mi>
                                                            <mo>-</mo>
                                                            <mn>1</mn>
                                                        </mrow>
                                                        <mo>+</mo>
                                                        <msup>
                                                            <mi>X</mi>
                                                            <mn>2</mn>
                                                        </msup>
                                                        <mo>&#x22C5;</mo>
                                                        <mi>P</mi>
                                                        <mo>&#x22C5;</mo>
                                                        <mrow>
                                                            <mn>1</mn>
                                                            <mo>-</mo>
                                                            <mi>P</mi>
                                                        </mrow>
                                                    </mrow>
                                                </mfrac>
                                            </math>
                                            <ul class="mt-2">
                                                <li><strong>s</strong> = ukuran sampel</li>
                                                <li><strong>N</strong> = ukuran populasi</li>
                                                <li><strong>P</strong> = proporsi populasi (biasanya 0.5)</li>
                                                <li><strong>d</strong> = margin of error</li>
                                                <li><strong>X²</strong> = nilai chi-square (misal 3.841 untuk 95%
                                                    confidence)</li>
                                            </ul>

                                            <hr class="my-2">

                                            <ul>
                                                <li><strong>N</strong> =
                                                    {{ number_format($totalPopulation, 0, ',', '.') }}</li>
                                                <li><strong>P</strong> = <span id="value_p">0.5</span></li>
                                                <li><strong>d</strong> = <span id="value_d">0.05</span></li>
                                                <li><strong>X²</strong> = <span id="value_x2">3.841</span></li>
                                            </ul>
                                        </div>

                                        <div class="form mt-2">
                                            <!-- Margin Error (e / d) -->
                                            <div class="form-group" id="margin_error_wrapper">
                                                <label for="margin_error">Margin of Error</label>
                                                <select class="custom-select" id="margin_error">
                                                    <option value="0.1">0.1 (10%)</option>
                                                    <option value="0.09">0.09 (9%)</option>
                                                    <option value="0.08">0.08 (8%)</option>
                                                    <option value="0.07">0.07 (7%)</option>
                                                    <option value="0.06">0.06 (6%)</option>
                                                    <option value="0.05" selected>0.05 (5%) → Standar</option>
                                                    <option value="0.04">0.04 (4%)</option>
                                                    <option value="0.03">0.03 (3%)</option>
                                                    <option value="0.02">0.02 (2%)</option>
                                                    <option value="0.01">0.01 (1%)</option>
                                                </select>
                                            </div>

                                            <!-- Proporsi P -->
                                            <div class="form-group" id="proportion_p_wrapper">
                                                <label for="proportion_p">Proporsi (P)</label>
                                                <select class="custom-select" id="proportion_p">
                                                    <option value="0.5" selected>0.5 → Maksimal Variasi</option>
                                                    <option value="0.4">0.4</option>
                                                    <option value="0.3">0.3</option>
                                                    <option value="0.2">0.2</option>
                                                    <option value="0.1">0.1</option>
                                                </select>
                                            </div>

                                            <!-- Confidence Level -->
                                            <div class="form-group" id="confidence_level_wrapper">
                                                <label for="confidence_level">Confidence Level</label>
                                                <select class="custom-select" id="confidence_level">
                                                    <option value="2.706">2.706 (90%)</option>
                                                    <option value="3.841" selected>3.841 (95%) → Standar</option>
                                                    <option value="6.635">6.635 (99%)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 mb-2">
                                        <div class="table-responsive">
                                            <table class="table-bordered table-striped table-align-middle w-100 table">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>No.</th>
                                                        <th>Nama Kecamatan</th>
                                                        <th>Jumlah Jiwa</th>
                                                        <th>Jumlah Sampel</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($districts as $district)
                                                        <tr>
                                                            <td class="text-right">{{ $loop->iteration }}</td>
                                                            <td>{{ $district->district_name }}</td>
                                                            <td class="text-right">
                                                                {{ number_format($district->resident_count, 0, ',', '.') }}
                                                            </td>
                                                            <td class="sample-count text-right"
                                                                data-id="{{ $district->id }}">
                                                                0
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                    <tr>
                                                        <th colspan="2" class="text-center">Total</th>
                                                        <th class="text-right">
                                                            {{ number_format($totalPopulation, 0, ',', '.') }}
                                                        </th>
                                                        <th class="text-right" id="totalSample">0</th>
                                                    </tr>
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
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const filterFormula = document.getElementById('filter_formula');
                const slovinWrapper = document.getElementById('slovinFormulaWrapper');
                const krejcieMorganWrapper = document.getElementById('krejcieMorganFormulaWrapper');

                const marginError = document.getElementById('margin_error');
                const proportionP = document.getElementById('proportion_p');
                const confidenceLevel = document.getElementById('confidence_level');

                const proportionPWrapper = document.getElementById('proportion_p_wrapper');
                const confidenceLevelWrapper = document.getElementById('confidence_level_wrapper');

                /* ==============================
                   RESET DEFAULT PARAMETER
                ============================== */
                function resetToDefault() {
                    marginError.value = "0.05";
                    proportionP.value = "0.5";
                    confidenceLevel.value = "3.841";
                    updateFormulaText();
                }

                /* ==============================
                   TOGGLE RUMUS & PARAMETER
                ============================== */
                function toggleFormula() {

                    const selected = filterFormula.value;

                    slovinWrapper.style.display =
                        selected === 'slovin' ? 'block' : 'none';

                    krejcieMorganWrapper.style.display =
                        selected === 'krejcie-morgan' ? 'block' : 'none';

                    if (selected === 'slovin') {
                        proportionPWrapper.style.display = 'none';
                        confidenceLevelWrapper.style.display = 'none';
                    } else {
                        proportionPWrapper.style.display = 'block';
                        confidenceLevelWrapper.style.display = 'block';
                    }
                }

                /* ==============================
                   UPDATE NILAI DI TAMPILAN RUMUS
                ============================== */
                function updateFormulaText() {

                    if (document.getElementById('value_e'))
                        document.getElementById('value_e').textContent = marginError.value;

                    if (document.getElementById('value_d'))
                        document.getElementById('value_d').textContent = marginError.value;

                    if (document.getElementById('value_p'))
                        document.getElementById('value_p').textContent = proportionP.value;

                    if (document.getElementById('value_x2'))
                        document.getElementById('value_x2').textContent = confidenceLevel.value;
                }

                /* ==============================
                   LOADING STATE
                ============================== */
                function showLoading() {
                    document.querySelectorAll('.sample-count').forEach(cell => {
                        cell.textContent = '...';
                    });

                    document.getElementById('totalSample').textContent = '...';
                }

                /* ==============================
                   FETCH DATA
                ============================== */
                function fetchFormula() {

                    const type = filterFormula.value;

                    let url = type === 'slovin' ?
                        "{{ route('ikli-survey.dashboard.questionnaire.respondent.slovin') }}" :
                        "{{ route('ikli-survey.dashboard.questionnaire.respondent.krejcie-morgan') }}";

                    showLoading();

                    fetch(
                            url +
                            '?margin_error=' + marginError.value +
                            '&p=' + proportionP.value +
                            '&confidence=' + confidenceLevel.value
                        )
                        .then(res => res.json())
                        .then(data => {

                            let total = 0;

                            data.distribution.forEach(item => {

                                const cell = document.querySelector(
                                    `.sample-count[data-id="${item.id}"]`
                                );

                                if (cell) {
                                    cell.textContent = Number(item.sample)
                                        .toLocaleString('id-ID');
                                }

                                total += item.sample;
                            });

                            document.getElementById('totalSample').textContent =
                                Number(total).toLocaleString('id-ID');

                        })
                        .catch(err => {

                            console.error(err);

                            document.querySelectorAll('.sample-count').forEach(cell => {
                                cell.textContent = 'Error';
                            });

                            document.getElementById('totalSample').textContent = 'Error';
                        });
                }

                /* ==============================
                   EVENT LISTENER
                ============================== */
                filterFormula.addEventListener('change', function() {
                    resetToDefault();
                    toggleFormula();
                    fetchFormula();
                });

                marginError.addEventListener('change', function() {
                    updateFormulaText();
                    fetchFormula();
                });

                proportionP.addEventListener('change', function() {
                    updateFormulaText();
                    fetchFormula();
                });

                confidenceLevel.addEventListener('change', function() {
                    updateFormulaText();
                    fetchFormula();
                });

                /* ==============================
                   INITIAL LOAD
                ============================== */
                resetToDefault();
                toggleFormula();
                updateFormulaText();
                fetchFormula();

            });
        </script>
    @endpush
</x-app-layout>
