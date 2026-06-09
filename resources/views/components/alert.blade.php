@php
    // Tentukan prioritas alert
    $types = ['success', 'warning', 'error'];
    $alertType = null;
    $alertMessage = null;

    foreach ($types as $type) {
        if (session($type)) {
            $alertType = $type === 'error' ? 'danger' : $type; // Bootstrap 4 pakai 'danger'
            $alertMessage = session($type);
            break; // ambil yang pertama muncul
        }
    }
@endphp

@if ($alertMessage)
    <div class="alert alert-{{ $alertType }} alert-dismissible fade show mb-4" role="alert">
        {{ $alertMessage }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
