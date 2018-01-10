<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller {

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authUtils
   *
   * @Route("/login", name="fos_user_security_check")
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function login(Request $request, AuthenticationUtils $authUtils) {
    $errors = $authUtils->getLastAuthenticationError();
    dump($errors);
//    $lastUsername = $authUtils->getLastUsername();
    return $this->json(compact('error'));
  }

  public function facebookLogin(Request $request) {

  }

  /**
   * @Route("/login", name="fos_user_security_logout")
   */
  public function logout() {

  }
}