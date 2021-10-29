(function ($) {
    $('.proofratings-settings-wrap [type="color"], .proofratings-color-field').wpColorPicker({
        change: function(event, ui) {
            $(event.target).trigger('update', ui.color.toString());
        },

        clear: function (event) {
            $(event.target).prev().find('input').trigger('update', '');
        }
    });

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

    const generate_css_style = (styles, keys = [], prefix = '--') => {
        properties = new Array();

        keys.forEach(key => {
            if ( styles[key] ) {
                properties.push(prefix + key + ':' + styles[key] + '!important');
            }
        })

        return properties.join(';');
    }

    function square_badge_tab() {
        square_badges = $('#proofratings-badge-square > :is(a, div)');

        badge_css = {
            'themeColor': $('[name="proofratings_badges_square[star_color]"]').val(),
            'textColor': $('[name="proofratings_badges_square[text_color]"]').val(),
            'reviewCountTextColor': $('[name="proofratings_badges_square[review_count_textcolor]"]').val(),
            'shadowColor': $('[name="proofratings_badges_square[shadow_color]"]').val(),
            'shadowHoverColor': $('[name="proofratings_badges_square[shadow_hover_color]"]').val(),
            'background-color': $('[name="proofratings_badges_square[background]"]').val(),
        }

        var generated_style_element = $('style#proofratings-square-generated-style');
        if ( !generated_style_element.length ) {
            generated_style_element = $('<style id="proofratings-square-generated-style" />').appendTo('body')
        }

        function generate_square_style(object = {}){
            badge_css = {...badge_css, ...object};

            proofratings_widget = new Array();
            if ( badge_css['themeColor'] ) {
                proofratings_widget.push(`--themeColor: ${badge_css['themeColor']}`);
            }

            if ( badge_css['textColor'] ) {
                proofratings_widget.push(`--textColor: ${badge_css['textColor']}`);
            }

            if ( badge_css['shadowColor'] ) {
                proofratings_widget.push(`--shadowColor: ${badge_css['shadowColor']}`);
            }

            if ( badge_css['reviewCountTextColor'] ) {
                proofratings_widget.push(`--reviewCountTextColor: ${badge_css['reviewCountTextColor']}`);
            }

            if ( badge_css['background-color'] ) {
                proofratings_widget.push(`background-color: ${badge_css['background-color']}`);
            }

            proofratings_widget_hover = new Array();
            if ( badge_css['themeColor'] ) {
                proofratings_widget_hover.push(`--borderColor: ${badge_css['themeColor']}`);
            }

            if ( badge_css['shadowHoverColor'] ) {
                proofratings_widget_hover.push(`--shadowColor: ${badge_css['shadowHoverColor']}`);
            }

            css_style = `
                .proofratings-widget.proofratings-widget-square {${proofratings_widget.join(';')}}
                .proofratings-widget.proofratings-widget-square:hover {${proofratings_widget_hover.join(';')}}
            `;

            if ( !$('[name="proofratings_badges_square[shadow]"]').is(':checked') ) {
                css_style += `.proofratings-widget.proofratings-widget-square, .proofratings-widget.proofratings-widget-square:hover {--borderColor: transparent; --shadowColor: transparent}`;
            }
            
            generated_style_element.html(css_style)
        }

        $('[name="proofratings_badges_square[customize]"]').on('change', function(){
            generate_square_style();

            if ( $(this).is(":checked") ) {
                square_badges.addClass('proofratings-widget-customized');
                return $('#square-badge-customize').show();
            }

            square_badges.removeClass('proofratings-widget-customized');            
            $('#square-badge-customize').hide()
        }).trigger('change')       
    
        $('[name="proofratings_badges_square[shadow]"]').on('change', function(){
            generate_square_style();

            if ( $(this).is(":checked") ) {
                return $('#proofratings-badges-sites-square-shadow-options').show();
            }
            
            $('#proofratings-badges-sites-square-shadow-options').hide()
        }).trigger('change');

        $('[name="proofratings_badges_square[star_color]"]').on('update', function(e, color){
            generate_square_style({'themeColor': color});
        })

        $('[name="proofratings_badges_square[text_color]"]').on('update', function(e, color){
            generate_square_style({'textColor': color})
        })

        $('[name="proofratings_badges_square[review_count_textcolor]"]').on('update', function(e, color){
            generate_square_style({'reviewCountTextColor': color})
        })

        $('[name="proofratings_badges_square[background]"]').on('update', function(e, color){
            if ( !color ) {
                color = '#fff';
            }

            generate_square_style({'background-color': color})
        })

        $('[name="proofratings_badges_square[shadow_color]"]').on('update', function(e, color){
            generate_square_style({'shadowColor': color})
        })

        $('[name="proofratings_badges_square[shadow_hover_color]"]').on('update', function(e, color){
            generate_square_style({'shadowHoverColor': color})
        })
    };
    square_badge_tab();

    const rectangle_badge_tab = function(){
        rectangle_badges = $('#proofratings-badge-rectangle > :is(a, div)');

        rectangle_badge_css = {
            'themeColor': $('[name="proofratings_badges_rectangle[star_color]"]').val(),
            'iconColor': $('[name="proofratings_badges_rectangle[icon_color]"]').val(),
            'textColor': $('[name="proofratings_badges_rectangle[text_color]"]').val(),
            'reviewCountTextColor': $('[name="proofratings_badges_rectangle[review_count_textcolor]"]').val(),
            'shadowColor': $('[name="proofratings_badges_rectangle[shadow_color]"]').val(),
            'shadowHoverColor': $('[name="proofratings_badges_rectangle[shadow_hover_color]"]').val(),
            'background-color': $('[name="proofratings_badges_rectangle[background]"]').val(),
        }

        var generated_style_element = $('style#proofratings-rectangle-generated-style');
        if ( !generated_style_element.length ) {
            generated_style_element = $('<style id="proofratings-rectangle-generated-style" />').appendTo('body')
        }

        function change_shadow_color(object = {}){
            rectangle_badge_css = {...rectangle_badge_css, ...object};

            proofratings_widget = new Array();
            if ( rectangle_badge_css['themeColor'] ) {
                proofratings_widget.push(`--themeColor: ${rectangle_badge_css['themeColor']}`);
            }

            if ( rectangle_badge_css['iconColor'] ) {
                proofratings_widget.push(`--iconColor: ${rectangle_badge_css['iconColor']}`);
            }

            if ( rectangle_badge_css['textColor'] ) {
                proofratings_widget.push(`--textColor: ${rectangle_badge_css['textColor']}`);
            }

            if ( rectangle_badge_css['shadowColor'] ) {
                proofratings_widget.push(`--shadowColor: ${rectangle_badge_css['shadowColor']}`);
            }

            if ( rectangle_badge_css['reviewCountTextColor'] ) {
                proofratings_widget.push(`--reviewCountTextColor: ${rectangle_badge_css['reviewCountTextColor']}`);
            }

            if ( rectangle_badge_css['background-color'] ) {
                proofratings_widget.push(`background-color: ${rectangle_badge_css['background-color']}`);
            }

            proofratings_widget_hover = new Array();
            if ( rectangle_badge_css['themeColor'] ) {
                proofratings_widget_hover.push(`--borderColor: ${rectangle_badge_css['themeColor']}`);
            }

            if ( rectangle_badge_css['shadowHoverColor'] ) {
                proofratings_widget_hover.push(`--shadowColor: ${rectangle_badge_css['shadowHoverColor']}`);
            }

            css_style = `
                .proofratings-widget.proofratings-widget-rectangle {${proofratings_widget.join(';')}}
                .proofratings-widget.proofratings-widget-rectangle:hover {${proofratings_widget_hover.join(';')}}
            `;

            if ( !$('[name="proofratings_badges_rectangle[shadow]"]').is(':checked') ) {
                css_style += `.proofratings-widget.proofratings-widget-rectangle, .proofratings-widget.proofratings-widget-rectangle:hover {--borderColor: transparent; --shadowColor: transparent}`;
            }
            
            generated_style_element.html(css_style)
        }

        $('[name="proofratings_badges_rectangle[customize]"]').on('change', function(){
            change_shadow_color();

            if ( $(this).is(":checked") ) {
                rectangle_badges.addClass('proofratings-widget-customized');
                return $('#rectangle-badge-customize').show();
            }

            rectangle_badges.removeClass('proofratings-widget-customized');            
            $('#rectangle-badge-customize').hide()
        }).trigger('change')       
    
        $('[name="proofratings_badges_rectangle[shadow]"]').on('change', function(){
            change_shadow_color();

            if ( $(this).is(":checked") ) {
                return $('#proofratings-badges-rectangle-shadow-options').show();
            }
            
            $('#proofratings-badges-rectangle-shadow-options').hide()
        }).trigger('change');

        $('[name="proofratings_badges_rectangle[star_color]"]').on('update', function(e, color){
            change_shadow_color({'themeColor': color});
        })

        $('[name="proofratings_badges_rectangle[icon_color]"]').on('update', function(e, color){
            change_shadow_color({'iconColor': color});
        })

        $('[name="proofratings_badges_rectangle[text_color]"]').on('update', function(e, color){
            change_shadow_color({'textColor': color})
        })

        $('[name="proofratings_badges_rectangle[review_count_textcolor]"]').on('update', function(e, color){
            change_shadow_color({'reviewCountTextColor': color})
        })

        $('[name="proofratings_badges_rectangle[background]"]').on('update', function(e, color){
            if ( !color ) {
                color = '#fff';
            }

            change_shadow_color({'background-color': color})
        })

        $('[name="proofratings_badges_rectangle[shadow_color]"]').on('update', function(e, color){
            change_shadow_color({'shadowColor': color})
        })

        $('[name="proofratings_badges_rectangle[shadow_hover_color]"]').on('update', function(e, color){
            change_shadow_color({'shadowHoverColor': color})
        })
    }

    rectangle_badge_tab();

    const Badges_Popup = () => {
        popup_badge_style = $('style#proofratings-badges-popup');
        if ( !popup_badge_style.length ) {
            popup_badge_style = $('<style id="proofratings-badges-popup" />').appendTo('body')
        }

        let badges_style = {
            'themeColor': $('[name="proofratings_badges_popup[star_color]"]').val(),
            'reviewCountTextColor': $('[name="proofratings_badges_popup[review_text_color]"]').val(),
            'review_text_background': $('[name="proofratings_badges_popup[review_text_background]"]').val(),
            'view_review_color': $('[name="proofratings_badges_popup[view_review_color]"]').val(),
        };

        function generate_css(object = {}) {
            badges_style = {...badges_style, ...object};
            css_style = '.proofratings-popup-widgets-box .proofratings-widget {' + generate_css_style(badges_style, [
                'themeColor', 'reviewCountTextColor', 'review_text_background', 'view_review_color'
            ]) + '}';

            popup_badge_style.html(css_style)
        }

        generate_css();

        $('[name="proofratings_badges_popup[star_color]"]').on('update', (e, themeColor) => generate_css({themeColor}))
        $('[name="proofratings_badges_popup[review_text_color]"]').on('update', (e, reviewCountTextColor) => generate_css({reviewCountTextColor}))
        $('[name="proofratings_badges_popup[review_text_background]"]').on('update', (e, review_text_background) => generate_css({review_text_background}))
        $('[name="proofratings_badges_popup[view_review_color]"]').on('update', (e, view_review_color) => generate_css({view_review_color}))


        $('[name="proofratings_badges_popup[customize]"]').on('change', function(){            
            targeted = $('#popup-badge-customize');
            if ( $(this).is(':checked') ) {
                return targeted.show();
            }
            targeted.hide();
        }).trigger('change')
    }

    Badges_Popup();


    function overall_ratings_rectangle() {
        $('.proofratings-badge.proofratings-badge-rectangle').attr('style', '')

        proofratings_overall_rectangle_style = $('style#proofratings-widget-rectangle');
        if ( !proofratings_overall_rectangle_style.length ) {
            proofratings_overall_rectangle_style = $('<style id="proofratings-widget-rectangle" />').appendTo('body')
        }

        let ovarall_rectangle_css = {
            'star_color': $('[name="proofratings_overall_ratings_rectangle[star_color]"]').val(),
            'rating_color': $('[name="proofratings_overall_ratings_rectangle[rating_color]"]').val(),
            'shadow_color': $('[name="proofratings_overall_ratings_rectangle[shadow_color]"]').val(),
            'shadow_hover': $('[name="proofratings_overall_ratings_rectangle[shadow_hover]"]').val(),
            'background_color': $('[name="proofratings_overall_ratings_rectangle[background_color]"]').val(),
            'review_text_color': $('[name="proofratings_overall_ratings_rectangle[review_text_color]"]').val(),
            'review_background': $('[name="proofratings_overall_ratings_rectangle[review_background]"]').val(),
        };

        function generate_css(object = {}) {
            ovarall_rectangle_css = {...ovarall_rectangle_css, ...object};

            css_style = '.proofratings-badge.proofratings-badge-rectangle {' + generate_css_style(ovarall_rectangle_css, [
                'star_color', 'rating_color', 'shadow_color', 'shadow_hover', 'background_color', 'review_text_color', 'review_background'
            ], '--') + '}';

            if (!$('[name="proofratings_overall_ratings_rectangle[float]"]:checked').length && !$('[name="proofratings_overall_ratings_rectangle[shadow]"]:checked').length) {
                css_style += '.proofratings-badge.proofratings-badge-rectangle {--shadow_color: transparent; --shadow_hover: transparent}'
            }

            proofratings_overall_rectangle_style.html(css_style)
        }

        $('[name="proofratings_overall_ratings_rectangle[float]"]').on('change', function(){
            generate_css()
            float_options = $('#badge-tablet-visibility, #badge-mobile-visibility, #badge-close-options, #badge-position, #floating-badge-pages');
                   
            if ( $(this).is(':checked') ) {
                $('#badge-hide-shadow').hide();
                $('[name="proofratings_overall_ratings_rectangle[shadow]"]').prop('checked', true).trigger('change');
                return float_options.show();
            }
    
            $('#badge-hide-shadow').show();
            float_options.hide();
    
        }).trigger('change');

        $('[name="proofratings_overall_ratings_rectangle[customize]"]').on('change', function(){            
            if ( $(this).is(':checked') ) {
                return $('#overall-ratings-customize-options').show();
            }    
            $('#overall-ratings-customize-options').hide();            
        }).trigger('change');

        $('[name="proofratings_overall_ratings_rectangle[shadow]"]').on('change', function(){
            generate_css()
            shadow_options = $('#badge-shadow-color, #badge-shadow-hover-color');    
            if ( $(this).is(':checked') ) {
                return shadow_options.show();
            }
    
            shadow_options.hide();            
        }).trigger('change');

        $('[name="proofratings_overall_ratings_rectangle[star_color]"]').on('update', (e, star_color) => generate_css({star_color}))
        $('[name="proofratings_overall_ratings_rectangle[rating_color]"]').on('update', (e, rating_color) => generate_css({rating_color}))
        $('[name="proofratings_overall_ratings_rectangle[shadow_color]"]').on('update', (e, shadow_color) => generate_css({shadow_color}))
        $('[name="proofratings_overall_ratings_rectangle[shadow_hover]"]').on('update', (e, shadow_hover) => generate_css({shadow_hover}))
        $('[name="proofratings_overall_ratings_rectangle[background_color]"]').on('update', (e, background_color) => generate_css({background_color}))
        $('[name="proofratings_overall_ratings_rectangle[review_text_color]"]').on('update', (e, review_text_color) => generate_css({review_text_color}))
        $('[name="proofratings_overall_ratings_rectangle[review_background]"]').on('update', (e, review_background) => generate_css({review_background}))
    }

    overall_ratings_rectangle();


    function overall_ratings_narrow() {
        $('.proofratings-badge.proofratings-badge-narrow').attr('style', '')

        proofratings_overall_narrow_style = $('style#proofratings-widget-narrow');
        if ( !proofratings_overall_narrow_style.length ) {
            proofratings_overall_narrow_style = $('<style id="proofratings-widget-narrow" />').appendTo('body')
        }

        let ovarall_narrow_css = {
            'star_color': $('[name="proofratings_overall_ratings_narrow[star_color]"]').val(),
            'rating_color': $('[name="proofratings_overall_ratings_narrow[rating_color]"]').val(),
            'shadow_color': $('[name="proofratings_overall_ratings_narrow[shadow_color]"]').val(),
            'shadow_hover': $('[name="proofratings_overall_ratings_narrow[shadow_hover]"]').val(),
            'background_color': $('[name="proofratings_overall_ratings_narrow[background_color]"]').val(),
            'review_text_color': $('[name="proofratings_overall_ratings_narrow[review_text_color]"]').val(),
            'review_background': $('[name="proofratings_overall_ratings_narrow[review_background]"]').val(),
        };

        function generate_css(object = {}) {
            ovarall_narrow_css = {...ovarall_narrow_css, ...object};

            css_style = '.proofratings-badge.proofratings-badge-narrow {' + generate_css_style(ovarall_narrow_css, [
                'star_color', 'rating_color', 'shadow_color', 'shadow_hover', 'background_color', 'review_text_color', 'review_background'
            ], '--') + '}';

            if (!$('[name="proofratings_overall_ratings_narrow[float]"]:checked').length && !$('[name="proofratings_overall_ratings_narrow[shadow]"]:checked').length) {
                css_style += '.proofratings-badge.proofratings-badge-narrow {--shadow_color: transparent!important; --shadow_hover: transparent!important}'
            }

            proofratings_overall_narrow_style.html(css_style)
        }

        $('[name="proofratings_overall_ratings_narrow[star_color]"]').on('update', (e, star_color) => generate_css({star_color}))
        $('[name="proofratings_overall_ratings_narrow[rating_color]"]').on('update', (e, rating_color) => generate_css({rating_color}))
        $('[name="proofratings_overall_ratings_narrow[shadow_color]"]').on('update', (e, shadow_color) => generate_css({shadow_color}))
        $('[name="proofratings_overall_ratings_narrow[shadow_hover]"]').on('update', (e, shadow_hover) => generate_css({shadow_hover}))
        $('[name="proofratings_overall_ratings_narrow[background_color]"]').on('update', (e, background_color) => generate_css({background_color}))
        $('[name="proofratings_overall_ratings_narrow[review_text_color]"]').on('update', (e, review_text_color) => generate_css({review_text_color}))
        $('[name="proofratings_overall_ratings_narrow[review_background]"]').on('update', (e, review_background) => generate_css({review_background}))


        $('[name="proofratings_overall_ratings_narrow[float]"]').on('change', function(){
            generate_css()
            float_options = $('#overall-ratings-narrow-float-options, #overall-narrow-ratings-pages');
                   
            if ( $(this).is(':checked') ) {
                $('#overall-ratings-shadow').hide();
                $('[name="proofratings_overall_ratings_narrow[shadow]"]').prop('checked', true).trigger('change');
                return float_options.show();
            }
    
            $('#overall-ratings-shadow').show();
            float_options.hide();
    
        }).trigger('change');


        $('[name="proofratings_overall_ratings_narrow[customize]"]').on('change', function(){
            if ( $(this).is(':checked') ) {
                return $('#overall-ratings-narrow-customize-options').show();
            }
    
            $('#overall-ratings-narrow-customize-options').hide();
            
        }).trigger('change');


        $('[name="proofratings_overall_ratings_narrow[shadow]"]').on('change', function(){
            generate_css()
            shadow_options = $('.overall-ratings-narrow-shadow-options');
    
            if ( $(this).is(':checked') ) {
                return shadow_options.show();
            }
    
            shadow_options.hide();
            
        }).trigger('change');
    }
    overall_ratings_narrow();

    const CTA_Banner_Badge = () => {

        const cta_banner = $('.proofratings-banner-badge');

        proofratings_cta_banner_style = $('style#proofratings-cta-banner');
        if ( !proofratings_cta_banner_style.length ) {
            proofratings_cta_banner_style = $('<style id="proofratings-cta-banner" />').appendTo('body')
        }

        let ovarall_cta_css = {
            'star_color': $('[name="proofratings_overall_ratings_cta_banner[star_color]"]').val(),
            'backgroundColor': $('[name="proofratings_overall_ratings_cta_banner[background_color]"]').val(),
            'rating_text_color': $('[name="proofratings_overall_ratings_cta_banner[rating_text_color]"]').val(),
            'review_rating_background_color': $('[name="proofratings_overall_ratings_cta_banner[review_rating_background_color]"]').val(),
            'reviewCountTextcolor': $('[name="proofratings_overall_ratings_cta_banner[number_review_text_color]"]').val()
        };

        function generate_css(object = {}) {
            ovarall_cta_css = {...ovarall_cta_css, ...object};
            css_style = '.proofratings-banner-badge {' + generate_css_style(ovarall_cta_css, [
                'star_color', 'backgroundColor', 'rating_text_color', 'rating_text_color', 'review_rating_background_color', 'reviewCountTextcolor'
            ]) + '}';

            proofratings_cta_banner_style.html(css_style)
        }

        generate_css();

        $('[name="proofratings_overall_ratings_cta_banner[star_color]"]').on('update', (e, star_color) => generate_css({star_color}))
        $('[name="proofratings_overall_ratings_cta_banner[background_color]"]').on('update', (e, backgroundColor) => generate_css({backgroundColor}))
        $('[name="proofratings_overall_ratings_cta_banner[rating_text_color]"]').on('update', (e, rating_text_color) => generate_css({rating_text_color}))
        $('[name="proofratings_overall_ratings_cta_banner[review_rating_background_color]"]').on('update', (e, review_rating_background_color) => generate_css({review_rating_background_color}))
        $('[name="proofratings_overall_ratings_cta_banner[number_review_text_color]"]').on('update', (e, reviewCountTextcolor) => generate_css({reviewCountTextcolor}))


        $('[name="proofratings_overall_ratings_cta_banner[customize]"]').on('change', function(){            
            targeted = $('#overall-ratings-cta-banner-customize-options');
            if ( $(this).is(':checked') ) {
                return targeted.show();
            }
            targeted.hide();
        }).trigger('change')

        $('[name="proofratings_overall_ratings_cta_banner[shadow]"]').on('change', function(){            
            if ($(this).is(':checked')) {
                cta_banner.addClass('has-shadow')
            } else {
                cta_banner.removeClass('has-shadow')
            }

        }).trigger('change')

        $('[name="proofratings_overall_ratings_cta_banner[button1_border]"]').on('change', function(){            
            targeted = $('#button1-border-hover-color, #button1-border-color');

            if ( $(this).is(':checked') ) {
                return targeted.show();
            }

            targeted.hide();
        }).trigger('change')

        $('[name="proofratings_overall_ratings_cta_banner[button2]"]').on('change', function(){
            if ( $(this).is(':checked') ) {
                return $('#cta-button2-options').show();
            }

            $('#cta-button2-options').hide();
        }).trigger('change')

        $('[name="proofratings_overall_ratings_cta_banner[button2_border]"]').on('change', function(){            
            targeted = $('#button2-border-hover-color, #button2-border-color');

            if ( $(this).is(':checked') ) {
                return targeted.show();
            }

            targeted.hide();
        }).trigger('change')
    }

    CTA_Banner_Badge();
   


    
})(jQuery)