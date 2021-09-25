(function ($) {
    $('.proofratings-settings-wrap [type="color"], .proofratings-color-field').wpColorPicker({
        change: function(event, ui){
            field_name = $(event.target).data('name');
            if ( field_name ) {
                $('#form-table-floating-badge').trigger('update', {
                    [field_name]: ui.color.toString()
                });
            }
        }
    });

    $('.checkbox-review-site input').on('change', function () {
        fieldset = $('#review-site-settings-' + $(this).parent().data('site'))

        if ($(this).is(':checked')) {
            fieldset.show();
        } else {
            fieldset.hide();
        }
    }).trigger('change');


    $('.nav-tab-wrapper a').click(function () {
        if ('#' !== jQuery(this).attr('href').substr(0, 1)) {
            return false;
        }

        $('.settings_panel').hide();
        $('.nav-tab-active').removeClass('nav-tab-active');

        $($(this).attr('href')).show();
        $(this).addClass('nav-tab-active');

        return false;
    });

    var goto_hash = window.location.hash;

    if (goto_hash) {
        var the_tab = jQuery('a[href="' + goto_hash + '"]');
        if (the_tab.length > 0) {
            the_tab.click();
        } else {
            jQuery('.nav-tab-wrapper a:first').click();
        }
    } else {
        jQuery('.nav-tab-wrapper a:first').click();
    }

    $('#proofratings_widget_style').on('change', function(){
        demo_image = $(this).find(':selected').data('img');
        $(this).next('img').prop('src', demo_image);

        $('#proofratings-widgets-shortcode').html(`[proofratings_widgets badge_style="${$(this).val()}"]`)
    }).trigger('change');

    $('[name="proofratings_floating_badge_settings[shadow]"]').on('change', function(){

        $('#form-table-floating-badge').trigger('update', {
            shadow: $('[name="proofratings_floating_badge_settings[shadow]"]:checked').length ? 'yes' : 'no'
        });

        if ($('[name="proofratings_floating_badge_settings[shadow]"]:checked').length ) {
            return $('#badge-shadow-color, #badge-shadow-hover-color').show();
        }

        $('#badge-shadow-color, #badge-shadow-hover-color').hide();
        
    }).trigger('change');


    let float_badge_form = {}

    $('#form-table-floating-badge').on('update', function(e, data){
        $(this).find('[data-name]').each(function(){
            field_name = $(this).data('name');
            field_value = $(this).val();

            float_badge_form[field_name] = field_value;
        });

        float_badge_form = Object.assign(float_badge_form, data);        
        if ( float_badge_form.shadow != 'yes') {
            delete float_badge_form.shadow;
            delete float_badge_form.shadow_color;
            delete float_badge_form.shadow_hover;
        }

        const attributes = Object.keys(float_badge_form).filter(key => float_badge_form[key].length).map(key => {
            return `${key}="${float_badge_form[key]}"`;
        })

        $('#floating-badge-shortcode').html(`[proofratings_floating_badge ${attributes.join(' ')}]`)

    }).trigger('update');

    $('#form-table-floating-badge').on('input change', 'input:not([type="checkbox"]), select', function(){
        $('#form-table-floating-badge').trigger('update');
    })

    $('[name="proofratings_floating_badge_settings[float]"]').on('change', function(){
        fields = $('#badge-tablet-visibility, #badge-mobile-visibility, #badge-close-options, #badge-position');
               
        if ( $(this).is(':checked') ) {
            $('#badge-hide-shadow').hide();
            $('[name="proofratings_floating_badge_settings[shadow]"]').prop('checked', true).trigger('change');
            $('.nav-tab-wrapper a[href="#settings-floating-pages"]').show();
            return fields.show();
        }

        $('#badge-hide-shadow').show();
        $('.nav-tab-wrapper a[href="#settings-floating-pages"]').hide();

        fields.hide();

    }).trigger('change');

    


    $('[name="proofratings_floating_badge_settings[badge_style]"]').on('change', function(){
        demo_image = $(this).find(':selected').data('img');

        position_select = $('[name="proofratings_floating_badge_settings[position]"]');
        
        $(this).next('img').prop('src', demo_image);

        style = $(this).val();

        let positions = ['Left', 'Center', 'Right'];

        if ( style == 'style1') {
            positions.splice(1, 1);
        }

        position_options = positions.map(pos => {
            pos_lower = pos.toLowerCase();
            const selected = (pos_lower == position_select.data('position')) ? 'selected' : '';
            return `<option value="${pos_lower}" ${selected}>${pos}</option>`;
        });

        position_select.html(position_options.join(''))
    }).trigger('change');

    
})(jQuery)