<?php
/**
 * @file
 * QuickpayPaymentHelper class.
 */

/**
 * QuickpayPaymentHelper.
 *
 * @since 1.0.0
 *
 * @package QuickPay
 *
 * @category Class
 */
class QuickpayPaymentHelper {
  /**
   * Returns an order number which is at least 4 digits.
   *
   * @return string
   *         The order number.
   */
  public static function orderNumberStandardize($order_number) {
    return str_pad($order_number, 4, 0, STR_PAD_LEFT);
  }


  /**
   * Returns the price with no decimals. 10.10 returns as 1010.
   *
   * @return int
   *         The multiplied price.
   */
  public static function priceMultiply($price) {
    return number_format($price * 100, 0, '', '');
  }


  /**
   * Returns the price with decimals. 1010 returns as 10.10.
   *
   * @return float
   *         The normalized price.
   */
  public static function priceNormalize($price) {
    return number_format($price / 100, 2, '.', '');
  }


  /**
   * Returns the proper revision log message depending on the param.
   *
   * @param string $status
   *        The order status.
   *
   * @return string
   *         A revision string.
   */
  public static function revision($status) {
    $log_message = 'QuickPay: ';

    switch ($status) {
      case 'cancel':
        $log_message .= t('Transaction canceled.');
        break;

      case 'authorize':
        $log_message .= t('Transaction authorized.');
        break;

      case 'capture':
        $log_message .= t('Transaction captured.');
        break;

    }

    return $log_message;
  }

}
