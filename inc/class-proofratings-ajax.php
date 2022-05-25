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

		add_action( 'wp_ajax_proofratings_get_location_settings', [$this, 'get_location_settings']);
		add_action( 'wp_ajax_nopriv_proofratings_get_location_settings', [$this, 'get_location_settings']);

		add_action( 'wp_ajax_save_proofratings_location_settings', [$this, 'save_location_settings']);
		add_action( 'wp_ajax_nopriv_save_proofratings_location_settings', [$this, 'save_location_settings']);

		


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

	public function get_location_settings() {
		$postdata = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

		$location = get_proofratings()->query->get_by_location(@$postdata['location_id']);
		$location = get_proofratings()->query->get_by_location('global');

		$settings['active_connections'] = [];

		if ( isset($location->settings->active_connections) && is_array($location->settings->active_connections) ) {
			$settings['active_connections'] = $location->settings->active_connections;
		}

		wp_send_json_success($settings);
	}

	public function save_location_settings() {
		$post_data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

		$active_connections = [];
		if ( isset($post_data['active_connections']) && is_array($post_data['active_connections'])) {
			$active_connections = $post_data['active_connections'];
		}
		
		

		$location_id = false;
		if ( isset($post_data['location']) ) {
			$location_id = $post_data['location'];
			unset($post_data['location']);
		}

		if ( $location_id === false ) {
			wp_send_json_error();
		}		

		$location = get_proofratings()->query;
		$location->save_settings_by_location($location_id, array('active_connections' => $active_connections));
	
	
		$connection_approved = get_proofratings_settings('connections_approved');
		$new_connections = array_diff(array_keys($active_connections), $connection_approved);

		//REMOVE LATER FOR APPROVAL
		wp_send_json_error($new_connections);

















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