<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller {

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authUtils
   *
   * @Route("/login", name="login")
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function login(Request $request, AuthenticationUtils $authUtils) {
    $errors = $authUtils->getLastAuthenticationError();
    $lastUserName = $authUtils->getLastUsername();
    return $this->render('security/login.html.twig', compact('errors', 'lastUserName'));
  }

  public function facebookLogin(Request $request) {

  }

  /**
   * @Route("/logout", name="fos_user_security_logout")
   */
  public function logout() {

  }
}