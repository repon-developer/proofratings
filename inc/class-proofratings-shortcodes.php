<?php
/**
 * File containing the class ProofRatings_Shortcodes.
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
class ProofRatings_Shortcodes {

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
        add_shortcode('proofratings_floating_badge', [$this, 'floating_badge']);
        add_shortcode('proofratings_widgets', [$this, 'proofratings_widgets']);
	}

	/**
	 * get active review sites
	 */
	private function get_active_review_sites() {
		$review_sites = [];

        foreach (get_proofratings_settings() as $key => $site) {
            if ($site['active'] == 'yes') {
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

		array_walk($review_sites, function(&$item, $key) use($proofratings_reviews) {
			$site_rating = isset($proofratings_reviews->{$key}) ? $proofratings_reviews->{$key} : [];
			$item = wp_parse_args( $item, wp_parse_args( $site_rating , ['rating' => 0, 'count' => 0, 'percent' => 0, 'review_url' => '']));
		});

		return $review_sites;
	}

	/**
	 * floating badge shortcode
	 */
	public function floating_badge($atts, $content = null) {
        $atts = shortcode_atts([
            'url' => '#proofratings_widgets'
        ], $atts, 'proofratings_floating_badge');


        $review_sites = $this->get_active_review_sites();
        if ( !$review_sites ) {
            return;
        }

		$total_reviews = array_sum(array_column($review_sites, 'count'));
		$has_reviews = array_filter($review_sites, function($item) {
			return $item['count'] > 0;
		});
		
		$total_score = array_sum(array_column($review_sites, 'rating')) / count($has_reviews);

		$total_score = floor($total_score*100)/100;

		$classes = ['proofratings-floating-badge'];

		$badget_settings = get_option( 'proofratings_floating_badge_settings');

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
			echo '<div class="proofratings-inner">';
		        echo '<div class="proofratings-logos">';
		        foreach ($review_sites as $key => $site) {
		            printf('<img src="%1$s/assets/images/icon-%2$s.webp" alt="%2$s" >', PROOFRATINGS_PLUGIN_URL, $key);
		        }
				echo '</div>';

		        echo '<div class="proofratings-reviews">';
		            printf('<span class="proofratings-score">%s</span>', $total_score);
		            printf( '<span class="proofratings-stars"><i style="width: %s%%"></i></span>', $total_score * 20);
		        echo '</div>';
	        echo '</div>';

        	printf('<div class="proofratings-review-count">%d %s</div>', $total_reviews, __('reviews', 'proofratings'));
        printf('</%s>', $tag);
        return ob_get_clean();

	}

	/**
	 * floating badge shortcode
	 */
	public function proofratings_widgets($atts, $content = null) {
		$atts = shortcode_atts([
            'id' => 'proofratings_widgets'
        ], $atts);

		$review_sites = $this->get_active_review_sites();
        if ( !$review_sites ) {
            return;
        }

		$logos = get_review_sites_logos();

        ob_start();
		
        printf('<div id="%s" class="proofratings-review-widgets-grid">', $atts['id']);
	        foreach ($review_sites as $key => $site) {
				$tag = 'div';
				$attribue= '';
				
				if( !empty($site['review_url']) ) {
					$tag = 'a';
					$attribue = sprintf('href="%s" target="_blank"', $site['review_url']);
				}
				
				printf('<%s class="proofratings-widget proofratings-widget-%s" %s>', $tag, $key, $attribue);
	            	printf('<div class="review-site-logo"><img src="%1$s" alt="%2$s" ></div>', $logos[$key]['logo'], $logos[$key]['alt']);
				
					echo '<div class="proofratings-reviews">';
						printf('<span class="proofratings-score">%s</span>', number_format($site['rating'], 1));
						printf('<span class="proofratings-stars"><i style="width: %s%%"></i></span>', $site['rating'] * 20);
			        echo '</div>';

					printf('<div class="review-count"> %d %s </div>', $site['count'], __('reviews', 'proofratings'));

					echo '<p class="view-reviews">' . __('View Reviews', 'proofratings') . '</p>';

				printf('</%s>', $tag);
	        }

        echo '</div>';
        return ob_get_clean();

	}
}