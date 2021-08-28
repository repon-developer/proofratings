<?php
/**
 * File containing the class WP_Job_Manager.
 *
 * @package proofratings
 * @since   1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * get reviews sites logo and icon
 * @since  1.0.1
 */
function get_review_sites_logos() {
    return [
        'google' => [
            'alt' => __('Google', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/google.svg'
        ],

        'facebook' => [
            'alt' => __('Facebook', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/facebook.svg'
        ],

        'energysage' => [
            'alt' => __('Energy Sage', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/energysage.png'
        ],

        'solarreviews' => [
            'alt' => __('Solar', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/solarreviews.svg'
        ],

        'yelp' => [
            'alt' => __('Yelp', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/yelp.svg'
        ],

        'bbb' => [
            'alt' => __('BBB', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/bbb.svg'
        ],

        'guildquality' => [
            'alt' => __('Guild Quality', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/guildquality.svg'
        ],

        'solarquotes' => [
            'alt' => __('Solarquotes', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/solarquotes.png'
        ],

        'trustpilot' => [
            'alt' => __('Trustpilot', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/trustpilot.png'
        ],

        'wordpress' => [
            'alt' => __('Wordpress', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/wordpress.png'
        ],

        
    ];
}


/**
 * get settings of proof ratings settings
 * @since  1.0.1
 */
function get_proofratings_settings() {
    $default = [
        'google' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#febc00',
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/google.svg',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-google.webp'
        ],

        'facebook' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#0f7ff3',
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/facebook.svg',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-facebook.webp'
        ],

        'energysage' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#bf793f',
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/energysage.svg',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-energysage.webp'
        ],

        'solarreviews' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#0f92d7',
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/solarreviews.svg',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-solarreviews.webp'
        ],

        'yelp' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#e21c21',
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/yelp.svg',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-yelp.webp'
        ],

        'bbb' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#136796',
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/bbb.svg',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-bbb.webp'
        ],

        'guildquality' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#032e57',
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/guildquality.svg',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-guildquality.webp'
        ],

        'solarquotes' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#208ECD',
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/solarquotes.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-solarquotes.png'
        ],

        'trustpilot' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#01B67B',
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/trustpilot.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-trustpilot.png'
        ],

        'wordpress' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#00769D',
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/wordpress.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-wordpress.png'
        ]
    ];

    $settings = get_option('proofratings_settings', []);
    
    if ( !is_array($settings) || empty($settings)) {
        return $default;
    }    

    array_walk($default, function(&$item, $key) use($settings) {         
        if ( !isset($settings[$key]) ) {
            return $item;
        }

        $item = array_merge($item, $settings[$key]);
    });

    return $default;
}


/**
 * get current status
 * @since  1.0.1
 */
function get_proofratings_current_status() {
    $proofratings_status = get_option( 'proofratings_status');

    if ( !$proofratings_status ) {
        return false;
    }

    return (object) wp_parse_args((array) $proofratings_status, [
        'status' => 'pending',
        'message' => ''
    ]);
}