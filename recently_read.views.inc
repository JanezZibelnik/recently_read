<?php
/**
 * Implements hook_views_data_alter().
 */

  function recently_read_views_data_alter(array &$data) {
    $data['recently_read']['sessions']['relationship'] = [
      'title' => t('Recently read user'),
      'label' => t('Recently read user'),
      'help' => t('Reference to Recently read user'),
      'group' => t('Recently read'),
      'id' => 'recently_read_relationship',
      'base' => 'sessions',
      'base field' => 'sid',
      'field' => 'session_id',
    ];

    $data['recently_read']['entity_link'] = array(
      'title' => t('Entity link'),
      'field' => array(
        'title' => t('Recently read entity link'),
        'help' => t('Links specific entity by type.'),
        'id' => 'recently_read_entity_link',
      ),
    );

    ksm($data);
  }