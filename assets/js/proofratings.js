(function($){

    $('.proofratings-floating-badge').on('click', function(){
        $('#proofratings-floating-embed').fadeIn(150);
    })
    
    $('#proofratings-floating-embed .proofrating-close').on('click', function(){
        $('#proofratings-floating-embed').fadeOut(150)
    })

    $('#proofratings-floating-embed a.proofratings-widget').on('click', function(){
        $('html, body').animate({scrollTop: $("#proofratings_widgets").offset().top});        
    })

})(jQuery)