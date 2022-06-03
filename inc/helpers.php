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
    function __construct($data = []) {
        if ( is_a($data, 'Proofratings_Site_Data') || empty($data)) {
            return $data;
        }

        if ( is_object($data)) {
            $data = (array) $data;
        }

        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function __isset($key) {
        return isset($this->$key);
    }

    public function __get($key) {
        return isset($this->$key) ? $this->$key : null;
    }
}

/**
 * Sanitize boolean data
 * @since  1.1.7
 */
function sanitize_proofrating_boolean_data($string) {
    if (is_array($string)) {
        foreach ($string as $k => $v) {
            $string[$k] = sanitize_proofrating_boolean_data($v); 
        }

        return $string;
    }

    if ( $string === 'true' ) {
        return true;
    }

    if ( $string === 'false' ) {
        return false;
    }
    
    return $string;
}

/**
 * get settings of proofratings settings
 * @since  1.0.1
 */
function get_proofratings_settings($key = null) { 
    $settings = get_option( 'proofratings_settings');
    if ( !is_array($settings) ) {
        $settings = [];
    }

    if (!isset($settings['connections_approved']) || !is_array($settings['connections_approved']) ) {
        $settings['connections_approved'] = [];
    }

    if ( !isset($settings['agency']) ) {
        $settings['agency'] = false;
    }

    if ( !isset($settings['schema']) ) {
        $settings['schema'] = '';
    }

    if ( $key ) {
        return isset($settings[$key]) ? $settings[$key] : false;
    }

    return sanitize_proofrating_boolean_data($settings);
}

/**
 * Update proofratings settings
 * @since  1.1.7
 */
function update_proofratings_settings($args) { 
    update_option('proofratings_settings', array_merge(get_proofratings_settings(), (array) $args));
}

/**
 * get current status
 * @since  1.0.1
 */
function get_proofratings_current_status() {
    $proofratings_status = get_proofratings_settings('status');
    if ( !$proofratings_status || !in_array($proofratings_status, ['pending', 'pause', 'active'])) {
        return false;
    }

    return $proofratings_status;
}

/**
 * Get api request args
 * @since  1.4.7
 */
function get_proofratings_api_args($args = []) {
    $params = array_merge(array(
        'name' => get_bloginfo( 'name' ),
        'email' => get_bloginfo( 'admin_email' ),
        'site_url' => get_site_url()
    ), (array) $args);

    return array('body' => $params);
}

/**
 * get square badges settings
 * @since  1.0.4
 */
function get_proofratings_display_settings() {
    return wp_parse_args(get_option( 'proofratings_display_badge'), [
        'square' => 'no',
        'rectangle' => 'no',
        'overall_ratings_rectangle' => 'no',
        'overall_ratings_narrow' => 'no',
        'overall_ratings_cta_banner' => 'no',
    ]);
}

/**
 * get square badges settings
 * @since  1.0.4
 */
function get_proofratings_badges_square() {
    return new Proofratings_Site_Data(wp_parse_args(get_option('proofratings_badges_square'), [
		'customize' => 'no',
		'shadow' => 'yes'
	]));
}

/**
 * get rectangle badges settings
 * @since  1.0.4
 */
function get_proofratings_badges_rectangle() {
    return new Proofratings_Site_Data(wp_parse_args(get_option('proofratings_badges_rectangle'), [
		'customize' => 'no',
		'shadow' => 'yes',
        'icon_color' => '#000'
	]));
}

/**
 * get popup badges settings
 * @since  1.0.4
 */
function get_proofratings_badges_popup() {
    return new Proofratings_Site_Data(wp_parse_args(get_option('proofratings_badges_popup'), ['customize' => 'no']));
}

/**
 * get overall rectangle settings
 * @since  1.0.4
 */
function get_proofratings_overall_ratings_rectangle() {
    return new Proofratings_Site_Data(wp_parse_args(get_option('proofratings_overall_ratings_rectangle'), [
		'float' => 'yes',
        'tablet' => 'yes',
        'mobile' => 'yes',
        'close_button' => 'yes',
        'customize' => 'no',
        'shadow' => 'yes',
        'pages' => [],
	]));
}

/**
 * get overall ratings narrow settings
 * @since  1.0.4
 */
function get_proofratings_overall_ratings_narrow() {
    return new Proofratings_Site_Data(wp_parse_args(get_option('proofratings_overall_ratings_narrow'), [
		'float' => 'yes',
        'tablet' => 'yes',
        'mobile' => 'yes',
        'close_button' => 'yes',
        'customize' => 'no',
        'shadow' => 'yes',
        'pages' => [],
	]));
}

/**
 * get overall ratings CTA Banner settings
 * @since  1.0.4
 */
function get_proofratings_overall_ratings_cta_banner() {
    return new Proofratings_Site_Data(wp_parse_args((array)get_option( 'proofratings_overall_ratings_cta_banner'), [
        'tablet' => 'yes',
        'close_button' => 'yes',
        'mobile' => 'yes',
        'shadow' => 'yes',

        'button1_text' => 'Sign Up',
        'button1_blank' => 'no',
        'button1_shape' => 'rectangle',
        'button1_border' => 'yes',
        
        'button2' => 'no',
        'button2_blank' => 'no',
        'button2_border' => 'yes'
    ]));
}


do_action( 'in_admin_footer' );

function proofratings_review_us() {?>
    <p class="proofratings-review-us">Enjoying Proofratings? <img draggable="false" role="img" class="emoji" alt="❤️" src="https://s.w.org/images/core/emoji/13.1.0/svg/2764.svg"> Review us <a href="https://wordpress.org/plugins/proofratings/" target="_blank">here</a></p>
    <?php
}
add_action( 'in_admin_footer', 'proofratings_review_us');


add_action( 'init', function(){
    return;

    $locations = get_proofratings()->Query->locations[0];

    var_dump($locations);
    exit;
});

