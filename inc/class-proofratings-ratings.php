<?php
/**
 * File containing the class Proofratings_Ratings.
 *
 * @package proofratings
 * @since   1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Proofratings_Ratings
 * @since 1.0.6
 */
class Proofratings_Ratings {
    /**
     * Rating sites
     * @since 1.0.6
     */
    var $review_sites = [];

    /**
     * Has ratings
     * @since 1.0.6
     */
    var $has_ratings = false;

    /**
     * Total ratings
     * @since 1.0.6
     */
    var $reviews = 0;

    /**
     * Average ratings
     * @since 1.0.6
     */
    var $rating = 0.0;

    /**
     * Percentage of ratings
     * @since 1.0.6
     */
    var $percent = 0;

	/**
	 * Constructor.
	 */
	public function __construct($review_sites, $connections = []) {
        if ( !is_array($review_sites) ) {
            $review_sites = [];
        }

        $total_reviews = array_sum(array_column($review_sites, 'reviews'));

        $has_reviews = array_filter($review_sites, function($item) {
            return $item['reviews'] > 0;
        });
        
        $total_score = 0.0;
        if (count($has_reviews) > 0) {
            $total_score = array_sum(wp_list_pluck($review_sites, 'rating')) / count($has_reviews);
        }

        $total_score = number_format(floor($total_score*100)/100, 1);

        foreach ($connections as $site_key => $current) {
            $current = array_merge($current, array('reviews' => 0, 'rating' => 0, 'percent' => 0));
            if ( isset($review_sites[$site_key]) ) {
                $current = array_merge($current, $review_sites[$site_key]);
            }

            $current['percent'] = $current['rating'] * 20;
            $this->review_sites[$site_key] = $current;
        }

        if ( sizeof($review_sites) > 0 ) {
            $this->has_ratings = true;
        }
        
        $this->reviews = $total_reviews;
        $this->rating = $total_score;
        $this->percent = $total_score * 20;
	}

	public function get_logos() {
        if ( !$this->has_ratings ) {
            return;
        }

        echo '<div class="proofratings-logos">';
        foreach ($this->review_sites as $key => $site) {
            printf('<img src="%1$s" alt="%2$s" >', esc_attr($site->icon), $key);
        }
        echo '</div>';
	}
}
