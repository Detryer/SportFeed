<?php

namespace App\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User extends BaseUser {

  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
  protected $facebook_id;

  /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
  protected $facebook_access_token;

  public function getFacebookId() {
    return $this->facebook_id;
  }

  public function setFacebookId($facebook_id) {
    $this->facebook_id = $facebook_id;
  }

  public function getFacebookAccessToken() {
    return $this->facebook_access_token;
  }

  public function setFacebookAccessToken($facebook_access_token) {
    $this->facebook_access_token = $facebook_access_token;
  }

  public function getSalt() {
    return NULL;
  }

  /**
   * Returns the roles granted to the user.
   *
   * @return array
   */
  public function getRoles() {
    return ['ROLE_USER'];
  }

  /**
   * Removes sensitive data from the user.
   */
  public function eraseCredentials() {
    $this->plainPassword = NULL;
  }

  /** @see \Serializable::serialize() */
  public function serialize() {
    return serialize([
      $this->id,
      $this->username,
      $this->password,
      // see section on salt below
      // $this->salt,
    ]);
  }

  /** @see \Serializable::unserialize() */
  public function unserialize($serialized) {
    list (
      $this->id,
      $this->username,
      $this->password,
      // see section on salt below
      // $this->salt
      ) = unserialize($serialized);
  }
}