<?php

declare(strict_types=1);

namespace Drupal\ai_google_analytics\Controller;

use Drupal\canvas\Entity\Page;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityMalformedException;
use Drupal\Core\Link;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for the AI Analytics Review admin page.
 */
class GoogleAnalyticsReviewController extends ControllerBase {

  /**
   * Constructs a GoogleAnalyticsReviewController.
   *
   * @param \Drupal\Core\State\StateInterface $state
   *   The state service.
   */
  public function __construct(
    protected readonly StateInterface $state,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('state'),
    );
  }

  /**
   * Builds the analytics review table.
   *
   * @return array
   *   A render array containing the review table.
   */
  public function content(): array {
    $context_data = $this->state->get('ai_google_analytics.context_data', []);
    $header = [
      'title' => $this->t('Page'),
      'summary' => $this->t('Summary'),
      'link' => $this->t('Operations'),
    ];

    $rows = [];
    foreach ($context_data as $id => $data) {
      $page = Page::load($id);
      if (!$page) {
        continue;
      }

      $ai_message_prefix = "This page is underperforming against its Google Analytics goals. A summary of the page's performance is below.\r\n\r\n";
      $ai_message_suffix = "\r\n\r\nReview the page layout and provide some suggestions to improve the failing metric(s).";

      try {
        $rows[] = [
          'title' => Link::fromTextAndUrl($page->label(), $page->toUrl()),
          'summary' => $data['summary'],
          'link' => Link::createFromRoute($this->t('Work on it'), 'canvas.boot.entity', [
            'entity_type' => 'canvas_page',
            'entity' => $page->id(),
          ],
            [
              'query' => [
                'ai_message' => $ai_message_prefix . $data['summary'] . $ai_message_suffix,
              ],
              'attributes' => [
                'target' => '_blank',
                'class' => ['button', 'button--secondary'],
              ],
            ])->toString(),
        ];
      }
      catch (EntityMalformedException $e) {
        $this->getLogger('ai_google_analytics')->error($e->getMessage());
      }
    }

    return [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('No content found.'),
      '#cache' => ['max-age' => 0],
    ];
  }

}
