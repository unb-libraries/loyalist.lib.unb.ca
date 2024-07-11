/**
 * @file
 * UNB Libraries selectize.js initialization.
 */
(function ($) {
    $(document).ready(function() {
        $('#edit-creator').selectize({
            maxItems: null,
            plugins: ['clear_button'],
            selectOnTab: false,
        });
        $('select#edit-collection').selectize({
            maxItems: null,
            plugins: ["clear_button"],
            selectOnTab: false,
        });
    });
})(jQuery);
