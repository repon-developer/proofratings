(function ($) {
    $('.proofratings-badge.badge-float').on('click', function () {
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
        container.fadeOut(120, function () {
            $(this).remove();
        });
    })
})(jQuery)