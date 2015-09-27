<?php
/**
 * @file
 * RequestTest.
 */

namespace QuickPay\Tests;

use QuickPay\API\Client;
use QuickPay\API\Request;
use QuickPay\API\Response;

/**
 * RequestTest.
 *
 * @since 1.0.0
 *
 * @package QuickPay
 *
 * @category Test
 */
class RequestTest extends \PHPUnit_Framework_TestCase {

  private $responseTestData = '{ "key1": "value1", "key2": "value2" }';

  protected $request;

  /**
   * SetUp.
   */
  public function setUp() {
    $client = new Client();
    $this->request = new Request($client);
  }

  /**
   * TestResponseInstance.
   */
  public function testResponseInstance() {
    $ping_response = $this->request->get('/ping');

    $this->assertTrue(($ping_response instanceof Response));
  }

  /**
   * TestBadAuthentication.
   */
  public function testBadAuthentication() {
    $client = new Client(':foo');
    $request = new Request($client);

    $response = $request->get('/ping');

    $this->assertEquals(401, $response->httpStatus());
  }

  /**
   * TestSuccessfulGetResponse.
   */
  public function testSuccessfulGetResponse() {
    $ping_response = $this->request->get('/ping');

    $this->assertTrue($ping_response->isSuccess());
  }

  /**
   * TestFailedGetResponse.
   */
  public function testFailedGetResponse() {
    $ping_response = $this->request->get('/foobar');

    $this->assertFalse($ping_response->isSuccess());
  }

  /**
   * TestSuccesfulPostResponse.
   */
  public function testSuccesfulPostResponse() {
    $ping_response = $this->request->post('/ping');

    $this->assertTrue($ping_response->isSuccess());
  }

  /**
   * TestFailedPostResponse.
   */
  public function testFailedPostResponse() {
    $ping_response = $this->request->post('/foobar');

    $this->assertFalse($ping_response->isSuccess());
  }

}
