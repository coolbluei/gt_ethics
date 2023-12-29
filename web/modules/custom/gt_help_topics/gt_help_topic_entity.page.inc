<?php

/**
 * @file
 * Contains gt_help_topic_entity.page.inc.
 *
 * Page callback for GT Help Topic entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for GT Help Topic templates.
 *
 * Default template: gt_help_topic_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_gt_help_topic_entity(array &$variables) {
  // Fetch GtHelpTopicEntity Entity Object.
  $gt_help_topic_entity = $variables['elements']['#gt_help_topic_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
