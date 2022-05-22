<?php
/**
 * File containing the class Proofratings_Ajax.
 *
 * @package proofratings
 * @since   1.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles core plugin hooks and action setup.
 *
 * @since 1.0.6
 */
class Proofratings_Ajax {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_proofratings_notice_feedback', [$this, 'notice_feedback']);
		add_action( 'wp_ajax_nopriv_proofratings_notice_feedback', [$this, 'notice_feedback']);

		add_action( 'wp_ajax_proofratings_get_settings', [$this, 'get_settings']);
		add_action( 'wp_ajax_nopriv_proofratings_get_settings', [$this, 'get_settings']);

		


		add_action( 'wp_ajax_proofratings_save_location', [$this, 'save_location']);
		add_action( 'wp_ajax_nopriv_proofratings_save_location', [$this, 'save_location']);

		add_action( 'wp_ajax_proofratings_get_location', [$this, 'get_location']);
		add_action( 'wp_ajax_nopriv_proofratings_get_location', [$this, 'get_location']);
	}

	public function notice_feedback() {
		$postdata = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

		if ( !isset($postdata['days']) ) {
			update_option('proofratings_feedback_hide', true);
			wp_send_json_success();
		}

		$days = $postdata['days'];
		setcookie("proofratings_feedback_hide", true, strtotime("+ $days days"));
		wp_send_json_success();
	}

	public function get_settings() {
		wp_send_json_success(get_proofratings_settings());
	}

	public function save_location() {
		$postdata = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

		$location = @$postdata['location_id'];
		if ( empty($location)) {
			wp_send_json_error();
		}

		unset($postdata['location_id'], $postdata['action']);
		get_proofratings()->locations->save_settings($location, $postdata);
		wp_send_json( $postdata );
	}

	function sanitize_data($string) {
		if (is_array($string)) {
        	foreach ($string as $k => $v) {
            	$string[$k] = $this->sanitize_data($v); 
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

	public function get_location() {
		$postdata = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

		$location = @$postdata['location_id'];
		if ( empty($location)) {
			wp_send_json_error();
		}
		
		$location = get_proofratings()->locations->get($location);

		if ( !$location ) {
			wp_send_json_error();
		}
		
		wp_send_json( $location->settings );
	}

	
}


return new Proofratings_Ajax();