(function($){

    $('.proofratings-banner-badge.badge-float, .proofratings-floating-badge').on('click', function(){
        $(this).addClass('opened');
    })
    
    $('#proofratings-floating-embed .proofrating-close').on('click', function(){
        $('.proofratings-badge').removeClass('opened');
    })
})(jQuery)