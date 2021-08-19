<?php
/**
 * File containing the class WP_Proof_Ratings_Admin.
 *
 * @package proof-ratings
 * @since   1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles core plugin hooks and action setup.
 *
 * @since 1.0.0
 */
class WP_Proof_Ratings_Admin {

	/**
	 * The single instance of the class.
	 * @var self
	 * @since  1.0.1
	 */
	private static $instance = null;

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @since  1.0.1
	 * @static
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * Constructor.
	 */
	public function __construct() {
		include_once dirname( __FILE__ ) . '/class-proof-ratings-settings.php';

		$this->settings_page = WP_Proof_Ratings_Settings::instance();

		add_action( 'admin_menu', [ $this, 'admin_menu' ] );

		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

	}

	/**
	 * Add menu page
	 */
	public function admin_menu() {
		add_menu_page(__('Proof Ratings', 'proof-ratings'), __('Proof Ratings', 'proof-ratings'), 'manage_options', 'proof-ratings', [$this->settings_page, 'output'], 'dashicons-star-filled', 25);
	}


	/**
	 * Enqueues CSS and JS assets.
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();

		if ( in_array( $screen->id, [ 'toplevel_page_proof-ratings' ] ) ) {
			wp_enqueue_style( 'proof-ratings', PROOF_RATINGS_PLUGIN_URL . '/assets/css/proof-ratings-admin.css', ['wp-color-picker'], PROOF_RATINGS_VERSION);
			wp_enqueue_script( 'proof-ratings', PROOF_RATINGS_PLUGIN_URL . '/assets/js/proof-ratings-admin.js', ['jquery', 'wp-color-picker'], PROOF_RATINGS_VERSION, true);
		}
	}


}
