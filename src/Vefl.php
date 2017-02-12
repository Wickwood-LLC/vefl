<?php

namespace Drupal\vefl;

use Drupal\layout_plugin\Layout;

/**
 * Helper class that holds all the main Display Suite helper functions.
 */
class Vefl {
  /**
   * Gets Display Suite layouts.
   */
  public static function getLayout($layout_id) {
    $layouts = Vefl::getLayouts();
    return !empty($layouts[$layout_id]) ? $layouts[$layout_id] : array();
  }

  /**
   * Gets Display Suite layouts.
   */
  public static function getLayouts() {
    static $layouts = FALSE;

    if (!$layouts) {
      $layouts = Layout::layoutPluginManager()->getDefinitions();
    }

    return $layouts;
  }

  /**
   * Gets Display Suite layouts.
   */
  public static function getLayoutsList() {
    $layouts = Vefl::getLayouts();

    $optgroup = '';
    $layout_options = array();
    foreach ($layouts as $key => $layout_definition) {
      $optgroup = t('Other');

      // Create new layout option group.
      if (!empty($layout_definition['category'])) {
        $optgroup = (string) $layout_definition['category'];
      }

      if (!isset($layout_options[$optgroup])) {
        $layout_options[$optgroup] = array();
      }

      // Stack the layout.
      $layout_options[$optgroup][$key] = $layout_definition['label'];
    }

    // If there is only one $optgroup, move it to the root.
    if (count($layout_options) == 2) {
      $options = $layout_options[$optgroup];
      $layout_options = $options;
    }

    return $layout_options;
  }
}
