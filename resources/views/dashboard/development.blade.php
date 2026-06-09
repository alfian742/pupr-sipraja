<x-app-layout>
    @php $pageTitle = urldecode(request('page')) @endphp

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
                                <div class="py-5 text-center">
                                    <i class="fa fa-code fa-4x text-muted mb-4 opacity-50"></i>
                                    <h4 class="text-muted font-weight-bold mb-2">Halaman Dalam Pengembangan</h4>
                                    <p class="text-muted font-italic">
                                        Maaf, halaman <span class="font-weight-bold">{{ $pageTitle }}</span> masih
                                        dalam proses pengembangan. Silakan kembali lagi nanti.
                                    </p>
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
