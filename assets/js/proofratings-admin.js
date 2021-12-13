(function ($) {
    $('table.locations_table tr td.column-action .dashicons').on('click', function(e){
        if (!confirm("Do you want to delete this item?")) {
            return e.preventDefault();
        }
    })
})(jQuery)