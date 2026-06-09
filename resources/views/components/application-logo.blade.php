@props(['height' => null, 'width' => null, 'class' => null, 'style' => null])

<img src="{{ asset('public/assets/images/logo-sipraja.png') }}"
    alt="Logo {{ config('app.name', 'Laravel') }} Dinas PUPR Kabupaten Lombok Tengah"
    @if ($height) height="{{ $height }}" @endif
    @if ($width) width="{{ $width }}" @endif
    @if ($class) class="{{ $class }}" @endif
    @if ($style) style="{{ $style }}" @endif>
