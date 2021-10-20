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

class Proofratings_Site_Data {
    var $active = 'no';

    function __construct($data = []) {
        if ( is_object($data)) {
            $data = (array) $data;
        }

        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function __get($key) {
        return isset($this->$key) ? $this->$key : null;
    }
}

/**
 * get settings of proof ratings settings
 * @since  1.0.1
 */
function get_proofratings_settings() {
    $default = [
        'google' => [
            'theme_color' => '#03AB4E',
            'name' => __('Google', 'proofratings'),
            'title' => __('Google Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/google.svg',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-google.png',
            'rating_title' => __('Google Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/google-black.png',
            'category' => 'general'
        ],

        'facebook' => [
            'theme_color' => '#0f7ff3',
            'name' => __('Facebook', 'proofratings'),
            'title' => __('Facebook Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/facebook.svg',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-facebook.png',
            'rating_title' => __('Facebook Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/facebook-black.png',
            'category' => 'general'
        ],

        'yelp' => [
            'theme_color' => '#e21c21',
            'name' => __('Yelp', 'proofratings'),
            'title' => __('Yelp Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/yelp.svg',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-yelp.png',
            'rating_title' => __('Yelp Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/yelp-black.png',
            'category' => 'general'
        ],

        'bbb' => [
            'theme_color' => '#136796',
            'name' => __('BBB', 'proofratings'),
            'title' => __('BBB Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/bbb.svg',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-bbb.png',
            'rating_title' => __('BBB Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/bbb-black.png',
            'category' => 'general'
        ],
        
        'trustpilot' => [
            'theme_color' => '#01B67B',
            'name' => __('Trustpilot', 'proofratings'),
            'title' => __('Trustpilot Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/trustpilot.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-trustpilot.png',
            'rating_title' => __('Trustpilot Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/trustpilot-black.png',
            'category' => 'general'
        ],

        'bestcompany' => [
            'theme_color' => '#3c5170',
            'name' => __('Best Comapny', 'proofratings'),
            'title' => __('Best Company Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/bestcompany.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-bestcompany.jpg',
            'rating_title' => __('Best Comapny Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/bestcompany-black.png',
            'category' => 'general'
        ],

        //Home service review sites
        'angi' => [
            'theme_color' => '#FF5E4F',
            'name' => __('Angi', 'proofratings'),
            'title' => __('Angi Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/angi.svg',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-angi.png',
            'rating_title' => __('Angi Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/angi-black.png',
            'category' => 'home-service'
        ],

        'guildquality' => [
            'theme_color' => '#032e57',
            'name' => __('Guild Quality', 'proofratings'),
            'title' => __('Guild Quality Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/guildquality.svg',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-guildquality.png',
            'rating_title' => __('Guild Quality Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/guildquality-black.png',
            'category' => 'home-service'
        ],

        //Solar review sites
        'energysage' => [
            'theme_color' => '#bf793f',
            'name' => __('Energy Sage', 'proofratings'),
            'title' => __('Energy Sage Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/energysage.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-energysage.png',
            'rating_title' => __('Energy Sage Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/energysage-black.png',
            'category' => 'solar'
        ],

        'solarreviews' => [
            'theme_color' => '#0f92d7',
            'name' => __('Solar', 'proofratings'),
            'title' => __('Solar Reviews Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/solarreviews.svg',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-solarreviews.png',
            'rating_title' => __('Solar Reviews Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/solarreviews-black.png',
            'category' => 'solar'
        ],

        'solarquotes' => [
            'theme_color' => '#208ECD',
            'name' => __('Solarquotes', 'proofratings'),
            'title' => __('Solarquotes Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/solarquotes.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-solarquotes.png',
            'rating_title' => __('Solarquotes Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/solarquotes-black.png',
            'category' => 'solar'
        ],

        'solartribune' => [
            'theme_color' => '#fbcb38',
            'name' => __('Solar Tribune', 'proofratings'),
            'title' => __('Solar Tribune Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/solartribune.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-solartribune.png',
            'rating_title' => __('Solar Tribune Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/solartribune-black.png',
            'category' => 'solar'
        ],

        'oneflare' => [
            'theme_color' => '#3D9EA0',
            'name' => __('Oneflare', 'proofratings'),
            'title' => __('Oneflare Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/oneflare.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-oneflare.png',
            'rating_title' => __('Oneflare Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/oneflare-black.png',
            'category' => 'solar'
        ],

        //SaaS/Software Review Sites
        'wordpress' => [
            'theme_color' => '#00769D',
            'name' => __('Wordpress', 'proofratings'),
            'title' => __('Wordpress Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/wordpress.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-wordpress.jpg',
            'rating_title' => __('Wordpress Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/wordpress-black.png',
            'category' => 'software'
        ],

        'capterra' => [
            'theme_color' => '#044D80',
            'name' => __('Capterra', 'proofratings'),
            'title' => __('Capterra Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/capterra.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-capterra.png',
            'rating_title' => __('Capterra Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/capterra-black.png',
            'category' => 'software'
        ],

        'g2' => [
            'theme_color' => '#EF4D35',
            'name' => __('G2', 'proofratings'),
            'title' => __('G2 Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/g2.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-g2.png',
            'rating_title' => __('G2 Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/g2-black.png',
            'category' => 'software'
        ],

        'getapp' => [
            'theme_color' => '#41E3E2',
            'name' => __('Getapp', 'proofratings'),
            'title' => __('Getapp Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/getapp.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-getapp.png',
            'rating_title' => __('Getapp Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/getapp-black.png',
            'category' => 'software'
        ],

        'softwareadvice' => [
            'theme_color' => '#FD810D',
            'name' => __('Software Advice', 'proofratings'),
            'title' => __('Software Advice Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/softwareadvice.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-softwareadvice.png',
            'rating_title' => __('Software Advice Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/softwareadvice-black.png',
            'category' => 'software'
        ],

        'saasworthy' => [
            'theme_color' => '#FEBA52',
            'name' => __('SaaSworthy', 'proofratings'),
            'title' => __('SaaSworthy Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/saasworthy.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-saasworthy.png',
            'rating_title' => __('SaaSworthy Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/saasworthy-black.png',
            'category' => 'software'
        ],

        'crozdesk' => [
            'theme_color' => '#015BE3',
            'name' => __('Crozdesk', 'proofratings'),
            'title' => __('Crozdesk Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/crozdesk.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-crozdesk.png',
            'rating_title' => __('Crozdesk Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/crozdesk-black.png',
            'category' => 'software'
        ],

        'quickbooks' => [
            'theme_color' => '#2C9F1C',
            'name' => __('Quickbooks', 'proofratings'),
            'title' => __('Quickbooks Review Settings', 'proofratings'),
            'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/quickbooks.png',
            'icon' => PROOFRATINGS_PLUGIN_URL . '/assets/images/icon-quickbooks.png',
            'rating_title' => __('Quickbooks Rating', 'proofratings'),
            'icon2' => PROOFRATINGS_PLUGIN_URL . '/assets/images/quickbooks-black.png',
            'category' => 'software'
        ]
    ];

    $settings = get_option('proofratings_settings', []);

    
    array_walk($default, function(&$item, $key) use($settings) {
        if ( !isset($settings[$key]) ) {
            return $item = new Proofratings_Site_Data($item);
        }
        
        $item = new Proofratings_Site_Data(array_merge($item, $settings[$key]));
    });    

    return $default;
}

/**
 * get square badges settings
 * @since  1.0.4
 */
function get_proofratings_badges_square() {
    return wp_parse_args(get_option('proofratings_badges_square'), [
		'customize' => 'no',
		'star_color' => '',
		'text_color' => '',
		'review_count_textcolor' => '',
		'background' => '',
		'shadow' => 'yes',
		'shadow_color' => '',
		'shadow_hover_color' => '',
	]);
}

/**
 * get rectangle badges settings
 * @since  1.0.4
 */
function get_proofratings_badges_rectangle() {
    return wp_parse_args(get_option('proofratings_badges_rectangle'), [
		'customize' => 'no',
		'star_color' => '',
		'text_color' => '',
		'review_count_textcolor' => '',
		'background' => '',
		'shadow' => 'yes',
		'shadow_color' => '',
		'shadow_hover_color' => '',
	]);
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

/**
 * get review sites
 * @since  1.0.4
 */
function get_proofratings_review_sites($group) {
    $group_sites = array_filter(get_proofratings_settings(), function($item) use($group) {
        return $item->category == $group;
    });

    echo '<div class="review-sites-checkboxes">';
    foreach ($group_sites as $key => $site) {
        printf(
            '<label class="checkbox-review-site" data-site="%1$s"><input type="checkbox" name="proofratings_settings[%1$s][active]" value="yes" %3$s /><img src="%2$s" alt="%1$s" /></label>', 
            $key, esc_attr($site->logo), checked('yes', $site->active, false)
        );
    }
    echo '</div>';
}