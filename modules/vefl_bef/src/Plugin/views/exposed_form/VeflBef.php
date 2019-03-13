<?php

namespace Drupal\vefl_bef\Plugin\views\exposed_form;

use Drupal\better_exposed_filters\Plugin\views\exposed_form\BetterExposedFilters;
use Drupal\vefl\Plugin\views\exposed_form\VeflTrait;

/**
 * Exposed form plugin that provides a better exposed filters form with layout.
 *
 * @ingroup views_exposed_form_plugins
 *
 * @ViewsExposedForm(
 *   id = "vefl_bef",
 *   title = @Translation("Better Exposed Filters (with layout)"),
 *   help = @Translation("Adds layout settings for Better Exposed Filters")
 * )
 */
class VeflBef extends BetterExposedFilters {
  use VeflTrait;

  /**
   * {@inheritdoc}
   */
  private function getRegionElements($layout_id, array $layouts = []) {
    $element = [
      '#prefix' => '<div id="edit-block-region-wrapper">',
      '#suffix' => '</div>',
    ];
    // Outputs regions selectbox for each filter.
    $types = [
      'filters' => $this->view->display_handler->getHandlers('filter'),
      'actions' => $this->vefl->getFormActions(),
    ];

    // Add option for secondary exposed form.
    $types['actions']['secondary'] = t('Secondary exposed form options');

    // Add additional action for combined sort.
    $types['actions']['sort_bef_combine'] = t('Combine sort order with sort by');

    $regions = [];
    foreach ($layouts[$layout_id]->getRegions() as $region_id => $region) {
      $regions[$region_id] = $region['label'];
    }

    foreach ($types as $type => $fields) {
      foreach ($fields as $id => $filter) {
        if ($type == 'filters') {
          if (!$filter->options['exposed']) {
            continue;
          }
          elseif ($filter->options['is_grouped']) {
            $id = $filter->options['group_info']['identifier'];
            $filter = $filter->options['group_info']['label'];
          }
          else {
            $id = $filter->options['expose']['identifier'];
            $filter = $filter->options['expose']['label'];
          }
        }

        $element[$id] = [
          '#type' => 'select',
          '#title' => $filter,
          '#options' => $regions,
        ];

        // Add states if secondary.
        if ($id == 'secondary') {
          $element[$id]['#states'] = [
            'visible' => [
              ':input[name="exposed_form_options[bef][general][allow_secondary]"]' => ['checked' => TRUE],
            ],
          ];
        }

        // Add states if combined sort.
        if ($id == 'sort_bef_combine') {
          $element[$id]['#states'] = [
            'visible' => [
              ':input[name="exposed_form_options[bef][sort][advanced][combine]"]' => ['checked' => TRUE],
            ],
          ];
        }

        // Set default region for chosen layout.
        if (!empty($this->options['layout']['widget_region'][$id]) && !empty($regions[$this->options['layout']['widget_region'][$id]])) {
          $element[$id]['#default_value'] = $this->options['layout']['widget_region'][$id];
        }
      }
    }

    return $element;
  }

}
