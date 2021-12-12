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
	 * Constructor.
	 */
	public function __construct($ratings) {
        
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
