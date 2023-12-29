<?php

/**
 * @file
 * Contains gt_legal_faq_entity.page.inc.
 *
 * Page callback for GT Legal FAQ entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for GT Legal FAQ templates.
 *
 * Default template: gt_legal_faq_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_gt_legal_faq_entity(array &$variables) {
  // Fetch GtLegalFaqEntity Entity Object.
  $gt_legal_faq_entity = $variables['elements']['#gt_legal_faq_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
