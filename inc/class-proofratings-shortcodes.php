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
        add_shortcode('proofratings_banner', [$this, 'banner_badge']);
        add_shortcode('proofratings_floating_widgets', [$this, 'proofratings_floating_widgets']);
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
	public function get_overall_reviews() {
		$review_sites = $this->get_active_review_sites();
        if ( !$review_sites ) {
            return false;
        }

		

		$total_reviews = array_sum(array_column($review_sites, 'count'));
		$has_reviews = array_filter($review_sites, function($item) {
			return $item['count'] > 0;
		});
		
		$total_score = 0.0;
		if (count($has_reviews) > 0) {
			$total_score = array_sum(array_column($review_sites, 'rating')) / count($has_reviews);
		}

		$total_score = number_format(floor($total_score*100)/100, 1);

		return ['sites' => $review_sites, 'count' => $total_reviews, 'rating' => $total_score, 'percent' => $total_score * 20];
	}

	/**
	 * floating badge shortcode
	 */
	public function floating_badge($atts, $content = null) {
        $atts = shortcode_atts([
			'mobile' => 'yes',
			'tablet' => 'yes',
            //'url' => '#proofratings_widgets'
        ], $atts, 'proofratings_floating_badge');

        $review_sites = $this->get_active_review_sites();
        if ( !$review_sites ) {
            return;
        }

		$total_reviews = array_sum(array_column($review_sites, 'count'));
		$has_reviews = array_filter($review_sites, function($item) {
			return $item['count'] > 0;
		});
		
		$total_score = 0.0;
		if (count($has_reviews) > 0) {
			$total_score = array_sum(array_column($review_sites, 'rating')) / count($has_reviews);
		}

		$total_score = number_format(floor($total_score*100)/100, 1);

		$classes = ['proofratings-badge', 'proofratings-floating-badge'];

		$badget_settings = get_option( 'proofratings_floating_badge_settings');

		if ( !empty($badget_settings['position']) ) {
			$classes[] = $badget_settings['position'];
		}

		if ( $atts['mobile'] == 'no') {
			$classes[] = 'proofratings-floating-badge-hidden-mobile';
		}

		if ( $atts['tablet'] == 'no') {
			$classes[] = 'proofratings-floating-badge-hidden-tablet';
		}

		$url_attribute = '';
		$tag = 'div';
		if (!empty($atts['url'])) {
			$tag = 'a';
			$url_attribute = sprintf('href="%s"', esc_url($atts['url']));
		}


        ob_start();
        printf('<%s %s class="%s" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">', $tag, $url_attribute, implode(' ', $classes));
			echo  '<i class="proofratings-close">&times;</i>';
			echo '<div class="proofratings-inner">';
		        echo '<div class="proofratings-logos">';
		        foreach ($review_sites as $key => $site) {
		            printf('<img src="%1$s" alt="%2$s" >', esc_attr($site['icon']), $key);
		        }
				echo '</div>';

		        echo '<div class="proofratings-reviews" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">';
		            printf('<span class="proofratings-score">%s</span>', $total_score);
		            printf( '<span class="proofratings-stars"><i style="width: %s%%"></i></span>', $total_score * 20);

					echo '<meta itemprop="worstRating" content = "1">';
					echo '<meta itemprop="ratingValue" content="'.$total_score.'">';
					echo '<meta itemprop="bestRating" content="5">';
		        echo '</div>';
	        echo '</div>';

        	printf('<div class="proofratings-review-count">%d %s</div>', $total_reviews, __('reviews', 'proofratings'));
        printf('</%s>', $tag);
        return ob_get_clean();
	}

	/**
	 * banner badge shortcode
	 */
	public function banner_badge($atts, $content = null) {
        $badge_settings = shortcode_atts([
			'url' => '#proofratings_widgets'
        ], $atts, 'proofratings_banner_badge');

		$badge_settings = wp_parse_args( $badge_settings, get_option( 'proofratings_banner_badge_settings'));

        $review_data = $this->get_overall_reviews();
        if ( !$review_data ) {
			return;
        }

		$close_button = '';
		
		$classes = ['proofratings-badge proofratings-banner-badge'];
		if ( $badge_settings['type'] == 'float') {
			$badge_settings['url'] = '';

			$classes[] = 'badge-float';
			if ( !empty($badge_settings['position'])) {
				$classes[] = $badge_settings['position'];
			}
			
			if ( @$badge_settings['mobile'] == 'no') {
				$classes[] = 'badge-hidden-mobile';
			}
			
			if ( @$badge_settings['tablet'] == 'no') {
				$classes[] = 'badge-hidden-tablet';
			}

			if ( $badge_settings['close_button'] == 'yes' ) {
				$close_button = '<i class="proofratings-close">&times;</i>';
			}
		}

			
		$url_attribute = '';
		$tag = 'div';
		if (!empty($badge_settings['url'])) {
			$tag = 'a';
			$url_attribute = sprintf('href="%s"', esc_url($badge_settings['url']));
		}
		
        ob_start();
        printf('<%s %s class="%s" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">', $tag, $url_attribute, implode(' ', $classes));

			echo $close_button;
			
	        echo '<div class="proofratings-logos">';
	        foreach ($review_data['sites'] as $key => $site) {
	            printf('<img src="%1$s" alt="%2$s" >', esc_attr($site['icon']), $key);
	        }
			echo '</div>';

	        echo '<div class="proofratings-reviews" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">';
	            printf('<span class="proofratings-score">%s</span>', $review_data['rating']);
	            printf( '<span class="proofratings-stars"><i style="width: %s%%"></i></span>', $review_data['percent']);

				echo '<meta itemprop="worstRating" content = "1">';
				echo '<meta itemprop="ratingValue" content="'.$review_data['rating'].'">';
				echo '<meta itemprop="bestRating" content="5">';
	        echo '</div>';

        	printf('<div class="proofratings-review-count">%d %s</div>', $review_data['count'], __('reviews', 'proofratings'));
        printf('</%s>', $tag);

		echo do_shortcode('[proofratings_floating_widgets]' );

        return ob_get_clean();

	}

	/**
	 * Floating widgets shortcode
	 */
	public function proofratings_floating_widgets($atts, $content = null) {
		$review_sites = $this->get_active_review_sites();
        if ( !$review_sites ) {
            return;
        }

		$column = 4;

		if ( count($review_sites) == 5 ) {
			$column = 5;
		}

		if ( count($review_sites) < 4 ) {
			$column = count($review_sites);
		}

        ob_start(); 
		
        printf('<div id="proofratings-floating-embed">');
			printf ('<div class="proofratings-floating-widgets-box" data-column="%d">', $column);
	        foreach ($review_sites as $key => $site) {
				$tag = 'div';
				$attribue= '';
				
				if( !empty($site['review_url']) ) {
					$tag = 'a';
					$attribue = sprintf('href="%s" target="_blank"', esc_url($site['review_url']));
				}
				
				printf('<%s class="proofratings-widget proofratings-widget-%s" %s>', $tag, $key, $attribue);
	            	printf('<div class="review-site-logo"><img src="%1$s" alt="%2$s" ></div>', esc_attr($site['logo']), esc_attr($site['name']));
				
					echo '<div class="proofratings-reviews" itemprop="reviewRating">';
						printf('<span class="proofratings-score">%s</span>', number_format($site['rating'], 1));
						printf('<span class="proofratings-stars"><i style="width: %s%%"></i></span>', esc_attr($site['rating'] * 20));
			        echo '</div>';

					printf('<div class="review-count"> %d %s </div>', esc_html($site['count']), __('reviews', 'proofratings'));

					echo '<p class="view-reviews">' . __('View Reviews', 'proofratings') . '</p>';

				printf('</%s>', $tag);
	        }
			echo '</div>';

			echo '<span class="proofrating-close">-<span>';
        echo '</div>';
        return ob_get_clean();
	}

	/**
	 * embed badge shortcode
	 */
	public function proofratings_widgets($atts, $content = null) {
		$atts = shortcode_atts([
            'id' => 'proofratings_widgets'
        ], $atts);

		$review_sites = $this->get_active_review_sites();
        if ( !$review_sites ) {
            return;
        }

        ob_start(); 
		
        printf('<div id="%s" class="proofratings-review-widgets-grid">', esc_attr($atts['id']));
	        foreach ($review_sites as $key => $site) {
				$tag = 'div';
				$attribue= '';
				
				if( !empty($site['review_url']) ) {
					$tag = 'a';
					$attribue = sprintf('href="%s" target="_blank"', esc_url($site['review_url']));
				}
				
				printf('<%s class="proofratings-widget proofratings-widget-%s" %s>', $tag, $key, $attribue);
	            	printf('<div class="review-site-logo"><img src="%1$s" alt="%2$s" ></div>', esc_attr($site['logo']), esc_attr($site['name']));
				
					echo '<div class="proofratings-reviews" itemprop="reviewRating">';
						printf('<span class="proofratings-score">%s</span>', number_format($site['rating'], 1));
						printf('<span class="proofratings-stars"><i style="width: %s%%"></i></span>', esc_attr($site['rating'] * 20));
			        echo '</div>';

					printf('<div class="review-count"> %d %s </div>', esc_html($site['count']), __('reviews', 'proofratings'));

					echo '<p class="view-reviews">' . __('View Reviews', 'proofratings') . '</p>';

				printf('</%s>', $tag);
	        }

        echo '</div>';
        return ob_get_clean();
	}
}
