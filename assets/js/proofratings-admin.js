(function ($) {
    $('.proofratings-settings-wrap [type="color"], .proofratings-color-field').wpColorPicker({
        change: function(event, ui) {
            $(event.target).trigger('update', ui.color.toString());
        },

        clear: function (event) {
            $(event.target).prev().find('input').trigger('update', '');
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

    var nav_buttons = $('.nav-tab-wrapper > a');
    $('[data-tab-button] > input').on('change', function(){
        button = $(this).parent().data('tab-button');
        current_button = nav_buttons.filter(`[href="${button}"]`).show();
        if ( !current_button.length ) {
            return;
        }

        if ( $(this).is(':checked') ) {
            return current_button.show()
        }

        current_button.hide();
    }).trigger('change')

    $('#proofratings_widget_style').on('change', function(){
        demo_image = $(this).find(':selected').data('img');
        $(this).next('img').prop('src', demo_image);

        $('#proofratings-widgets-shortcode').html(`[proofratings_widgets badge_style="${$(this).val()}"]`)
    }).trigger('change');



    $('[name="proofratings_floating_badge_settings[shadow]"]').on('change', function(){
        if ($('[name="proofratings_floating_badge_settings[shadow]"]:checked').length ) {
            return $('#badge-shadow-color, #badge-shadow-hover-color').show();
        }

        $('#badge-shadow-color, #badge-shadow-hover-color').hide();
        
    }).trigger('change');


    $('[name="proofratings_floating_badge_settings[float]"]').on('change', function(){
        fields = $('#badge-tablet-visibility, #badge-mobile-visibility, #badge-close-options, #badge-position, #floating-badge-pages');
               
        if ( $(this).is(':checked') ) {
            $('#badge-hide-shadow').hide();
            $('[name="proofratings_floating_badge_settings[shadow]"]').prop('checked', true).trigger('change');
            return fields.show();
        }

        $('#badge-hide-shadow').show();
        fields.hide();

    }).trigger('change');

    
    $('[name="proofratings_floating_badge_settings[badge_style]"]').on('change', function(){
        $('#form-table-floating-badge').trigger('update', {
            badge_style: $(this).val()
        })

        demo_image = $(this).find(':selected').data('img');

        position_select = $('[name="proofratings_floating_badge_settings[position]"]');
        
        $(this).next('img').prop('src', demo_image);

        style = $(this).val();
        $('#floating-badge-shortcode').html(`[proofratings_floating_badge badge_style="${style}"]`)

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


    const BannerBadge = () => {
        $('[name="proofratings_banner_badge[button1_border]"]').on('change', function(){            
            targeted = $('#button1-border-hover-color, #button1-border-color');

            if ( $(this).is(':checked') ) {
                return targeted.show();
            }

            targeted.hide();
        }).trigger('change')

        $('[name="proofratings_banner_badge[button2]"]').on('change', function(){
            if ( $(this).is(':checked') ) {
                return $('#cta-button2-options').show();
            }

            $('#cta-button2-options').hide();
        }).trigger('change')

        $('[name="proofratings_banner_badge[button2_border]"]').on('change', function(){            
            targeted = $('#button2-border-hover-color, #button2-border-color');

            if ( $(this).is(':checked') ) {
                return targeted.show();
            }

            targeted.hide();
        }).trigger('change')
    }

    BannerBadge();


    (function SiteSquare(){
        square_badges = $('#proofratings-badge-sites-square > :is(a, div)');

        badge_css = {
            '--themeColor': $('[name="proofratings_badges_sites_square[star_color]"]').val(),
            '--textColor': $('[name="proofratings_badges_sites_square[text_color]"]').val(),
            '--borderColor': '',
            '--shadowColor': $('[name="proofratings_badges_sites_square[shadow_color]"]').val(),
            '--shadowHoverColor': $('[name="proofratings_badges_sites_square[shadow_hover_color]"]').val(),
            'background-color': $('[name="proofratings_badges_sites_square[background]"]').val(),
        }

        function change_shadow_color(object = {}){
            badge_css = {...badge_css, ...object};

            if ( !$('[name="proofratings_badges_sites_square[shadow]"]').is(':checked') ) {
                return square_badges.css({...badge_css, '--borderColor': 'transparent', '--shadowColor': 'transparent', '--shadowHoverColor': 'transparent'})
            }

            square_badges.css(badge_css)
        }

        $('[name="proofratings_badges_sites_square[customize]"]').on('change', function(){
            change_shadow_color();

            if ( $(this).is(":checked") ) {
                return $('#sites-square-badge-customize').show();
            }
            
            $('#sites-square-badge-customize').hide()
        }).trigger('change')       
    
        $('[name="proofratings_badges_sites_square[shadow]"]').on('change', function(){
            change_shadow_color();

            if ( $(this).is(":checked") ) {
                return $('#proofratings-badges-sites-square-shadow-options').show();
            }
            
            $('#proofratings-badges-sites-square-shadow-options').hide()
        }).trigger('change');

        $('[name="proofratings_badges_sites_square[star_color]"]').on('update', function(e, color){
            change_shadow_color({'--themeColor': color});
        })

        $('[name="proofratings_badges_sites_square[text_color]"]').on('update', function(e, color){
            square_badges.css({'--textColor': color})
        })

        $('[name="proofratings_badges_sites_square[review_count_textcolor]"]').on('update', function(e, color){
            change_shadow_color({'--reviewCountTextColor': color})
        })

        $('[name="proofratings_badges_sites_square[background]"]').on('update', function(e, color){
            if ( !color ) {
                color = '#fff';
            }

            change_shadow_color({'background-color': color})
        })

        $('[name="proofratings_badges_sites_square[shadow_color]"]').on('update', function(e, color){
            change_shadow_color({'--shadowColor': color})
        })

        $('[name="proofratings_badges_sites_square[shadow_hover_color]"]').on('update', function(e, color){
            change_shadow_color({'--shadowHoverColor': color})
        })
    })()    
})(jQuery)