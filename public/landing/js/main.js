(function ($) {
    "use strict";

    // Dropdown on mouse hover
    $(document).ready(function () {
        function toggleNavbarMethod() {
            if ($(window).width() > 992) {
                $('.navbar .dropdown').on('mouseover', function () {
                    $('.dropdown-toggle', this).trigger('click');
                }).on('mouseout', function () {
                    $('.dropdown-toggle', this).trigger('click').blur();
                });
            } else {
                $('.navbar .dropdown').off('mouseover').off('mouseout');
            }
        }
        toggleNavbarMethod();
        $(window).resize(toggleNavbarMethod);
    });

    // Date and time picker
    if ($('.date').length) {
        $('.date').datetimepicker({
            format: 'L'
        });
    }
    if ($('.time').length) {
        $('.time').datetimepicker({
            format: 'LT'
        });
    }

    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            if ($('.back-to-top').length) $('.back-to-top').fadeIn('slow');
        } else {
            if ($('.back-to-top').length) $('.back-to-top').fadeOut('slow');
        }
    });
    if ($('.back-to-top').length) {
        $('.back-to-top').click(function () {
            $('html, body').animate({ scrollTop: 0 }, 1500, 'easeInOutExpo');
            return false;
        });
    }

    // Public service carousel
    if ($(".public-service-carousel").length) {
        $(".public-service-carousel").owlCarousel({
            autoplay: true,
            smartSpeed: 1000,
            margin: 45,
            dots: false,
            loop: true,
            nav: true,
            navText: [
                '<i class="fa fa-arrow-left"></i>',
                '<i class="fa fa-arrow-right"></i>'
            ],
            responsive: {
                0: { items: 1 },
                992: { items: 2 },
                1200: { items: 3 }
            }
        });
    }

    // Team carousel
    if ($(".team-carousel, .related-carousel").length) {
        $(".team-carousel, .related-carousel").owlCarousel({
            autoplay: true,
            smartSpeed: 1000,
            margin: 45,
            dots: false,
            loop: true,
            nav: true,
            navText: [
                '<i class="fa fa-arrow-left"></i>',
                '<i class="fa fa-arrow-right"></i>'
            ],
            responsive: {
                0: { items: 1 },
                992: { items: 2 }
            }
        });
    }

    // Testimonials carousel
    if ($(".testimonial-carousel").length) {
        $(".testimonial-carousel").owlCarousel({
            autoplay: true,
            smartSpeed: 1000,
            items: 1,
            dots: true,
            loop: true,
        });
    }
})(jQuery);
