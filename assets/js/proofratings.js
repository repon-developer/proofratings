(function ($) {
    $('.proofratings-badge.badge-float').on('click', function () {
        $(this).addClass('opened');
    })

    $('.proofratings-badges-popup .proofrating-close').on('click', function () {
        $('.proofratings-badge').removeClass('opened');
        $.post(proofratings.api + '/stats', {site_url: proofratings.site_url, type: 'engagement'})
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

        $.post(proofratings.api + '/stats', {site_url: proofratings.site_url, type: 'engagement'})
    })

    $('.proofratings-banner-badge .proofratings-banner-close').on('click', function(e){
        e.stopPropagation();
        e.preventDefault();
        $(this).closest('.proofratings-banner-badge').fadeOut(100, function(){
            $(this).remove();
        })

        $.post(proofratings.api + '/stats', {site_url: proofratings.site_url, type: 'engagement'})
    })

    last_scroll = 0;
    $(window).on('scroll', function(){
        current_position = $(window).scrollTop();

        going_down = current_position > last_scroll;
        last_scroll = current_position;

        if ( going_down ) {
            return $('.proofratings-banner-badge').addClass('going-down');
        }
        
        $('.proofratings-banner-badge').removeClass('going-down')
    })

    const proofratings_items = $('.proofratings-widget, .proofratings-badge, .proofratings-banner-badge');
    if ( proofratings_items.length ) {
        $.post(proofratings.api + '/stats', {site_url: proofratings.site_url, type: 'impression'})
    }

    proofratings_items.on('click', function(){
        $.post(proofratings.api + '/stats', {site_url: proofratings.site_url, type: 'click'})
    })

    proofratings_items.on('mouseenter', function(){
        $.post(proofratings.api + '/stats', {site_url: proofratings.site_url, type: 'engagement'})
    })

})(jQuery)