<?php
/**
 * @file
 * Client class.
 */

namespace QuickPay\API;

/**
 * Client class.
 *
 * @since 1.0.0
 *
 * @package QuickPay
 *
 * @category Class
 */
class Client {
  /**
   * Contains cURL instance.
   *
   * @access public
   */
  public $ch;

  /**
   * Contains the authentication string.
   *
   * @access protected
   */
  protected $authString;

  /**
   * Instantiate object.
   */
  public function __construct($auth_string = '') {
    // Check if lib cURL is enabled.
    if (!function_exists('curl_init')) {
      throw new Exception('Lib cURL must be enabled on the server');
    }

    // Set auth string property.
    $this->authString = $auth_string;

    // Instantiate cURL object.
    $this->authenticate();
  }

  /**
   * Closes the current cURL connection.
   *
   * @access public
   */
  public function shutdown() {
    if (!empty($this->ch)) {
      curl_close($this->ch);
    }
  }

  /**
   * Create a cURL instance with authentication headers.
   *
   * @access public
   */
  protected function authenticate() {
    $this->ch = curl_init();

    $headers = array(
      'Accept-Version: v10',
      'Accept: application/json',
    );

    if (!empty($this->authString)) {
      $headers[] = 'Authorization: Basic ' . base64_encode($this->authString);
    }

    $options = array(
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_SSL_VERIFYPEER => TRUE,
      CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
      CURLOPT_HTTPHEADER => $headers,
    );

    curl_setopt_array($this->ch, $options);
  }

}
