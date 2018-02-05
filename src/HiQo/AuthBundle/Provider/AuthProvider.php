<?php

namespace App\HiQo\AuthBundle\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Provider\UserAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AuthProvider extends UserAuthenticationProvider implements AuthenticationProviderInterface {
  private $userProvider;

  public function __construct(UserProviderInterface $userProvider, UserCheckerInterface $userChecker)
  {
    $this->userProvider = $userProvider;
  }

  /**
   * Retrieves the user from an implementation-specific location.
   *
   * @param string $username The username to retrieve
   * @param UsernamePasswordToken $token The Token
   *
   * @return UserInterface The user
   *
   * @throws AuthenticationException if the credentials could not be validated
   */
  protected function retrieveUser($username, UsernamePasswordToken $token) {
    $user = $this->userProvider->loadUserByUsername($username);
    if(!$user) {
      throw new UsernameNotFoundException('User not found!');
    }
    if(!$token instanceof UsernamePasswordToken) {
      throw new UsernameNotFoundException('User not found!');
    }
  }

  /**
   * Does additional checks on the user and token (like validating the
   * credentials).
   *
   * @throws AuthenticationException if the credentials could not be validated
   */
  protected function checkAuthentication(UserInterface $user, UsernamePasswordToken $token) {
    // TODO: Implement checkAuthentication() method.
  }
}