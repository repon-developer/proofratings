(function ($) {
    $('.proofratings-color-input').wpColorPicker();

    const btn_suport = $('.btn-support').get(0);
    tippy(btn_suport, { content: 'Need Help?' });

    $('.btn-cancel-subscription').on('click', function (e) {
        e.preventDefault();

        Swal.fire({
            icon: 'info',
            text: 'Are you sure you want to cancel your subscription? Cancellation will be effective immediately upon confirmation.',
            showDenyButton: true,
            confirmButtonText: 'Yes I\'m sure',
            denyButtonText: `No don't cancel`,
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = $(this).attr('href');
            }
        })
    })

    const card_container = $('.proofratings-customer-card .card-form');

    card_container.find('.card-number').mask('0000 0000 0000 0000');
    card_container.find('.card-expiry').mask('00 / 00');

    card_container.find('.card-number').validateCreditCard(function (result) {
        //console.log(result)
        
    });


})(jQuery)