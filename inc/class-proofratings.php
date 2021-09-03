<?php
/**
 * File containing the class Wordpress_ProofRatings.
 *
 * @package proofratings
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
class Wordpress_ProofRatings {
	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since  1.0.1
	 */
	private static $instance = null;

	/**
	 * Main WP Proofratings Instance.
	 * Ensures only one instance of WP Job Manager is loaded or can be loaded.
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
		include_once PROOFRATINGS_PLUGIN_DIR . '/inc/helpers.php';
		include_once PROOFRATINGS_PLUGIN_DIR . '/inc/class-proofratings-admin.php';
		include_once PROOFRATINGS_PLUGIN_DIR . '/inc/class-proofratings-shortcodes.php';

		$this->admin = ProofRatings_Admin::instance();
		$this->shortcodes = ProofRatings_Shortcodes::instance();

		// Actions.
		add_action( 'init', [ $this, 'load_plugin_textdomain' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_footer', [ $this, 'embed_floating_badge' ] );
		add_action( 'proofratings_get_reviews', [ $this, 'proofratings_get_reviews' ] );

		self::maybe_schedule_cron_jobs();
	}

	/**
	 * proof ratings activate
	 */
	public function activate() {
		update_option('proofratings_version', PROOFRATINGS_VERSION );

		$request_url = add_query_arg(array(
			'name' => get_bloginfo( 'name' ),
			'email' => get_bloginfo( 'admin_email' ),
			'url' => get_site_url()
		), PROOFRATINGS_API_URL . '/register');

		$response = wp_remote_get($request_url);

		if( $response['response']['code'] !== 200) {
			return;			
		}

		$data = json_decode(wp_remote_retrieve_body($response));
		if ( is_object($data) && $data->success ) {
			update_option('proofratings_status', $data );
			if ( $data->status == 'active' ) {
				$this->proofratings_get_reviews();
			}
		}
	}

	/**
	 * Loads textdomain for plugin.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'proofratings', false, PROOFRATINGS_PLUGIN_DIR . '/languages' );
	}

	/**
	 * frontend CSS and JS assets.
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'didact-gothic', 'https://fonts.googleapis.com/css2?family=Didact+Gothic&display=swap', [], PROOFRATINGS_VERSION);
		wp_enqueue_style( 'proofratings', PROOFRATINGS_PLUGIN_URL . '/assets/css/proofratings.css', [], PROOFRATINGS_VERSION);

		$upload_dir = wp_upload_dir();
		$generated_css = $upload_dir['basedir'] . '/proofratings-generated.css';
		if ( file_exists($generated_css) ) {
			wp_enqueue_style( 'proofratings-generated', $upload_dir['baseurl'] . '/proofratings-generated.css', [], filemtime($generated_css));			
		}
	}

	/**
	 * Embed floating badge on frontend
	 */
	public function embed_floating_badge() {
		echo do_shortcode( '[proofratings_floating_badge]' );
	}
	
	/**
	 * Schedule cron jobs for proof rating events.
	 * @since 1.0.1
	 */
	public static function maybe_schedule_cron_jobs() {
		//do_action( 'proofratings_get_reviews');
		if ( ! wp_next_scheduled( 'proofratings_get_reviews' ) ) {
			wp_schedule_event( time(), 'daily', 'proofratings_get_reviews' );
		}
	}

	public function proofratings_get_reviews() {
		$request_url = add_query_arg(array(
			'domain' => get_site_url()
		), PROOFRATINGS_API_URL . '/get-reviews');

		
		$response = wp_remote_get($request_url);
		if ( is_wp_error( $response ) ) {
			return;
		}

		$data = json_decode(wp_remote_retrieve_body($response));
		if ( isset($data->data) ) {
			unset($data->data);
		}

		if( $response['response']['code'] === 412) {
			$data->status = $data->code;
			unset($data->code);
			return update_option('proofratings_status', $data );
		}

		if( $response['response']['code'] !== 200) {
			return;
		}

		if( is_object($data) ) {
			update_option( 'proofratings_reviews', $data);
			update_option('proofratings_status', ['status' => 'active']);		
		}
	}

}
