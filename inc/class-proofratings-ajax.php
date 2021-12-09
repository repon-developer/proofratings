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
		add_action( 'wp_ajax_proofratings_get_location', [$this, 'get_location']);
		add_action( 'wp_ajax_nopriv_proofratings_get_location', [$this, 'get_location']);
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

		if ( $location ) {
			$settings = maybe_unserialize( $location->settings );
			wp_send_json( $settings );
		}


		wp_send_json_error();
	}

	
}


return new Proofratings_Ajax();