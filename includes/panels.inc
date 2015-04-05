<?php

/**
 * @file
 * Provides Panels integration.
 */

/**
 * Implements hook_vefl_layouts().
 */
function panels_vefl_layouts() {
  ctools_include('plugins', 'panels');
  $module_layouts = panels_get_layouts();

  $layouts = array();
  foreach ($module_layouts as $id => $layout) {
    if (empty($layout['builder'])) {
      $layouts['panels_' . $id] = array(
        'title' => $layout['title'],
        'regions' => $layout['regions'],
        'module' => 'Panels',
      );
    }
  }
  return $layouts;
}

/**
 * A theme preprocess function for vefl_views_exposed_form_panels().
 *
 * Adds $region_widgets array with separated by regions widgets.
 */
function vefl_preprocess_vefl_views_exposed_form_panels(&$vars) {
  ctools_include('vefl', 'vefl');
  template_preprocess_views_exposed_form($vars);
  vefl_preprocess_views_exposed_form($vars);
}

/**
 * Theme function for Views exposed form.
 *
 * Wraps form field into regions.
 *
 * @see _vefl_form_theme_functions()
 */
function vefl_theme_views_exposed_form_panels(&$vars) {
  ctools_include('plugins', 'panels');

  // Outputs content in panels layout.
  $layout_name = substr($vars['form']['#layout']['layout'], 7);
  $panel_content = panels_print_layout(panels_get_layout($layout_name), $vars['region_widgets']);
  return '<div class="views-exposed-form views-exposed-widgets clearfix">' . $panel_content . '</div>';
}
