@props(['height' => null, 'width' => null, 'class' => null, 'style' => null])

@php
    $logo = request()->routeIs('ikli-survey.*')
        ? 'assets/images/logo-pupr-loteng.png'
        : 'assets/images/logo-sipraja.png';
@endphp

<img src="{{ asset($logo) }}" alt="Logo {{ config('app.name', 'Laravel') }} Dinas PUPR Kabupaten Lombok Tengah"
    @if ($height) height="{{ $height }}" @endif
    @if ($width) width="{{ $width }}" @endif
    @if ($class) class="{{ $class }}" @endif
    @if ($style) style="{{ $style }}" @endif>
