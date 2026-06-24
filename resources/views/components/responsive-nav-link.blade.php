@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'list-group-item list-group-item-action active font-weight-medium'
            : 'list-group-item list-group-item-action';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
