/**
 * @file
 * UNB Libraries selectize.js initialization.
 */
(function ($) {
    $(document).ready(function() {
        // Creator aka Issuing Body.
        $('#edit-creator').selectize({
            maxItems: null,
            plugins: ['clear_button'],
            selectOnTab: false,
        });
        // Section aka Collection Category aka Subject Heading.
        $('#edit-section').selectize({
            maxItems: null,
            plugins: ["clear_button"],
            selectOnTab: false,
        });
    });
})(jQuery);
