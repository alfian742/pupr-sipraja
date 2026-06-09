@props(['value'])

<label {{ $attributes->merge(['class' => 'form-label font-weight-medium']) }}>
    {{ $value ?? $slot }}
</label>
