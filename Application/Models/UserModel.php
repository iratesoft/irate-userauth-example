<?php

namespace Application\Models;

use Irate\System;

/**
* UserModel model
*/
class UserModel extends \Irate\Core\Model {

  // Set public class variables
  public $isGuest  = true;
  public $identityData = false;

  // Error is set during registration and login methods.
  public $error    = false;

  /**
   * Instantiate is ran after __construct in the parent class.
   * In this example, we will use this method to set
   * the session.
   */
  public function instantiate() {

    // Checking if a user session already exists.
    if ($this->session->userData('loggedIn') === true) {

      // Set the identity
      $this->setIdentity($this->session->userData('id'));

      // Set isGuest to false.
      $this->isGuest = false;
    }
  }

  /**
   * Handles login logic based on email
   * and password passed as arguments. If there is an
   * error, $this->error is set and can be accessed after
   * a return is made.
   * @param  string $email
   * @param  string $password
   * @return boolean
   */
  public function login($email, $password) {
    $userData = $this->get('email', $email);

    // Example of how to set error.
    if (!$userData) {
      $this->error = 'User does not exist.';
      return false;
    }

    if ($this->validatePassword($password, $userData['password'])) {
      // Setup a new session array
      $session = ['loggedIn' => true, 'id' => $userData['id']];

      // Use userData to store data.
      $this->session->setUserData($session);

      // Set the class identity variable.
      $this->setIdentity($userData['id']);
      return true;
    }

    $this->error = 'Password is incorrect.';
    return false;
  }

  public function register($email, $password, $confirmPassword) {
    $userData = $this->get('email', $email);

    // Example of how to set error.
    if ($userData !== false) {
      $this->error = 'User already exists.';
      return false;
    }

    if ($password !== $confirmPassword) {
      $this->error = 'Please confirm your password.';
      return false;
    }

    $res = $this->insert('user', [
      'email' => $email,
      'password' => sha1($password)
    ]);

    // If failed, set error.
    if (!$res) {
      $this->error = 'Something went wrong.';
      return false;
    }

    // Fetch data (TODO: Return new insert id in insert method)
    $userData = $this->get('email', $email);

    // Setup a new session array
    $session = ['loggedIn' => true, 'id' => $userData['id']];

    // Use userData to store data.
    $this->session->setUserData($session);

    // Set the class identity variable.
    $this->setIdentity($userData['id']);
    return true;
  }

  /**
   * Handles unsetting the userData
   * @return boolean
   */
  public function logout() {
    $this->session->unsetUserData();
    return true;
  }

  public function identity($field) {
    if (!isset($this->identityData[$field])) return false;
    return $this->identityData[$field];
  }

  /**
   * Sets the class identity variable
   * @param int $id
   */
  private function setIdentity($id) {
    $userData = $this->get('id', $id);
    $this->identityData = $userData;
    return true;
  }

  private function get($field = 'id', $value) {
    $sql = "SELECT * FROM user WHERE $field = :value";
    $statement = $this->db->client->prepare($sql);
    $statement->bindParam(':value', $value);
    $statement->execute();
    $data = $statement->fetch(\PDO::FETCH_ASSOC);
    return $data;
  }

  private function validatePassword($password, $hash) {
    if (sha1($password) === $hash) return true;
    return false;
  }
}
