<?php
/**
 * Contains \Drupal\recently_read\Form\SettingsForm.
 */
namespace Drupal\recently_read\Form;

use Druapl\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provices the recently read config form.
 */
class SettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'recently_read_config_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['recently_read.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    $config = $this->config('recently_read.settings');

    $types = $this->entityManager()->getEntityTypeLabels();
    ksort($types);
    $entity_info = array();
    foreach (array_keys($types) as $type) {
      $definition = $this->entityManager()->getDefinition($type);
      $reflected_definition = new \ReflectionClass($definition);
      $props = array();
      foreach ($reflected_definition->getProperties() as $property) {
        $property->setAccessible(TRUE);
        $value = $property->getValue($definition);
        $props[$property->name] = $value;
      }
      $entity_info[$type] = $props;
    }


    $form['#tree'] = TRUE;

    $form['recently_read_config'] = array(
      '#type' => 'fieldset',
      '#title' => t('Recently Read Config'),
    );

    $url = Url::fromRoute('recently_read.settings');
    $form['recently_read_config']['session_api_cfg'] = array(
      '#markup' => t('First, goto !link to config the session api Cookie expire time.',array('!link' => \Drupal::l('Session Api',$url))),
    );


    foreach ($entity_info as $entity_type => $entity) {
      if (!empty($entity['view modes']) && entity_type_supports($entity_type, 'view')) {
        $form['recently_read_config'][$entity_type] = array(
          '#type' => 'fieldset',
          '#title' => t('Recently Read ' . $entity['label'] . ' config'),
        );
        $form['recently_read_config'][$entity_type]['enable'] = array(
          '#type' => 'checkbox',
          '#title' => t('Enable'),
          '#default_value' => isset($config[$entity_type]['enable']) ? $config[$entity_type]['enable'] : FALSE, 
        );
        $form['recently_read_config'][$entity_type]['max_record'] = array(
          '#type' => 'textfield',
          '#title' => t('Max Record for Recently Read @entity',array('@entity' => $entity['label'])),
          '#default_value' => isset($config[$entity_type]['max_record']) ? $config[$entity_type]['max_record'] : 10, 
        );
        $form['recently_read_config'][$entity_type]['view_mode'] = array(
          '#type' => 'checkboxes',
          '#title' => t('View mode for track'),
          '#default_value' => isset($config[$entity_type]['view_mode']) ? $config[$entity_type]['view_mode'] : array('full' => 'full'), 
          '#options' => isset($entity['view modes']) ? drupal_map_assoc(array_keys($entity['view modes'])) : array(),
        );
      }
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
  }
}
