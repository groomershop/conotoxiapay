/**
 * Applying renderer for Conotoxia Pay
 */
/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'conotoxia_pay',
                component: 'Conotoxia_Pay/js/view/payment/method-renderer/conotoxia_pay'
            }
        );
        return Component.extend({});
    }
);
