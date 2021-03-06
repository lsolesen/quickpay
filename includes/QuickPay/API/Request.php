<?php
/**
 * @file
 * Request class.
 */

namespace QuickPay\API;

use QuickPay\API\Constants;
use QuickPay\API\Response;

/**
 * QuickPay_Request.
 *
 * @since 1.0.0
 *
 * @package QuickPay
 *
 * @category Class
 */
class Request {

  /**
   * Contains QuickPay_Client instance.
   *
   * @access protected
   */
  protected $client;

  /**
   * Instantiates the object.
   *
   * @access public
   */
  public function __construct($client) {
    $this->client = $client;
  }


  /**
   * Performs an API GET request.
   *
   * @param string $path
   *        The path to request.
   * @param array $query
   *        The query params.
   *
   * @return resource Response
   *         The response resource.
   */
  public function get($path, $query = array()) {
    // Add query parameters to $path?
    if ($query) {
      if (strpos($path, '?') === FALSE) {
        $path .= '?' . http_build_query($query);
      }
      else {
        $path .= ini_get('arg_separator.output') . http_build_query($query);
      }
    }

    // Set the request params.
    $this->setUrl($path);

    // Start the request and return the response.
    return $this->execute('GET');
  }


  /**
   * Performs an API POST request.
   *
   * @access public
   *
   * @return Response
   *         The response.
   */
  public function post($path, $form = array()) {
    // Set the request params.
    $this->setUrl($path);

    // Start the request and return the response.
    return $this->execute('POST', $form);
  }


  /**
   * Performs an API PUT request.
   *
   * @access public
   *
   * @return Response
   *         The response.
   */
  public function put($path, $form = array()) {
    // Set the request params.
    $this->setUrl($path);

    // Start the request and return the response.
    return $this->execute('PUT', $form);
  }


  /**
   * Performs an API PATCH request.
   *
   * @access public
   *
   * @return Response
   *         The response.
   */
  public function patch($path, $form = array()) {
    // Set the request params.
    $this->setUrl($path);

    // Start the request and return the response.
    return $this->execute('PATCH', $form);
  }


  /**
   * Performs an API DELETE request.
   *
   * @access public
   *
   * @return Response
   *         The response.
   */
  public function delete($path, $form = array()) {
    // Set the request params.
    $this->setUrl($path);

    // Start the request and return the response.
    return $this->execute('DELETE', $form);
  }


  /**
   * Takes an API request string and appends it to the API url.
   *
   * @access protected
   */
  protected function setUrl($params) {
    curl_setopt($this->client->ch, CURLOPT_URL, Constants::API_URL . trim($params, '/'));
  }


  /**
   * Performs the prepared API request.
   *
   * @param string $request_type
   *         The request type.
   * @param array $form
   *         The form.
   *
   * @return Response
   *         The response.
   */
  protected function execute($request_type, $form = array()) {
    // Set the HTTP request type.
    curl_setopt($this->client->ch, CURLOPT_CUSTOMREQUEST, $request_type);

    // Send additional data along with the API request (if provided).
    if (is_array($form) && !empty($form)) {
      curl_setopt($this->client->ch, CURLOPT_POSTFIELDS, http_build_query($form));
    }

    // Store received headers in temporary memory file, remember sent headers.
    $fh_header = fopen('php://memory', 'w+');
    curl_setopt($this->client->ch, CURLOPT_WRITEHEADER, $fh_header);
    curl_setopt($this->client->ch, CURLINFO_HEADER_OUT, TRUE);

    // Execute the request.
    $response_data = curl_exec($this->client->ch);

    if (curl_errno($this->client->ch) !== 0) {
      // An error occurred.
      fclose($fh_header);
      throw new Exception(curl_error($this->client->ch), curl_errno($this->client->ch));
    }

    // Grab the headers.
    $sent_headers = curl_getinfo($this->client->ch, CURLINFO_HEADER_OUT);
    rewind($fh_header);
    $received_headers = stream_get_contents($fh_header);
    fclose($fh_header);

    // Retrieve the HTTP response code.
    $response_code = (int) curl_getinfo($this->client->ch, CURLINFO_HTTP_CODE);

    // Return the response object.
    return new Response($response_code, $sent_headers, $received_headers, $response_data);
  }

}
