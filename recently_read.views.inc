<?php
/**
 * Implements hook_views_data_alter().
 */

  function recently_read_views_data_alter(array &$data) {

    $entity_type_manager = \Drupal::entityTypeManager();
    $entity_subqueue = $entity_type_manager->getDefinition('recently_read');

    $data['recently_read']['sessions']['relationship'] = [
      'title' => t('Recently read user'),
      'label' => t('Recently read user'),
      'help' => t('Reference to Recently read user'),
      'group' => t('Recently read'),
      'id' => 'recently_read_relationship',
      'base' =>  $entity_subqueue->getDataTable() ?: $entity_subqueue->getBaseTable(),
      'base field' => $entity_subqueue->getKey('id'),
      'field' => 'session_id',
    ];

    $data['recently_read']['entity_link'] = [
      'title' => t('Entity link'),
      'field' => [
        'title' => t('Recently read entity link'),
        'help' => t('Links specific entity by type.'),
        'id' => 'recently_read_entity_link',
      ],
    ];

    return $data;
  }