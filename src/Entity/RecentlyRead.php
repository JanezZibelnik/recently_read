<?php

namespace Drupal\recently_read\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Recently read entity.
 *
 * @ingroup recently_read
 *
 * @ContentEntityType(
 *   id = "recently_read",
 *   label = @Translation("Recently read"),
 *   bundle_label = @Translation("Recently read type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\recently_read\RecentlyReadListBuilder",
 *     "views_data" = "Drupal\recently_read\Entity\RecentlyReadViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\recently_read\Form\RecentlyReadForm",
 *       "add" = "Drupal\recently_read\Form\RecentlyReadForm",
 *       "edit" = "Drupal\recently_read\Form\RecentlyReadForm",
 *       "delete" = "Drupal\recently_read\Form\RecentlyReadDeleteForm",
 *     },
 *     "access" = "Drupal\recently_read\RecentlyReadAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\recently_read\RecentlyReadHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "recently_read",
 *   admin_permission = "administer recently read entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/recently_read/{recently_read}",
 *     "add-page" = "/admin/structure/recently_read/add",
 *     "add-form" = "/admin/structure/recently_read/add/{recently_read_type}",
 *     "edit-form" = "/admin/structure/recently_read/{recently_read}/edit",
 *     "delete-form" = "/admin/structure/recently_read/{recently_read}/delete",
 *     "collection" = "/admin/structure/recently_read",
 *   },
 *   bundle_entity_type = "recently_read_type",
 *   field_ui_base_route = "entity.recently_read_type.edit_form"
 * )
 */
class RecentlyRead extends ContentEntityBase implements RecentlyReadInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityId() {
    return $this->get('entity_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setEnitityId($entityId) {
    $this->set('entity_id', $entityId);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSessionId() {
    return $this->get('session_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSessionId($sessionId) {
    $this->set('session_id', $sessionId);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }


  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Recently read entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['entity_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Entity ID'))
      ->setRequired(TRUE)
      ->setDescription(t('The Entity ID.'));

    $fields['session_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Session ID'))
      ->setDescription(t('The session ID associated with an anonymous user.'));

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    return $fields;
  }

}
