<?php

namespace Drupal\recently_read\Plugin\views\relationship;

use Drupal\views\Plugin\views\relationship\RelationshipPluginBase;
use Drupal\Core\Session\AccountProxy;
use Drupal\Core\PageCache\ResponsePolicy\KillSwitch;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Provides a views relationship to recently read.
 *
 * @ViewsRelationship("recently_read_relationship")
 */
class RecentlyReadRelationship extends RelationshipPluginBase {

  /**
   * Drupal\Core\Session\AccountProxy definition.
   *
   * @var \Drupal\Core\Session\AccountProxy
   */
  protected $currentUser;

  /**
   * Drupal\Core\PageCache\ResponsePolicy\KillSwitch definition.
   *
   * @var \Drupal\Core\PageCache\ResponsePolicy\KillSwitch
   */
  protected $killSwitch;

  /**
   * RecentlyReadRelationship constructor.
   */
  function __construct(
    array $configuration,
    $pluginId,
    $pluginDefinition,
    AccountProxy $currentUser,
    KillSwitch $killSwitch
  ) {
    parent::__construct($configuration, $pluginId, $pluginDefinition);

    $this->currentUser = $currentUser;
    $this->killSwitch = $killSwitch;
  }

  /**
   * RecentlyReadRelationship create function.
   */
  public static function create(ContainerInterface $container, array $configuration, $pluginId, $pluginDefinition) {
    return new static(
      $configuration,
      $pluginId,
      $pluginDefinition,
    // Load the service required to construct this class.
      $container->get('current_user'),
      $container->get('page_cache_kill_switch')
    );
  }

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
    if ($this->currentUser->id() === 0) {
      // Disable page caching for anonymous users.
      $this->killSwitch->trigger();
      $this->query->addWhere('recently_read', "recently_read_$basetable.session_id", session_id(), "=");
    }
    else {
      $this->query->addWhere('recently_read', "recently_read_$basetable.user_id", $this->currentUser->id(), "=");
    }
  }

}