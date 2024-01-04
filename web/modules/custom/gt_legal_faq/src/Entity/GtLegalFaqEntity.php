<?php

namespace Drupal\gt_legal_faq\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EditorialContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the GT Legal FAQ entity.
 *
 * @ingroup gt_legal_faq
 *
 * @ContentEntityType(
 *   id = "gt_legal_faq_entity",
 *   label = @Translation("GT Legal FAQ"),
 *   handlers = {
 *     "storage" = "Drupal\gt_legal_faq\GtLegalFaqEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\gt_legal_faq\GtLegalFaqEntityListBuilder",
 *     "views_data" = "Drupal\gt_legal_faq\Entity\GtLegalFaqEntityViewsData",
 *     "translation" = "Drupal\gt_legal_faq\GtLegalFaqEntityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\gt_legal_faq\Form\GtLegalFaqEntityForm",
 *       "add" = "Drupal\gt_legal_faq\Form\GtLegalFaqEntityForm",
 *       "edit" = "Drupal\gt_legal_faq\Form\GtLegalFaqEntityForm",
 *       "delete" = "Drupal\gt_legal_faq\Form\GtLegalFaqEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\gt_legal_faq\GtLegalFaqEntityHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\gt_legal_faq\GtLegalFaqEntityAccessControlHandler",
 *   },
 *   base_table = "gt_legal_faq_entity",
 *   data_table = "gt_legal_faq_entity_field_data",
 *   revision_table = "gt_legal_faq_entity_revision",
 *   revision_data_table = "gt_legal_faq_entity_field_revision",
 *   show_revision_ui = TRUE,
 *   translatable = TRUE,
 *   admin_permission = "administer gt legal faq entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
*   revision_metadata_keys = {
*     "revision_user" = "revision_uid",
*     "revision_created" = "revision_timestamp",
*     "revision_log_message" = "revision_log"
*   },
 *   links = {
 *     "canonical" = "/admin/structure/gt_legal_faq_entity/{gt_legal_faq_entity}",
 *     "add-form" = "/admin/structure/gt_legal_faq_entity/add",
 *     "edit-form" = "/admin/structure/gt_legal_faq_entity/{gt_legal_faq_entity}/edit",
 *     "delete-form" = "/admin/structure/gt_legal_faq_entity/{gt_legal_faq_entity}/delete",
 *     "version-history" = "/admin/structure/gt_legal_faq_entity/{gt_legal_faq_entity}/revisions",
 *     "revision" = "/admin/structure/gt_legal_faq_entity/{gt_legal_faq_entity}/revisions/{gt_legal_faq_entity_revision}/view",
 *     "revision_revert" = "/admin/structure/gt_legal_faq_entity/{gt_legal_faq_entity}/revisions/{gt_legal_faq_entity_revision}/revert",
 *     "revision_delete" = "/admin/structure/gt_legal_faq_entity/{gt_legal_faq_entity}/revisions/{gt_legal_faq_entity_revision}/delete",
 *     "translation_revert" = "/admin/structure/gt_legal_faq_entity/{gt_legal_faq_entity}/revisions/{gt_legal_faq_entity_revision}/revert/{langcode}",
 *     "collection" = "/admin/structure/gt_legal_faq_entity",
 *   },
 *   field_ui_base_route = "gt_legal_faq_entity.settings"
 * )
 */
class GtLegalFaqEntity extends EditorialContentEntityBase implements GtLegalFaqEntityInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = parent::urlRouteParameters($rel);

    if ($rel === 'revision_revert' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }
    elseif ($rel === 'revision_delete' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }

    return $uri_route_parameters;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

//    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
//      $translation = $this->getTranslation($langcode);
//
//      // If no owner has been set explicitly, make the anonymous user the owner.
//      if (!$translation->getOwner()) {
//        $translation->setOwnerId(0);
//      }
//    }
//
//    // If no revision author has been set explicitly,
//    // make the gt_legal_faq_entity owner the revision author.
//    if (!$this->getRevisionUser()) {
//      $this->setRevisionUserId($this->getOwnerId());
//    }
  }

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
      ->setLabel(t('Question'))
      ->setDescription(t('The Question of the GT Legal FAQ entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 512,
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

    $fields['status']->setDescription(t('A boolean indicating whether the GT Legal FAQ is published.'))
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

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }

}
