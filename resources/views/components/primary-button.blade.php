<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-indigo']) }}>
    {{ $slot }}
</button>
