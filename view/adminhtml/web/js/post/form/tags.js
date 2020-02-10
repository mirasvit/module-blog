define([
    'knockout',
    'jquery',
    'Magento_Ui/js/form/element/abstract',
    'Mirasvit_Blog/js/lib/tokenize2'
], function (ko, $, Abstract) {
    'use strict';
    
    ko.bindingHandlers.tokenize2 = {
        init: function (element, valueAccessor, allBindings, model) {
            if (valueAccessor()) {
                var $el = $('#' + element.id);
                
                $el.tokenize2({
                    tokensAllowCustom: true
                });
                
                $el.on('tokenize:tokens:add', function () {
                    model.value($el.val());
                });
                $el.on('tokenize:tokens:remove', function () {
                    model.value($el.val());
                });
                
                var $input = $('input', $el.parent());
                $input.on('blur', function () {
                    $el.trigger('tokenize:tokens:add', $input.val());
                    model.value($el.val());
                });
                
                var $input = $('input', $el.parent());
                $input.on('blur', function () {
                    $el.trigger('tokenize:tokens:add', $input.val());
                    model.value($el.val());
                })
            }
        }
    };
    
    return Abstract.extend({});
});
