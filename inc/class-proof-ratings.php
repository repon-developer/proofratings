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
		include_once PROOF_RATINGS_PLUGIN_DIR . '/inc/helpers.php';
		include_once PROOF_RATINGS_PLUGIN_DIR . '/inc/class-proof-ratings-admin.php';
		include_once PROOF_RATINGS_PLUGIN_DIR . '/inc/class-proof-ratings-shortcodes.php';

		$this->admin = Proof_Ratings_Admin::instance();
		$this->shortcodes = Proof_Ratings_Shortcodes::instance();

		// Actions.
		add_action( 'init', [ $this, 'load_plugin_textdomain' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_footer', [ $this, 'embed_floating_badge' ] );
		add_action( 'proof_ratings_get_reviews', [ $this, 'proof_ratings_get_reviews' ] );

		self::maybe_schedule_cron_jobs();
	}

	/**
	 * proof ratings activate
	 */
	public function activate() {
		update_option('proof_ratings_version', PROOF_RATINGS_VERSION );

		$request_url = add_query_arg(array(
			'name' => get_bloginfo( 'name' ),
			'email' => get_bloginfo( 'admin_email' ),
			'url' => get_site_url()
		), PROOF_RATINGS_API_URL . '/register');

		$response = wp_remote_get($request_url);

		if( $response['response']['code'] !== 200) {
			return;			
		}

		$data = json_decode(wp_remote_retrieve_body($response));
		if ( is_object($data) && $data->success ) {
			update_option('proof_ratings_status', $data );
			if ( $data->status == 'active' ) {
				$this->proof_ratings_get_reviews();
			}
		}
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
	
	/**
	 * Schedule cron jobs for proof rating events.
	 * @since 1.0.1
	 */
	public static function maybe_schedule_cron_jobs() {
		//do_action( 'proof_ratings_get_reviews');
		if ( ! wp_next_scheduled( 'proof_ratings_get_reviews' ) ) {
			wp_schedule_event( time(), 'daily', 'proof_ratings_get_reviews' );
		}
	}

	public function proof_ratings_get_reviews() {
		$request_url = add_query_arg(array(
			'domain' => get_site_url()
		), PROOF_RATINGS_API_URL . '/get-reviews');

		$response = wp_remote_get($request_url);

		$data = json_decode(wp_remote_retrieve_body($response));
		if ( isset($data->data) ) {
			unset($data->data);
		}

		if( $response['response']['code'] === 412) {
			$data->status = $data->code;
			unset($data->code);
			return update_option('proof_ratings_status', $data );
		}

		if( $response['response']['code'] !== 200) {
			return;
		}

		if( is_object($data) ) {
			update_option( 'proof_ratings_reviews', $data);
			update_option('proof_ratings_status', ['status' => 'active']);		
		}
	}

}
