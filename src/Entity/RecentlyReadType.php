<?php

namespace Drupal\recently_read\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Recently read type entity.
 *
 * @ConfigEntityType(
 *   id = "recently_read_type",
 *   label = @Translation("Recently read type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\recently_read\RecentlyReadTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\recently_read\Form\RecentlyReadTypeForm",
 *       "edit" = "Drupal\recently_read\Form\RecentlyReadTypeForm",
 *       "delete" = "Drupal\recently_read\Form\RecentlyReadTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\recently_read\RecentlyReadTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "recently_read_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "recently_read",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/system/recently_read_type/{recently_read_type}",
 *     "add-form" = "/admin/config/system/recently_read_type/add",
 *     "edit-form" = "/admin/config/system/recently_read_type/{recently_read_type}/edit",
 *     "delete-form" = "/admin/config/system/recently_read_type/{recently_read_type}/delete",
 *     "collection" = "/admin/config/system/recently_read_type"
 *   }
 * )
 */
class RecentlyReadType extends ConfigEntityBundleBase implements RecentlyReadTypeInterface {

  /**
   * The Recently read type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Recently read type label.
   *
   * @var string
   */
  protected $label = '';

  /**
   * The Recently read type enabled.
   *
   * @var bool
   */
  protected $enabled = FALSE;

  /**
   * The Recently read types.
   *
   * @var array
   */
  protected $types = [];

  /**
   * {@inheritdoc}
   */
  public function getTypes() {
    return array_filter($this->get('types'));
  }

}
