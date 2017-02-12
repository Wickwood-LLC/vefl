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

    $options = Vefl::getLayoutsList();
    $layout_id = $this->options['layout']['layout_id'];

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
      '#options' => $options,
      '#title' => t('Layout'),
      '#default_value' => $layout_id,
    );
    $form['layout']['change'] = array(
      '#type' => 'submit',
      '#value' => t('Change'),
      '#submit' => array('_vefl_change_layout_button'),
      '#suffix' => '</div>',
    );

    // Go through each filter and add BEF options.
    foreach ($this->view->display_handler->getHandlers('filter') as $label => $filter) {
      if (!$filter->options['exposed']) {
        continue;
      }


    }
  }

}
