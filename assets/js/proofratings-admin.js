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

    $('#proofratings_widget_style').on('change', function(){
        demo_image = $(this).find(':selected').data('img');
        $(this).next('img').prop('src', demo_image);

        $('#proofratings-widgets-shortcode').html(`[proofratings_widgets badge_style="${$(this).val()}"]`)
    }).trigger('change');



    $('[name="proofratings_floating_badge_settings[shadow]"]').on('change', function(){
        if ($('[name="proofratings_floating_badge_settings[shadow]"]:checked').length ) {
            return $('#badge-shadow-color, #badge-shadow-hover-color').show();
        }

        $('#badge-shadow-color, #badge-shadow-hover-color').hide();
        
    }).trigger('change');


    $('[name="proofratings_floating_badge_settings[float]"]').on('change', function(){
        fields = $('#badge-tablet-visibility, #badge-mobile-visibility, #badge-close-options, #badge-position, #floating-badge-pages');
               
        if ( $(this).is(':checked') ) {
            $('#badge-hide-shadow').hide();
            $('[name="proofratings_floating_badge_settings[shadow]"]').prop('checked', true).trigger('change');
            return fields.show();
        }

        $('#badge-hide-shadow').show();
        fields.hide();

    }).trigger('change');

    
    $('[name="proofratings_floating_badge_settings[badge_style]"]').on('change', function(){
        $('#form-table-floating-badge').trigger('update', {
            badge_style: $(this).val()
        })

        demo_image = $(this).find(':selected').data('img');

        position_select = $('[name="proofratings_floating_badge_settings[position]"]');
        
        $(this).next('img').prop('src', demo_image);

        style = $(this).val();
        $('#floating-badge-shortcode').html(`[proofratings_floating_badge badge_style="${style}""]`)

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


    const BannerBadge = () => {


        
        
        
        $('[name="proofratings_banner_badge[button2]"]').on('change', function(){
            if ( $(this).is(':checked') ) {
                return $('#cta-button2-options').show();
            }

            $('#cta-button2-options').hide();
        }).trigger('change')
    }

    BannerBadge();

    
})(jQuery)