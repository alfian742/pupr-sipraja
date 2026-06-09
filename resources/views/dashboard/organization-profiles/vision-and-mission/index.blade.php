<x-app-layout>
    @php $pageTitle = 'Visi dan Misi' @endphp

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
                                    action="{{ route('dashboard.organization-profiles.upsert-vision-and-mission') }}"
                                    method="POST" id="myForm">
                                    @csrf

                                    <div class="form-body">
                                        <div class="form-group">
                                            <label for="organization_about">
                                                Tentang Organisasi <span class="text-danger">*</span>
                                            </label>
                                            <textarea id="organization_about" class="form-control ckeditor @error('organization_about') is-invalid @enderror"
                                                placeholder="Masukkan ringkasan" name="organization_about">{{ old('organization_about', $organizationProfile->organization_about) }}</textarea>
                                            @error('organization_about')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="organization_summary">
                                                Ringkasan Visi dan Misi <span class="text-danger">*</span>
                                            </label>
                                            <textarea id="organization_summary" class="form-control ckeditor @error('organization_summary') is-invalid @enderror"
                                                placeholder="Masukkan ringkasan" name="organization_summary">{{ old('organization_summary', $organizationProfile->organization_summary) }}</textarea>
                                            @error('organization_summary')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="organization_vision">
                                                Visi <span class="text-danger">*</span>
                                            </label>
                                            <textarea id="organization_vision" class="form-control ckeditor @error('organization_vision') is-invalid @enderror"
                                                placeholder="Masukkan visi" name="organization_vision">{{ old('organization_vision', $organizationProfile->organization_vision) }}</textarea>
                                            @error('organization_vision')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="organization_mission">
                                                Misi <span class="text-danger">*</span>
                                            </label>
                                            <textarea id="organization_mission" class="form-control ckeditor @error('organization_mission') is-invalid @enderror"
                                                placeholder="Masukkan misi" name="organization_mission">{{ old('organization_mission', $organizationProfile->organization_mission) }}</textarea>
                                            @error('organization_mission')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-actions d-flex align-items-center justify-content-between">
                                        <div>
                                            Terakhir diperbarui:
                                            <span class="text-muted">
                                                {{ \Carbon\Carbon::parse($organizationProfile->profile_modified_at)->locale(app()->getLocale())->diffForHumans() }}
                                            </span>
                                            |
                                            <span class="text-muted">
                                                {{ $organizationProfile->profile_modified_by ? optional($organizationProfile->profileModifiedBy)->name : '-' }}
                                            </span>
                                        </div>
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
