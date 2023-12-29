<?php

namespace Drupal\gt_help_topics;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of GT Help Topic entities.
 *
 * @ingroup gt_help_topics
 */
class GtHelpTopicEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('GT Help Topic ID');
    $header['name'] = $this->t('Topic');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\gt_help_topics\Entity\GtHelpTopicEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.gt_help_topic_entity.edit_form',
      ['gt_help_topic_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
