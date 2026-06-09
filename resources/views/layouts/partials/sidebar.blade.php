<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="nav-item @if (request()->routeIs('dashboard.index')) active @endif">
                <a href="{{ route('dashboard.index') }}">
                    <i class="icon-grid"></i>
                    <span class="menu-title">{{ __('Dashboard') }}</span>
                </a>
            </li>

            <li class="nav-item @if (request()->routeIs('dashboard.about')) active @endif">
                <a href="{{ route('dashboard.about') }}">
                    <i class="icon-info"></i>
                    <span class="menu-title">Tentang</span>
                </a>
            </li>

            <li class="navigation-header">
                <span>Profil Organisasi</span>
                <i class="ft-more-horizontal ft-minus" data-toggle="tooltip" data-placement="right"
                    data-original-title="Profil Organisasi"></i>
            </li>

            <li class="nav-item @if (request()->routeIs('dashboard.organization-profiles.organization-structure')) active @endif">
                <a href="{{ route('dashboard.organization-profiles.organization-structure') }}">
                    <i class="icon-organization"></i>
                    <span class="menu-title">Struktur Organisasi</span>
                </a>
            </li>

            <li class="nav-item @if (request()->routeIs('dashboard.organization-profiles.vision-and-mission')) active @endif">
                <a href="{{ route('dashboard.organization-profiles.vision-and-mission') }}">
                    <i class="icon-eye"></i>
                    <span class="menu-title">Visi dan Misi</span>
                </a>
            </li>

            <li class="nav-item @if (request()->routeIs('dashboard.organization-profiles.departments.*')) active @endif">
                <a href="{{ route('dashboard.organization-profiles.departments.index') }}">
                    <i class="icon-layers"></i>
                    <span class="menu-title">Bidang</span>
                </a>
            </li>

            <li class="nav-item @if (request()->routeIs('dashboard.organization-profiles.personnel-profiles.*')) active @endif">
                <a href="{{ route('dashboard.organization-profiles.personnel-profiles.index') }}">
                    <i class="icon-people"></i>
                    <span class="menu-title">Profil Personel</span>
                </a>
            </li>

            <li class="navigation-header">
                <span>Indikator Kinerja</span>
                <i class="ft-more-horizontal ft-minus" data-toggle="tooltip" data-placement="right"
                    data-original-title="Indikator Kinerja"></i>
            </li>

            <li class="nav-item @if (request()->routeIs('dashboard.performance-indicators.main-indicators.*')) active @endif">
                <a href="{{ route('dashboard.performance-indicators.main-indicators.index') }}">
                    <i class="icon-target"></i>
                    <span class="menu-title">Indikator <br> Kinerja Utama</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="#">
                    <i class="icon-graph"></i>
                    <span class="menu-title">Indikator <br> Kinerja Daerah</span>
                </a>
                <ul class="menu-content">
                    <li class="@if (request()->routeIs('dashboard.performance-indicators.regional-indicators.geographical-and-demographic-aspects.*')) active @endif">
                        <a class="menu-item"
                            href="{{ route('dashboard.performance-indicators.regional-indicators.geographical-and-demographic-aspects.index') }}">Aspek
                            Geografi <br> dan Demografi</a>
                    </li>
                    <li class="@if (request()->routeIs('dashboard.performance-indicators.regional-indicators.regional-competitiveness-aspects.*')) active @endif">
                        <a class="menu-item"
                            href="{{ route('dashboard.performance-indicators.regional-indicators.regional-competitiveness-aspects.index') }}">Aspek
                            Daya Saing <br> Daerah</a>
                    </li>
                    <li class="@if (request()->routeIs('dashboard.performance-indicators.regional-indicators.key-performance-indicators.*')) active @endif">
                        <a class="menu-item"
                            href="{{ route('dashboard.performance-indicators.regional-indicators.key-performance-indicators.index') }}">Indikator
                            Kinerja Kunci</a>
                    </li>
                </ul>
            </li>

            {{-- <li class="nav-item">
                <a href="{{ route('dashboard.development') . '?page=' . urlencode('Indikator Program') }}">
                    <i class="icon-layers"></i>
                    <span class="menu-title">Indikator Program</span>
                </a>
            </li> --}}

            <li class="navigation-header">
                <span>Monev Tahun Berjalan</span>
                <i class="ft-more-horizontal ft-minus" data-toggle="tooltip" data-placement="right"
                    data-original-title="Monev Tahun Berjalan"></i>
            </li>

            <li class="nav-item">
                <a href="#">
                    <i class="icon-wallet"></i>
                    <span class="menu-title">Realisasi Keuangan</span>
                </a>
                <ul class="menu-content">
                    <li class="@if (request()->routeIs('dashboard.monev.finances.ls-payments.*')) active @endif">
                        <a class="menu-item" href="{{ route('dashboard.monev.finances.ls-payments.index') }}">Data
                            LS</a>
                    </li>
                    <li class="@if (request()->routeIs('dashboard.monev.finances.contracts.*')) active @endif">
                        <a class="menu-item" href="{{ route('dashboard.monev.finances.contracts.index') }}">Data
                            Kontrak</a>
                    </li>
                    <li class="@if (request()->routeIs('dashboard.monev.finances.realizations.*')) active @endif">
                        <a class="menu-item" href="{{ route('dashboard.monev.finances.realizations.index') }}">Data
                            Realisasi</a>
                    </li>
                </ul>
            </li>

            <li class="navigation-header">
                <span>Informasi Lainnya</span>
                <i class="ft-more-horizontal ft-minus" data-toggle="tooltip" data-placement="right"
                    data-original-title="Informasi Lainnya"></i>
            </li>

            <li class="nav-item">
                <a href="#">
                    <i class="icon-notebook"></i>
                    <span class="menu-title">Blog</span>
                </a>
                <ul class="menu-content">
                    <li class="@if (request()->routeIs('dashboard.blog.categories.*')) active @endif">
                        <a class="menu-item" href="{{ route('dashboard.blog.categories.index') }}">Kategori</a>
                    </li>
                    <li class="@if (request()->routeIs('dashboard.blog.articles.*')) active @endif">
                        <a class="menu-item" href="{{ route('dashboard.blog.articles.index') }}">Artikel</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item @if (request()->routeIs('dashboard.other-informations.faqs.*')) active @endif">
                <a href="{{ route('dashboard.other-informations.faqs.index') }}">
                    <i class="icon-question"></i>
                    <span class="menu-title">FAQ</span>
                </a>
            </li>

            <li class="nav-item @if (request()->routeIs('dashboard.other-informations.download-center.*')) active @endif">
                <a href="{{ route('dashboard.other-informations.download-center.index') }}">
                    <i class="icon-cloud-download"></i>
                    <span class="menu-title">Pusat Unduhan</span>
                </a>
            </li>

            @if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin')
                <li class="navigation-header">
                    <span>Pengaturan</span>
                    <i class="ft-more-horizontal ft-minus" data-toggle="tooltip" data-placement="right"
                        data-original-title="Pengaturan"></i>
                </li>

                <li class="nav-item @if (request()->routeIs('dashboard.hero-carousels.*')) active @endif">
                    <a href="{{ route('dashboard.hero-carousels.index') }}">
                        <i class="icon-picture"></i>
                        <span class="menu-title">Carousel</span>
                    </a>
                </li>

                <li class="nav-item @if (request()->routeIs('dashboard.other-informations.contact.*')) active @endif">
                    <a href="{{ route('dashboard.other-informations.contact.index') }}">
                        <i class="icon-earphones-alt"></i>
                        <span class="menu-title">Kontak dan <br> Pengaduan</span>
                    </a>
                </li>


                <li class="navigation-header">
                    <span>Pengguna</span>
                    <i class="ft-more-horizontal ft-minus" data-toggle="tooltip" data-placement="right"
                        data-original-title="Pengguna"></i>
                </li>

                <li class="nav-item @if (request()->routeIs('dashboard.users.*')) active @endif">
                    <a href="{{ route('dashboard.users.index') }}">
                        <i class="icon-user"></i>
                        <span class="menu-title">Kelola Pengguna</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
