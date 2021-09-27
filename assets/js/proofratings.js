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

    $('.proofratings-banner-badge .proofratings-banner-close').on('click', function(e){
        e.preventDefault();
        $(this).closest('.proofratings-banner-badge').fadeOut(100, function(){
            $(this).remove();
        })
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


})(jQuery)