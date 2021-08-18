<?php
/**
 * File containing the class WP_Job_Manager.
 *
 * @package wp-job-manager
 * @since   1.33.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles core plugin hooks and action setup.
 *
 * @since 1.0.0
 */
class Wordpress_Proof_Ratings {
	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since  1.0.1
	 */
	private static $instance = null;

	/**
	 * Main WP Proof Ratings Instance.
	 * Ensures only one instance of WP Job Manager is loaded or can be loaded.
	 *
	 * @since  1.0.1
	 * @static
	 * @see WP_Proof_Ratings()
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
		include_once PROOF_RATINGS_PLUGIN_DIR . '/inc/class-proof-ratings-admin.php';

		$this->admin = WP_Proof_Ratings_Admin::instance();

		// Actions.
		add_action( 'init', [ $this, 'load_plugin_textdomain' ] );
	}

	/**
	 * Loads textdomain for plugin.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'proof-ratings', false, PROOF_RATINGS_PLUGIN_DIR . '/languages' );
	}

}
