<?php

namespace Drupal\gt_legal_faq;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface GtLegalFaqEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of GT Legal FAQ revision IDs for a specific GT Legal FAQ.
   *
   * @param \Drupal\gt_legal_faq\Entity\GtLegalFaqEntityInterface $entity
   *   The GT Legal FAQ entity.
   *
   * @return int[]
   *   GT Legal FAQ revision IDs (in ascending order).
   */
  public function revisionIds(GtLegalFaqEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as GT Legal FAQ author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   GT Legal FAQ revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\gt_legal_faq\Entity\GtLegalFaqEntityInterface $entity
   *   The GT Legal FAQ entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(GtLegalFaqEntityInterface $entity);

  /**
   * Unsets the language for all GT Legal FAQ with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
