<?php

namespace App\Entity;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;

class FOSUBUserProvider extends BaseClass {

  public function connect(UserInterface $user, UserResponseInterface $response) {
    dump($user);
    $property = $this->getProperty($response);

    $username = $response->getUsername();

    // On connect, retrieve the access token and the user id
    $service = $response->getResourceOwner()->getName();

    $setter = 'set' . ucfirst($service);
    $setter_id = $setter . 'Id';
    $setter_token = $setter . 'AccessToken';

    // Disconnect previously connected users
    if (NULL !== $previousUser = $this->userManager->findUserBy([$property => $username])) {
      $previousUser->$setter_id(NULL);
      $previousUser->$setter_token(NULL);
      $this->userManager->updateUser($previousUser);
    }

    // Connect using the current user
    $user->$setter_id($username);
    $user->$setter_token($response->getAccessToken());
    $this->userManager->updateUser($user);
  }

  public function loadUserByOAuthUserResponse(UserResponseInterface $response) {
    dump($response->getResponse());
    die();
    $data = $response->getResponse();
    $username = $response->getUsername();
    $email = $response->getEmail() ? $response->getEmail() : $username;
    $user = $this->userManager->findUserBy([$this->getProperty($response) => $username]);

    // If the user is new
    if (NULL === $user) {
      $service = $response->getResourceOwner()->getName();
      $setter = 'set' . ucfirst($service);
      $setter_id = $setter . 'Id';
      $setter_token = $setter . 'AccessToken';
      // create new user here
      $user = $this->userManager->createUser();
      $user->$setter_id($username);
      $user->$setter_token($response->getAccessToken());

      //I have set all requested data with the user's username
      //modify here with relevant data
      $user->setUsername($this->generateRandomUsername($username, $response->getResourceOwner()
        ->getName()));
      $user->setEmail($email);
      $user->setPassword($username);
      $user->setEnabled(TRUE);
      $this->userManager->updateUser($user);
      return $user;
    }

    // If the user exists, use the HWIOAuth
    $user = parent::loadUserByOAuthUserResponse($response);

    $serviceName = $response->getResourceOwner()->getName();

    $setter = 'set' . ucfirst($serviceName) . 'AccessToken';

    // Update the access token
    $user->$setter($response->getAccessToken());

    return $user;
  }

  /**
   * Generates a random username with the given
   * e.g 12345_github, 12345_facebook
   *
   * @param string $username
   * @param string $serviceName
   *
   * @return string
   */
  private function generateRandomUsername($username, $serviceName) {
    if (!$username) {
      $username = "user" . uniqid((rand()), TRUE) . $serviceName;
    }

    return $username . "_" . $serviceName;
  }
}