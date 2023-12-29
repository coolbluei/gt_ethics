<?php

namespace Drupal\gt_help_topics;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the GT Help Topic entity.
 *
 * @see \Drupal\gt_help_topics\Entity\GtHelpTopicEntity.
 */
class GtHelpTopicEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\gt_help_topics\Entity\GtHelpTopicEntityInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished gt help topic entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published gt help topic entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit gt help topic entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete gt help topic entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add gt help topic entities');
  }


}
