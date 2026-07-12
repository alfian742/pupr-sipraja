<x-app-layout>
    @php $pageTitle = 'Struktur Organisasi' @endphp

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

                                <div class="row">
                                    <div class="col-12">
                                        <form action="{{ route('dashboard.organization-profiles.upsert-structure') }}"
                                            method="POST" class="w-100" id="myForm">
                                            @csrf

                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <button class="btn btn-info" type="button"
                                                            data-toggle="collapse" data-target="#guideWrapper"
                                                            aria-expanded="false" aria-controls="guideWrapper">
                                                            <i class="fa fa-info-circle"></i>
                                                            <span class="d-none d-md-inline"> Panduan</span>
                                                        </button>
                                                    </div>

                                                    <input type="url"
                                                        class="form-control @error('organization_structure') is-invalid @enderror"
                                                        name="organization_structure" id="organization_structure"
                                                        placeholder="Contoh: https://drive.google.com/file/d/1JlQJ05zoYfBWzRLEc2lAesm9AUQ9pA25/preview"
                                                        value="{{ $organizationProfile->organization_structure ?? '' }}"
                                                        aria-label="Link Google Drive">

                                                    <div class="input-group-append">
                                                        <button class="btn btn-indigo" type="submit" id="btnSubmit"
                                                            disabled>
                                                            Perbarui
                                                        </button>
                                                    </div>
                                                </div>

                                                @error('organization_structure')
                                                    <div class="invalid-feedback d-block">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-12">
                                        <div class="multi-collapse collapse" id="guideWrapper">
                                            <div
                                                class="bs-callout-info callout-border-left callout-bordered border-info mb-2 overflow-hidden rounded bg-transparent">
                                                <div class="media align-items-stretch">
                                                    <div
                                                        class="media-left d-flex align-items-center bg-info position-relative callout-arrow-left p-2">
                                                        <i class="fa fa-info-circle fa-lg white font-medium-5"></i>
                                                    </div>
                                                    <div class="media-body p-1 pl-2">
                                                        <h4 class="info mb-2">Panduan</h4>

                                                        <ul>
                                                            <li>Link struktur organisasi harus berasal dari Google
                                                                Drive.</li>
                                                            <li>Setelah mengunggah file ke Google Drive, pastikan file
                                                                tersebut
                                                                dapat
                                                                diakses
                                                                publik.</li>
                                                            <li>Salin link file Google Drive dan tempelkan di kolom
                                                                input yang tersedia.
                                                            </li>
                                                            <li>Tekan tombol "Perbarui" untuk menyimpan perubahan.</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($organizationProfile->organization_structure)
                                    <div class="mb-2">
                                        Terakhir diperbarui:
                                        <span class="text-muted">
                                            {{ \Carbon\Carbon::parse($organizationProfile->organization_structure_modified_at)->locale(app()->getLocale())->diffForHumans() }}
                                        </span>
                                        |
                                        <span class="text-muted">
                                            {{ $organizationProfile->organization_structure_modified_by ? optional($organizationProfile->structureModifiedBy)->name : '-' }}
                                        </span>
                                    </div>
                                    <div class="embed-responsive embed-responsive-16by9 rounded border">
                                        <iframe class="embed-responsive-item"
                                            src="{{ $organizationProfile->organization_structure }}" allow="autoplay"
                                            style="border:0;" allowfullscreen>
                                        </iframe>
                                    </div>
                                @else
                                    <div class="py-5 text-center">
                                        <i class="fas fa-sitemap fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">
                                            Struktur organisasi belum tersedia.
                                        </p>
                                    </div>
                                @endif
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
                const form = document.getElementById("myForm");
                const btnSubmit = document.getElementById("btnSubmit");
                const input = document.getElementById('organization_structure');

                if (!form || !btnSubmit || !input) return;

                const initialValue = input.value.trim();

                // Handle enable / disable button
                input.addEventListener('input', function() {
                    if (
                        input.value.trim() === initialValue ||
                        input.value.trim() === ''
                    ) {
                        btnSubmit.disabled = true;
                    } else {
                        btnSubmit.disabled = false;
                    }
                });

                // Handle submit dengan blocking UI
                btnSubmit.addEventListener('click', function(e) {
                    if (btnSubmit.disabled) {
                        e.preventDefault();
                        return;
                    }

                    e.preventDefault();

                    blockWholePage("Mohon tunggu...");

                    setTimeout(function() {
                        form.submit();
                    }, 300);
                });
            });
        </script>
    @endpush
</x-app-layout>
