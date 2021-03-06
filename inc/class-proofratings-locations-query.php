<?php
/**
 * File containing the class Proofratings_Locations.
 *
 * @package proofratings
 * @since   1.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get locations
 * @since 1.0.6
 */
class Proofratings_Locations  {
	/**
	 * Is global
	 * @since  1.0.6
	 */
	var $global = true;

	/**
	 * The single instance of the class.
	 * @var self
	 * @since  1.0.6
	 */
	private static $instance = null;

	/**
	 * Main Proofratings_Locations
	 * @since  1.0.6
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Items
	 * @var self
	 * @since  1.1.0
	 */
	var $items = [];


	/**
	 * Constructor.
	 * @since  1.0.6
	 */
	public function __construct() {
        $this->get_locations();
		$this->total = sizeof($this->items);
	}


	/**
	 * save location
	 * @since  1.0.6
	 */
	function save_settings($id, $data) {
		if ( $id === 'overall' ) {
			return update_option('proofratings_overall_rating_settings', $data);
		}

		global $wpdb;
		$result = $wpdb->update($wpdb->proofratings, ['settings' => maybe_serialize( $data )], ['id' => $id]);
		do_action( 'proofrating_location_save_settings' );
		return $result;
	}

	/**
	 * save location
	 * @since  1.0.6
	 */
	function save_settings_by_location($location_id, $settings) {		
		$key = array_search($location_id, array_column($this->items, 'location_id'));
		if ( $key !== false) {
			return $this->save_settings($this->items[$key]->id, $settings);
		}

		return array('success' => false);		
	}

	/**
	 * get location
	 * @since  1.0.6
	 */
	function sanitize_location($location) {
		$reviews = maybe_unserialize( $location->reviews );
		if ( !is_array($reviews) ) {
			$reviews = [];
		}

		$location->reviews = $reviews;

		$settings = maybe_unserialize( $location->settings );
		if ( !is_array($settings) ) {
			$settings = [];
		}

		$settings = $location->settings = new Proofratings_Site_Data($this->sanitize_boolean_data($settings));

		$location->connected = 0;
		if ( is_array($settings->activeSites) ) {
			$location->connected = sizeof($settings->activeSites);
		}

		$location->widgets = 0;
		if ( is_array($settings->badge_display) ) {
			$location->widgets = sizeof(array_filter($settings->badge_display));
		}

		return $location;
	}

	/**
	 * Sanitize boolean data
	 * @since  1.0.6
	 */
	function sanitize_boolean_data($string) {
		if (is_array($string)) {
			foreach ($string as $k => $v) {
				$string[$k] = $this->sanitize_boolean_data($v); 
			}

			return $string;
		}

		if ( $string === 'true' ) {
			return true;
		}

		if ( $string === 'false' ) {
			return false;
		}
		
		return $string;
	}

	/**
	 * get overall location
	 * @since  1.0.6
	 */
	function overall_location($locations) {
		$status_text = __('~', 'proofratings');
		if ( sizeof(array_unique(wp_list_pluck( $locations, 'status'))) == 1 ) {
			$status_text = $locations[0]->status;
		}

		$sites_reviews = [];
		foreach ($locations as $location) {
			if (empty($location->reviews)) {
				continue;
			}

			foreach ($location->reviews as $key => $rating) {
				$sites_reviews[$key][] = $rating;
			}			
		}

		$site_overall_review = [];		
		foreach ($sites_reviews as $key => $reviews) {			
			$count = sizeof($reviews);
			if ($count == 0) {
				$count = 1;
			}
			
			$rating = floatval(array_sum(wp_list_pluck( $reviews, 'rating')) / $count);
			$site_overall_review[$key] = array(
				'rating' => $rating,
				'percent' => $rating * 20,
				'count' => array_sum(wp_list_pluck( $reviews, 'count')),
			);
		}

		return $this->sanitize_location((object) array(
			'id' => 'overall',
			'location_id' => 'overall',
			'location' => __('ALL LOCATIONS (OVERALL)', 'proofratings'),
			'settings' => get_option('proofratings_overall_rating_settings'),
			'reviews' => $site_overall_review,
			'status' => $status_text
		));
	}
	
	/**
     * Get locations
     * @since 1.0.6
     */
	function get_locations() {
		global $wpdb;

		$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $wpdb->proofratings ) );
		if ( ! $wpdb->get_var( $query ) == $wpdb->proofratings ) {
			return [];
		}

		$locations = $wpdb->get_results("SELECT * FROM $wpdb->proofratings WHERE status != 'deleted' ORDER BY location ASC");

		array_walk($locations, function(&$location){
			$location = $this->sanitize_location($location);
		});

		$location_ids = wp_list_pluck( $locations, 'location_id');

		if ( !in_array('global', $location_ids) ) {
			$this->global = false;

			if ( sizeof($locations) >= 1) {
				array_unshift($locations, $this->overall_location($locations));
			}
		}

		$rating_sites = get_proofratings_rating_sites();

		foreach ($locations as $key => $location) {
			$active_sites = [];
			if ( isset($location->settings->activeSites) && is_array($location->settings->activeSites)) {
				$active_sites = $location->settings->activeSites;
			}

			foreach ($location->reviews as $id => $rating) {
				if ( !in_array($id, $active_sites) ) {
					unset($location->reviews[$id]);
				}
			}
	
			while ($site_id = current($active_sites)) {
				next($active_sites);
				if ( !isset($location->reviews[$site_id])) {
					$location->reviews[$site_id] = array('rating' => 0, 'count' => 0, 'percent' => 0);
				}			
			}

			array_walk($location->reviews, function(&$rating, $key) use($rating_sites) {
				$data = isset($rating_sites[$key]) && is_array($rating_sites[$key]) ? $rating_sites[$key] : [];
				$rating = new Proofratings_Site_Data(array_merge($data, $rating));
			});

			$location->has_ratings = false;
			if ( sizeof($location->reviews) > 0 ) {
				$location->has_ratings = true;
			}

			$location->ratings = new Proofratings_Ratings($location->reviews);
		}

		return $this->items = $locations;
	}

	/**
	 * get global location id
	 * @since  1.0.6
	 */
	function get_global_id() {
		$key = array_search('global', array_column($this->items, 'location_id'));
		return $key === false ? false : $this->items[$key]->id;
	}

	/**
	 * get single location
	 * @since  1.0.6
	 */
	function get($id) {
		$key = array_search($id, array_column($this->items, 'id'));
		return $key === false ? false : $this->items[$key];
	}

	/**
	 * get single location by location id
	 * @since  1.0.6
	 */
	function get_by_location($location_id) {
		$key = array_search($location_id, array_column($this->items, 'location_id'));
		return $key === false ? false : $this->items[$key];
	}
}