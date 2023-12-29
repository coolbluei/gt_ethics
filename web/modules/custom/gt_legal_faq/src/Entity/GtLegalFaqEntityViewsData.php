<?php

namespace Drupal\gt_legal_faq\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for GT Legal FAQ entities.
 */
class GtLegalFaqEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
