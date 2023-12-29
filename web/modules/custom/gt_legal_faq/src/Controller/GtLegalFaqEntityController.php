<?php

namespace Drupal\gt_legal_faq\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\gt_legal_faq\Entity\GtLegalFaqEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class GtLegalFaqEntityController.
 *
 *  Returns responses for GT Legal FAQ routes.
 */
class GtLegalFaqEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * Displays a GT Legal FAQ revision.
   *
   * @param int $gt_legal_faq_entity_revision
   *   The GT Legal FAQ revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($gt_legal_faq_entity_revision) {
    $gt_legal_faq_entity = $this->entityTypeManager()->getStorage('gt_legal_faq_entity')
      ->loadRevision($gt_legal_faq_entity_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('gt_legal_faq_entity');

    return $view_builder->view($gt_legal_faq_entity);
  }

  /**
   * Page title callback for a GT Legal FAQ revision.
   *
   * @param int $gt_legal_faq_entity_revision
   *   The GT Legal FAQ revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($gt_legal_faq_entity_revision) {
    $gt_legal_faq_entity = $this->entityTypeManager()->getStorage('gt_legal_faq_entity')
      ->loadRevision($gt_legal_faq_entity_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $gt_legal_faq_entity->label(),
      '%date' => $this->dateFormatter->format($gt_legal_faq_entity->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a GT Legal FAQ.
   *
   * @param \Drupal\gt_legal_faq\Entity\GtLegalFaqEntityInterface $gt_legal_faq_entity
   *   A GT Legal FAQ object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(GtLegalFaqEntityInterface $gt_legal_faq_entity) {
    $account = $this->currentUser();
    $gt_legal_faq_entity_storage = $this->entityTypeManager()->getStorage('gt_legal_faq_entity');

    $langcode = $gt_legal_faq_entity->language()->getId();
    $langname = $gt_legal_faq_entity->language()->getName();
    $languages = $gt_legal_faq_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $gt_legal_faq_entity->label()]) : $this->t('Revisions for %title', ['%title' => $gt_legal_faq_entity->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all gt legal faq revisions") || $account->hasPermission('administer gt legal faq entities')));
    $delete_permission = (($account->hasPermission("delete all gt legal faq revisions") || $account->hasPermission('administer gt legal faq entities')));

    $rows = [];

    $vids = $gt_legal_faq_entity_storage->revisionIds($gt_legal_faq_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\gt_legal_faq\Entity\GtLegalFaqEntityInterface $revision */
      $revision = $gt_legal_faq_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $gt_legal_faq_entity->getRevisionId()) {
          $link = Link::fromTextAndUrl($date, new Url('entity.gt_legal_faq_entity.revision', [
            'gt_legal_faq_entity' => $gt_legal_faq_entity->id(),
            'gt_legal_faq_entity_revision' => $vid,
          ]))->toString();
        }
        else {
          $link = $gt_legal_faq_entity->toLink($date)->toString();
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.gt_legal_faq_entity.translation_revert', [
                'gt_legal_faq_entity' => $gt_legal_faq_entity->id(),
                'gt_legal_faq_entity_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.gt_legal_faq_entity.revision_revert', [
                'gt_legal_faq_entity' => $gt_legal_faq_entity->id(),
                'gt_legal_faq_entity_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.gt_legal_faq_entity.revision_delete', [
                'gt_legal_faq_entity' => $gt_legal_faq_entity->id(),
                'gt_legal_faq_entity_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['gt_legal_faq_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
