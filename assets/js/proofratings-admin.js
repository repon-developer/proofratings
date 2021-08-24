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

        window.location.hash = jQuery(this).attr('href');
        $('form.proofratings-options').attr('action', 'options.php' + $(this).attr('href'));
        window.scrollTo(0, 0);
        return false;
    });

    var goto_hash = window.location.hash;
    if ('#' === goto_hash.substr(0, 1)) {
        $('form.proofratings-options').attr('action', 'options.php' + $(this).attr('href'));
    }

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
})(jQuery)