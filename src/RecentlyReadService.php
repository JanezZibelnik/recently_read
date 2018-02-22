<?php

namespace Drupal\recently_read;


use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Recently read service.
 */
class RecentlyReadService {

  /**
   * The current user injected into the service.
   *
   * @var AccountInterface
   */
  private $currentUser;

  /**
   * @var EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * Constructor.
   *
   * @param AccountInterface $current_user
   *   The current user.
   * @param EntityTypeManagerInterface $entity_type_manager
   *   The entity manager.
   */
  public function __construct(AccountInterface $current_user,
                              EntityTypeManagerInterface $entity_type_manager
                              ) {
    $this->currentUser = $current_user;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Custom function to insert or update an entry for recently read.
   */
  function insert_entity($entity) {
    $user_id = $this->currentUser->id();
    // If anonymous set user_id to 0 and check for any existing entries.
    if ($this->currentUser->isAnonymous()) {
      $user_id = 0;
      $exists = $this->entityTypeManager->getStorage('recently_read')->loadByProperties([
        'session_id' => session_id(),
        'type' => $entity->getEntityTypeId(),
        'entity_id' => $entity->id(),
      ]);
    }
    else {
      $exists = $this->entityTypeManager->getStorage('recently_read')->loadByProperties([
        'user_id' => $user_id,
        'type' => $entity->getEntityTypeId(),
        'entity_id' => $entity->id(),
      ]);
    }
    // If exists then update created else create new.
    if (!empty($exists)) {
      foreach ($exists as $entry) {
        $entry->setCreatedTime(time())->save();
      }
    }
    else {

      // Create new.
      $recentlyRead = $this->entityTypeManager->getStorage('recently_read')->create([
        'type' => $entity->getEntityTypeId(),
        'user_id' => $user_id,
        'entity_id' => $entity->id(),
        'session_id' => $user_id ? 0 : session_id(),
        'created' => time(),
      ]);
      $recentlyRead->save();
    }
  }

}
