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
 * get settings of proof ratings settings
 * @since  1.0.1
 */
function get_proofratings_settings() {
    $default = [
        'google' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#03AB4E',
            'name' => __('Google', 'proofratings'),
            'title' => __('Google Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/google.svg',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-google.png'
        ],

        'facebook' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#0f7ff3',
            'name' => __('Facebook', 'proofratings'),
            'title' => __('Facebook Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/facebook.svg',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-facebook.png'
        ],

        'energysage' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#bf793f',
            'name' => __('Energy Sage', 'proofratings'),
            'title' => __('Energy Sage Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/energysage.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-energysage.png'
        ],

        'solarreviews' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#0f92d7',
            'name' => __('Solar', 'proofratings'),
            'title' => __('Solar Reviews Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/solarreviews.svg',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-solarreviews.png'
        ],

        'yelp' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#e21c21',
            'name' => __('Yelp', 'proofratings'),
            'title' => __('Yelp Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/yelp.svg',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-yelp.png'
        ],

        'bbb' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#136796',
            'name' => __('BBB', 'proofratings'),
            'title' => __('BBB Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/bbb.svg',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-bbb.png'
        ],

        'guildquality' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#032e57',
            'name' => __('Guild Quality', 'proofratings'),
            'title' => __('Guild Quality Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/guildquality.svg',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-guildquality.png'
        ],

        'solarquotes' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#208ECD',
            'name' => __('Solarquotes', 'proofratings'),
            'title' => __('Solarquotes Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/solarquotes.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-solarquotes.png'
        ],

        'trustpilot' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#01B67B',
            'name' => __('Trustpilot', 'proofratings'),
            'title' => __('Trustpilot Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/trustpilot.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-trustpilot.png'
        ],

        'wordpress' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#00769D',
            'name' => __('Wordpress', 'proofratings'),
            'title' => __('Wordpress Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/wordpress.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-wordpress.jpg'
        ],

        'bestcompany' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#3c5170',
            'name' => __('Best Comapny', 'proofratings'),
            'title' => __('Best Company Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/bestcompany.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-bestcompany.jpg'
        ],

        'solartribune' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#fbcb38',
            'name' => __('Solar Tribune', 'proofratings'),
            'title' => __('Solar Tribune Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/solartribune.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-solartribune.png'
        ],

        'oneflare' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#3D9EA0',
            'name' => __('Oneflare', 'proofratings'),
            'title' => __('Oneflare Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/oneflare.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-oneflare.png'
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