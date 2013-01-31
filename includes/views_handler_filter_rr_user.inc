<?php

/**
 * @file
 * Definition of views_handler_filter_user_current.
 */

/**
 * Filter handler for the current user.
 *
 * @ingroup views_filter_handlers
 */
class views_handler_filter_rr_user extends views_handler_filter_boolean_operator {
  function construct() {
    parent::construct();
    $this->value_value = t('Is the logged in user');
  }
  /**
  function pre_query(){
    parent::pre_query();
    //dsm($this->view,'view');
    //dsm($this->view->argument['nid'],'argument');
    global $user;
    if (!$user->uid) {
      if (empty($_SESSION)) {
        return;
      } else {
        $node_types = variable_get('recently_read_node_types', array());
        $nids = array();
        foreach ($node_types as $type) {
          if (!empty($_SESSION['recently_read-' . $type])) {
            //$nids .= implode('+',array_keys($_SESSION['recently_read-'. $type]));
            $nids = array_merge($nids, array_keys($_SESSION['recently_read-' . $type]));
          }
        }
        $nids = implode('+' , $nids);
        //dsm($nids);
      }
      $this->view->relationship = array();
      $this->view->filter = array();
      $argument = new views_handler_argument_node_nid();
      $options = array(
        'id' => 'nid',
        'table' => 'node',
        'field' => 'nid',
        'default_action' => 'default',
        'default_argument_type' => 'fixed',
        'default_argument_options' => array(
          'argument' => $nids,
        ),
        'validate' => array(
          'type' => 'none',
        ),
        //allow multiplue value;
        'break_phrase' => 1,
      );
      $argument->init($this->view,$options);
      //dsm($argument);
      $this->view->argument['nid'] =  $argument;
    }
  }  
  **/

  function query() {
    global $user;
    if (!$user->uid) {
      return FALSE;
    }
    $this->ensure_my_table();

    $field = $this->table_alias . '.' . $this->real_field . ' ';
    $or = db_or();

    if (empty($this->value)) {
      $or->condition($field, '***CURRENT_USER***', '<>');
      if ($this->accept_null) {
        $or->isNull($field);
      }
    }
    else {
      $or->condition($field, '***CURRENT_USER***', '=');
    }
    $this->query->add_where($this->options['group'], $or);
  }
}

