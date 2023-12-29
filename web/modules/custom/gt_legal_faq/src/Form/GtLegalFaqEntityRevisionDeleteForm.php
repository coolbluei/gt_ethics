<?php

namespace Drupal\gt_legal_faq\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a GT Legal FAQ revision.
 *
 * @ingroup gt_legal_faq
 */
class GtLegalFaqEntityRevisionDeleteForm extends ConfirmFormBase {


  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * The GT Legal FAQ revision.
   *
   * @var \Drupal\gt_legal_faq\Entity\GtLegalFaqEntityInterface
   */
  protected $revision;

  /**
   * The GT Legal FAQ storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $gtLegalFaqEntityStorage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->gtLegalFaqEntityStorage = $container->get('entity_type.manager')->getStorage('gt_legal_faq_entity');
    $instance->connection = $container->get('database');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'gt_legal_faq_entity_revision_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete the revision from %revision-date?', [
      '%revision-date' => $this->dateFormatter->format($this->revision->getRevisionCreationTime()),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.gt_legal_faq_entity.version_history', ['gt_legal_faq_entity' => $this->revision->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $gt_legal_faq_entity_revision = NULL) {
    $this->revision = $this->GtLegalFaqEntityStorage->loadRevision($gt_legal_faq_entity_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->GtLegalFaqEntityStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')->notice('GT Legal FAQ: deleted %title revision %revision.', ['%title' => $this->revision->label(), '%revision' => $this->revision->getRevisionId()]);
    $this->messenger()->addMessage(t('Revision from %revision-date of GT Legal FAQ %title has been deleted.', ['%revision-date' => $this->dateFormatter->format($this->revision->getRevisionCreationTime()), '%title' => $this->revision->label()]));
    $form_state->setRedirect(
      'entity.gt_legal_faq_entity.canonical',
       ['gt_legal_faq_entity' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {gt_legal_faq_entity_field_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.gt_legal_faq_entity.version_history',
         ['gt_legal_faq_entity' => $this->revision->id()]
      );
    }
  }

}
