@props(['status'])

@if ($status)
    <div style="position:fixed; top:20px; left:50%; transform:translateX(-50%); z-index:1080;">
        <div id="toastSession" class="alert alert-success fade shadow" role="alert"
            style="min-width:320px; max-width:360px; display:none;">

            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <i class="fa fa-check-circle mr-1"></i>
                    <strong>Berhasil!</strong>
                    <div class="small mt-1">{{ $status }}</div>
                </div>

                <button type="button" class="close ml-3" id="closeToast">
                    <span>&times;</span>
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(function() {
                const $toast = $('#toastSession');

                // Show animation
                $toast
                    .css('display', 'block')
                    .addClass('show');

                // Auto hide 10 detik
                setTimeout(function() {
                    hideToast();
                }, 10000);

                // Manual close
                $('#closeToast').on('click', function() {
                    hideToast();
                });

                function hideToast() {
                    $toast.removeClass('show');

                    setTimeout(function() {
                        $toast.fadeOut(300);
                    }, 300);
                }
            });
        </script>
    @endpush
@endif
