<?php

namespace App\HiQo\AuthBundle\Controller;

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

  /**
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function socialRegister(Request $request) {
    if (isset($request->type)) {
      switch ($request->type) {
        case 'facebook':
          return $this->facebookRegister($request);
        default:
          return $this->registerFailed();
      }
    }
    //    $this->get('security.token_storage')->setToken($token);
    //    $this->get('session')->set('_security_main', serialize($token));
  }

  protected function facebookRegister(Request $request) {
    if (isset($request->token, $request->facebookId, $request->email)) {
      $user = new User();
      $user->setFacebookId($request->facebookId);
      $user->setEmail($request->email);
      $user->setFacebookAccessToken($request->token);
      $em = $this->getDoctrine()->getManager();
      $em->persist($user);
      $em->flush();
      return $this->registerSuccess('dashboard');
    }
    return $this->registerFailed();
  }

  /**
   * Method for successful register
   * @param string $url Name of route to redirect if register successful
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  protected function registerSuccess($url = '/') {
    return $this->json([
      'status' => 'success',
      'redirect' => $this->generateUrl($url),
    ]);
  }

  /**
   * Method for failed register
   * @param string $url Name of route to redirect if register fails
   * @param string $message Error message
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  protected function registerFailed($url = 'login', $message = 'Authentication failed') {
    return $this->json([
      'status' => 'error',
      'message' => $message,
      'redirect' => $this->generateUrl($url),
    ]);
  }
}