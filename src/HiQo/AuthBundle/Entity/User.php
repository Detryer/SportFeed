<?php

namespace App\HiQo\AuthBundle\Entity;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements UserInterface {

  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /** @ORM\Column(name="username", type="string", length=255) */
  protected $username;

  /** @ORM\Column(name="email", type="string", length=255,) */
  protected $email;

  /** @ORM\Column(name="active", type="tinyint", length=1) */
  protected $active;

  /** @ORM\Column(name="password", type="string", length=255) */
  protected $password;

  /** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
  protected $facebook_id;

  /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
  protected $facebook_access_token;

  /** @ORM\Column(name="last_login", type="datetime", nullable=true) */
  protected $lastLogin;

  /** @ORM\Column(type="json_array") */
  private $roles = [];

  /**
   * @return int
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @param string $password
   *
   * @return $this
   */
  public function setPassword($password) {
    $this->password = $password;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getUsername() {
    return $this->username;
  }

  /**
   * @param mixed $username
   *
   * @return $this
   */
  public function setUsername($username) {
    $this->username = $username;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * @param mixed $email
   *
   * @return $this
   */
  public function setEmail($email) {
    $this->email = $email;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getFacebookId() {
    return $this->facebook_id;
  }

  /**
   * @param mixed $facebook_id
   *
   * @return $this
   */
  public function setFacebookId($facebook_id) {
    $this->facebook_id = $facebook_id;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getFacebookAccessToken() {
    return $this->facebook_access_token;
  }

  /**
   * @param mixed $facebook_access_token
   *
   * @return $this
   */
  public function setFacebookAccessToken($facebook_access_token) {
    $this->facebook_access_token = $facebook_access_token;
    return $this;
  }

  /**
   * Checks whether the user is enabled.
   *
   * Internally, if this method returns false, the authentication system
   * will throw a DisabledException and prevent login.
   *
   * @return bool true if the user is enabled, false otherwise
   *
   * @see DisabledException
   */
  public function isEnabled() {
    return $this->active;
  }

  /** @see \Serializable::serialize() */
  public function serialize() {
    return serialize([
      $this->id,
      $this->username,
      $this->email,
      $this->password,
    ]);
  }

  /** @see \Serializable::unserialize()
   * @param string $serialized
   */
  public function unserialize($serialized) {
    list (
      $this->id,
      $this->username,
      $this->email,
      $this->password,
      ) = unserialize($serialized);
  }

  public function setActive() {
    $this->active = TRUE;
  }

  /**
   * Sets the last login time.
   *
   * @param \DateTime $time
   *
   * @return void
   */
  public function setLastLogin(\DateTime $time = NULL) {
    $this->lastLogin = $time;
  }

  /**
   * Sets the super admin status.
   *
   * @param bool $boolean
   *
   * @return $this
   */
  public function setSuperAdmin($boolean) {
    if ($boolean) {
      $this->addRole('ROLE_SUPER_ADMIN');
      return $this;
    }
    $this->removeRole('ROLE_SUPER_ADMIN');
    return $this;
  }

  /**
   * Never use this to check if this user has access to anything!
   *
   * Use the AuthorizationChecker, or an implementation of AccessDecisionManager
   * instead, e.g.
   *
   *         $authorizationChecker->isGranted('ROLE_USER');
   *
   * @param string $role
   *
   * @return bool
   */
  public function hasRole($role) {
    return in_array($role, $this->roles);
  }

  /**
   * Sets the roles of the user.
   *
   * This overwrites any previous roles.
   *
   * @param array $roles
   *
   * @return $this
   */
  public function setRoles(array $roles) {
    $this->roles = $roles;
    return $this;
  }

  /**
   * Returns the roles granted to the user.
   *
   * <code>
   * public function getRoles()
   * {
   *     return array('ROLE_USER');
   * }
   * </code>
   *
   * Alternatively, the roles might be stored on a ``roles`` property,
   * and populated in any number of different ways when the user object
   * is created.
   *
   * @return array (Role|string)[] The user roles
   */
  public function getRoles() {
    return $this->roles;
  }

  /**
   * Adds a role to the user.
   *
   * @param string $role
   *
   * @return $this
   */
  public function addRole($role) {
    array_push($this->roles, $role);
    return $this;
  }

  /**
   * Removes a role to the user.
   *
   * @param string $role
   *
   * @return $this
   */
  public function removeRole($role) {
    if (($key = array_search($role, $this->roles)) !== FALSE) {
      unset($this->roles[$key]);
    }
    return $this;
  }

  /**
   * Tells if the the given user has the super admin role.
   *
   * @return bool
   */
  public function isSuperAdmin() {
    return in_array(self::ROLE_SUPER_ADMIN, $this->roles);
  }

  /**
   * @param bool $boolean
   *
   * @return self
   */
  public function setEnabled($boolean) {
    $this->active = $boolean;
    return $this;
  }
}