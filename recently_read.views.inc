<?php

function recently_read_views_data_alter(array &$data) {
  $recently_read_types = \Drupal\recently_read\Entity\RecentlyReadType::loadMultiple();
  $entity_type_manager = \Drupal::entityTypeManager();
  foreach ($recently_read_types as $type) {
    $entity_type_id = $type->id();
    $entity_type = $entity_type_manager->getDefinition($entity_type_id);
    if ($entity_type->hasHandlerClass('views_data')) {
      $base_table = $entity_type_manager->getHandler($entity_type_id, 'views_data')->getViewsTableForEntityType($entity_type);
      $data[$base_table]['recently_read_relationship'] = [
        'title' => t('@entity_label recently read', ['@entity_label' => $entity_type->getLabel()]),
        'help' => t('Limit results to the recently read @entity_label.', ['@entity_label' => $entity_type->getLabel()]),
        'relationship' => [
          'group' => t('Recently read'),
          'label' => t('Recently read'),
          'base' => 'recently_read',
          'base field' => 'entity_id',
          'relationship field' => $entity_type->getKey('id'),
          'id' => 'recently_read_relationship',
        ],
      ];
    }
  }

  $data['views']['recently_read_user_filter'] = [
    'title' => t('Recently read user filter'),
    'filter' => [
      'title' => t('Recently read user filter'),
      'group' => t('Recently read'),
      'help' => t('Provides user filter for recently read'),
      'id' => 'recently_read_user_filter'
    ],
  ];

}

