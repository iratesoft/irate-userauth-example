<?php

namespace Application\Controllers;

use Application\Models\UserModel;

/**
 * Site controller
 */
class Site extends \Irate\Core\Controller {

  /**
   * Application site index
   */
  protected function index() {
    $User = new UserModel();
    return $this->view->renderTemplate('site/index', ['user' => $User]);
  }
}
