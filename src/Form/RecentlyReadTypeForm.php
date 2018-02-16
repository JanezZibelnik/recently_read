<?php

namespace Drupal\recently_read\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\ContentEntityType;
use Drupal\recently_read\Entity\RecentlyReadType;


/**
 * Class RecentlyReadTypeForm.
 */
class RecentlyReadTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $entity = $this->entity;

    $readTypeConfig = RecentlyReadType::load('node');

    $t = $readTypeConfig->getTypes();
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $entity->label(),
      '#description' => $this->t("Label for the Recommendation type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\recently_read\Entity\RecentlyReadType::load',
      ],
      '#disabled' => !$entity->isNew(),
    ];


    $types = \Drupal::entityTypeManager()
      ->getStorage('node_type')
      ->loadMultiple();

    $form['enabled']= [
      '#type' => 'checkbox',
      '#title' => $this->t("Enabled"),
    ];

    $types = \Drupal::entityTypeManager()
      ->getStorage('node_type')
      ->loadMultiple();

    $options = [];
    foreach($types as $typeId => $type) {
      $options[$typeId] = $type->label();
    }

    $form['types'] = [
      '#type' => 'checkboxes',
      '#options' => $options,
      '#title' => $this->t('Types to be inserted on view'),
    ];

    $form['#cache']['max-age'] = 0;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $recently_read_type = $this->entity;
    $status = $recently_read_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Recently read type.', [
          '%label' => $recently_read_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Recently read type.', [
          '%label' => $recently_read_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($recently_read_type->toUrl('collection'));
  }

}
