<?php
/**
 * File containing the class Proof_Ratings_Shortcodes.
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
class Proof_Ratings_Shortcodes {

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
	 * get active review sites
	 */
	private function get_active_review_sites() {
		$review_sites = [];

        foreach (get_proof_ratings_settings() as $key => $site) {
            if ($site['active'] == 'yes') {
                $review_sites[$key] = $site;
            }
        }

        if ( empty($review_sites) ) {
            return false;
        }

		$xlsx = SimpleXLSX::parse( PROOF_RATINGS_PLUGIN_DIR . '/inc/reviews.xlsx');

		$rows = $xlsx->rows();
		unset($rows[0]);

		foreach ($rows as $site_rating) {
			$key = strtolower($site_rating[0]);
			if ( !isset($review_sites[$key])) {
				continue;
			}

			$review_sites[$key] = array_merge($review_sites[$key], [
				'rating' => $site_rating[1],
				'count' => $site_rating[2],
				'percent' => $site_rating[1] * 20
			]);
		}

		return $review_sites;
	}

	/**
	 * floating badge shortcode
	 */
	public function floating_badge($atts, $content = null) {
        $atts = shortcode_atts([
            'url' => '#proof_ratings_widgets'
        ], $atts, 'proof_ratings_floating_badge');


        $review_sites = $this->get_active_review_sites();
        if ( !$review_sites ) {
            return;
        }

		$total_reviews = array_sum(array_column($review_sites, 'count'));
		
		$total_score = array_sum(array_column($review_sites, 'rating')) / count($review_sites);

		$total_score = floor($total_score*100)/100;



		$classes = ['proof-ratings-floating-badge'];

		$badget_settings = get_option( 'proof_ratings_floating_badge_settings');

		if ( !empty($badget_settings['position']) ) {
			$classes[] = $badget_settings['position'];
		}




		$url_attribute = '';
		$tag = 'div';
		if (!empty($atts['url'])) {
			$tag = 'a';
			$url_attribute = sprintf('href="%s"', $atts['url']);
		}

        ob_start();
        printf('<%s %s class="%s">', $tag, $url_attribute, implode(' ', $classes));
			echo '<div class="proof-ratings-inner">';
		        echo '<div class="proof-ratings-logos">';
		        foreach ($review_sites as $key => $site) {
		            printf('<img src="%1$s/assets/images/icon-%2$s.webp" alt="%2$s" >', PROOF_RATINGS_PLUGIN_URL, $key);
		        }
				echo '</div>';

		        echo '<div class="proof-ratings-reviews">';
		            printf('<span class="proof-ratings-score">%s</span>', $total_score);
		            printf( '<span class="proof-ratings-stars"><i style="width: %s%%"></i></span>', $total_score * 20);
		        echo '</div>';
	        echo '</div>';

        	printf('<div class="proof-ratings-review-count">%d %s</div>', $total_reviews, __('reviews', 'proof-ratings'));
        printf('</%s>', $tag);
        return ob_get_clean();

	}

	/**
	 * floating badge shortcode
	 */
	public function proof_ratings_widgets($atts, $content = null) {
		$atts = shortcode_atts([
            'id' => 'proof_ratings_widgets'
        ], $atts);

		$review_sites = $this->get_active_review_sites();
        if ( !$review_sites ) {
            return;
        }

		$logos = get_review_sites_logos();

        ob_start();
		
        printf('<div id="%s" class="proof-ratings-review-widgets-grid">', $atts['id']);
	        foreach ($review_sites as $key => $site) {
				printf('<div class="proof-ratings-widget proof-ratings-widget-%s">', $key);
	            	printf('<div class="review-site-logo"><img src="%1$s" alt="%2$s" ></div>', $logos[$key]['logo'], $logos[$key]['alt']);
				
					echo '<div class="proof-ratings-reviews">';
						printf('<span class="proof-ratings-score">%s</span>', number_format($site['rating'], 1));
						printf('<span class="proof-ratings-stars"><i style="width: %s%%"></i></span>', $site['rating'] * 20);
			        echo '</div>';

					printf('<div class="review-count"> %d %s </div>', $site['count'], __('reviews', 'proof-ratings'));

					echo '<p class="view-reviews">' . __('View Reviews', 'proof-ratings') . '</p>';

				echo '</div>';
	        }

        echo '</div>';
        return ob_get_clean();

	}
}
