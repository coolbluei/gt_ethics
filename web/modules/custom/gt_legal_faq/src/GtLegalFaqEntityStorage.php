<?php

namespace Drupal\gt_legal_faq;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\gt_legal_faq\Entity\GtLegalFaqEntityInterface;

/**
 * Defines the storage handler class for GT Legal FAQ entities.
 *
 * This extends the base storage class, adding required special handling for
 * GT Legal FAQ entities.
 *
 * @ingroup gt_legal_faq
 */
class GtLegalFaqEntityStorage extends SqlContentEntityStorage implements GtLegalFaqEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(GtLegalFaqEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {gt_legal_faq_entity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {gt_legal_faq_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(GtLegalFaqEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {gt_legal_faq_entity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('gt_legal_faq_entity_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
