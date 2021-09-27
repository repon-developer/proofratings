<?php
/**
 * File containing the class Proofratings_Review.
 *
 * @package proofratings
 * @since   1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode class
 *
 * @since 1.0.0
 */
class Proofratings_Review {

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

    //get sites review
    var $sites = false;

    var $count = 0;
    var $rating = 0;
    var $percent = 0;


	/**
	 * Constructor.
	 */
	public function __construct() {
        $this->sites = $this->get_active_review_sites();
        $this->get_overall_reviews();
	}

	/**
	 * get active review sites
	 */
	private function get_active_review_sites() {
		$review_sites = [];

        foreach (get_proofratings_settings() as $key => $site) {
            if ($site->active == 'yes') {
                $review_sites[$key] = $site;
            }
        }
		
        if ( empty($review_sites) ) {
			return false;
        }
		
		$proofratings_reviews = get_option( 'proofratings_reviews' );
		if ( !$proofratings_reviews ) {
			return false;
		}

		$proofratings_reviews = (array) $proofratings_reviews;

		array_walk($review_sites, function(&$item, $key) use($proofratings_reviews) {
			$site_rating = isset($proofratings_reviews[$key]) ? $proofratings_reviews[$key] : [];

			$item = new Proofratings_Site_Data(wp_parse_args( $item, wp_parse_args( $site_rating , ['rating' => 0, 'count' => 0, 'percent' => 0, 'review_url' => ''])));
		});

		return $review_sites;
	}

	/**
	 * floating badge shortcode
	 */
	public function get_overall_reviews() {
        if ( !$this->sites ) {
            return false;
        }

		$total_reviews = array_sum(array_column($this->sites, 'count'));
		$has_reviews = array_filter($this->sites, function($item) {
			return $item->count > 0;
		});
		
		$total_score = 0.0;
		if (count($has_reviews) > 0) {
			$total_score = array_sum(wp_list_pluck($this->sites, 'rating')) / count($has_reviews);
		}

		$total_score = number_format(floor($total_score*100)/100, 1);

        $this->count = $total_reviews;
        $this->rating = $total_score;
        $this->percent = $total_score * 20;
	}


	public function get_review_logos() {
        if ( !$this->sites ) {
            return;
        }

        echo '<div class="proofratings-logos">';
        foreach ($this->sites as $key => $site) {
            printf('<img src="%1$s" alt="%2$s" >', esc_attr($site->icon), $key);
        }
        echo '</div>';
	}

    public function get_rating_star($class = '') {
        printf( '<span class="proofratings-stars %s"><i style="width: %s%%"></i></span>', $class, $this->percent);
        echo '<meta itemprop="worstRating" content = "1">';
        echo '<meta itemprop="ratingValue" content="'.$this->rating.'">';
        echo '<meta itemprop="bestRating" content="5">';
    }

}
