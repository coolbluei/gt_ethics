<?php

namespace Drupal\gt_help_topics\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the GT Help Topic entity.
 *
 * @ingroup gt_help_topics
 *
 * @ContentEntityType(
 *   id = "gt_help_topic_entity",
 *   label = @Translation("GT Help Topic"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\gt_help_topics\GtHelpTopicEntityListBuilder",
 *     "views_data" = "Drupal\gt_help_topics\Entity\GtHelpTopicEntityViewsData",
 *     "translation" = "Drupal\gt_help_topics\GtHelpTopicEntityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\gt_help_topics\Form\GtHelpTopicEntityForm",
 *       "add" = "Drupal\gt_help_topics\Form\GtHelpTopicEntityForm",
 *       "edit" = "Drupal\gt_help_topics\Form\GtHelpTopicEntityForm",
 *       "delete" = "Drupal\gt_help_topics\Form\GtHelpTopicEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\gt_help_topics\GtHelpTopicEntityHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\gt_help_topics\GtHelpTopicEntityAccessControlHandler",
 *   },
 *   base_table = "gt_help_topic_entity",
 *   data_table = "gt_help_topic_entity_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer gt help topic entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/gt_help_topic_entity/{gt_help_topic_entity}",
 *     "add-form" = "/admin/structure/gt_help_topic_entity/add",
 *     "edit-form" = "/admin/structure/gt_help_topic_entity/{gt_help_topic_entity}/edit",
 *     "delete-form" = "/admin/structure/gt_help_topic_entity/{gt_help_topic_entity}/delete",
 *     "collection" = "/admin/structure/gt_help_topic_entity",
 *   },
 *   field_ui_base_route = "gt_help_topic_entity.settings"
 * )
 */
class GtHelpTopicEntity extends ContentEntityBase implements GtHelpTopicEntityInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Admin Name'))
      ->setDescription(t('The name of the GT Help Topic entity.'))
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['status']->setDescription(t('A boolean indicating whether the GT Help Topic is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
