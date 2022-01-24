(function ($) {
    $('table.locations_table tr td.column-action .dashicons').on('click', function(e){
        if (!confirm("Do you want to delete this item?")) {
            return e.preventDefault();
        }
    })

    
    var email_address_template = wp.template( 'reporting-email-address' );
    $('#email-report-add-email').on('keypress', function(e){
        if ( e.keyCode !== 13 ) {
            return;
        }

        const email = $(this).val().trim();
        const check_email = /\S+@\S+\.\S+/.test(email);

        if ( check_email === false ) {
            alert('Please enter email address and hit enter.');
            return false;
        }
        
        e.preventDefault();
        $('#reporting-email-addresses').append(email_address_template( { email } ))
        $(this).val('')
    })

    $('#reporting-email-addresses').on('click', '.remove', function(){
        $(this).closest('li').remove();
    })
})(jQuery)