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
            'theme_color' => '#febc00'
        ],

        'facebook' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#0f7ff3',
        ],

        'energysage' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#bf793f',
        ],

        'solarreviews' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#0f92d7',
        ],

        'yelp' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#e21c21',
        ],

        'bbb' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#136796',
        ],

        'guildquality' => [
            'active' => 'no',
            'text_color' => '',
            'background' => '',
            'theme_color' => '#032e57',
        ]
    ];

    $settings = get_option('proofratings_settings', []);

    if ( !is_array($settings) || empty($settings)) {
        return $default;
    }

    array_walk($settings, function(&$item, $key) use($default) {
        $item = array_merge($default[$key], $item);
    });

    return $settings;
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