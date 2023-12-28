<?php

/**
 * @file
 * Contains \Drupal\cbi_dashboard\Controller\PageController.
 */

namespace Drupal\cbi_dashboard\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class PageController.
 *
 * @package Drupal\cbi_dashboard\Controller
 */
class PageController extends ControllerBase {
  /**
   * Content.
   *
   * @return array
   *   Return page content.
   */
  public function blankContent() {
    return [
        '#type' => 'markup',
        '#markup' => '',
    ];
  }

}
