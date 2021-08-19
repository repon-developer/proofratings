<?php
/**
 * File containing the class Wordpress_Proof_Ratings.
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
		include_once PROOF_RATINGS_PLUGIN_DIR . '/inc/SimpleXLSX.php';
		include_once PROOF_RATINGS_PLUGIN_DIR . '/inc/helpers.php';
		include_once PROOF_RATINGS_PLUGIN_DIR . '/inc/class-proof-ratings-admin.php';
		include_once PROOF_RATINGS_PLUGIN_DIR . '/inc/class-proof-ratings-shortcodes.php';

		$this->admin = Proof_Ratings_Admin::instance();
		$this->shortcodes = Proof_Ratings_Shortcodes::instance();

		// Actions.
		add_action( 'init', [ $this, 'load_plugin_textdomain' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_footer', [ $this, 'embed_floating_badge' ] );
	}

	/**
	 * Loads textdomain for plugin.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'proof-ratings', false, PROOF_RATINGS_PLUGIN_DIR . '/languages' );
	}

	/**
	 * frontend CSS and JS assets.
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'proof-ratings', PROOF_RATINGS_PLUGIN_URL . '/assets/css/proof-ratings.css', [], PROOF_RATINGS_VERSION);
		wp_enqueue_style( 'proof-ratings-generated', PROOF_RATINGS_PLUGIN_URL . '/assets/css/proof-ratings-generated.css', [], PROOF_RATINGS_VERSION);
	}

	/**
	 * Embed floating badge on frontend
	 */
	public function embed_floating_badge() {
		echo do_shortcode( '[proof_ratings_floating_badge]' );
	}

	

}
