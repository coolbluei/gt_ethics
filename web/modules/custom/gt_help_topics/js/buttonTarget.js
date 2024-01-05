// animation testing code
/**
 * @file
 * Placeholder file for custom sub-theme behaviors.
 *
 */
(function ($, Drupal, drupalSettings) {

  'use strict';

  // DEV CODE - testing only - remove after review
  // Add animation to show effect on browser of multiple animations running
  // at the same time.
  Drupal.behaviors.gtHelpTopicsButtonTarget = {
    attach: function (context, settings) {
      function processTopicButton() {
        // Custom once.
        if ($(this).hasClass('processed')) {
          return;
        }
        $(this).addClass('processed');
        // Get the hrefs for this work.
        const targets = drupalSettings.helpTopicsButtonTargets;
        // Get a handle on the select element.
        const $options = $(this).find('select');
        // Find and Enable select options.
        $(this).find('#topic-go-button').on('click', function() {
          // Find target.
          const target = targets[$options.val()];
          // Redirect when we have a target redirect.
          if (target.length > 0) {
            window.location.href = target;
            return;
          }
          // No redirection?  Alert to make a selection.
          alert('Missing target.');
        });
        // Add Button and controls.
      }

      $('#help-topic-select', context).each(processTopicButton);
    }
  };

})(jQuery, Drupal, drupalSettings);
