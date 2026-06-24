function blockWholePage(messageText) {
    $.blockUI({
        message: `<div class="semibold"><span class="ft-refresh-cw icon-spin"></span>&nbsp; ${messageText}</div>`,
        fadeIn: 200,
        overlayCSS: {
            backgroundColor: '#fff',
            opacity: 0.8,
            cursor: 'wait'
        },
        css: {
            border: 0,
            padding: '10px 15px',
            color: '#fff',
            width: 'auto',
            backgroundColor: '#333',
            borderRadius: '4px',

            // POSITION CENTER
            position: 'fixed',
            top: '50%',
            left: '50%',
            transform: 'translate(-50%, -50%)',
        }
    });
}