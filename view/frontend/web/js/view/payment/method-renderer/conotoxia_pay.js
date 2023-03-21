/**
 * Renderer for Conotoxia Pay view in checkout page
 */
/*browser:true*/
/*global define*/
define(
    [
        'jquery',
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/model/payment/additional-validators',
        'mage/url',
        'Magento_Checkout/js/model/full-screen-loader'
    ],
    function ($,
              Component,
              additionalValidators,
              url,
              fullScreenLoader) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Conotoxia_Pay/payment/form',
                orderData: 'conotoxia_pay/data/getOrderData',
            },

            getCode: function () {
                return 'conotoxia_pay';
            },

            getData: function () {
                return {
                    'method': this.item.method
                };
            },

            /**
             * @param {Object} data
             * @param {Object} event
             * @return {Boolean}
             */
            placeOrder: function (data, event) {
                var self = this;

                if (event) {
                    event.preventDefault();
                }

                if (this.validate() && additionalValidators.validate() && this.isPlaceOrderActionAllowed() === true) {
                    fullScreenLoader.startLoader();
                    this.isPlaceOrderActionAllowed(false);

                    this.getPlaceOrderDeferredObject()
                        .fail(
                            function () {
                                fullScreenLoader.stopLoader();
                                self.isPlaceOrderActionAllowed(true);
                            }
                        ).done(
                        function () {
                            self.afterPlaceOrder();

                            if (self.redirectAfterPlaceOrder) {
                                $.getJSON(url.build(self.orderData), function (response) {
                                    window.location.replace(response.redirectUrl);
                                });
                            }
                        }
                    );

                    return true;
                }

                return false;
            },

            getConotoxiaPayLogo: function () {
                return window.checkoutConfig.payment[this.getCode()].conotoxiaPayLogo;
            },

            isConotoxiaPayIconEnabled: function () {
                return window.checkoutConfig.payment[this.getCode()].isConotoxiaPayIconEnabled;
            },

            getPaymentMethodIcons: function () {
                const paymentMethodIcons = window.checkoutConfig.payment[this.getCode()].paymentMethodIcons;
                const formattedPaymentMethodIcons = [];
                paymentMethodIcons.forEach((paymentMethodIcon) => {
                    formattedPaymentMethodIcons.push({icon: paymentMethodIcon});
                });
                return formattedPaymentMethodIcons;
            },
        });
    }
);