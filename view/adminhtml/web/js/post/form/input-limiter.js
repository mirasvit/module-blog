define([
    'ko',
    'jquery',
    'Magento_Ui/js/form/element/textarea',
    'mage/translate'
], function (ko, $, Textarea, t) {
    'use strict';
    
    return Textarea.extend({
        defaults: {
            min:  10,
            max:  100000,
            note: t('Used {len} characters. Recommend length between {min}–{max} characters.'),
            
            listens: {
                value: 'updateNote'
            }
        },
        
        initObservable: function () {
            this._super();
            
            var appearTimer = setInterval(function () {
                var $el = $("[name=" + this.index + "]");
                
                if ($el.length) {
                    clearInterval(appearTimer);
                    
                    $el.on('keyup', function (e) {
                        this.value($(e.target).val());
                    }.bind(this));
                }
            }.bind(this), 100);
            
            return this;
        },
        
        updateNote: function (value) {
            var len = value ? value.length : 0;

            if(!this.notice) { //For Magento 2.1.* (this.notice is not a function)
                return;
            }
            
            if (len > 0) {
                this.notice(
                    this.note
                        .replace('{len}', len)
                        .replace('{min}', this.min)
                        .replace('{max}', this.max)
                );
                
                if (len < this.min || len > this.max) {
                    this.warn(true);
                } else {
                    this.warn(false);
                }
            } else {
                    this.notice('');
                
            }
        }
    });
});
