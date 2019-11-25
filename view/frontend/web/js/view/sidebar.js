define([
    'uiComponent',
    'ko',
    'jquery',
    'Magento_Checkout/js/model/sidebar',
    'Magento_Checkout/js/model/step-navigator'
], function (Component, ko, $, sidebarModel, stepNavigator) {
    'use strict';

    var mixin = {
        /**
         * Back to shipping method.
         */
        backToShippingMethod: function () {
            sidebarModel.hide();
            stepNavigator.navigateTo('shipping', 'opc-shipping_method');
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
