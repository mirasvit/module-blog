define([
    'jquery',
    'Magento_Catalog/js/components/new-category'
], function ($, UiSelect) {
    'use strict';

    return UiSelect.extend({
        onUpdate: function (currentValue) {
            var $stores = $('#store_ids');
            $stores.val(currentValue.join(','));
        },
    });
});
