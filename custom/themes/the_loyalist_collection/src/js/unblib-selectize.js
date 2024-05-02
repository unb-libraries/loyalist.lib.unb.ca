/**
 * @file
 * UNB Libraries selectize.js initialization.
 */
(function($, Drupal) {
    'use strict';

    Drupal.behaviors.unblib_selectize = {
        attach: function () {
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

            // Button color change. TO DO: custom module hook_form_alter().
            $('#edit-actions input:nth-child(2)').toggleClass('btn-primary btn-light border');
        }
    };
})(jQuery, Drupal);