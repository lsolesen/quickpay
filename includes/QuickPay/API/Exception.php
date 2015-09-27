<?php
/**
 * @file
 * Exception class.
 */

namespace QuickPay\API;

/**
 * QuickPay_Exception.
 *
 * @since 1.0.0
 *
 * @package QuickPay
 *
 * @category Class
 */
class Exception extends \Exception {
  /**
   * Redefine the exception so message isn't optional.
   */
  public function __construct($message, $code = 0, Exception $previous = NULL) {
    // Make sure everything is assigned properly.
    parent::__construct($message, $code, $previous);
  }

}
