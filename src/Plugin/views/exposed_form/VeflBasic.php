<?php

namespace Drupal\vefl\Plugin\views\exposed_form;

use Drupal\views\Plugin\views\exposed_form\Basic;
use Drupal\Core\Form\FormStateInterface;
use Drupal\vefl\Vefl;

/**
 * Exposed form plugin that provides a basic exposed form with layout.
 *
 * @ingroup views_exposed_form_plugins
 *
 * @ViewsExposedForm(
 *   id = "vefl_basic",
 *   title = @Translation("Basic (with layout)"),
 *   help = @Translation("Adds layout settings for Exposed form")
 * )
 */
class VeflBasic extends Basic {

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['layout'] = array(
      'contains' => array(
        'layout_id' => array('default' => 'vefl_onecol'),
        'regions' => array('default' => array()),
        'widget_region' => array('default' => array()),
      ),
    );
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $layout_id = $this->options['layout']['layout_id'];
    $layouts = Vefl::getLayouts();

    // Converts layouts array to options.
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
    if (count($layout_options) < 2) {
      $layout_options = reset($layout_options);
    }

    // Outputs layout selectbox.
    $form['layout'] = array(
      '#type' => 'fieldset',
      '#title' => t('Layout settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['layout']['layout_id'] = array(
      '#prefix' => '<div class="container-inline">',
      '#type' => 'select',
      '#options' => $layout_options,
      '#title' => t('Layout'),
      '#default_value' => $layout_id,
    );
    $form['layout']['change'] = array(
      '#type' => 'submit',
      '#value' => t('Change'),
      '#submit' => array('_vefl_change_layout_button'),
      '#suffix' => '</div>',
    );

    // Outputs regions selectbox for each filter.
    $types = array(
      'filters' => $this->view->display_handler->getHandlers('filter'),
      'actions' => Vefl::getFormActions(),
    );
    $regions = array();
    foreach ($layouts[$layout_id]['regions'] as $region_id => $region) {
      $regions[$region_id] = $region['label'];
    }
    foreach ($types as $type => $fields) {
      foreach ($fields as $id => $filter) {
        if ($type == 'filters') {
          if (!$filter->options['exposed']) {
            continue;
          }
          $filter = $filter->definition['title'];
        }

        $form['layout']['widget_region'][$id] = array(
          '#type' => 'select',
          '#title' => $filter,
          '#options' => $regions,
        );

        // Set default region for chosen layout.
        if (!empty($this->options['layout']['widget_region'][$id]) && !empty($regions[$this->options['layout']['widget_region'][$id]])) {
          $form['layout']['widget_region'][$id]['#default_value'] = $this->options['layout']['widget_region'][$id];
        }
      }
    }
    // Store regions in form_state to have it in options array.
    //$form_state['layout_regions'] = $regions;
  }

}
