<?php

/**
 * @file
 * Contains the node from URL argument default plugin.
 */

/**
 * Default argument plugin to extract a node via menu_get_object
 *
 * This plugin actually has no options so it odes not need to do a great deal.
 */
class views_plugin_argument_rr extends views_plugin_argument_default {
  function get_argument() {
    global $user;
    if ($user->uid) {
      return 'all';
    }
    if (empty($_SESSION)) {
      return 0;
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
    return $nids;
  }
}
