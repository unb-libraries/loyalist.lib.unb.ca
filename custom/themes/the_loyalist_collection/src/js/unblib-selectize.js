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
            placeholder: '- All creators -',
        });
        // Section aka Collection Category aka Subject Heading.
        $('#edit-section').selectize({
            maxItems: null,
            plugins: ["clear_button"],
            selectOnTab: false,
            placeholder: '- All categories -',
        });
        $('#edit-location').selectize({
            maxItems: null,
            plugins: ['clear_button'],
            selectOnTab: false,
        });
    });
})(jQuery);
