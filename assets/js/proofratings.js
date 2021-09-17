(function ($) {
    $('.proofratings-banner-badge.badge-float, .proofratings-floating-badge').on('click', function () {

        has_tab = $(this).closest('.proofratings-banner-badge-tab');
        if ( has_tab.length) {
            return has_tab.toggleClass('opened');
        }


        $(this).addClass('opened');
    })

    $('#proofratings-floating-embed .proofrating-close').on('click', function () {
        $('.proofratings-badge').removeClass('opened');
    })

    $('.proofratings-badge .proofratings-close').on('click', function (e) {
        e.stopPropagation();
        if (Cookies) {
            Cookies.set('hide_proofratings_float_badge', true)
        }

        container = $(this).closest('.proofratings-badge');
        
        if ( $(this).closest('.proofratings-banner-badge-tab').length) {
            container = $(this).closest('.proofratings-banner-badge-tab');
        }


        container.fadeOut(120, function () {
            $(this).remove();
        });
    })
})(jQuery)