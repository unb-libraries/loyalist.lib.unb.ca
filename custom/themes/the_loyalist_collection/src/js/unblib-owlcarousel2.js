/**
 * @file
 * Add keyboard 'spacebar' activation support for link elements whose role is button.
 */
(function ($) {
    $(document).ready(function() {
        $('.owl-carousel').owlCarousel({
            items: 1,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            autoplay: true,
            loop: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            dots: false,
            nav: true,
            navText: ["<i class='fa-solid fa-xl fa-chevron-left'></i>","<i class='fa-solid fa-xl fa-chevron-right'></i>"],
        });
    });
})(jQuery);
