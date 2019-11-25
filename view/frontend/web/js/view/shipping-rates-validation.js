define([
    'uiComponent',
    'Magento_Checkout/js/model/shipping-rates-validator',
    'Magento_Checkout/js/model/shipping-rates-validation-rules',
    'JustShout_Gfs/js/model/shipping-rates-validator',
    'JustShout_Gfs/js/model/shipping-rates-validation-rules'
], function (
    Component,
    defaultShippingRatesValidator,
    defaultShippingRatesValidationRules,
    upsShippingRatesValidator,
    upsShippingRatesValidationRules
) {
    'use strict';

    defaultShippingRatesValidator.registerValidator('gfs', upsShippingRatesValidator);
    defaultShippingRatesValidationRules.registerRules('gfs', upsShippingRatesValidationRules);

    return Component;
});
