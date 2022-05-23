<?php
/**
 * File containing the class Proofratings_Manager_Admin.
 *
 * @package proofratings-manager
 * @since   1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles core plugin hooks and action setup.
 *
 * @since 1.0.1
 */
class Proofratings_Analytics {

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
		add_action('in_admin_header', [$this, 'remove_notice']);
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
	}

	public function remove_notice() {
		$current_screen = preg_match('/toplevel_page_proofratings/', get_current_screen()->id, $matches);
		if(!$current_screen) {
			return;
		}
		
		remove_all_actions('admin_notices');
		remove_all_actions('all_admin_notices');
	}

	/**
	 * Enqueues CSS and JS assets.
	 */
	public function admin_enqueue_scripts() {
		preg_match('/proofratings-analytics/', get_current_screen()->id, $matches);
		if(!$matches) {
			return;
		}

		wp_enqueue_style( 'daterange-picker', PROOFRATINGS_PLUGIN_URL . '/assets/css/daterangepicker.css');
		wp_register_script( 'chart', PROOFRATINGS_PLUGIN_URL . '/assets/js/chart.min.js', [], PROOFRATINGS_VERSION, true);
		wp_register_script( 'daterange-picker', PROOFRATINGS_PLUGIN_URL . '/assets/js/daterangepicker.min.js', [], PROOFRATINGS_VERSION, true);
		wp_register_script( 'redux', PROOFRATINGS_PLUGIN_URL . '/assets/js/redux.min.js', [], PROOFRATINGS_VERSION, true);
		wp_enqueue_script( 'proofratings-analytics', PROOFRATINGS_PLUGIN_URL . '/assets/js/analytics.js', ['jquery', 'moment', 'chart', 'redux', 'daterange-picker'], PROOFRATINGS_VERSION, true);
		wp_localize_script( 'proofratings-analytics', 'proofratings', array(
			'api' => PROOFRATINGS_API_URL,
			'site_url' => get_site_url()
		));
	}

	/**
	 * Output the form
	 * @since  1.0.1
	 */
	public function output() {
		$locations = get_proofratings()->locations->items; ?>
		<div class="wrap proofratings-settings-wrap proofratings-analytics-wrap">
			<header class="proofratins-header header-row">
				<div class="header-left">
					<a class="btn-back-main-menu" href="<?php menu_page_url( 'proofratings' ) ?>"><i class="icon-back fa-solid fa-angle-left"></i> Back to Main Menu</a>
					<h1 class="title"><?php _e('Analytics', 'proofratings') ?></h1>
				</div>
				
				<div class="header-right analytics-filter">
					<?php if(get_proofratings()->locations->global !== true): ?>
					<select class="location-filter">
						<option value="">View all</option>
						<?php foreach ($locations as $location) {
							printf('<option value="%s">%s</option>', $location->id, $location->location);
						} ?>
					</select>
					<?php endif; ?>

					<div id="analytics-date"><i class="dashicons dashicons-calendar-alt"></i> <span></span></div>

					<a class="btn-support fa-regular fa-circle-question" href="<?php menu_page_url( 'proofratings-support' ) ?>"></a>
				</div>
			</header>

			

			<div class="analytics-information">
				<div class="impressions">
					<span class="counter">0</span>
					<h4 class="name">Impressions</h4>
					<p>Times notifications were shown</p>
				</div>

				<div class="hovers">
					<span class="counter">0</span>
					<h4 class="name">Hovers</h4>
					<p>Number of hovers on all notifications</p>
				</div>

				<div class="clicks">
					<span class="counter">0</span>
					<h4 class="name">Clicks</h4>
					<p>Number of clicks on all notifications</p>
				</div>

				<div class="conversions" style="display:none">
					<span class="counter">0</span>
					<h4 class="name">Conversions</h4>
					<p>Number of conversions on all notifications</p>
				</div>

				<div class="engagements">
					<span class="counter">0</span>
					<h4 class="name">Engagements</h4>
					<p>Clicks, hovers and dismission combined</p>
				</div>
			</div>

			<canvas id="analytics-chart" style="width:100%; height: 500px"></canvas>
		</div>
		<?php
	}
}

return Proofratings_Analytics::instance();