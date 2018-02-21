<?php

namespace Drupal\recently_read\Plugin\views\relationship;

use Drupal\views\Plugin\views\relationship\RelationshipPluginBase;

/**
 * Provides a views relationship to recently read.
 *
 * @ViewsRelationship("recently_read_relationship")
 */
class RecentlyReadRelationship extends RelationshipPluginBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    parent::query();
    $this->ensureMyTable();
    // Get base table and entity_type from relationship.
    $basetable = $this->definition['base_table'];
    $entity_type = $this->definition['recently_read_type'];
    // Add query for selected entity type.
    $this->query->addWhere('recently_read', "recently_read_$basetable.type", $entity_type, "=");
    // Add query to filter data if auth.user or anonymous.
    if (\Drupal::currentUser()->id() === 0) {
      // Disable page caching for anonymous users.
      \Drupal::service('page_cache_kill_switch')->trigger();
      $this->query->addWhere('recently_read', "recently_read_$basetable.session_id", session_id(), "=");
    }
    else {
      $this->query->addWhere('recently_read', "recently_read_$basetable.user_id", \Drupal::currentUser()->id(), "=");
    }
  }

}