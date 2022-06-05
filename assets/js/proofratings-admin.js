(function ($) {
    $('.proofratings-color-input').wpColorPicker();

    const btn_suport = $('.btn-support').get(0);
    tippy(btn_suport, { content: 'Need Help?' });

})(jQuery)