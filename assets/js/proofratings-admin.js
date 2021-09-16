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

    $('[name="proofratings_banner_badge_settings[type]"]').on('change', function(){
        $('[name="proofratings_floating_badge_settings[show]"]').prop('checked', !$(this).is(':checked'));

        if ( $(this).is(':checked') ) {
            return $(this).closest('table.form-table').removeClass('banner-badge-embed');
        }

        $(this).closest('table.form-table').addClass('banner-badge-embed');
    }).trigger('change')

    $('[name="proofratings_floating_badge_settings[show]"]').on('change', function(){

        $('[name="proofratings_banner_badge_settings[type]"]').prop('checked', !$(this).is(':checked'));

        next_rows = $(this).closest('tr').nextAll();
        
        if ( $(this).is(':checked') ) {
            return next_rows.show();
        }

        next_rows.hide();

    }).trigger('change')
})(jQuery)