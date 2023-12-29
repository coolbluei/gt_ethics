<?php

namespace Drupal\gt_help_topics\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'UnitHelpTopicBlock' block.
 *
 * @Block(
 *  id = "unit_help_topic_block",
 *  admin_label = @Translation("Unit help topic block"),
 * )
 */
class UnitHelpTopicBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['unit'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Unit'),
      '#default_value' => $this->configuration['unit'],
      '#maxlength' => 255,
      '#size' => 64,
      '#weight' => '0',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['unit'] = $form_state->getValue('unit');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'unit_help_topic_block';
    $build['#content'][] = $this->configuration['unit'];

    return $build;
  }

}
