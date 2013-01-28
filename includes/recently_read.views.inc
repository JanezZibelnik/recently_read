<?php

/**
 * Implements of hook_views_data().
 **/
function recently_read_views_data() {
  $data['recently_read_nodes']['table']['group'] = t('Recently Read');
  $data['recently_read_nodes']['table']['base'] = array(
    'field' => 'nid',
    'title' => t('Recently read'),
    'help' => t('Recently Read')
  );
  $data['recently_read_nodes']['table']['join'] = array(
    'node' => array(
      'left_field' => 'nid',
      'field' => 'nid',
    )
  );

  $data['recently_read_nodes']['uid'] = array(
    'title' => t('Recently Read uid'),
    'help' => t('Recently Read uid'),
    'field' => array(
      'handler' => 'views_handler_field_user',
      'click sortable' => TRUE,
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_user_name',
    ),
    'relationship' => array(
      'base' => 'users',
      'base field' => 'uid',
      'handler' => 'views_handler_relationship',
      'label' => t('Recently Read User'),
      'title' => t('Relationship uid'),
      'help' => t('Relationship to users'),
    ),
  );

  $data['recently_read_nodes']['current_user'] = array(
    'real field' => 'uid',
    'title' => t('Current'),
    'help' => t('Filter the view to the currently logged in user.'),
    'filter' => array(
      'handler' => 'views_handler_filter_rr_user',
      'type' => 'yes-no',
    ),
  );

  $data['recently_read_nodes']['timestamp'] = array(
    'title' => t('Recently Read Date'),
    'help' => t('Recently Read Time'),
    'field' => array(
      'handler' => 'views_handler_field_date',
      'click sortable' => TRUE,
    ),
    'sort' => array(
      'handler' => 'views_handler_sort_date',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_date',
    ),
  );
  return $data;
}

/**
 * Implements of hook_views_data_alter().
 **/
function recently_read_views_data_alter(&$data) {
  $data['node']['rid'] = array(
    'title' => t('Recently Read'),
    'help' => t('Recently Read Relationship'),
    'filter' => array(
      'handler' => 'views_handler_filter_rr_nid',
    ),
    'relationship' => array(
      'group' => 'Recently Read',
      'label' => t('Recently Read'),
      'base' => 'recently_read_nodes',
      'base field' => 'nid',
      'relationship field' => 'nid',
      'handler' => 'views_handler_relationship'
    ),
  );
}

