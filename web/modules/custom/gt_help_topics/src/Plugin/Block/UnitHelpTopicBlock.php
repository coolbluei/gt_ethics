<?php

namespace Drupal\gt_help_topics\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\Url;
use Drupal\safe_field_getter\SafeFieldGetter;
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
    list($selectOptions, $targets) = $this->getRenderArrayForUnitTopics($this->configuration['unit_select']);
//    $build['#content']['unit_topics'] = [
//      '#type' => 'radios',
//      '#title' => t('I want'),
//      '#default' => 0,
//      '#options' => $selectOptions,
//    ];
    $build['#content']['raw_options'] = $selectOptions;
    // Add library and settings.
    $build['#attached']['library'][] = 'gt_help_topics/gthelptopics';
    $build['#attached']['drupalSettings']['helpTopicsButtonTargets'] = $targets;

    // Set cache tags for unit.

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
    $select_options = [];
    $targets = [ 0 => ''];
    foreach ($options as $option) {
      //$select_options[$option->id()] = SafeFieldGetter::firstSimple($option, 'field_question_topic');
      $select_options[] = [
        'id' => $option->id(),
        'label' => SafeFieldGetter::firstSimple($option, 'field_question_topic'),
      ];
      $linkField = SafeFieldGetter::firstComplex($option, 'field_destination');
      $targets[$option->id()] = \Drupal\Core\Url::fromUri($linkField['uri'],['absolute' => TRUE])->toString();
    }
    return [$select_options, $targets];
  }

  protected function getUnitName($unit_id) {
    $unitArray = $this->buildUnitSelect();
    return $unitArray[$unit_id];
  }
  public function getCacheTags() {
    //With this when your node change your block will rebuild
    if ($node = \Drupal::routeMatch()->getParameter('node')) {
      //if there is node add its cachetag
      return Cache::mergeTags(parent::getCacheTags(), array('node:' . $node->id()));
    } else {
      //Return default tags instead.
      return parent::getCacheTags();
    }
  }

  public function getCacheContexts() {
    //if you depends on \Drupal::routeMatch()
    //you must set context of this block with 'route' context tag.
    //Every new route this block will rebuild
    return Cache::mergeContexts(parent::getCacheContexts(), array('route'));
  }
}
