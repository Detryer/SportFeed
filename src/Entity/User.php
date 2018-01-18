<?php

namespace App\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

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
  private $id;

  /**
   * @ORM\Column(type="string", length=255, unique=true)
   * @Assert\NotBlank()
   * @Assert\Email()
   */
  private $email;

  /**
   * @ORM\Column(type="string", length=255, unique=true)
   * @Assert\NotBlank()
   */
  private $username;

  /**
   * @Assert\NotBlank()
   * @Assert\Length(max=4096)
   */
  private $plainPassword;

  /**
   * The below length depends on the "algorithm" you use for encoding
   * the password, but this works well with bcrypt.
   *
   * @ORM\Column(type="string", length=64)
   */
  private $password;

  public function getEmail() {
    return $this->email;
  }

  public function setEmail($email) {
    $this->email = $email;
  }

  public function getUsername() {
    return $this->username;
  }

  public function setUsername($username) {
    $this->username = $username;
  }

  public function getPlainPassword() {
    return $this->plainPassword;
  }

  public function setPlainPassword($password) {
    $this->plainPassword = $password;
  }

  public function getPassword() {
    return $this->password;
  }

  public function setPassword($password) {
    $this->password = $password;
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