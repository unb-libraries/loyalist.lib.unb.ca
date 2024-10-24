/**
 * @file
 * Add keyboard 'spacebar' activation support for link elements whose role is button.
 */
(function ($) {
    $(document).ready(function() {
        const checkboxInputId = document.getElementById("owlAutoplaySwitch");
        var owl_carousel = $('.owl-carousel');
        owl_carousel.owlCarousel({
            items: 1,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            autoplay: true,
            loop: true,
            autoplayTimeout: 7500,
            autoplayHoverPause: true,
            dots: false,
            nav: true,
            navText: [
                "<i class='fa-solid fa-xl fa-chevron-left' aria-label='Previous Slide'></i>",
                "<i class='fa-solid fa-xl fa-chevron-right' aria-label='Next Slide'></i>"
            ],
        });

        function toggleOwlAutoplay() {
            console.log(checkboxInputId.type);
            if (
                checkboxInputId
                && checkboxInputId.type === 'checkbox'
                && $(checkboxInputId).is(':checked')
            ) {
                owl_carousel.trigger('play.owl.autoplay',7500);
                return true;
            }
            owl_carousel.trigger('stop.owl.autoplay',500);
            return false;
        }

        checkboxInputId.addEventListener("click", toggleOwlAutoplay);
    });
})(jQuery);
