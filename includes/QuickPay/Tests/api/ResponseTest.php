<?php
/**
 * @file
 * ResponseTest.
 */

namespace QuickPay\Tests;

use QuickPay\API\Response;

/**
 * ResponseTest.
 *
 * @since 1.0.0
 *
 * @package QuickPay
 *
 * @category Test
 */
class ResponseTest extends \PHPUnit_Framework_TestCase {

  private $responseTestData = '{ "key1": "value1", "key2": "value2" }';

  /**
   * Tests the response Http codes.
   *
   * @param string $http_code
   *        The Http code we want to test.
   * @param string $expected_result
   *        What we expect the result to be.
   *
   * @dataProvider providerTestSuccessResponseHttpCodes
   */
  public function testSuccessResponseHttpCodes($http_code, $expected_result) {
    $response = new Response($http_code, '', '', '');

    $result = $response->isSuccess();

    $this->assertEquals($result, $expected_result);
  }

  /**
   * ProviderTestSuccessResponseHttpCodes.
   *
   * @return array
   *         Array of data.
   */
  public function providerTestSuccessResponseHttpCodes() {
    return array(
      array(200, TRUE),
      array(255, TRUE),
      array(299, TRUE),
      array(300, FALSE),
      array(400, FALSE),
    );
  }

  /**
   * Tests the return of Http status codes.
   *
   * @param string $http_code
   *        The Http code we want to test.
   * @param string $expected_result
   *        What we expect the result to be.
   *
   * @dataProvider providerTestReturnOfHttpStatusCodes
   */
  public function testReturnOfHttpStatusCodes($http_code, $expected_result) {
    $response = new Response($http_code, '', '', '');

    $status_code = $response->httpStatus();

    $this->assertEquals($status_code, $expected_result);
  }

  /**
   * ProviderTestReturnOfHttpStatusCodes.
   *
   * @return array
   *         Array of status codes.
   */
  public function providerTestReturnOfHttpStatusCodes() {
    return array(
      array(200, 200),
      array(300, 300),
      array(500, 500),
    );
  }

  /**
   * TestReturnOfResponseDataAsArray.
   */
  public function testReturnOfResponseDataAsArray() {
    $response = new Response(200, '', '', $this->responseTestData);

    $response_array = $response->asArray();

    $this->assertTrue(is_array($response_array));
  }

  /**
   * TestReturnOfEmptyResponseDataAsArray.
   */
  public function testReturnOfEmptyResponseDataAsArray() {
    $response = new Response(200, '', '', '');

    $response_array = $response->asArray();

    $this->assertTrue(is_array($response_array));
  }

  /**
   * TestReturnOfResponseDataAsObject.
   */
  public function testReturnOfResponseDataAsObject() {
    $response = new Response(200, '', '', $this->responseTestData);

    $response_object = $response->asObject();

    $this->assertTrue(is_object($response_object));
  }

  /**
   * TestReturnOfEmptyResponseDataAsObject.
   */
  public function testReturnOfEmptyResponseDataAsObject() {
    $response = new Response(200, '', '', '');

    $response_object = $response->asObject();

    $this->assertTrue(is_object($response_object));
  }

  /**
   * TestReturnOfResponseDataAsRaw.
   */
  public function testReturnOfResponseDataAsRaw() {
    $response = new Response(200, '', '', $this->responseTestData);

    list($status_code, $headers, $response_raw) = $response->asRaw();

    $this->assertTrue(is_int($status_code));
    $this->assertTrue(is_array($headers));
    $this->assertTrue(is_string($response_raw));
  }

}
