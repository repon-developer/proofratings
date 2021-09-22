(function ($) {
    $('.proofratings-settings-wrap [type="color"], .proofratings-color-field').wpColorPicker();

    $('.checkbox-review-site input').on('change', function () {
        fieldset = $('#review-site-settings-' + $(this).parent().data('site'))

        if ($(this).is(':checked')) {
            fieldset.show();
        } else {
            fieldset.hide();
        }
    }).trigger('change');


    $('.nav-tab-wrapper a').click(function () {
        if ('#' !== jQuery(this).attr('href').substr(0, 1)) {
            return false;
        }

        $('.settings_panel').hide();
        $('.nav-tab-active').removeClass('nav-tab-active');

        $($(this).attr('href')).show();
        $(this).addClass('nav-tab-active');

        return false;
    });

    var goto_hash = window.location.hash;

    if (goto_hash) {
        var the_tab = jQuery('a[href="' + goto_hash + '"]');
        if (the_tab.length > 0) {
            the_tab.click();
        } else {
            jQuery('.nav-tab-wrapper a:first').click();
        }
    } else {
        jQuery('.nav-tab-wrapper a:first').click();
    }

    $('[name="proofratings_widget_settings[badge_style]"]').on('change', function(){
        demo_image = $(this).find(':selected').data('img');
        $(this).next('img').prop('src', demo_image);

        
        
    }).trigger('change');

    $('[name="proofratings_floating_badge_settings[show]"]').on('change', function(){
        next_rows = $(this).closest('tr').nextAll();        
        if ( $(this).is(':checked') ) {
            return next_rows.show();
        }

        next_rows.hide();

    }).trigger('change');

    $('[name="proofratings_floating_badge_settings[badge_style]"]').on('change', function(){
        demo_image = $(this).find(':selected').data('img');

        position_select = $('[name="proofratings_floating_badge_settings[position]"]');
        
        $(this).next('img').prop('src', demo_image);

        style = $(this).val();

        let positions = ['Left', 'Center', 'Right'];

        if ( style == 'style1') {
            positions.splice(1, 1);
        }

        position_options = positions.map(pos => {
            pos_lower = pos.toLowerCase();
            const selected = (pos_lower == position_select.data('position')) ? 'selected' : '';
            return `<option value="${pos_lower}" ${selected}>${pos}</option>`;
        });

        position_select.html(position_options.join(''))
    }).trigger('change');

    
})(jQuery)