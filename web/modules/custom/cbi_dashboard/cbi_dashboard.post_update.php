<?php

use Drupal\safe_field_getter\SafeFieldGetter;

/**
 * Implements hook_post_update_NAME().
 */
function cbi_dashboard_post_update_move_faqs(&$sandbox)
{
  // Keep track of process.
  $processed = 0;
  $fail = 0;
  $new = 0;
  $node_storage = \Drupal::entityTypeManager()->getStorage('node');
  $faq_storage = \Drupal::entityTypeManager()->getStorage('gt_legal_faq_entity');
  // Find all FAQ nodes.
  $nodes = $node_storage->loadByProperties([
    'type' => 'faq',
  ]);
  // Iterate over the nodes.
  foreach ($nodes as $node) {
    // Build new entities.
    try {
      $processed++;
      $body = SafeFieldGetter::firstComplex($node, 'body');
      $faq = $faq_storage->create([
        'name' => $node->getTitle(),
        'field_answer' => [
            'value' => $body['value'],
            'format' => 'full_html',
          ],
        'field_subject_matter' => SafeFieldGetter::allReferences($node, 'field_subject_mater'),
        'field_unit_taxonomy' => SafeFieldGetter::firstReference($node, 'field_unit_taxonomy'),
      ]);
      $faq->save();
      $new++;
    }
    catch (\Exception $e) {
      $fail++;
    }
  }
  return t('Operation complete. Processed: @p. New: @n. Failures: @f.',[
    '@p' => $processed,
    '@n' => $new,
    '@f' => $fail,
  ]);
}
