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

  /**
   * @var string
   */
  protected $username;

  /**
   * @var string
   */
  protected $usernameCanonical;

  /**
   * @var string
   */
  protected $email;

  /**
   * @var string
   */
  protected $emailCanonical;

  /**
   * @var bool
   */
  protected $enabled;

  /**
   * The salt to use for hashing.
   *
   * @var string
   */
  protected $salt;

  /**
   * Encrypted password. Must be persisted.
   *
   * @var string
   */
  protected $password;

  /**
   * Plain password. Used for model validation. Must not be persisted.
   *
   * @var string
   */
  protected $plainPassword;

  /**
   * @var \DateTime
   */
  protected $lastLogin;

  /**
   * Random string sent to the user email address in order to verify it.
   *
   * @var string
   */
  protected $confirmationToken;

  /**
   * @var \DateTime
   */
  protected $passwordRequestedAt;

  /**
   * @var Collection
   */
  protected $groups;

  /**
   * @var array
   */
  protected $roles;

  /** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
  protected $facebook_id;

  /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
  protected $facebook_access_token;

  /**
   * User constructor.
   */
  public function __construct()
  {
    $this->enabled = false;
    $this->roles = array();
  }

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