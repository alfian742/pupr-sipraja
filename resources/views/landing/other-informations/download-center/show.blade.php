<x-guest-layout>
    @php $pageTitle = $data->document_title; @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <section id="page-section">
        <div class="row justify-content-center g-4">
            <div class="col-lg-7">
                @php
                    // Tentukan siapa yang memodifikasi
                    $user = optional($data->modifiedBy)->name ?? '-';

                    // Format tanggal dengan locale aplikasi
                    $createdAt = \Carbon\Carbon::parse($data->created_at)
                        ->locale(app()->getLocale())
                        ->translatedFormat('d F Y H:i');

                    $updatedAt = \Carbon\Carbon::parse($data->updated_at)
                        ->locale(app()->getLocale())
                        ->translatedFormat('d F Y H:i');
                @endphp

                <div class="table-responsive mb-4">
                    <table class="table-borderless table-sm mb-0 table">
                        <tbody>
                            <tr>
                                <th class="text-nowrap" width="140">
                                    @if ($data->created_at->eq($data->updated_at))
                                        Ditambahkan pada
                                    @else
                                        Diperbarui pada
                                    @endif
                                </th>
                                <td width="10">:</td>
                                <td>
                                    {{ $data->created_at->eq($data->updated_at) ? $createdAt : $updatedAt }}
                                </td>
                            </tr>
                            <tr>
                                <th>Oleh</th>
                                <td>:</td>
                                <td>{{ $user }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Dokumen</th>
                                <td>:</td>
                                <td>{{ $data->document_type ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mb-4">
                    {!! $data->description ?? '' !!}
                </div>

                @if (!empty($data->document_url))
                    <a href="{{ $data->document_url }}" class="btn btn-secondary rounded-pill" target="_blank">
                        <i class="fa-solid fa-up-right-from-square me-1"></i> Klik Untuk Mengunduh Dokumen
                    </a>
                @endif
            </div>
            @if (!empty($data->document_url) && str_contains($data->document_url, 'drive.google.com'))
                <div class="col-lg-5">
                    <div class="ratio ratio-16x9 rounded border">
                        <iframe src="{{ $data->document_url }}" style="border:0;" allow="autoplay; fullscreen"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @push('styles')
    @endpush

    @push('scripts')
    @endpush
</x-guest-layout>
