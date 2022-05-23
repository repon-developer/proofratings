(function ($) {
    $('table.locations_table tr td.column-action .dashicons').on('click', function (e) {
        if (!confirm("Do you want to delete this item?")) {
            return e.preventDefault();
        }
    })

    $('.proofratings-color-input').wpColorPicker();

    const btn_suport = $('.btn-support').get(0);
    tippy(btn_suport, { content: 'Need Help?' });

})(jQuery)