<?php

namespace Drupal\gt_help_topics\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining GT Help Topic entities.
 *
 * @ingroup gt_help_topics
 */
interface GtHelpTopicEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the GT Help Topic name.
   *
   * @return string
   *   Name of the GT Help Topic.
   */
  public function getName();

  /**
   * Sets the GT Help Topic name.
   *
   * @param string $name
   *   The GT Help Topic name.
   *
   * @return \Drupal\gt_help_topics\Entity\GtHelpTopicEntityInterface
   *   The called GT Help Topic entity.
   */
  public function setName($name);

  /**
   * Gets the GT Help Topic creation timestamp.
   *
   * @return int
   *   Creation timestamp of the GT Help Topic.
   */
  public function getCreatedTime();

  /**
   * Sets the GT Help Topic creation timestamp.
   *
   * @param int $timestamp
   *   The GT Help Topic creation timestamp.
   *
   * @return \Drupal\gt_help_topics\Entity\GtHelpTopicEntityInterface
   *   The called GT Help Topic entity.
   */
  public function setCreatedTime($timestamp);

}
