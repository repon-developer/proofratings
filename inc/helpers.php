<?php
/**
 * File containing the class WP_Job_Manager.
 *
 * @package proof-ratings
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
            'alt' => __('Google', 'proof-ratings'),
            'logo' => PROOF_RATINGS_PLUGIN_URL . '/assets/images/google.svg'
        ],

        'facebook' => [
            'alt' => __('Facebook', 'proof-ratings'),
            'logo' => PROOF_RATINGS_PLUGIN_URL . '/assets/images/facebook.svg'
        ],

        'energysage' => [
            'alt' => __('Energy Sage', 'proof-ratings'),
            'logo' => PROOF_RATINGS_PLUGIN_URL . '/assets/images/energysage.png'
        ],

        'solarreviews' => [
            'alt' => __('Solar', 'proof-ratings'),
            'logo' => PROOF_RATINGS_PLUGIN_URL . '/assets/images/solarreviews.svg'
        ],

        'yelp' => [
            'alt' => __('Yelp', 'proof-ratings'),
            'logo' => PROOF_RATINGS_PLUGIN_URL . '/assets/images/yelp.svg'
        ],

        'bbb' => [
            'alt' => __('BBB', 'proof-ratings'),
            'logo' => PROOF_RATINGS_PLUGIN_URL . '/assets/images/bbb.svg'
        ],

        'guildquality' => [
            'alt' => __('Guild Quality', 'proof-ratings'),
            'logo' => PROOF_RATINGS_PLUGIN_URL . '/assets/images/guildquality.svg'
        ],
    ];
}


/**
 * get settings of proof ratings settings
 * @since  1.0.1
 */
function get_proof_ratings_settings() {
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

    $settings = get_option('proof_ratings_settings', []);

    if ( !is_array($settings) || empty($settings)) {
        return $default;
    }

    array_walk($settings, function(&$item, $key) use($default) {
        $item = array_merge($default[$key], $item);
    });

    return $settings;
}
