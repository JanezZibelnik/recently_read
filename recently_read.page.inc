<?php

/**
 * @file
 * Contains recently_read.page.inc.
 *
 * Page callback for Recently read entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Recently read templates.
 *
 * Default template: recently_read.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_recently_read(array &$variables) {
  // Fetch RecentlyRead Entity Object.
  $recently_read = $variables['elements']['#recently_read'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
