<?php
/**
 * File containing the class Proofratings_Admin.
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
class Proofratings_Admin {

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
		include_once dirname( __FILE__ ) . '/class-proofratings-generate-style.php';
		include_once dirname( __FILE__ ) . '/class-proofratings-settings.php';

		$this->settings_page = Proofratings_Settings::instance();
		$this->analytics = include_once dirname( __FILE__ ) . '/class-proofratings-analytics.php';

		if ( ! defined( 'DISABLE_NAG_NOTICES' ) || ! DISABLE_NAG_NOTICES ) {
			add_action( 'admin_notices', [$this, 'admin_notice_rating_us']);
		}
		
		add_action( 'init', [$this, 'register_your_domain']);
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
	}

	function admin_notice_rating_us() {
		$class = 'notice notice-info is-dismissible';
		$message = __( 'Rating us on <a target="_blank" href="https://wordpress.org/plugins/proofratings/">wordpress.org</a>.', 'proofratings' );
	 
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message); 
	}

	/**
	 * Register domain for getting review data
	 * @since 1.0.1
	 */
	public function register_your_domain() {
		if ( !isset($_GET['_regsiter_nonce']) ) {
			return;
		}

		if( !wp_verify_nonce( $_GET['_regsiter_nonce'], 'register_proofratings') ) {
			return;
		}

		WP_ProofRatings()->activate();

		exit(wp_safe_redirect(remove_query_arg('_regsiter_nonce')));
	}

	/**
	 * Add menu page
	 */
	public function admin_menu() {
		$proofratings_status = get_proofratings_current_status();

		$main_screen = [$this->settings_page, 'awaiting'];
		if (isset($proofratings_status->status) && $proofratings_status->status == 'active' ) {
			$main_screen = [$this->analytics, 'output'];
		}

		if ( !$proofratings_status || 'not_registered' == $proofratings_status->status) {
			$main_screen = [$this->settings_page, 'account_inactive_output'];			
		}

		if ( $proofratings_status && 'pause' == $proofratings_status->status) { 
			$main_screen = [$this->settings_page, 'pause'];
		}

		if (isset($proofratings_status->status) && $proofratings_status->status == 'active' ) {
			$main_screen = [$this->analytics, 'output'];
		}

		$proofratings_icon = PROOFRATINGS_PLUGIN_URL . '/assets/images/proofratings-icon.png';

		add_menu_page(__('Proofratings', 'proofratings'), __('Proofratings', 'proofratings'), 'manage_options', 'proofratings', $main_screen, $proofratings_icon, 25);

		if (isset($proofratings_status->status) && $proofratings_status->status == 'active' ) {
			add_submenu_page('proofratings', __('Proofratings Analytics', 'proofratings'), __('Analytics', 'proofratings'), 'manage_options', 'proofratings', [$this->analytics, 'output']);
			add_submenu_page('proofratings', __('Proofratings Widgets', 'proofratings'), __('Widgets', 'proofratings'), 'manage_options', 'proofratings-analytics', [$this->settings_page, 'output']);
		}
	}

	/**
	 * Enqueues CSS and JS assets.
	 */
	public function admin_enqueue_scripts() {		
		$screen = get_current_screen();
		if ( in_array( $screen->id, [ 'toplevel_page_proofratings', 'proofratings_page_proofratings-analytics' ] ) ) {
			wp_enqueue_style( 'didact-gothic', 'https://fonts.googleapis.com/css2?family=Didact+Gothic&display=swap', [], PROOFRATINGS_VERSION);
			wp_enqueue_style( 'proofratings-frontend', PROOFRATINGS_PLUGIN_URL . '/assets/css/proofratings.css', ['wp-color-picker'], PROOFRATINGS_VERSION);
			wp_enqueue_style( 'proofratings', PROOFRATINGS_PLUGIN_URL . '/assets/css/proofratings-admin.css', ['wp-color-picker'], PROOFRATINGS_VERSION);
			wp_enqueue_script( 'proofratings', PROOFRATINGS_PLUGIN_URL . '/assets/js/proofratings-admin.js', ['jquery', 'wp-color-picker'], PROOFRATINGS_VERSION, true);
		}
	}
}
