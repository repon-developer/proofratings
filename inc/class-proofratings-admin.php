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
		include_once dirname( __FILE__ ) . '/class-proofratings-locations.php';
		include_once dirname( __FILE__ ) . '/class-proofratings-settings.php';
		include_once dirname( __FILE__ ) . '/class-proofratings-email-reporting.php';
		
		$this->settings_page = Proofratings_Settings::instance();
		$this->locations_page = Proofratings_Locations_Admin::instance();
		$this->email_reporting = Proofratings_Email_Reporting::instance();
		$this->analytics = include_once dirname( __FILE__ ) . '/class-proofratings-analytics.php';
		
		if ( ! defined( 'DISABLE_NAG_NOTICES' ) || ! DISABLE_NAG_NOTICES ) {
			add_action( 'admin_notices', [$this, 'admin_notice_rating_us']);
		}
		
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
	}

	function admin_notice_rating_us() {
		$feedback_hide = get_option( 'proofratings_feedback_hide');
		if ( $feedback_hide || isset($_COOKIE['proofratings_feedback_hide'])) {
			return;
		} ?>
		<div id="proofrating-notice" class="notice notice-info is-dismissible">
			<p>We are excited that you chose Proofratings to display your reputation. We are working hard around the clock to continually help you convert more website visitors and increase sales. Would you please take 2 minutes to leave us a review?</p>
			<div class="btn-actions">
				<a href="https://wordpress.org/support/plugin/proofratings/reviews/" target="_blank"><span class="dashicons dashicons-external"></span> Yes, of course!</a> |
				<a href="#" data-days="28"><span class="dashicons dashicons-calendar-alt"></span> Maybe later</a> |
				<a href="#" data-days="90">Not quite yet!</a> |
				<a href="#"><span class="dashicons dashicons-dismiss"></span> No thank you</a>
			</div>
		</div>
		<?php
	}

	/**
	 * Add menu page
	 */
	public function admin_menu() {
		$proofratings_status = get_proofratings_current_status();		

		$main_screen = [$this->settings_page, 'account_inactive_output'];
		if ( 'pending' == $proofratings_status ) {
			$main_screen = [$this->settings_page, 'awaiting'];
		}

		if ('pause' == $proofratings_status) { 
			$main_screen = [$this->settings_page, 'pause'];
		}

		if ($proofratings_status == 'active' ) {
			$main_screen = [$this->analytics, 'output'];
		}
		
		$proofratings_icon = PROOFRATINGS_PLUGIN_URL . '/assets/images/proofratings-icon.png';

		$menu_name = get_proofratings()->locations->global ? __('Widgets', 'proofratings') :  __('Locations', 'proofratings');



		add_menu_page(__('Proofratings', 'proofratings'), __('Proofratings', 'proofratings'), 'manage_options', 'proofratings', $main_screen, $proofratings_icon, 25);

		if ($proofratings_status == 'active' ) {
			add_submenu_page('proofratings', __('Proofratings Analytics', 'proofratings'), __('Analytics', 'proofratings'), 'manage_options', 'proofratings', [$this->analytics, 'output']);
			
			$location_menu = add_submenu_page('proofratings', $menu_name, $menu_name, 'manage_options', 'proofratings-locations', [$this->locations_page, 'render']);
			add_action( "load-$location_menu", [$this->locations_page, 'screen_option' ] );
			
			add_submenu_page('', __('Add Location', 'proofratings'), __('Add Location', 'proofratings'), 'manage_options', 'proofratings-add-location', [$this->settings_page, 'add_location']);

			add_submenu_page('proofratings', __('Emails Settings', 'proofratings'), __('Emails', 'proofratings'), 'manage_options', 'proofratings-emails', [$this->email_reporting, 'email_settings']);
		}
	}

	/**
	 * Enqueues CSS and JS assets.
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'proofratings-dashboard', PROOFRATINGS_PLUGIN_URL . '/assets/css/proofratings-dashboard.css', [], PROOFRATINGS_VERSION);
		wp_enqueue_script( 'proofratings-dashboard', PROOFRATINGS_PLUGIN_URL . '/assets/js/proofratings-dashboard.js', ['jquery'], PROOFRATINGS_VERSION, true);
		wp_localize_script( 'proofratings-dashboard', 'proofratingsDashboard', array(
			'ajaxurl' => admin_url('admin-ajax.php')
		));

		$screen = get_current_screen();

		if ( WP_DEBUG ) {
			wp_deregister_script('react');
			wp_deregister_script('react-dom');
			wp_register_script( 'react', 'https://unpkg.com/react@17/umd/react.development.js', [], 17, true);
			wp_register_script( 'react-dom', 'https://unpkg.com/react-dom@17/umd/react-dom.development.js', [], 17, true);
		}
		
		preg_match('/(proofratings_page|proofratings-widgets)/', $screen->id, $matches);
		
		if ( $screen->id == 'toplevel_page_proofratings' || $matches  ) {
			wp_enqueue_style( 'didact-gothic', 'https://fonts.googleapis.com/css2?family=Didact+Gothic&display=swap', [], PROOFRATINGS_VERSION);
			wp_enqueue_style( 'proofratings-frontend', PROOFRATINGS_PLUGIN_URL . '/assets/css/proofratings.css', ['wp-color-picker'], PROOFRATINGS_VERSION);
			wp_enqueue_style( 'proofratings', PROOFRATINGS_PLUGIN_URL . '/assets/css/proofratings-admin.css', ['wp-color-picker'], PROOFRATINGS_VERSION);
			wp_enqueue_script( 'proofratings', PROOFRATINGS_PLUGIN_URL . '/assets/js/proofratings-admin.js', ['jquery', 'wp-util', 'wp-color-picker'], PROOFRATINGS_VERSION, true);
		}
		
		if ( $screen->id === 'proofratings_page_proofratings-locations' && (isset($_GET['location']) || get_proofratings()->locations->global) ) {
			wp_enqueue_script( 'proofratings-widgets', PROOFRATINGS_PLUGIN_URL . '/assets/js/proofratings-widgets.js', ['react', 'react-dom'], PROOFRATINGS_VERSION, true);
			wp_localize_script( 'proofratings-widgets', 'proofratings', array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'api' => PROOFRATINGS_API_URL,
				'site_url' => home_url(),
				'assets_url' => PROOFRATINGS_PLUGIN_URL . '/assets/',
				'review_sites' => get_proofratings_settings(),
				'pages' => get_pages()
			));
		}
	}
}
