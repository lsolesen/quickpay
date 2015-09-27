<?php
/**
 * @file
 * QuickpayPaymentJSON class.
 */

/**
 * QuickpayPaymentJSON.
 *
 * @since 1.0.0
 *
 * @package QuickPay
 *
 * @category Class
 */
class QuickpayPaymentJSON {

  /**
   * Returns an error response object.
   *
   * @param string $message
   *         The error message.
   *
   * @return object
   *         JSON object.
   */
  public static function error($message) {
    return json_encode(array(
      'status' => 'error',
      'message' => $message,
    ));
  }

  /**
   * From_array.
   *
   * @return object
   *         JSON object.
   */
  public static function fromArray($array) {
    return json_encode($array);
  }

}
