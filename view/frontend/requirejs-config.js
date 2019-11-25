var config = {
    map: {
        '*': {
            'Magento_Checkout/js/model/shipping-save-processor/default': 'JustShout_Gfs/js/model/shipping-save-processor/default',
            'gfsAsync' : 'JustShout_Gfs/js/async'
        }
    },
    config: {
        mixins: {
            'Magento_Checkout/js/view/shipping': {
                'JustShout_Gfs/js/view/shipping': true
            },
            'Magento_Checkout/js/view/sidebar': {
                'JustShout_Gfs/js/view/sidebar': true
            }
        }
    }
};
