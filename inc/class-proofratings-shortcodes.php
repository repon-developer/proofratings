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
        add_shortcode('proofratings_overall_ratings', [$this, 'proofratings_overall_ratings']);
        
        add_shortcode('proofratings_floating_widgets', [$this, 'proofratings_floating_widgets']);
        add_shortcode('proofratings_widgets', [$this, 'proofratings_widgets']);
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
		$review_sites = $this->get_active_review_sites();
        if ( !$review_sites ) {
            return false;
        }

		$total_reviews = array_sum(array_column($review_sites, 'count'));
		$has_reviews = array_filter($review_sites, function($item) {
			return $item->count > 0;
		});
		
		$total_score = 0.0;
		if (count($has_reviews) > 0) {
			$total_score = array_sum(wp_list_pluck($review_sites, 'rating')) / count($has_reviews);
		}

		$total_score = number_format(floor($total_score*100)/100, 1);

		return ['sites' => $review_sites, 'count' => $total_reviews, 'rating' => $total_score, 'percent' => $total_score * 20];
	}

	/**
	 * floating badge shortcode
	 */
	public function proofratings_overall_ratings($atts, $content = null) {
        $atts = shortcode_atts([
			'float' => 'no',
			'type' => 'rectangle',
        ], $atts);
		
		$review_data = $this->get_overall_reviews();
        if ( !$review_data ) {
			return;
        }
				
		$classes = ['proofratings-badge', 'proofratings-badge-'.$atts['type']];

		$badget_settings = get_proofratings_overall_ratings_rectangle();
		if ( $atts['type'] == 'narrow') {
			$badget_settings = get_proofratings_overall_ratings_narrow();			
		}		

		if ( $atts['float'] == 'yes' ) {
			array_push($classes, 'badge-float');

			if ( !empty($badget_settings['position']) ) {
				$classes[] = $badget_settings['position'];
			}

			if ( $badget_settings['mobile'] == 'no') {
				$classes[] = 'badge-hidden-mobile';
			}

			if ( $badget_settings['tablet'] == 'no') {
				$classes[] = 'badge-hidden-tablet';
			}

			$badget_settings['shadow'] = 'yes';
		}

		$url_attribute = '';
		$tag = 'div';
		if (!empty($atts['url'])) {
			$tag = 'a';
			$url_attribute = sprintf('href="%s"', esc_url($atts['url']));
		}

		
		$styles = [];
		$supported_keys = ['star_color', 'shadow_color', 'shadow_hover', 'background_color', 'review_text_color', 'review_background'];

		if ( $badget_settings['shadow'] == 'no') {
			$badget_settings['shadow_color'] = $badget_settings['shadow_hover'] = 'transparent';
		}

		array_walk($supported_keys, function($key) use (&$styles, $badget_settings) {
			if ( $badget_settings['customize'] != 'yes') {
				return;
			}

			if ( !empty($badget_settings[$key])) {
				$styles[$key] = $badget_settings[$key];
			}
		});

		$styles = array_map(function($item, $key){
			return "--$key:$item";
		}, $styles, array_keys($styles));

        ob_start();
        printf('<%s %s class="%s" style="%s" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">', $tag, $url_attribute, implode(' ', $classes), implode(';', $styles));
			if ( @$badget_settings['close_button'] != 'no' && $atts['float'] == 'yes' ) {
				echo  '<i class="proofratings-close">&times;</i>';
			}

			if($atts['type'] == 'narrow') {
				$this->overall_ratings_narrow($review_data);
			} else {				
				$this->overall_ratings_rectangle($review_data);
			}
			
        printf('</%s>', $tag);
        return ob_get_clean();
	}

	private function overall_ratings_rectangle($review_data) {
		echo '<div class="proofratings-inner">';
			echo '<div class="proofratings-logos">';
			foreach ($review_data['sites'] as $key => $site) {
				printf('<img src="%1$s" alt="%2$s" >', esc_attr($site->icon), $key);
			}
			echo '</div>';

			echo '<div class="proofratings-reviews" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">';
				printf('<span class="proofratings-score">%s</span>', $review_data['rating']);
				printf( '<span class="proofratings-stars"><i style="width: %s%%"></i></span>', $review_data['percent']);

				echo '<meta itemprop="worstRating" content = "1">';
				echo '<meta itemprop="ratingValue" content="'.$review_data['rating'].'">';
				echo '<meta itemprop="bestRating" content="5">';
			echo '</div>';
		echo '</div>';

		printf('<div class="proofratings-review-count">%d %s</div>', $review_data['count'], __('reviews', 'proofratings'));
	}

	private function overall_ratings_narrow($review_data) {
		echo '<div class="proofratings-logos">';
        foreach ($review_data['sites'] as $key => $site) {
            printf('<img src="%1$s" alt="%2$s" >', esc_attr($site->icon), $key);
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
				
				if( !empty($site->review_url) ) {
					$tag = 'a';
					$attribue = sprintf('href="%s" target="_blank"', esc_url($site->review_url));
				}
				
				printf('<%s class="proofratings-widget proofratings-widget-%s" %s>', $tag, $key, $attribue);
	            	printf('<div class="review-site-logo"><img src="%1$s" alt="%2$s" ></div>', esc_attr($site->logo), esc_attr($site->name));
				
					echo '<div class="proofratings-reviews" itemprop="reviewRating">';
						printf('<span class="proofratings-score">%s</span>', number_format($site->rating, 1));
						printf('<span class="proofratings-stars"><i style="width: %s%%"></i></span>', esc_attr($site->percent));
			        echo '</div>';

					printf('<div class="review-count"> %d %s </div>', esc_html($site->count), __('reviews', 'proofratings'));

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
			'style' => 'square',
            'id' => 'proofratings_widgets'
        ], $atts);

		
		$review_sites = $this->get_active_review_sites();
        if ( !$review_sites ) {
			return;
        }

		$badge_class = ['proofratings-widget'];

		$badges_sites_square = get_proofratings_badges_square();
		if ( $badges_sites_square['customize'] == 'yes' ) {
			$badge_class[] = 'proofratings-widget-customized';
		}


		$badge_style = sanitize_key($atts['style']);
		if ( empty($badge_style) || !method_exists($this, 'proofratings_widgets_' . $badge_style)) {
			$badge_style = 'square';
		}

        ob_start();		
        printf('<div id="%s" class="proofratings-review-widgets-grid proofratings-widgets-grid-%s">', esc_attr($atts['id']), $badge_style);
	        foreach ($review_sites as $key => $site) {
				$tag = 'div';
				$attribue = '';
				
				if( !empty($site->review_url) ) {
					$tag = 'a';
					$attribue = sprintf('href="%s" target="_blank"', esc_url($site->review_url));
				}

				$badge_class[] = 'proofratings-widget-' . $badge_style;
				$badge_class[] = 'proofratings-widget-' . $key;
				
				printf('<%s class="%s" %s>', $tag, implode(' ', $badge_class), $attribue);
					$this->{'proofratings_widgets_' . $badge_style}($site);
				printf('</%s>', $tag);
	        }

        echo '</div>';
        return ob_get_clean();
	}

	/**
	 * Embed badge sites square
	 */
	public function proofratings_widgets_square($site) {			
    	printf('<div class="review-site-logo"><img src="%1$s" alt="%2$s" ></div>', esc_attr($site->logo), esc_attr($site->name));
	
		echo '<div class="proofratings-reviews" itemprop="reviewRating">';
			printf('<span class="proofratings-score">%s</span>', number_format($site->rating, 1));
			printf('<span class="proofratings-stars"><i style="width: %s%%"></i></span>', esc_attr($site->percent));
        echo '</div>';

		printf('<div class="review-count"> %d %s </div>', esc_html($site->count), __('reviews', 'proofratings'));

		echo '<p class="view-reviews">' . __('View Reviews', 'proofratings') . '</p>';
	}

	/**
	 * Embed badge style2
	 */
	public function proofratings_widgets_rectangle($site) {		
    	printf('<div class="review-site-logo"><img src="%1$s" alt="%2$s" ></div>', esc_attr($site->icon2), esc_attr($site->rating_title));

		if ( $site->rating_title ) {
			echo '<h4 class="rating-title">'.$site->rating_title.'</h4>';
		}

	
		echo '<div class="proofratings-reviews" itemprop="reviewRating">';
			printf('<span class="proofratings-score">%s</span>', number_format($site->rating, 1));
			printf('<span class="proofratings-stars"><i style="width: %s%%"></i></span>', esc_attr($site->percent));
        echo '</div>';

		printf('<div class="review-count"> %d %s </div>', esc_html($site->count), __('reviews', 'proofratings'));
	}
}
