// required for fix issue with "max" (related products)
var config = {
    shim: {
        'Magento_Ui/js/dynamic-rows/dynamic-rows-grid': {
            deps: ['prototype']
        },
        'Magento_Ui/js/dynamic-rows/dynamic-rows':      {
            deps: ['prototype']
        }
    }
};