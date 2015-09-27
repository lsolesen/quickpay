<?php
/**
 * @file
 * QuickpayPaymentSettings class.
 */

/**
 * QuickpayPaymentSettings.
 *
 * @since 1.0.0
 *
 * @package QuickPay
 *
 * @category Class
 */
class QuickpayPaymentSettings {

  protected $options;
  protected $gatewayName;
  protected $merchant;
  protected $agreementId;
  protected $agreementApiKey;
  protected $agreementPrivateKey;
  protected $apiApiKey;
  protected $paymentMethods;
  protected $language;
  protected $autocapture;
  protected $autofee;
  protected $brandingId;

  /**
   * Construct.
   *
   * @param array $settings
   *        The settings array.
   */
  public function __construct($settings = array()) {
    $this->options = array();

    $this->set('gatewayName');
    $this->set('merchant');
    $this->set('agreementId');
    $this->set('agreementApiKey');
    $this->set('agreementPrivateKey');
    $this->set('apiApiKey');
    $this->set('paymentMethods');
    $this->set('language');
    $this->set('autocapture');
    $this->set('autofee');
    $this->set('brandingId');
  }

  /**
   * Fast way of setting an options property.
   *
   * @param string $key
   *        The setting key to retrieve.
   */
  private function set($key) {
    if (isset($this->options[$key])) {
      $this->$key = $this->options[$key];
    }
  }

  /**
   * Returns languages supported in the QuickPay manager.
   *
   * @return array
   *         An array of languages.
   */
  protected static function getLanguages() {
    return array(
      'da' => t('Danish'),
      'de' => t('German'),
      'en' => t('English'),
      'fo' => t('Faeroese'),
      'fr' => t('French'),
      'gl' => t('Greenlandish'),
      'it' => t('Italian'),
      'no' => t('Norwegian'),
      'nl' => t('Dutch'),
      'pl' => t('Polish'),
      'se' => t('Swedish'),
    );
  }

  /**
   * Returns the language code as a string.
   *
   * @param string $langcode
   *        Language code in Drupal format.
   *
   * @return string
   *         The language code.
   */
  protected static function getCountryCode($langcode) {
    $langcodes = array(
      'da' => 'da',
      'de' => 'de',
      'en' => 'en',
      'fo' => 'fo',
      'fr' => 'fr',
      'kl' => 'gl',
      'it' => 'it',
      'nb' => 'no',
      'nn' => 'no',
      'nl' => 'nl',
      'pl' => 'pl',
      'sv' => 'se',
    );

    return isset($langcodes[$langcode]) ? $langcodes[$langcode] : 'en';
  }

  /**
   * Returns all the settings fields.
   *
   * @return array
   *         The settings array
   */
  public static function settingsForm($settings = array()) {
    $settings += array(
      'gatewayName' => t('Credit card'),
      'merchant' => '',
      'agreementId' => '',
      'agreementApiKey' => '',
      'agreementPrivateKey' => '',
      'apiApiKey' => '',
      'paymentMethods' => array(),
      'language' => LANGUAGE_NONE,
      'autocapture' => FALSE,
      'autofee' => FALSE,
      'brandingId' => '',
    );

    // Gateway name.
    $form['gatewayName'] = array(
      '#type' => 'textfield',
      '#title' => t('Gateway Name'),
      '#description' => t('The name of the gateway that is presented to the customers on checkout.'),
      '#default_value' => $settings['gatewayName'],
      '#required' => TRUE,
    );

    // Merchant.
    $form['merchant'] = array(
      '#type' => 'textfield',
      '#title' => t('Merchant ID'),
      '#description' => t('Your Payment Window agreement merchant id. Found in the "Integration" tab inside the QuickPay manager.'),
      '#default_value' => $settings['merchant'],
      '#required' => TRUE,
    );

    // Payment Window.
    $form['agreementId'] = array(
      '#type' => 'textfield',
      '#title' => t('Payment Window - Agreement ID'),
      '#description' => t('Your Payment Window agreement id. Found in the "Integration" tab inside the QuickPay manager.'),
      '#default_value' => $settings['agreementId'],
      '#required' => TRUE,
    );

    $form['agreementApiKey'] = array(
      '#type' => 'textfield',
      '#title' => t('Payment Window - API key'),
      '#description' => t('Your Payment Window agreement API key. Found in the "Integration" tab inside the QuickPay manager.'),
      '#default_value' => $settings['agreementApiKey'],
      '#required' => TRUE,
    );

    $form['agreementPrivateKey'] = array(
      '#type' => 'textfield',
      '#title' => t('Payment Window - Private key'),
      '#description' => t('Your Payment Window agreement private key. Found in the "Integration" tab inside the QuickPay manager.'),
      '#default_value' => $settings['agreementPrivateKey'],
      '#required' => TRUE,
    );

    // API setup.
    $form['apiApiKey'] = array(
      '#type' => 'textfield',
      '#title' => t('API User - API key'),
      '#description' => t('Your API User\'s key. Create a separate API user in the "Users" tab inside the QuickPay manager.'),
      '#default_value' => $settings['apiApiKey'],
      '#required' => TRUE,
    );

    // Extra Settings.
    $form['brandingId'] = array(
      '#type' => 'textfield',
      '#title' => t('Branding ID'),
      '#description' => t('Leave empty if you have no custom branding options'),
      '#default_value' => $settings['brandingId'],
    );

    $form['paymentMethods'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Payment Methods'),
      '#options' => self::paymentMethodOptions(),
      '#description' => t('Recommended: creditcard. Type in the cards you wish to accept (comma separated). See the valid payment types here: <b>http://tech.quickpay.net/appendixes/payment-methods/</b>'),
      '#default_value' => $settings['paymentMethods'],
    );

    $languages = self::getLanguages() + array(LANGUAGE_NONE => t('Language of the user'));

    $form['language'] = array(
      '#type' => 'select',
      '#title' => t('Language'),
      '#description' => t('The language for the credit card form.'),
      '#options' => $languages,
      '#default_value' => $settings['language'],
    );

    $form['autocapture'] = array(
      '#type' => 'checkbox',
      '#title' => t('Autocapture'),
      '#description' => t('Automatically capture payments.'),
      '#default_value' => $settings['autocapture'],
    );

    $form['autofee'] = array(
      '#type' => 'checkbox',
      '#title' => t('Autofee'),
      '#description' => t('If enabled, the fee charged by the acquirer will be calculated and added to the transaction amount.'),
      '#default_value' => $settings['autofee'],
    );

    return $form;
  }


  /**
   * Return an array of possible payment options.
   *
   * @return array
   *         The select options.
   */
  public static function paymentMethodOptions() {
    // Available methods.
    $payment_methods = quickpay_payment_cards();

    // Prepare options array.
    $options = array();

    // Populate options array with payment methods.
    foreach ($payment_methods as $key => $value) {
      $options[$key] = sprintf('<img class="quickpay-card-icon" src="%s" title="%s" /> %2$s', $value['image'], $value['name']);
    }

    return $options;
  }

}
