<?php

namespace Drupal\gt_legal_faq\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining GT Legal FAQ entities.
 *
 * @ingroup gt_legal_faq
 */
interface GtLegalFaqEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the GT Legal FAQ name.
   *
   * @return string
   *   Name of the GT Legal FAQ.
   */
  public function getName();

  /**
   * Sets the GT Legal FAQ name.
   *
   * @param string $name
   *   The GT Legal FAQ name.
   *
   * @return \Drupal\gt_legal_faq\Entity\GtLegalFaqEntityInterface
   *   The called GT Legal FAQ entity.
   */
  public function setName($name);

  /**
   * Gets the GT Legal FAQ creation timestamp.
   *
   * @return int
   *   Creation timestamp of the GT Legal FAQ.
   */
  public function getCreatedTime();

  /**
   * Sets the GT Legal FAQ creation timestamp.
   *
   * @param int $timestamp
   *   The GT Legal FAQ creation timestamp.
   *
   * @return \Drupal\gt_legal_faq\Entity\GtLegalFaqEntityInterface
   *   The called GT Legal FAQ entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the GT Legal FAQ revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the GT Legal FAQ revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\gt_legal_faq\Entity\GtLegalFaqEntityInterface
   *   The called GT Legal FAQ entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the GT Legal FAQ revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the GT Legal FAQ revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\gt_legal_faq\Entity\GtLegalFaqEntityInterface
   *   The called GT Legal FAQ entity.
   */
  public function setRevisionUserId($uid);

}
