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
		include_once dirname( __FILE__ ) . '/class-proofratings-locations.php';
		include_once dirname( __FILE__ ) . '/class-proofratings-settings.php';

		$this->settings_page = Proofratings_Settings::instance();
		$this->locations_page = Proofratings_Locations::instance();
		$this->analytics = include_once dirname( __FILE__ ) . '/class-proofratings-analytics.php';

		if ( ! defined( 'DISABLE_NAG_NOTICES' ) || ! DISABLE_NAG_NOTICES ) {
			add_action( 'admin_notices', [$this, 'admin_notice_rating_us']);
		}
		
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
	}

	function admin_notice_rating_us() {
		$class = 'notice notice-info is-dismissible';
		$message = __( 'Rating us on <a target="_blank" href="https://wordpress.org/plugins/proofratings/">wordpress.org</a>.', 'proofratings' );
	 
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message); 
	}

	/**
	 * Add menu page
	 */
	public function admin_menu() {
		$proofratings_status = get_proofratings_current_status();		

		$main_screen = [$this->settings_page, 'awaiting'];
		if ( !$proofratings_status) {
			$main_screen = [$this->settings_page, 'account_inactive_output'];
		}

		if ('pause' == $proofratings_status) { 
			$main_screen = [$this->settings_page, 'pause'];
		}

		if ($proofratings_status == 'active' ) {
			$main_screen = [$this->analytics, 'output'];
		}
		
		$proofratings_icon = PROOFRATINGS_PLUGIN_URL . '/assets/images/proofratings-icon.png';

		add_menu_page(__('Proofratings', 'proofratings'), __('Proofratings', 'proofratings'), 'manage_options', 'proofratings', $main_screen, $proofratings_icon, 25);

		if ($proofratings_status == 'active' ) {
			add_submenu_page('proofratings', __('Proofratings Analytics', 'proofratings'), __('Analytics', 'proofratings'), 'manage_options', 'proofratings', [$this->analytics, 'output']);
			
			$location_menu = add_submenu_page('proofratings', __('Locations', 'proofratings'), __('Locations', 'proofratings'), 'manage_options', 'proofratings-locations', [$this->locations_page, 'render']);
			add_action( "load-$location_menu", [$this->locations_page, 'screen_option' ] );
			
			add_submenu_page('proofratings', __('Proofratings Widgets', 'proofratings'), __('Widgets', 'proofratings'), 'manage_options', 'proofratings-widgets', [$this->settings_page, 'output']);
		}
	}

	/**
	 * Enqueues CSS and JS assets.
	 */
	public function admin_enqueue_scripts() {		
		$screen = get_current_screen();
		
		preg_match('/(proofratings_page|proofratings-widgets)/', $screen->id, $matches);
		
		if ( $screen->id == 'toplevel_page_proofratings' || $matches  ) {
			wp_enqueue_style( 'didact-gothic', 'https://fonts.googleapis.com/css2?family=Didact+Gothic&display=swap', [], PROOFRATINGS_VERSION);
			wp_enqueue_style( 'proofratings-frontend', PROOFRATINGS_PLUGIN_URL . '/assets/css/proofratings.css', ['wp-color-picker'], PROOFRATINGS_VERSION);
			wp_enqueue_style( 'proofratings', PROOFRATINGS_PLUGIN_URL . '/assets/css/proofratings-admin.css', ['wp-color-picker'], PROOFRATINGS_VERSION);
			wp_enqueue_script( 'proofratings', PROOFRATINGS_PLUGIN_URL . '/assets/js/proofratings-admin.js', ['jquery', 'wp-color-picker'], PROOFRATINGS_VERSION, true);
		}

		if ( $screen->id === 'proofratings_page_proofratings-locations' && isset($_GET['location']) ) {
			wp_enqueue_script( 'proofratings-widgets', PROOFRATINGS_PLUGIN_URL . '/assets/js/proofratings-widgets.js', ['react', 'react-dom'], PROOFRATINGS_VERSION, true);
			wp_localize_script( 'proofratings-widgets', 'proofratings', array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'assets_url' => PROOFRATINGS_PLUGIN_URL . '/assets/',
				'review_sites' => get_proofratings_settings()
			));
		}
	}
}
