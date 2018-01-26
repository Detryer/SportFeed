<?php

namespace App\Controller;

use FOS\UserBundle\Controller\SecurityController as FOSSecurityController;

class SecurityController extends FOSSecurityController {

  protected function renderLogin(array $data) {
    return $this->render('security/login.html.twig', $data);
  }
}