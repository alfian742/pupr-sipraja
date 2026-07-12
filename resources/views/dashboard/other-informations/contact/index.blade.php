<x-app-layout>
    @php $pageTitle = 'Kontak dan Pengaduan' @endphp

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
                                @include('components.alert')

                                <form class="form"
                                    action="{{ route('dashboard.other-informations.contact.update', $contact->id) }}"
                                    method="POST" id="myForm">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card border-indigo">
                                                    <div class="card-header bg-indigo">
                                                        <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                            Kontak Utama
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="email">Email <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" id="email"
                                                                class="form-control @error('email') is-invalid @enderror"
                                                                placeholder="Contoh: john.doe@gmail.com" name="email"
                                                                value="{{ old('email', $contact->email) }}">
                                                            @error('email')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="phone_number">Telepon <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="tel" id="phone_number"
                                                                class="form-control @error('phone_number') is-invalid @enderror"
                                                                placeholder="Contoh: (0370) 654393" name="phone_number"
                                                                value="{{ old('phone_number', $contact->phone_number) }}">
                                                            @error('phone_number')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="whatsapp_number">Nomor HP/WA</label>
                                                            <input type="number" id="whatsapp_number"
                                                                class="form-control @error('whatsapp_number') is-invalid @enderror"
                                                                placeholder="Contoh: 081234567890"
                                                                name="whatsapp_number"
                                                                value="{{ old('whatsapp_number', $contact->whatsapp_number) }}">

                                                            @error('whatsapp_number')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card border-indigo">
                                                    <div class="card-header bg-indigo">
                                                        <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                            Kontak Alternatif (Opsional)
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="email">Email Alternatif</label>
                                                            <input type="text" id="email"
                                                                class="form-control @error('email_alternative') is-invalid @enderror"
                                                                placeholder="Contoh: john.doe@gmail.com"
                                                                name="email_alternative"
                                                                value="{{ old('email_alternative', $contact->email_alternative) }}">
                                                            @error('email_alternative')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="phone_number_alternative">Telepon
                                                                Alternatif</label>
                                                            <input type="tel" id="phone_number_alternative"
                                                                class="form-control @error('phone_number_alternative') is-invalid @enderror"
                                                                placeholder="Contoh: (0370) 654393"
                                                                name="phone_number_alternative"
                                                                value="{{ old('phone_number_alternative', $contact->phone_number_alternative) }}">
                                                            @error('phone_number_alternative')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="whatsapp_number_alternative">Nomor HP/WA
                                                                Alternatif</label>
                                                            <input type="number" id="whatsapp_number_alternative"
                                                                class="form-control @error('whatsapp_number_alternative') is-invalid @enderror"
                                                                placeholder="Contoh: 081234567890"
                                                                name="whatsapp_number_alternative"
                                                                value="{{ old('whatsapp_number_alternative', $contact->whatsapp_number_alternative) }}">

                                                            @error('whatsapp_number_alternative')
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card border-indigo">
                                                    <div class="card-header bg-indigo">
                                                        <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                            Waktu Operasional & Alamat Kantor
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="operational_time">Waktu Operasional <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" id="operational_time"
                                                                class="form-control @error('operational_time') is-invalid @enderror"
                                                                placeholder="Contoh: 08:00-16:00 WITA"
                                                                name="operational_time"
                                                                value="{{ old('operational_time', $contact->operational_time) }}">
                                                            @error('operational_time')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="address">
                                                                Alamat <span class="text-danger">*</span>
                                                            </label>
                                                            <textarea id="address" class="form-control @error('address') is-invalid @enderror"
                                                                placeholder="Contoh: Jl. Merdeka No. 123" name="address">{{ old('address', $contact->address) }}</textarea>
                                                            @error('address')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="d-flex align-items-center justify-content-between mb-1"
                                                                style="gap: 0.5rem">
                                                                <label for="google_maps_embed" class="mb-0">Sematkan
                                                                    Peta Google Maps (Opsional)</label>

                                                                <button class="btn btn-sm btn-info" type="button"
                                                                    data-toggle="collapse" data-target="#guideWrapper"
                                                                    aria-expanded="false"
                                                                    aria-controls="guideWrapper">
                                                                    <i class="fa fa-info-circle"></i>
                                                                    <span class="d-none d-md-inline"> Panduan</span>
                                                                </button>
                                                            </div>
                                                            <textarea id="google_maps_embed" class="form-control @error('google_maps_embed') is-invalid @enderror"
                                                                name="google_maps_embed">{{ old('google_maps_embed', $contact->google_maps_embed) }}</textarea>
                                                            @error('google_maps_embed')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="multi-collapse collapse" id="guideWrapper">
                                                            <div
                                                                class="bs-callout-info callout-border-left callout-bordered border-info mb-2 overflow-hidden rounded bg-transparent">
                                                                <div class="media align-items-stretch">
                                                                    <div
                                                                        class="media-left d-flex align-items-center bg-info position-relative callout-arrow-left p-2">
                                                                        <i
                                                                            class="fa fa-info-circle fa-lg white font-medium-5"></i>
                                                                    </div>
                                                                    <div class="media-body p-1 pl-2">
                                                                        <h4 class="info mb-2">Panduan</h4>

                                                                        <ul>
                                                                            <li>Buka <a
                                                                                    href="https://www.google.com/maps"
                                                                                    target="_blank">Google Maps</a> dan
                                                                                cari lokasi yang ingin disematkan.</li>
                                                                            <li>Klik tombol <strong>Bagikan</strong>
                                                                                atau <strong>Share</strong> pada menu
                                                                                lokasi.</li>
                                                                            <li>Pilih opsi <strong>Sematkan
                                                                                    peta</strong> atau <strong>Embed a
                                                                                    map</strong>.</li>
                                                                            <li>Salin kode HTML yang muncul pada kotak
                                                                                embed.</li>
                                                                            <li>Tempelkan kode HTML tersebut di halaman
                                                                                website atau kolom input yang tersedia.
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @if (!empty($contact->google_maps_embed))
                                                            <div class="embed-responsive embed-responsive-16by9">
                                                                {!! $contact->google_maps_embed !!}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card border-indigo">
                                                    <div class="card-header bg-indigo">
                                                        <h5 class="font-weight-bold text-uppercase mb-0 text-white">
                                                            Media Sosial (Opsional)
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="facebook_url">Facebook</label>
                                                            <input type="url" id="facebook_url"
                                                                class="form-control @error('facebook_url') is-invalid @enderror"
                                                                placeholder="Contoh: https://www.facebook.com/"
                                                                name="facebook_url"
                                                                value="{{ old('facebook_url', $contact->facebook_url) }}">
                                                            @error('facebook_url')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="instagram_url">Instagram</label>
                                                            <input type="url" id="instagram_url"
                                                                class="form-control @error('instagram_url') is-invalid @enderror"
                                                                placeholder="Contoh: https://www.instagram.com/"
                                                                name="instagram_url"
                                                                value="{{ old('instagram_url', $contact->instagram_url) }}">
                                                            @error('instagram_url')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="twitter_url">X/Twitter</label>
                                                            <input type="url" id="twitter_url"
                                                                class="form-control @error('twitter_url') is-invalid @enderror"
                                                                placeholder="Contoh: https://www.x.com/"
                                                                name="twitter_url"
                                                                value="{{ old('twitter_url', $contact->twitter_url) }}">
                                                            @error('twitter_url')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="tiktok_url">TikTok</label>
                                                            <input type="url" id="tiktok_url"
                                                                class="form-control @error('tiktok_url') is-invalid @enderror"
                                                                placeholder="Contoh: https://www.tiktok.com/"
                                                                name="tiktok_url"
                                                                value="{{ old('tiktok_url', $contact->tiktok_url) }}">
                                                            @error('tiktok_url')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="youtube_url">YouTube</label>
                                                            <input type="url" id="youtube_url"
                                                                class="form-control @error('youtube_url') is-invalid @enderror"
                                                                placeholder="Contoh: https://www.youtube.com/"
                                                                name="youtube_url"
                                                                value="{{ old('youtube_url', $contact->youtube_url) }}">
                                                            @error('youtube_url')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions d-flex align-items-center justify-content-between">
                                        <div>
                                            Terakhir diperbarui:
                                            <span class="text-muted">
                                                {{ \Carbon\Carbon::parse($contact->updated_at)->locale(app()->getLocale())->diffForHumans() }}
                                            </span>
                                            |
                                            <span class="text-muted">
                                                {{ $contact->modified_by ? optional($contact->modifiedBy)->name : '-' }}
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
        <script src="{{ asset('app-assets/js/scripts/extensions/block-ui-custom.js') }}"></script>
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
