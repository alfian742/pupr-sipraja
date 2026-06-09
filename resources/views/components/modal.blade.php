@props(['name', 'title' => null, 'maxWidth' => 'md', 'centered' => true, 'scrollable' => false, 'static' => false])

@php
    $sizeClass = match ($maxWidth) {
        'sm' => 'modal-sm',
        'md' => '',
        'lg' => 'modal-lg',
        'xl', '2xl' => 'modal-xl',
        default => '',
    };

    $centeredClass = $centered ? 'modal-dialog-centered' : '';
    $scrollableClass = $scrollable ? 'modal-dialog-scrollable' : '';
@endphp

<div class="modal fade" id="modal-{{ $name }}" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel-{{ $name }}" aria-hidden="true"
    @if ($static) data-backdrop="static"
        data-keyboard="false" @endif>
    <div class="modal-dialog {{ $sizeClass }} {{ $centeredClass }} {{ $scrollableClass }}" role="document">
        <div class="modal-content">

            @if ($title)
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel-{{ $name }}">
                        {{ $title }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="modal-body">
                {{ $slot }}
            </div>

        </div>
    </div>
</div>
