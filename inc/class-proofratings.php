<?php
/**
 * File containing the class Proofratings.
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
class Proofratings {
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
		$this->add_proofratings_tables();

		include_once PROOFRATINGS_PLUGIN_DIR . '/inc/helpers.php';
		include_once PROOFRATINGS_PLUGIN_DIR . '/inc/class-proofratings-ratings.php';
		include_once PROOFRATINGS_PLUGIN_DIR . '/inc/class-proofratings-locations-query.php';
		include_once PROOFRATINGS_PLUGIN_DIR . '/inc/class-proofratings-ajax.php';
		include_once PROOFRATINGS_PLUGIN_DIR . '/inc/class-proofratings-review.php';
		include_once PROOFRATINGS_PLUGIN_DIR . '/inc/class-proofratings-shortcodes.php';
		
		$this->locations = Proofratings_Locations::instance();
		$this->shortcodes = Proofratings_Shortcodes::instance();

		if ( is_admin(  ) ) {
			include_once PROOFRATINGS_PLUGIN_DIR . '/inc/class-proofratings-admin.php';
			$this->admin = Proofratings_Admin::instance();
		}	
		

		add_action( 'rest_api_init', [$this, 'register_rest_api']);

		// Actions.
		add_action( 'init', [ $this, 'load_plugin_textdomain' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		add_action( 'wp_footer', [ $this, 'overall_ratings_narrow' ] );
		add_action( 'wp_footer', [ $this, 'overall_ratings_rectangle' ] );
		add_action( 'wp_footer', [ $this, 'banner_badge' ] );
	}

	/**
	 * add proofratings table in $wpdb
	 * @since 1.0.7
	 */	
	function add_proofratings_tables() {
	    global $wpdb;
		$wpdb->proofratings = $wpdb->prefix . 'proofratings';
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
		error_log(print_r($request->get_params(), true));

		$review_locations = $request->get_param('review_locations');
		if ( !is_array($review_locations) ) {
			$review_locations = [];
		}

		global $wpdb;

		foreach ($review_locations as $id => $location) {
			$reviews = null;
			if ( isset($location['reviews']) && is_array($location['reviews'])) {
				$reviews = maybe_serialize($location['reviews']);
			}

			$location_data = array(
				'location_id' => $id,
				'location' => @$location['name'],
				'reviews' => $reviews,
				'status' => @$location['status']
			);
			
			if ( $get_id = $wpdb->get_var("SELECT * FROM $wpdb->proofratings WHERE location_id = '$id'") ) {
				$wpdb->update($wpdb->proofratings, $location_data, ['id' => $get_id]);
				continue;
			}

			$wpdb->insert($wpdb->proofratings, $location_data);
		}

		if ( $request->get_param('global') ) {
			$wpdb->query("UPDATE $wpdb->proofratings SET `status` = 'pause' WHERE location_id != 'global'");
		}

		$has_locations = $request->get_param('has_locations');
		if ( is_array($has_locations) ) {
			$ids = implode("','", $has_locations);
			$wpdb->query(sprintf("DELETE FROM $wpdb->proofratings WHERE location_id NOT IN ('%s')", $ids));
		}

		update_option( 'proofratings_status', $request->get_param('status'));
	}

	/**
	 * proofratings activate
	 */
	public function activate() {
		global $wpdb;

		update_option('proofratings_version', PROOFRATINGS_VERSION );

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		maybe_create_table($wpdb->proofratings, "CREATE TABLE $wpdb->proofratings (
			`id` INT NOT NULL AUTO_INCREMENT, 
			`location_id` VARCHAR(50) NULL,
			`location` VARCHAR(100) NULL, 
			`reviews` LONGTEXT NULL, 
			`settings` LONGTEXT NULL, 
			`status` VARCHAR(20) NOT NULL DEFAULT 'pending', 
			`created_on` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`)
		);");

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
		wp_enqueue_style( 'proofratings-font', PROOFRATINGS_PLUGIN_URL . '/assets/webfonts/fonts.css', [], PROOFRATINGS_VERSION);
		wp_enqueue_style( 'proofratings', PROOFRATINGS_PLUGIN_URL . '/assets/css/proofratings.css', [], PROOFRATINGS_VERSION);

		wp_register_script( 'js-cookie', PROOFRATINGS_PLUGIN_URL.  '/assets/js/js.cookie.min.js', [], '3.0.1', true);

		$upload_dir = wp_upload_dir();
		$generated_css = $upload_dir['basedir'] . '/proofratings-generated.css';
		if ( file_exists($generated_css) ) {
			wp_enqueue_style( 'proofratings-generated', $upload_dir['baseurl'] . '/proofratings-generated.css', [], filemtime($generated_css));	
		}

		wp_enqueue_script( 'proofratings', PROOFRATINGS_PLUGIN_URL . '/assets/js/proofratings.js', ['jquery', 'js-cookie'], PROOFRATINGS_VERSION, true);
		wp_localize_script( 'proofratings', 'proofratings', array('api' => PROOFRATINGS_API_URL, 'site_url' => get_site_url()));
	}

	/**
	 * Overrall Ratings Rectangle  badge on frontend
	 * @since 1.0.4
	 */
	public function overall_ratings_rectangle() {
		if ( get_proofratings_display_settings()['overall_ratings_rectangle'] !== 'yes' ) {
			return;
		}

		$badge_settings = get_proofratings_overall_ratings_rectangle();
		if ($badge_settings->float !== 'yes') {
			return;
		}

		$on_pages = (array) @$badge_settings->pages;
		$has_page = !isset($badge_settings->pages[get_the_ID()]) || $badge_settings->pages[get_the_ID()] == 'yes'? true : false;

		if ($has_page ) {
			echo do_shortcode('[proofratings_overall_ratings type="rectangle" float="yes"]' );
			echo do_shortcode('[proofratings_badges_popup]' );
		}
	}

	/**
	 * Overrall Ratings Narrow on frontend
	 * @since 1.0.4
	 */
	public function overall_ratings_narrow() {
		// var_dump(get_proofratings()->locations->get('overall'));
		// exit;
		if ( get_proofratings_display_settings()['overall_ratings_narrow'] !== 'yes' ) {
			return;
		}

		$badge_settings = get_proofratings_overall_ratings_narrow();
		if ($badge_settings->float !== 'yes') {
			return;
		}

		$on_pages = (array) @$badge_settings->pages;
		$has_page = !isset($badge_settings->pages[get_the_ID()]) || $badge_settings->pages[get_the_ID()] == 'yes'? true : false;

		if ($has_page ) {
			echo do_shortcode('[proofratings_overall_ratings type="narrow" float="yes"]' );
			echo do_shortcode('[proofratings_badges_popup]' );
		}
	}

	/**
	 * Banner badge on frontend
	 */
	public function banner_badge() {
		if ( get_proofratings_display_settings()['overall_ratings_cta_banner'] !== 'yes' ) {
			return;
		}

		$badge_settings = get_proofratings_overall_ratings_cta_banner();
		$on_pages = (array) @$badge_settings->pages;
		$is_targeted_page = !isset($badge_settings->pages[get_the_ID()]) || $badge_settings->pages[get_the_ID()] == 'yes'? true : false;

		
		if (!$is_targeted_page ) {
			return;
		}
		
		echo do_shortcode('[proofratings_overall_ratings_cta_banner]' );
	}
}
