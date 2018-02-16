<?php

namespace Drupal\recently_read\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Recently read entities.
 *
 * @ingroup recently_read
 */
interface RecentlyReadInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Recently read name.
   *
   * @return string
   *   Name of the Recently read.
   */
  public function getName();

  /**
   * Sets the Recently read name.
   *
   * @param string $name
   *   The Recently read name.
   *
   * @return \Drupal\recently_read\Entity\RecentlyReadInterface
   *   The called Recently read entity.
   */
  public function setName($name);

  /**
   * Gets the Recently read creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Recently read.
   */
  public function getCreatedTime();

  /**
   * Sets the Recently read creation timestamp.
   *
   * @param int $timestamp
   *   The Recently read creation timestamp.
   *
   * @return \Drupal\recently_read\Entity\RecentlyReadInterface
   *   The called Recently read entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Recently read published status indicator.
   *
   * Unpublished Recently read are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Recently read is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Recently read.
   *
   * @param bool $published
   *   TRUE to set this Recently read to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\recently_read\Entity\RecentlyReadInterface
   *   The called Recently read entity.
   */
  public function setPublished($published);

}
