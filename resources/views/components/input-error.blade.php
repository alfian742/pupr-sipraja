@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'list-unstyled text-danger small mb-0']) }}>
        @foreach ((array) $messages as $message)
            <li>
                <i class="fa fa-exclamation-circle"></i>
                {{ $message }}
            </li>
        @endforeach
    </ul>
@endif
