<?php

namespace Drupal\gt_help_topics\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\TermStorageInterface;

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
  public function blockForm($form, FormStateInterface $form_state)
  {
    $form['intro'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Intro Text'),
      '#default_value' => $this->configuration['intro'],
      '#maxlength' => 255,
      '#size' => 64,
      '#weight' => '0',
    ];
    $form['unit_select'] = [
      '#type' => 'select',
      '#title' => $this->t('Select specific Unit'),
      '#options' => $this->buildUnitSelect(),
      '#default_value' => $this->configuration['unit_select'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['intro'] = $form_state->getValue('intro');
    $this->configuration['unit_select'] = $form_state->getValue('unit_select');

  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'unit_help_topic_block';
    $build['#content']['intro'] = $this->configuration['intro'];
    $build['#content']['unit_select'] = $this->getUnitName($this->configuration['unit_select']);
    $build['#content']['unit_topics'] = $this->getRenderArrayForUnitTopics($this->configuration['unit_select']);

    return $build;
  }

  protected function buildUnitSelect() {
    /** @var TermStorageInterface $termStorage */
    $termStorage = \Drupal::service('entity_type.manager')->getStorage("taxonomy_term");
    $terms = $termStorage->loadTree('units', $parent = 0, $max_depth = NULL, $load_entities = FALSE);
    $results = [];
    foreach ($terms as $term) {
      $results[$term->tid] = $term->name;
    }
    return $results;
  }

  protected function getRenderArrayForUnitTopics($unit_id) {
    /** @var EntityStorageInterface $helpTopicManager */
    $helpTopicManager = \Drupal::service('entity_type.manager')->getStorage('gt_help_topic_entity');
    // Load Topics by unit id.
    $options = $helpTopicManager->loadByProperties([
      'field_unit' => $unit_id,
    ]);
    if (empty($options)) {
      return [
        '#type' => 'markup',
        '#markup' => 'Do not have help topics for unit id = ' . $unit_id . '.',
      ];
    }
    // build select list???
    return [];
  }

  protected function getUnitName($unit_id) {
    $unitArray = $this->buildUnitSelect();
    return $unitArray[$unit_id];
  }

}
