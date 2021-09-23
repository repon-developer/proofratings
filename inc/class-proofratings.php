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
class Wordpress_Proofratings {
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

		add_action( 'rest_api_init', [$this, 'register_rest_api']);

		// Actions.
		add_action( 'init', [ $this, 'load_plugin_textdomain' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_footer', [ $this, 'embed_floating_badge' ] );
	}
		
	/**
	 * proofratings rest api for getting data
	 */
	public function register_rest_api() {		
		register_rest_route( 'proofratings/v1', 'set_reviews', array(
			'methods' => 'POST',
			'callback' => [$this, 'set_reviews'],
			'permission_callback' => '__return_true'
		));
	}

	/**
	 * proofratings rest api callback
	 */
	public function set_reviews(WP_REST_Request $request) {
		$reviews_info = wp_parse_args($request->get_params(), ['status' => false, 'message' => '', 'reviews' => array()]);

		$reviews = $reviews_info['reviews'];
		unset($reviews_info['reviews']);

		update_option('proofratings_status', $reviews_info );
		update_option( 'proofratings_reviews', $reviews);
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
		if ( is_wp_error( $response ) ) {
			return;
		}

		if( $response['response']['code'] !== 200) {
			return;			
		}

		$data = json_decode(wp_remote_retrieve_body($response));
		if ( is_object($data) && $data->success ) {
			update_option('proofratings_status', $data );
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

		wp_register_script( 'js-cookie', PROOFRATINGS_PLUGIN_URL.  '/assets/js/js.cookie.min.js', [], '3.0.1', true);

		$upload_dir = wp_upload_dir();
		$generated_css = $upload_dir['basedir'] . '/proofratings-generated.css';
		if ( file_exists($generated_css) ) {
			wp_enqueue_style( 'proofratings-generated', $upload_dir['baseurl'] . '/proofratings-generated.css', [], filemtime($generated_css));			
		}

		wp_enqueue_script( 'proofratings', PROOFRATINGS_PLUGIN_URL . '/assets/js/proofratings.js', ['jquery', 'js-cookie'], PROOFRATINGS_VERSION, true);
	}

	/**
	 * Embed floating badge on frontend
	 */
	public function embed_floating_badge() {
		if ( isset($_COOKIE['hide_proofratings_float_badge'])) {
			return;
		}

		$badge_settings = get_option( 'proofratings_floating_badge_settings');
		$on_pages = (array) @$badge_settings['on_pages'];

		$has_page = !isset($badge_settings['on_pages'][get_the_ID()]) || $badge_settings['on_pages'][get_the_ID()] == 'yes'? true : false;
		$show_badge = @$badge_settings['show'];
		if ( !($show_badge != 'yes') && $has_page ) {
			
			if ( @$badge_settings['position'] == 'center') {
				echo '<div class="proofratings-banner-badge-tab">';
			}

			echo do_shortcode(sprintf('[proofratings_floating_badge float="true" badge_style="%s" mobile="%s" tablet="%s"]', $badge_settings['badge_style'], $badge_settings['mobile'], $badge_settings['tablet']) );
			echo do_shortcode('[proofratings_floating_widgets]' );

			if ( @$badge_settings['position'] == 'center') {
				echo '</div>';
			}
		}
	}
}
