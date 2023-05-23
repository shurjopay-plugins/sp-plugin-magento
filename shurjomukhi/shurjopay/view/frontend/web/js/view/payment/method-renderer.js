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
                type: 'shurjopay',
                component: 'shurjomukhi_shurjopay/js/view/payment/method-renderer/shurjopay'
            }
        );
        return Component.extend({});
    }
);