<?php

namespace Drupal\recently_read\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\NodeType;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Drupal\comment\Entity\Comment;
use Drupal\taxonomy\Entity\Term;
use Drupal\recently_read\Entity\RecentlyReadType;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Drupal\Core\Link;
use Drupal\Core\Url;


/**
 * Field handler to flag the node type.
 *
 * @ViewsField("recently_read_entity_link")
 */
class RecentlyReadEntityLink extends FieldPluginBase {

  /**
   * @{inheritdoc}
   */
  public function query() {
    // Leave empty to avoid a query on this field.
  }

  /**
   * Define the available options
   * @return array
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['recently_read_type'] = array('default' => 'node');

    return $options;
  }

  /**
   * Provide the options form.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    $types =
    $types = RecentlyReadType::loadMultiple();
    $options = [];
    foreach ($types as $key => $type) {
      $options[$key] = $type->label();
    }
    $form['recently_read_type'] = [
      '#title' => $this->t('What content type is being linked'),
      '#type' => 'select',
      '#default_value' => $this->options['recently_read_type'],
      '#options' => $options,
      '#multiple' => TRUE,
    ];

    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * @{inheritdoc}
   */
  public function render(ResultRow $values) {
    $entity = $values->_entity;

    $k = 1;
    if ($entity->bundle() == $this->options['recently_read_type']) {
      switch($this->options['recently_read_type']) {
        case 'comment':
          return Link::fromTextAndUrl(t(Comment::load($entity->get('entity_id')->getString())->label()), Url::fromUri('internal:/comment/'.$entity->get('entity_id')->getString()))->toString();
          break;
        case 'user':
          return Link::fromTextAndUrl(t(User::load($entity->get('entity_id')->getString())->label()), Url::fromUri('internal:/user/'.$entity->get('entity_id')->getString()))->toString();
          break;
        case 'taxonomy_term':
          return Link::fromTextAndUrl(t(Term::load($entity->get('entity_id')->getString())->label()), Url::fromUri('internal:/taxonomy_term/'.$entity->get('entity_id')->getString()))->toString();
          break;
        default:
          return Link::fromTextAndUrl(t(Node::load($entity->get('entity_id')->getString())->label()), Url::fromUri('internal:/node/'.$entity->get('entity_id')->getString()))->toString();

      }
    }
  }
}