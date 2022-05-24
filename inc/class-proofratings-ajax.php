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

		add_action( 'wp_ajax_save_proofratings_settings', [$this, 'save_settings']);
		add_action( 'wp_ajax_nopriv_save_proofratings_settings', [$this, 'save_settings']);

		


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

	public function save_settings() {
		$settings = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
		unset($settings['action']);
		update_option('proofratings_settings', $settings);

		$review_sites = get_proofratings_review_sites();	
		

		$connections = isset($settings['connections']) && is_array($settings['connections']) ? $settings['connections'] : [];
		$connection_approved = $settings['connection_approved'];

		$new_connections = [];
		foreach ($connections as $slug => $connection) {			
			if ( isset($connection['active']) && $connection['active'] == true ) {
				if ( !in_array($slug, $connection_approved) ) {
				 	$new_connections[] = $review_sites[$slug]['name'];
				}		
			}				
		}

		if ( empty($new_connections) ) {
			wp_send_json_success();
		}

		$response = wp_remote_get(PROOFRATINGS_API_URL . '/request_connection', array(
			'body' => get_proofratings_api_args(['connections' => $new_connections])
		));

		$result = json_decode(wp_remote_retrieve_body( $response ));
		if ( !is_object($result) ) {
			wp_send_json_error();
		}

		
		
		

		//error_log(print_r($result, true));



		wp_send_json_success();
	}

	public function save_location() {
		$postdata = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

		$location = @$postdata['location_id'];
		if ( empty($location)) {
			wp_send_json_error();
		}

		unset($postdata['location_id'], $postdata['action']);
		get_proofratings()->query->save_settings($location, $postdata);
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
		
		$location = get_proofratings()->query->get($location);

		if ( !$location ) {
			wp_send_json_error();
		}
		
		wp_send_json( $location->settings );
	}

	
}


return new Proofratings_Ajax();