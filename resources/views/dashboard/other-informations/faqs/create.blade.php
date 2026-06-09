<x-app-layout>
    @php $pageTitle = 'Tambah FAQ' @endphp

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

                                <form class="form" action="{{ route('dashboard.other-informations.faqs.store') }}"
                                    method="POST" id="myForm">
                                    @csrf

                                    <div class="form-body">
                                        <div class="form-group">
                                            <label for="faq_question">
                                                Pertanyaan <span class="text-danger">*</span>
                                            </label>
                                            <textarea id="faq_question" class="form-control @error('faq_question') is-invalid @enderror"
                                                placeholder="Masukkan pertanyaan" name="faq_question">{{ old('faq_question') }}</textarea>
                                            @error('faq_question')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="faq_answer">
                                                Jawaban <span class="text-danger">*</span>
                                            </label>
                                            <textarea id="faq_answer" class="form-control ckeditor @error('faq_answer') is-invalid @enderror"
                                                placeholder="Masukkan jawaban" name="faq_answer">{{ old('faq_answer') }}</textarea>
                                            @error('faq_answer')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-actions right">
                                        <a href="{{ route('dashboard.other-informations.faqs.index') }}"
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
        <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
        <script src="{{ asset('public/app-assets/js/scripts/ckeditor-config.js') }}"></script>

        <script src="{{ asset('public/app-assets/js/scripts/extensions/block-ui-custom.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
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
