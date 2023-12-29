<?php

namespace Drupal\gt_legal_faq;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of GT Legal FAQ entities.
 *
 * @ingroup gt_legal_faq
 */
class GtLegalFaqEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('GT Legal FAQ ID');
    $header['name'] = $this->t('Question');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\gt_legal_faq\Entity\GtLegalFaqEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.gt_legal_faq_entity.edit_form',
      ['gt_legal_faq_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
