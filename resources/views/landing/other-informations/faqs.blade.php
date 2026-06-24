<x-guest-layout>
    @php $pageTitle = 'Pertanyaan yang Sering Diajukan (FAQ)'; @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <section id="page-section">
        <div class="row justify-content-center">
            <div class="col-lg-7 mb-5 text-center">
                <p class="text-dark">
                    Temukan jawaban atas pertanyaan yang sering diajukan masyarakat terkait layanan
                    publik dan berbagai informasi lainnya di {{ config('app.subname', 'Laravel') }}.
                </p>
            </div>
        </div>

        <div class="row justify-content-center g-4">
            <div class="col-lg-7">
                <form action="{{ route('other-informations.faqs') }}" method="GET">
                    <div class="input-group input-group-lg">
                        <input type="search" name="search" class="form-control rounded-start" placeholder="Cari..."
                            value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary rounded-end" type="submit">
                            <i class="fa fa-search me-2"></i> Cari
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-lg-7">
                <div class="accordion" id="faqAccordion">
                    @forelse ($faqs as $faq)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHeading{{ $loop->iteration }}">
                                <button class="accordion-button text-secondary fw-bold bg-white" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq{{ $loop->iteration }}"
                                    aria-expanded="true" aria-controls="faq{{ $loop->iteration }}">
                                    {{ $faq->faq_question }}
                                </button>
                            </h2>

                            <div id="faq{{ $loop->iteration }}" class="accordion-collapse show collapse"
                                aria-labelledby="faqHeading{{ $loop->iteration }}">
                                <div class="accordion-body text-dark">
                                    {!! $faq->faq_answer !!}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-5 text-center">
                            <i class="fa fa-question-circle fa-4x text-muted mb-4 opacity-50"></i>
                            <h4 class="text-muted fw-bold mb-2">FAQ Belum Tersedia</h4>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-7">
                {{ $faqs->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            .accordion-button {
                border: none !important;
                box-shadow: none !important;
                background-color: white !important;
            }
        </style>
    @endpush

    @push('scripts')
    @endpush
</x-guest-layout>
