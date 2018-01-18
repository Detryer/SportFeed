<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller {

  /**
   * @Route("/register", name="user_registration")
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
   */
  public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder) {
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
      $user->setPassword($password);

      $em = $this->getDoctrine()->getManager();
      $em->persist($user);
      $em->flush();

      $token = new UsernamePasswordToken(
        $user,
        $password,
        'main',
        $user->getRoles()
      );

      $this->get('security.token_storage')->setToken($token);
      $this->get('session')->set('_security_main', serialize($token));

      $this->addFlash('success', 'You are now successfully registered');
      return $this->redirectToRoute('dashboard');
    }

    return $this->render(
      'security/register.html.twig',
      ['form' => $form->createView()]
    );
  }
}