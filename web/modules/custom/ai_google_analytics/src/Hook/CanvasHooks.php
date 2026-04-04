<?php

declare(strict_types=1);

namespace Drupal\ai_google_analytics\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Hook implementations for Canvas library integration.
 */
class CanvasHooks {

  /**
   * Implements hook_library_info_alter().
   *
   * Attaches the AI panel bridge script to the Canvas UI library so the
   * analytics review page can pre-populate the AI chat panel.
   */
  #[Hook('library_info_alter')]
  public function libraryInfoAlter(array &$libraries, string $extension): void {
    if ($extension === 'canvas' && isset($libraries['canvas-ui'])) {
      $libraries['canvas-ui']['dependencies'][] = 'ai_google_analytics/ai_panel_bridge';
    }
  }

}
