<?php

namespace Drupal\recently_read\Plugin\views\filter;

use Drupal\views\Plugin\views\filter\BooleanOperator;
use Drupal\Core\Form\FormStateInterface;

/**
 * Filters recently read content by user.
 *
 * @ViewsFilter("recently_read_user_filter")
 */
class RecentlyReadUserViewsFilter extends BooleanOperator {

  /**
   * {@inheritdoc}
   */
  public function defineOptions() {
    $options = parent::defineOptions();
    $options['value'] = array('default' => 1);

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $form['value']['#type'] = 'radios';
    $form['value']['#title'] = t('User scope');
    $form['value']['#options'] = [
      1 => $this->t('Current user'),
      0 => $this->t('All users'),
      // @todo Find out what in the hell filter type ALL is supposed to do.
      // 'All' => t('All'),
    ];
    $form['value']['#default_value'] = empty($this->options['value']) ? FALSE : $this->options['value'];
    // Workaround for bug in Views: $no_operator class property has no effect.
    // TODO: remove when https://www.drupal.org/node/2869191 is fixed.
    unset($form['operator']);
    unset($form['expose']['use_operator']);
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $this->ensureMyTable();

    if ($this->value) {
      if (\Drupal::currentUser()->id() === 0) {
        // Disable page caching for anonymous users.
        \Drupal::service('page_cache_kill_switch')->trigger();
        $this->query->addWhere($this->options['group'], "recently_read.session_id", session_id(), "=");
      }
      else
      {
        $this->query->addWhere($this->options['group'], "recently_read.user_id", \Drupal::currentUser()->id(), "=");
      }
    }
  }

}
