@php
    // Urutan prioritas alert
    $types = ['success', 'warning', 'error', 'info'];
    $alertType = null;
    $alertMessage = null;

    foreach ($types as $type) {
        if (session($type)) {
            $alertType = $type;
            $alertMessage = session($type);
            break;
        }
    }
@endphp

@if ($alertMessage)
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            let iconType = "{{ $alertType }}";

            let titleText = "Notifikasi";

            if (iconType === "success") {
                titleText = "Berhasil";
            } else if (iconType === "warning") {
                titleText = "Perhatian";
            } else if (iconType === "error") {
                titleText = "Terjadi Kesalahan";
            } else if (iconType === "info") {
                titleText = "Informasi";
            }

            swal({
                title: titleText,
                text: "{{ $alertMessage }}",
                icon: iconType,
                button: "OK",
                timer: 3000,
            });

        });
    </script>
@endif
