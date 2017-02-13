<?php

namespace Drupal\vefl\Plugin\views\exposed_form;

use Drupal\views\Plugin\views\exposed_form\Basic;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Views;
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

    $options = $form_state->getValue('exposed_form_options');
    $layout_id = !empty($options['layout']['layout_id']) ? $options['layout']['layout_id'] : $this->options['layout']['layout_id'];
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
//      '#ajax' => array(
//        //'callback' => array($this, 'updateOptions'),
//        'callback' => array(get_class($this), 'updateOptions'),
//        'wrapper' => 'edit-block-region-wrapper',
//      ),
    );
    $form['layout']['change'] = array(
      '#type' => 'submit',
      '#value' => t('Change'),
      '#submit' => array(array($this, 'formSubmit11')),
      //'#submit' => array('_vefl_change_layout_button'),
      '#suffix' => '</div>',
//      '#ajax' => array(
//        'callback' => array(get_class($this), 'updateOptions'),
//        'wrapper' => 'edit-block-region-wrapper',
//      ),
    );
    $form['layout']['widget_region'] = VeflBasic::getRegionElement($layout_id, $layouts);
    // Store regions in form_state to have it in options array.
    //$form_state['layout_regions'] = $regions;

//    $item = &$this->options;
//
//    $view = $form_state->get('view');
//    $display_id = $form_state->get('display_id');
//    $type = $form_state->get('type');
//    $id = $form_state->get('id');
//    $view->getExecutable()->setHandler($display_id, $type, $id, $item);
//
//    $view->addFormToStack($form_state->get('form_key'), $display_id, $type, $id, TRUE, TRUE);
//
//    $view->cacheSet();
//    $form_state->set('rerender', TRUE);
//    $form_state->setRebuild();
//    $form_state->get('force_build_group_options', TRUE);
  }

  private function getRegionElement($layout_id, $layouts = array()) {
    if (empty($layouts)) {
      $layouts = Vefl::getLayouts();
    }
    $element = array(
      '#prefix' => '<div id="edit-block-region-wrapper">',
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

        $element[$id] = array(
          '#type' => 'select',
          '#title' => $filter,
          '#options' => $regions,
        );

        // Set default region for chosen layout.
        if (!empty($this->options['layout']['widget_region'][$id]) && !empty($regions[$this->options['layout']['widget_region'][$id]])) {
          $element[$id]['#default_value'] = $this->options['layout']['widget_region'][$id];
        }
      }
    }
    return $element;
  }

  function updateOptions($form, FormStateInterface &$form_state) {
//    $options = $form_state->getValue('exposed_form_options');
//    $layout_id = $options['layout']['layout_id'];
//    $layout = Vefl::getLayout($layout_id);
////
    $widget_region = &$form['options']['exposed_form_options']['layout']['widget_region'];
//
//    $regions = array();
//    foreach ($layout['regions'] as $region_id => $region) {
//      $regions[$region_id] = $region['label'];
//    }
//
//    foreach ($widget_region as $key => $item) {
//      if (!empty($item['#type']) && $item['#type'] == 'select') {
//        $widget_region[$key]['#options'] = $regions;
//        $widget_region[$key]['#default_value'] = reset(array_keys($regions));
//      }
//    }
//    $form_state->set('rerender', NULL);
//    $form_state->setRebuild(true);

//    $storage = $form_state->getStorage();
//    $display_id = $storage['display_id'];
////    $view = $storage['view']->get('storage');
//
//    $view = Views::getView('vefl_test');
//    $view->setDisplay($display_id);
//    $exposed_form = $view->display_handler->getOption('exposed_form');

//    $exposed_form['options']['layout']['layout_id'] = 'vefl_onecol';
//    $view->display_handler->setOption('exposed_form', $exposed_form);
//    $view->save();

//    $display->handler->options_submit($form, $form_state);

//    return array('#markup' => 'aaaaa');
    return $widget_region;
  }

  /**
   * Form submission handler for ContentTranslationHandler::entityFormAlter().
   *
   * Takes care of content translation deletion.
   */
  function formSubmit11($form, FormStateInterface $form_state) {
//    $display = &$form_state['view']->display[$form_state['display_id']];
//    $display->handler->options_submit($form, $form_state);
//
//    views_ui_cache_set($form_state['view']);
//    $form_state['rerender'] = TRUE;
//    $form_state['rebuild'] = TRUE;



//    $options = $form_state->getValue('exposed_form_options');

//    $options['layout']['layout_id'] = 'vefl_onecol';
//    $form_state->setValue('exposed_form_options', $options);
//    $this->options['layout']['layout_id'] = $options['layout']['layout_id'];
//    $form_object = $form_state->getFormObject();

//    $form_state->set('rerender', NULL);
//    $form_state->setRebuild(true);


//
//    $item = &$this->options;
//    // flip. If the filter was a group, set back to a standard filter.
//    $item['is_grouped'] = empty($item['is_grouped']);
//
//    $view = $form_state->get('view');
////    $display_id = $form_state->get('display_id');
////    $type = $form_state->get('type');
////    $id = $form_state->get('id');
////    $view->getExecutable()->setHandler($display_id, $type, $id, $item);
////
////    $view->addFormToStack($form_state->get('form_key'), $display_id, $type, $id, TRUE, TRUE);


    $view = $form_state->get('view');
    $display_id = $form_state->get('display_id');

    $display = &$view->getExecutable()->displayHandlers->get($display_id);
    // optionsOverride toggles the override of this section.
    $display->optionsOverride($form, $form_state);
    $display->submitOptionsForm($form, $form_state);


    $view->cacheSet();
    $form_state->set('rerender', TRUE);
    $form_state->setRebuild();
  }
}
