define([
    'knockout',
    'jquery',
    'Magento_Ui/js/form/element/abstract'
], function (ko, $, Abstract) {
    'use strict';
    
    return Abstract.extend({
        defaults: {
            listens: {
                '${ $.provider }:data.name': 'updateName'
            },
            imports: {
                submitUrl: '${ $.provider }:submit_url',
                data:      '${ $.provider }:data'
            }
        },
        
        initialize: function () {
            this._super();
            
            $('#preview').on('click', function (e) {
                e.preventDefault();
                
                $('#preview').addClass('loading');
                
                $.ajax({
                    url:      this.submitUrl,
                    method:   'POST',
                    dataType: 'json',
                    data:     this.data,
                    success:  function (data) {
                        window.open(data.url, 'preview');
                        $('#preview').removeClass('loading');
                    }
                });
            }.bind(this))
        },
        
        updateName: function (name) {
            $('.page-actions-inner').attr('data-title', name);
            $('.page-title').html(name);
        }
    });
});
