<?php
/**
 * @file
 * Response class.
 */

namespace QuickPay\API;

/**
 * QuickPay_Response.
 *
 * @since 1.0.0
 *
 * @package QuickPay
 *
 * @category Class
 */
class Response {
  /**
   * HTTP status code of request.
   */
  protected $statusCode;

  /**
   * The headers sent during the request.
   */
  protected $sentHeaders;

  /**
   * The headers received during the request.
   */
  protected $receivedHeaders;

  /**
   * Response body of last request.
   */
  protected $responseData;

  /**
   * Instantiates a new response object.
   *
   * @param int $status_code
   *        The HTTP status code.
   * @param string $sent_headers
   *        The headers sent.
   * @param string $received_headers
   *        The headers received.
   * @param string $response_data
   *        The http response body.
   */
  public function __construct($status_code, $sent_headers, $received_headers, $response_data) {
    $this->statusCode = $status_code;
    $this->sentHeaders = $sent_headers;
    $this->receivedHeaders = $received_headers;
    $this->responseData = $response_data;
  }

  /**
   * Returns the HTTP status code, headers and response body.
   *
   * Usage: list($statusCode, $headers, $response_body) = $response->asRaw().
   *
   * @param bool $keep_authorization_value
   *        Normally the value of the Authorization: header is masked.
   *        True keeps the sent value.
   *
   * @return array
   *         Ex: [integer, string[], string].
   */
  public function asRaw($keep_authorization_value = FALSE) {
    // To avoid unintentional logging of credentials the default
    // is to mask the value of the Authorization: header.
    if ($keep_authorization_value) {
      $sent_headers = $this->sentHeaders;
    }
    else {
      // Avoid dependency on mbstring.
      $lines = explode("\n", $this->sentHeaders);
      foreach ($lines as &$line) {
        if (strpos($line, 'Authorization: ') === 0) {
          $line = 'Authorization: <hidden by default>';
        }
      }
      $sent_headers = implode("\n", $lines);
    }

    return [$this->statusCode, ['sent' => $sent_headers, 'received' => $this->receivedHeaders], $this->responseData];
  }

  /**
   * Returns the response body as an array.
   *
   * @return array
   *         The result.
   */
  public function asArray() {
    if ($response = json_decode($this->responseData, TRUE)) {
      return $response;
    }

    return array();
  }

  /**
   * Returns the response body as an array.
   *
   * @return array
   *         The result.
   */
  public function asObject() {
    if ($response = json_decode($this->responseData)) {
      return $response;
    }

    return new \stdClass();
  }

  /**
   * Returns the httpStatus code.
   *
   * @return int
   *         The status code.
   */
  public function httpStatus() {
    return $this->statusCode;
  }

  /**
   * Checks if the http status code indicates a successful or an error response.
   *
   * @return bool
   *         Was the request successful?
   */
  public function isSuccess() {
    if ($this->statusCode > 299) {
      return FALSE;
    }

    return TRUE;
  }

}
