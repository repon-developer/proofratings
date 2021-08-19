<?php
/**
 * File containing the class WP_Proof_Ratings_Admin.
 *
 * @package proof-ratings
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
class WP_Proof_Ratings_Shortcodes {

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
        add_shortcode('proof_ratings_floating_badge', [$this, 'floating_badge']);
        add_shortcode('proof_ratings_widgets', [$this, 'proof_ratings_widgets']);
	}

	/**
	 * floating badge shortcode
	 */
	public function floating_badge($atts, $content = null) {
        $atts = shortcode_atts([
            'url' => ''
        ], $atts, 'proof_ratings_floating_badge');


        $review_sites = [];

        foreach (get_proof_ratings_settings() as $key => $site) {
            if ($site['active'] == 'yes') {
                $review_sites[$key] = $site;
            }
        }

        if ( empty($review_sites) ) {
            return;
        }

        ob_start();

        echo '<div class="proof-ratings-floating-badge">';
			echo '<div class="proof-ratings-inner">';
		        echo '<div class="proof-ratings-logos">';
		        foreach ($review_sites as $key => $site) {
		            printf('<img src="%1$s/assets/images/icon-%2$s.webp" alt="%2$s" >', PROOF_RATINGS_PLUGIN_URL, $key);
		        }
				echo '</div>';

		        echo '<div class="proof-ratings-reviews">';
		            echo '<span class="proof-ratings-score">4.9</span>';
		            echo '<span class="proof-ratings-stars"><i></i></span>';        
		        echo '</div>';
	        echo '</div>';

        	printf('<div class="proof-ratings-review-count">%d %s</div>', 225, __('reviews', 'proof-ratings'));
        echo '</div>';
        return ob_get_clean();

	}

	/**
	 * floating badge shortcode
	 */
	public function proof_ratings_widgets($atts, $content = null) {
		$atts = shortcode_atts([
            'id' => 'proof_ratings_widgets'
        ], $atts);

        $review_sites = [];

        foreach (get_proof_ratings_settings() as $key => $site) {
            if ($site['active'] == 'yes') {
                $review_sites[$key] = $site;
            }
        }

        if ( empty($review_sites) ) {
            return;
        }

		$logos = get_review_sites_logos();

        ob_start();

        printf('<div id="%s" class="proof-ratings-review-widgets-grid">', $atts['id']);
	        foreach ($review_sites as $key => $site) {
				printf('<div class="proof-ratings-widget proof-ratings-widget-%s">', $key);
	            	printf('<div class="review-site-logo"><img src="%1$s" alt="%2$s" ></div>', $logos[$key]['logo'], $logos[$key]['alt']);
				
					echo '<div class="proof-ratings-reviews">';
						echo '<span class="proof-ratings-score">4.9</span>';
						echo '<span class="proof-ratings-stars"><i></i></span>';        
			        echo '</div>';

					printf('<div class="review-count"> %d %s </div>', 34, __('reviews', 'proof-ratings'));

					echo '<p class="view-reviews">' . __('View Reviews', 'proof-ratings') . '</p>';

				echo '</div>';
	        }

        echo '</div>';
        return ob_get_clean();

	}
}
