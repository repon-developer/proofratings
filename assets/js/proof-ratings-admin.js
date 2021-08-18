(function($){    
    $('.proof-ratings-color-field').wpColorPicker();

    $('.checkbox-review-site input').each(function(){
        
    });

    $('.checkbox-review-site input').on('change', function(){
        fieldset = $('#review-site-settings-' + $(this).parent().data('site'))

        if ( $(this).is(':checked') ) {
            fieldset.show();
        } else {
            fieldset.hide();
        }
    }).trigger('change')

})(jQuery)