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

		add_action( 'wp_ajax_proofratings_get_stats', [$this, 'get_stats']);
		add_action( 'wp_ajax_nopriv_proofratings_get_stats', [$this, 'get_stats']);
	}

	public function remove_notice() {
		$current_screen = preg_match('/toplevel_page_proofratings/', get_current_screen()->id, $matches);
		if(!$current_screen) {
			return;
		}
		
		remove_all_actions('admin_notices');
		remove_all_actions('all_admin_notices');
	}

	public function get_stats() {
		global $wpdb;
		$response = array('clicks' => [], 'impressions' => [], 'engagements' => []);

		$where = $wpdb->prepare("created_date BETWEEN '%s' AND '%s'", $_POST['start'], $_POST['end']);
		if ( !empty($_POST['domain']) ) {
			$where .= $wpdb->prepare(" AND domain='%s'", $_POST['domain']);
		}
		
		
		$columns = "count(*) as result, Date(`created_date`) as date";
		$group_date = "DAY(`created_date`)";

		if ( $_POST['monthly'] == 'true') {
			$columns = "count(*) as result, DATE_FORMAT(created_date, '%Y-%m') as date";
			$group_date = "DATE_FORMAT(created_date, '%Y-%m')";
		}

		if ($clicks = $wpdb->get_results("SELECT $columns FROM $wpdb->proofratings_manager_stats WHERE $where AND type = 'click' GROUP BY type, $group_date")) {
			$response['clicks'] = $clicks;
		}

		if ( $impressions = $wpdb->get_results("SELECT $columns FROM $wpdb->proofratings_manager_stats WHERE $where AND type = 'impression' GROUP BY type, $group_date") ) {
			$response['impressions'] = $impressions;
		}

		if ( $engagements = $wpdb->get_results("SELECT $columns FROM $wpdb->proofratings_manager_stats WHERE $where GROUP BY $group_date") ) {
			$response['engagements'] = $engagements;
		}		

		wp_send_json($response);
	}

	/**
	 * Enqueues CSS and JS assets.
	 */
	public function admin_enqueue_scripts() {
		$current_screen = preg_match('/toplevel_page_proofratings/', get_current_screen()->id, $matches);
		if(!$current_screen) {
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
	public function output() {?>
		<div class="wrap proofratings-analytics-wrap loading">
			<hr class="wp-header-end">

			<div class="analytics-filter">
				<div class="right">
					<div id="analytics-date"><i class="dashicons dashicons-calendar-alt"></i> <span></span></div>
				</div>
			</div>

			<div class="analytics-information">
				<div class="impressions">
					<span class="counter">0</span>
					<h4 class="name">Impressions</h4>
					<p>Times notifications were shown</p>
				</div>

				<div class="clicks">
					<span class="counter">0</span>
					<h4 class="name">Clicks</h4>
					<p>Number of clicks on all notifications</p>
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