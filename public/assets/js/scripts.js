(function (window, undefined) {
  'use strict';

  /*
  NOTE:
  ------
  PLACE HERE YOUR OWN JAVASCRIPT CODE IF NEEDED
  WE WILL RELEASE FUTURE UPDATES SO IN ORDER TO NOT OVERWRITE YOUR JAVASCRIPT CODE PLEASE CONSIDER WRITING YOUR SCRIPT HERE.  */

  // =========================
  // SAFE BLOCK UI
  // =========================
  function blockWholePage(messageText) {
    if (typeof jQuery === 'undefined' || !jQuery.blockUI) {
      console.warn('jQuery or blockUI is not loaded.');
      return;
    }

    jQuery.blockUI({
      message:
        '<div class="semibold">' +
        '<span class="ft-refresh-cw icon-spin"></span>&nbsp; ' +
        messageText +
        '</div>',
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
        position: 'fixed',
        top: '50%',
        left: '50%',
        transform: 'translate(-50%, -50%)'
      }
    });
  }

  function unblockWholePage() {
    if (typeof jQuery === 'undefined' || !jQuery.unblockUI) {
      return;
    }

    jQuery.unblockUI({ fadeOut: 200 });
  }

  // =========================
  // DOM READY
  // =========================
  document.addEventListener('DOMContentLoaded', function () {

    // =========================
    // SCROLL TO TOP BUTTON
    // =========================
    var btn = document.getElementById('scrollToTopPage');

    if (btn) {

      var SHOW_AT = 200;

      function toggleBtn() {
        var y = window.pageYOffset || document.documentElement.scrollTop || 0;

        if (y > SHOW_AT) {
          btn.classList.add('is-show');
        } else {
          btn.classList.remove('is-show');
        }
      }

      window.addEventListener('scroll', toggleBtn, { passive: true });
      toggleBtn();

      function easeInOutCubic(t) {
        if (t < 0.5) {
          return 4 * t * t * t;
        } else {
          return 1 - Math.pow(-2 * t + 2, 3) / 2;
        }
      }

      function smoothScrollToY(targetY, duration) {
        duration = duration || 650;

        var startY = window.pageYOffset || document.documentElement.scrollTop || 0;
        var distance = targetY - startY;
        var startTime = performance.now();

        function step(now) {
          var elapsed = now - startTime;
          var t = Math.min(elapsed / duration, 1);
          var eased = easeInOutCubic(t);

          window.scrollTo(0, startY + distance * eased);

          if (t < 1) {
            requestAnimationFrame(step);
          }
        }

        requestAnimationFrame(step);
      }

      btn.addEventListener('click', function () {
        var target = document.querySelector('.to-top-page');
        var y = 0;

        if (target) {
          y = target.getBoundingClientRect().top +
            (window.pageYOffset || document.documentElement.scrollTop || 0);
        }

        smoothScrollToY(y, 650);
      });
    }

    // =========================
    // LOGOUT CONFIRMATION
    // =========================
    var logoutBtn = document.getElementById('btn-logout');

    if (logoutBtn) {
      logoutBtn.addEventListener('click', function (e) {
        e.preventDefault();

        // Cek apakah SweetAlert tersedia
        if (typeof swal === 'undefined') {
          // fallback jika swal tidak ada
          if (confirm('Apakah Anda yakin ingin keluar?')) {
            var form = document.getElementById('logout-form');
            if (form) form.submit();
          }
          return;
        }

        swal({
          title: "Apakah Anda yakin ingin keluar?",
          text: "Anda akan keluar dari sesi saat ini.",
          icon: "warning",
          buttons: ["Batal", "Ya, keluar"],
          dangerMode: true,
        }).then(function (willLogout) {
          if (willLogout) {
            var form = document.getElementById('logout-form');
            if (form) form.submit();
          }
        });
      });
    }

  });

  // expose global jika diperlukan
  window.blockWholePage = blockWholePage;
  window.unblockWholePage = unblockWholePage;
})(window);