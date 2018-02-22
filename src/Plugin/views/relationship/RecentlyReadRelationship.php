<?php

namespace Drupal\recently_read\Plugin\views\relationship;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\relationship\RelationshipPluginBase;
use Drupal\Core\Session\AccountProxy;
use Drupal\Core\PageCache\ResponsePolicy\KillSwitch;
use Drupal\Core\Config\CachedStorage;
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
   * Drupal\Core\Config\CachedStorage definition.
   *
   * @var \Drupal\Core\Config\CachedStorage
   */
  protected $cachedStorage;

  /**
   * RecentlyReadRelationship constructor.
   */
  function __construct(
    array $configuration,
    $pluginId,
    $pluginDefinition,
    AccountProxy $currentUser,
    KillSwitch $killSwitch,
    CachedStorage $cachedStorage
  ) {
    parent::__construct($configuration, $pluginId, $pluginDefinition);

    $this->currentUser = $currentUser;
    $this->killSwitch = $killSwitch;
    $this->cachedStorage = $cachedStorage;
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
      $container->get('page_cache_kill_switch'),
      $container->get('config.storage'),
      $container->get('recently_read')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defineOptions() {
    $options = parent::defineOptions();
    $options['bundles'] = ['default' => []];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $entity_type = $this->definition['recently_read_type'];
    $typesOptions = false;

    // Read the entity_type configuration and load the types
    $types = $this->cachedStorage->read('recently_read.recently_read_type.'.$entity_type)['types'];

    // If types are enabled prepare the array for checkboxes options
    if (isset($types) && !empty($types))
    {
      $typesOptions = array_combine($types, $types);
    }

    if ($typesOptions) {
      $form['bundles'] = [
        '#type' => 'checkboxes',
        '#title' => $this->t('Bundles'),
        '#default_value' => $this->options['bundles'],
        '#required' => TRUE,
        '#options' => $typesOptions,
      ];
    }

    $form['#cache']['max-age'] = 0;
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

    // Filter by entity bundles selected while configuring the relationship
    if(!empty($this->options['bundles'])) {
      $this->query->addWhere('recently_read', "$basetable.type", array_filter(array_values($this->options['bundles'])), "IN");
    }
  }

}