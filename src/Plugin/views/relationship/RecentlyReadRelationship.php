<?php

namespace Drupal\recently_read\Plugin\views\relationship;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\PageCache\ResponsePolicy\KillSwitch;
use Drupal\Core\Url;
use Drupal\Core\Session\AccountProxy;
use Drupal\views\Plugin\views\relationship\RelationshipPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a views relationship to recently read.
 *
 * @ViewsRelationship("recently_read_relationship")
 */
class RecentlyReadRelationship extends RelationshipPluginBase {

  /**
   * The Page Cache Kill switch.
   *
   * @var Drupal\Core\PageCache\ResponsePolicy\KillSwitch
   */
  protected $currentUser;

  /**
   * The Page Cache Kill switch.
   *
   * @var Drupal\Core\PageCache\ResponsePolicy\KillSwitch
   */
  protected $pageCacheKillSwitch;

  /**
   * Constructs a FlagViewsRelationship object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\PageCache\ResponsePolicy\KillSwitch $page_cache_kill_switch
   *   The kill switch.
   * @param \Drupal\flag\FlagServiceInterface $flag_service
   *   The flag service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, KillSwitch $page_cache_kill_switch, AccountProxy $currentUser) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->pageCacheKillSwitch = $page_cache_kill_switch;
    $this->currentUser = $currentUser;
    $this->definition = $plugin_definition + $configuration;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $page_cache_kill_switch = $container->get('page_cache_kill_switch');
    $curentUser = $container->get('current_user');
    return new static($configuration, $plugin_id, $plugin_definition, $page_cache_kill_switch, $curentUser);
  }

  /**
   * {@inheritdoc}
   */
  public function defineOptions() {
    $options = parent::defineOptions();
    $options['user_scope'] = ['default' => 'current'];
    return $options;
  }


  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);


    $form['user_scope'] = [
      '#type' => 'radios',
      '#title' => $this->t('By'),
      '#options' => ['current' => $this->t('Current user'), 'any' => $this->t('Any user')],
      '#default_value' => $this->options['user_scope'],
    ];

  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $this->ensureMyTable();

    if ($this->options['user_scope'] == 'current') {

      // Add in the SID from Session API for anonymous users.

      if ($this->currentUser->isAnonymous()) {
        // Disable page caching for anonymous users.
        $this->pageCacheKillSwitch->trigger();


        // Add in the SID from Session API for anonymous users.
        $this->definition['extra'][] = [
          'field' => 'sid',
          'value' => session_id(),
        ];

        $this->query->addWhere(0, "recently_read.session_id", session_id(), "=");
       }
       else
       {
         $this->query->addWhere(0, "recently_read.user_id", $this->currentUser->id(), "=");
       }
    }

    parent::query();
  }


}