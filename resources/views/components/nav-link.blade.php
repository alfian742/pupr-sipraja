@props(['active'])

@php
    $classes = $active ?? false ? 'nav-link active font-weight-medium' : 'nav-link text-muted';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
