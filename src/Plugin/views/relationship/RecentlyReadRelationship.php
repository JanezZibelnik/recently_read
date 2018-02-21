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

}