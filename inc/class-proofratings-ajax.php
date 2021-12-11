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
		add_action( 'wp_ajax_proofratings_save_location', [$this, 'save_location']);
		add_action( 'wp_ajax_nopriv_proofratings_save_location', [$this, 'save_location']);

		add_action( 'wp_ajax_proofratings_get_location', [$this, 'get_location']);
		add_action( 'wp_ajax_nopriv_proofratings_get_location', [$this, 'get_location']);
	}

	public function save_location() {
		$location = @$_POST['location_id'];
		if ( empty($location)) {
			wp_send_json_error();
		}

		unset($_POST['location_id'], $_POST['action']);

		global $wpdb;

		$wpdb->update($wpdb->proofratings, ['settings' => maybe_serialize( $_POST )], ['id' => $location]);

		wp_send_json( $_POST );
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
		$location = @$_POST['location_id'];
		if ( empty($location)) {
			wp_send_json_error();
		}
		
		global $wpdb;

		$settings = [];
		if ( $location === 'overall') {
			$settings = get_option('proofratings_overall_settings');
			wp_send_json( $settings );
		}

		$location = $wpdb->get_row("SELECT * FROM $wpdb->proofratings WHERE id = $location");

		if ( !$location ) {
			wp_send_json_error();
		}

		$settings = maybe_unserialize( $location->settings );

		if ( !is_array($settings) ) {
			$settings = [];
		}

		$settings = $this->sanitize_data($settings);

		wp_send_json( $settings );
	}

	
}


return new Proofratings_Ajax();