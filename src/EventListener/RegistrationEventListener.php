<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RegistrationEventListener implements EventSubscriberInterface {

  private $router;

  private $entityManager;

  private $tokenStorage;

  private $session;

  public function __construct(UrlGeneratorInterface $router, EntityManagerInterface $entityManager, SessionInterface $session, TokenStorageInterface $tokenStorage) {
    $this->router = $router;
    $this->entityManager = $entityManager;
    $this->session = $session;
    $this->tokenStorage = $tokenStorage;
  }

  /**
   * {@inheritDoc}
   */
  public static function getSubscribedEvents() {
    return [
      FOSUserEvents::REGISTRATION_CONFIRM => 'onRegistrationConfirm',
      FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
    ];
  }

  public function onRegistrationConfirm(GetResponseUserEvent $event) {

    $token = new UsernamePasswordToken(
      $event->getUser(),
      $event->getUser()->getPassword(),
      'main',
      $event->getUser()->getRoles()
    );
    $this->tokenStorage->setToken($token);
    $this->session->set('_security_main', serialize($token));
    $url = $this->router->generate('dashboard');
    $event->setResponse(new RedirectResponse($url));
  }

  public function onRegistrationSuccess(GetResponseUserEvent $event) {
    $user = $event->getUser();
    $user->setEnabled(TRUE);
    $this->entityManager->flush();
  }
}