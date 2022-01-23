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
    private $rating_sites = [];

    /**
     * Has ratings
     * @since 1.0.6
     */
    var $has_ratings = false;

    /**
     * Total ratings
     * @since 1.0.6
     */
    var $count = 0;

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
	public function __construct($ratings) {
        if ( !is_array($ratings) ) {
            $ratings = [];
        }

        $total_reviews = array_sum(array_column($ratings, 'count'));

        $has_reviews = array_filter($ratings, function($item) {
            return $item->count > 0;
        });
        
        $total_score = 0.0;
        if (count($has_reviews) > 0) {
            $total_score = array_sum(wp_list_pluck($ratings, 'rating')) / count($has_reviews);
        }

        $total_score = number_format(floor($total_score*100)/100, 1);

        $this->rating_sites = $ratings;

        if ( sizeof($ratings) > 0 ) {
            $this->has_ratings = true;
        }
        
        $this->count = $total_reviews;
        $this->rating = $total_score;
        $this->percent = $total_score * 20;
	}

	public function get_logos() {
        if ( !$this->has_ratings ) {
            return;
        }

        echo '<div class="proofratings-logos">';
        foreach ($this->rating_sites as $key => $site) {
            printf('<img src="%1$s" alt="%2$s" >', esc_attr($site->icon), $key);
        }
        echo '</div>';
	}
}
