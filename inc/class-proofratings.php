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
		include_once PROOFRATINGS_PLUGIN_DIR . '/inc/class-proofratings-generate-style.php';
		include_once PROOFRATINGS_PLUGIN_DIR . '/inc/class-proofratings-ratings.php';
		include_once PROOFRATINGS_PLUGIN_DIR . '/inc/class-proofratings-locations-query.php';
		include_once PROOFRATINGS_PLUGIN_DIR . '/inc/class-proofratings-ajax.php';
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

		add_action( 'wp_footer', [ $this, 'overall_ratings_float' ] );
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

		register_rest_route( 'proofratings/v1', 'save_location_settings', array(
			'methods' => 'POST',
			'callback' => [$this, 'save_location_settings'],
			'permission_callback' => '__return_true'
		));

		register_rest_route( 'proofratings/v1', 'get_location_settings', array(
			'methods' => 'GET',
			'callback' => [$this, 'get_location_settings'],
			'permission_callback' => '__return_true'
		));
	}

	/**
	 * proofratings rest api callback
	 */
	public function set_reviews(WP_REST_Request $request) {
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
			
			$sql = $wpdb->prepare("SELECT * FROM $wpdb->proofratings WHERE location_id = '%d'", $id);
			if ( $get_location = $wpdb->get_row($sql) ) {

				$settings = maybe_unserialize( $get_location->settings );
				if ( is_array($settings) && isset($location['schema']) ) {
					$settings['schema'] = $location['schema'];
				}

				$location_data['settings'] = maybe_serialize($settings);

				$wpdb->update($wpdb->proofratings, $location_data, ['id' => $get_location->id]);
				continue;
			}

			$location_data['settings']['schema'] = maybe_serialize($location['schema']);
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

		$this->clear_cache();
	}

	/**
	 * Clear cache
	 * @since 1.1.2
	 */
	public function clear_cache() {
		if ( function_exists( 'wp_cache_flush' ) ) {
			wp_cache_flush();
		}

		// Super Cache Plugin - https://wordpress.org/plugins/wp-super-cache/
		if ( function_exists( 'wp_cache_clear_cache' ) ) {
			wp_cache_clear_cache( is_multisite() && is_plugin_active_for_network( PROOFRATINGS_PLUGIN_BASENAME ) ? get_current_blog_id() : 0 );
		}

		// W3 Total Cache Plugin - https://wordpress.org/plugins/w3-total-cache/
		if ( function_exists( 'w3tc_flush_all' ) ) {
			w3tc_flush_all();
		}
		
		// WP Fastest Cache Plugin - https://wordpress.org/plugins/wp-fastest-cache/
		if ( isset( $GLOBALS['wp_fastest_cache'] ) && method_exists( $GLOBALS['wp_fastest_cache'], 'deleteCache' ) ) {
			$GLOBALS['wp_fastest_cache']->deleteCache( true );
		}

		// Cache Enabler Plugin - https://wordpress.org/plugins/cache-enabler/
		if ( class_exists( 'Cache_Enabler' ) && method_exists( 'Cache_Enabler', 'clear_site_cache' ) ) {
			Cache_Enabler::clear_site_cache();
		}

		// SG Optimizer Plugin - https://wordpress.org/plugins/sg-cachepress/
		if ( class_exists( '\\SiteGround_Optimizer\\Supercacher\\Supercacher' ) && method_exists( '\\SiteGround_Optimizer\\Supercacher\\Supercacher', 'purge_cache' ) ) {
			\SiteGround_Optimizer\Supercacher\Supercacher::purge_cache();
		}

		// LiteSpeed Cache Plugin - https://wordpress.org/plugins/litespeed-cache/
		if ( class_exists( '\\LiteSpeed\\Purge' ) && method_exists( '\\LiteSpeed\\Purge', 'purge_all' ) ) {
			\LiteSpeed\Purge::purge_all( 'Purged by Proofratings' );
		}

		// Nginx Helper Plugin - https://wordpress.org/plugins/nginx-helper/
		global $nginx_purger;
		if ( is_a( $nginx_purger, 'Purger' ) && method_exists( $nginx_purger, 'purge_all' ) ) {
			$nginx_purger->purge_all();
		}

		// WP Rocket Plugin - https://wp-rocket.me/
		if ( function_exists( 'rocket_clean_domain' ) ) {
			rocket_clean_domain();
		}

		// NitroPack - https://wordpress.org/plugins/nitropack/
		if ( function_exists('nitropack_sdk_purge') ) {
			nitropack_sdk_purge(NULL, NULL, 'Manual purge of all pages');
		}
	}

	/**
	 * proofratings rest api callback
	 */
	public function get_location_settings(WP_REST_Request $request) {
		$this->clear_cache();
		$location = $this->locations->get_by_location($request->get_param('location_id'));
		if ( isset($location->settings) ) {
			return $location->settings;
		}

		return false;
	}

	/**
	 * proofratings rest api callback
	 */
	public function save_location_settings(WP_REST_Request $request) {
		$settings = $request->get_params();

		$location_id = $settings['location_id'];
		unset($settings['location_id']);
		return $this->locations->save_settings_by_location($location_id, $settings);
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
			`meta_data` LONGTEXT NULL, 
			`status` VARCHAR(20) NOT NULL DEFAULT 'pending', 
			`created_on` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`)
		);");

		$this->registration('activate');
	}

	/**
	 * Sign up 
	 * @since 1.0.6
	 */
	function registration($source = 'registration') {
		$request_url = add_query_arg(array(
			'name' => get_bloginfo( 'name' ),
			'email' => get_bloginfo( 'admin_email' ),
			'url' => get_site_url(),
			'source' => $source
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
			update_option('proofratings_status', $data->status );
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
	public function overall_ratings_float() {
		$locations = get_proofratings()->locations->items;

		foreach ($locations as $location) {
			$schema = $location->settings->schema;
			if ( !empty($schema)) {
				$schema = str_replace('{{ratingValue}}', $location->ratings->rating, $schema);
				$schema = str_replace('{{bestRating}}', 5, $schema);
				$schema = str_replace('{{ratingCount}}', $location->ratings->count, $schema);
			}

			$schema = json_decode($schema);
			if ( is_object($schema)) {
				echo '<script type="application/ld+json">' . stripslashes(json_encode($schema, JSON_PRETTY_PRINT)) . '</script>';
			}

			if( !isset($location->settings->badge_display['overall_rectangle_float']) || !$location->settings->badge_display['overall_rectangle_float'] ) {
				continue;
			}

			if ( isset($_COOKIE['proofratings_badge_overall_rectangle_float_' . $location->id] ) ) {
				continue;
			}

			$on_pages = [];
			if (isset($location->settings->overall_rectangle_float['on_pages'])) {
				$on_pages = $location->settings->overall_rectangle_float['on_pages'];
			}

			if ( in_array(get_the_ID(), $on_pages) ) {
				echo '<div>';
				echo do_shortcode(sprintf('[proofratings_overall_rectangle id="%s" float="yes"]', esc_attr($location->id) ));
				echo do_shortcode(sprintf('[proofratings_badges_popup id="%s"]', esc_attr($location->id)));
				echo '</div>';
			}
		}

		foreach ($locations as $location) {
			if( !isset($location->settings->badge_display['overall_narrow_float']) || !$location->settings->badge_display['overall_narrow_float'] ) {
				continue;
			}

			if ( isset($_COOKIE['proofratings_badge_overall_narrow_float_' . $location->id] ) ) {
				continue;
			}

			$on_pages = [];
			if (isset($location->settings->overall_narrow_float['on_pages'])) {
				$on_pages = $location->settings->overall_narrow_float['on_pages'];
			}

			if ( in_array(get_the_ID(), $on_pages) ) {
				echo '<div>';
				echo do_shortcode(sprintf('[proofratings_overall_narrow id="%s" float="yes"]', esc_attr($location->id) ));
				echo do_shortcode(sprintf('[proofratings_badges_popup id="%s"]', esc_attr($location->id)));
				echo '</div>';
			}
		}
	}

	/**
	 * Banner badge on frontend
	 */
	public function banner_badge() {
		$locations = get_proofratings()->locations->items;

		foreach ($locations as $location) {
			if( !isset($location->settings->badge_display['overall_cta_banner']) || !$location->settings->badge_display['overall_cta_banner'] ) {
				continue;
			}

			if ( isset($_COOKIE['proofratings_badge_overall_cta_banner_' . $location->id] ) ) {
				continue;
			}

			$on_pages = [];
			if (isset($location->settings->overall_cta_banner['on_pages'])) {
				$on_pages = $location->settings->overall_cta_banner['on_pages'];
			}

			if ( in_array(get_the_ID(), $on_pages) ) {
				echo do_shortcode(sprintf('[proofratings_overall_ratings_cta_banner id="%s"]', esc_attr($location->id) ));
			}
		}
	}
}
