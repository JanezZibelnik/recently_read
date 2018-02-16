<?php

namespace Drupal\recently_read;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Recently read entity.
 *
 * @see \Drupal\recently_read\Entity\RecentlyRead.
 */
class RecentlyReadAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\recently_read\Entity\RecentlyReadInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished recently read entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published recently read entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit recently read entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete recently read entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add recently read entities');
  }

}
