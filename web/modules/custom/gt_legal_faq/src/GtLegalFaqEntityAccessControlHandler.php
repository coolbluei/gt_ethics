<?php

namespace Drupal\gt_legal_faq;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the GT Legal FAQ entity.
 *
 * @see \Drupal\gt_legal_faq\Entity\GtLegalFaqEntity.
 */
class GtLegalFaqEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\gt_legal_faq\Entity\GtLegalFaqEntityInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished gt legal faq entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published gt legal faq entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit gt legal faq entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete gt legal faq entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add gt legal faq entities');
  }


}
