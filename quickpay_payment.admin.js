/**
 * @file
 * Object to handle the QP Api Behaviour.
 */

(function ($) {
  "use strict";

  Drupal.behaviors.quickpayCardSelection = {
    attach: function (context, settings) {
      function QuickPay() {
        this.container = $('#quickpay_payment');
        this.order_id = $('#quickpay_payment__order-id').text();
        this.transaction_id = $('#quickpay_payment__transaction-id').text();
        this.last_operation = $('#quickpay_payment__status');
        this.test = $('#quickpay_payment__test');
        this.button_container = $('.quickpay_payment_buttons');
        this.payment_module = this.container.data('quickpay-gateway');

        this.button_capture = this.button_container.children('[data-quickpay-action="capture"]');
        this.button_cancel = this.button_container.children('[data-quickpay-action="cancel"]');
        this.button_refund = this.button_container.children('[data-quickpay-action="refund"]');
      }

      /**
       * Method to start the magic.
       *
       * @return {void}
       */
      QuickPay.prototype.init = function () {
        var self = this;

        // Retrieve transaction status.
        this.request('status', this.handleResponse);

        // Add event handlers to action buttons.
        this.button_container.children('[data-quickpay-action]').click(function (e) {
          e.preventDefault();
          self.request($(this).data('quickpay-action'), self.handleResponse);
        });
      };

      /**
       * Perform API requests.
       *
       * @param {string} action - the request action type
       * @param {function} callbackHandler - method to handle the response
       *
       * @return {void}
       */
      QuickPay.prototype.request = function (action, callbackHandler) {
        var self = this;

        // Add loader.
        self.container.addClass('loading');

        // Perform request.
        $.getJSON(Drupal.settings.basePath + 'quickpay-payment/ajax', {
          action: action,
          transaction_id: this.transaction_id,
          order_id: this.order_id,
          payment_module: this.payment_module
        },
          function (response) {
            // Process callback.
            callbackHandler(response, self);

            // Remove loader.
            self.container.removeClass('loading');
          });
      };

      /**
       * Handle API responses.
       *
       * @param {object} response - API response
       * @param {object} self - QuickPay
       *
       * @return {void}
       */
      QuickPay.prototype.handleResponse = function (response, self) {
        var lastOperationType = self.getLastOperation(response.operations);

        // Show operation status.
        self.last_operation.text(lastOperationType);

        // Visualize transaction test mode.
        if (response.test_mode === true) {
          self.test.fadeIn();
        }

        // Update action buttons view.
        self.updateButtons(lastOperationType);
      };

      /**
       * Returns the type of the last operation.
       *
       * @param {object} operations The operations object
       *
       * @return {string} The last operation
       */
      QuickPay.prototype.getLastOperation = function (operations) {
        return operations[operations.length - 1].type;
      };

      /**
       * Updates the button view depending on the last operation type.
       *
       * @param {string} operationType Transaction operation type
       *
       * @return {void}
       */
      QuickPay.prototype.updateButtons = function (operationType) {
        var self = this;
        if (operationType === 'authorize') {
          this.button_container.fadeIn();
          self.button_capture.show();
          self.button_cancel.show();
          self.button_refund.hide();
        }

        if (operationType === 'capture') {
          this.button_container.show();
          this.button_capture.hide();
          this.button_cancel.hide();
          this.button_refund.show();
        }

        if (['cancel', 'refund'].indexOf(operationType) > -1) {
          this.button_container.fadeOut();
        }
      };

      // Document ready.
      $(function () {
        var QP = new QuickPay();

        // Init the object if the API container is present.
        if (QP.container.length) {
          QP.init();
        }
      });
    }
  };
})(jQuery);
