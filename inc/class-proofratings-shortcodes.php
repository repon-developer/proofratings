<?php
/**
 * File containing the class Proofratings_Shortcodes.
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
class Proofratings_Shortcodes {

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
        add_shortcode('proofratings_widgets', [$this, 'proofratings_widgets']);
		add_shortcode('proofratings_badges_popup', [$this, 'proofratings_badges_popup']);

        add_shortcode('proofratings_overall_rectangle', [$this, 'overall_rectangle']);
        add_shortcode('proofratings_overall_narrow', [$this, 'overall_narrow']);

        add_shortcode('proofratings_overall_ratings_cta_banner', [$this, 'overall_ratings_cta_banner']);
	}

	public function overall_rectangle($atts, $content = null) {
		$atts = shortcode_atts(['id' => 'overall', 'float' => 'no', 'type' => 'rectangle'], $atts);
		return $this->proofratings_overall_ratings($atts, $content = null);
	}

	public function overall_narrow($atts, $content = null) {
		$atts = shortcode_atts(['id' => 'overall', 'float' => 'no', 'type' => 'narrow'], $atts);
		return $this->proofratings_overall_ratings($atts, $content = null);
	}

	/**
	 * floating badge shortcode
	 */
	public function proofratings_overall_ratings($atts, $content = null) {
        $atts = shortcode_atts([
			'id' => 'overall',
			'float' => 'no',
			'type' => 'rectangle',
        ], $atts);

		$type = sanitize_title( $atts['type']);
		if ( !in_array($type, array('rectangle', 'narrow')) ) {
			$type = 'rectangle';
		}

		$overall_slug = "overall_{$type}_embed";
		if ( $atts['float'] === 'yes' ) {
			$overall_slug = "overall_{$type}_float";
		}

		$location = get_proofratings()->locations->get($atts['id']);
		if ( !$location ) {
			return;
		}
		
		if ( !$location->has_ratings ) {
			return;
		}

		if ( $atts['float'] !== 'yes' && isset($location->settings->badge_display[$overall_slug]) && !$location->settings->badge_display[$overall_slug]) {
			return;
		}

		$attributes = array();
		$tag = 'div';
		
		$badge_settings = new Proofratings_Site_Data($location->settings->$overall_slug);
		
		$classes = ['proofratings-badge', 'proofratings-badge-'.$type];
		$classes[] = 'proofratings-badge-' . $location->id;

		if ( $atts['float'] !== 'yes' ) {
			$classes[] = 'badge-embed';

			$link = $badge_settings->link;

			if ( isset($link['enable']) && $link['enable'] === true ) {
				if ( !empty($link['url']) ) {
					$tag = 'a';
					$attributes['href'] = esc_attr( $link['url'] );
					if ( $link['_blank'] === true  ) {
						$attributes['target'] = '_blank';
					}
				}
			}
		}

		if ( $atts['float'] == 'yes' ) {
			array_push($classes, 'badge-float');

			if ( !empty($badge_settings->position) ) {
				$classes[] = $badge_settings->position;
			}

			if ( $badge_settings->mobile === false) {
				$classes[] = 'badge-hidden-mobile';
			}

			if ( $badge_settings->tablet === false) {
				$classes[] = 'badge-hidden-tablet';
			}
		}

		if ( sizeof($location->reviews) > 5 ) {
			$classes[] = 'connected-more';
		}

		$attributes['class'] = implode(' ', $classes);
		$attributes['data-location'] = $location->id;
		$attributes['data-type'] = $overall_slug;

		$attribute_html = '';

		foreach ($attributes as $ak => $attribute_value) {
			$attribute_html .= sprintf(' %s="%s"', $ak, $attribute_value);
		}

        ob_start();
        printf('<%s %s itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">', $tag, $attribute_html);
			if ( $badge_settings->close_button && $atts['float'] == 'yes' ) {
				echo  '<i class="proofratings-close">&times;</i>';
			}

			if($type == 'narrow') {
				$this->overall_ratings_narrow($location);
			} else {				
				$this->overall_ratings_rectangle($location);
			}
			
        printf('</%s>', $tag);
        return ob_get_clean();
	}

	private function get_meta($overall) {
		echo '<meta itemprop="worstRating" content = "1">';
		echo '<meta itemprop="ratingValue" content="'.$overall->rating.'">';
		echo '<meta itemprop="bestRating" content="5">';
	}

	private function overall_ratings_rectangle($location) {		
		echo '<div class="proofratings-inner">';
			
			$location->ratings->get_logos();

			echo '<div class="proofratings-reviews" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">';
				printf('<span class="proofratings-score">%s</span>', $location->ratings->rating);
				printf( '<span class="proofratings-stars"><i style="width: %s%%"></i></span>', $location->ratings->percent);

				$this->get_meta($location->ratings);
			echo '</div>';
		echo '</div>';

		printf('<div class="proofratings-review-count">%d %s</div>', $location->ratings->count, __('reviews', 'proofratings'));
	}

	private function overall_ratings_narrow($location) {
		$location->ratings->get_logos();

        echo '<div class="proofratings-reviews" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">';
            printf('<span class="proofratings-score">%s</span>', $location->ratings->rating);
            printf( '<span class="proofratings-stars"><i style="width: %s%%"></i></span>', $location->ratings->percent);
			$this->get_meta($location->ratings);
        echo '</div>';

    	printf('<div class="proofratings-review-count">%d %s</div>', $location->ratings->count, __('reviews', 'proofratings'));
	}

	/**
	 * Floating widgets shortcode
	 */
	public function proofratings_badges_popup($atts, $content = null) {
		$atts = shortcode_atts(['id' => 'overall'], $atts);

		$location = get_proofratings()->locations->get($atts['id']);
		if ( !$location || !$location->has_ratings ) {
			return;
		}

		$review_sites = $location->reviews;

		$column = 4;
		if ( count($review_sites) == 5 ) {
			$column = 5;
		}

		if ( count($review_sites) < 4 ) {
			$column = count($review_sites);
		}

		$badges_popup = new Proofratings_Site_Data($location->settings->overall_popup);

		$classes = '';
		if ( $badges_popup->customize ) {
			$classes = 'proofratings-widget-customized';
		}

        ob_start(); 
		
        printf('<div class="proofratings-badges-popup proofratings-badges-popup-%1$s" data-location="%1$s">', $location->id);
			printf ('<div class="proofratings-popup-widgets-box" data-column="%d">', $column);
	        foreach ($review_sites as $key => $site) {
				$tag = 'div';
				$attribue= '';
				
				if( !empty($site->review_url) ) {
					$tag = 'a';
					$attribue = sprintf('href="%s" target="_blank"', esc_url($site->review_url));
				}
				
				printf('<%s class="proofratings-widget proofratings-widget-%s %s" %s data-location="%s">', $tag, $key, $classes, $attribue, $location->id);
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
            'id' => 'overall',
			'column' => false
        ], $atts);

		$location = get_proofratings()->locations->get($atts['id']);
		if ( !$location ) {
			return;
		}

		if ( !$location->has_ratings ) {
			return;
		}

		$ratings = $location->reviews;
		

		$badge_styles = array('square' => 'sites_square', 'rectangle' => 'sites_rectangle', 'basic' => 'badge_basic');

		$badge_type = 'sites_square';
		
		$badge_style = sanitize_key($atts['style']);
		if ( array_key_exists($badge_style, $badge_styles) ) {
			$badge_type = $badge_styles[$badge_style];
		}

		if ( !method_exists($this, 'proofratings_widgets_' . $badge_type)) {
			$badge_style = 'square';
			$badge_type = 'sites_square';
		}

		$badge_widget = isset($location->settings->$badge_type) ? $location->settings->$badge_type : [];
		$badge_widget = new Proofratings_Site_Data($badge_widget);

		if ( isset($location->settings->badge_display[$badge_type]) && !$location->settings->badge_display[$badge_type]) {
			return;
		}
		
		$active_sites = $location->settings->activeSites;
		if ( is_array($badge_widget->active_sites) ) {
			$active_sites = array_intersect($active_sites, $badge_widget->active_sites);
		}
		
		foreach ($ratings as $id => $rating) {
			if ( !in_array($id, $active_sites) ) {
				unset($ratings[$id]);
			}
		}
		
		if ( sizeof($ratings) === 0) {
			return;
		}

		$badge_class = ['proofratings-widget', 'proofratings-widget-' . $location->id, 'proofratings-widget-' . $atts['style']];
		
		if ( $badge_widget->customize ) {
			$badge_class[] = 'proofratings-widget-customized';
		}

		if ( $badge_widget->customize && !empty($badge_widget->logo_color) ) {
			$badge_class[] = 'proofratings-widget-logo-color';
		}

		$wrapper_classes[] = 'proofratings-review-widgets-grid';
		$wrapper_classes[] = sprintf('proofratings-widgets-%s', $location->id);
		$wrapper_classes[] = sprintf('proofratings-widgets-grid-%s', $badge_style);

		if ( absint($atts['column']) > 0 ) {
			$wrapper_classes[] = sprintf('proofratings-widgets-grid-column-%s', absint($atts['column']));
		}

        ob_start();		
        printf('<div class="%s">', implode(' ', $wrapper_classes));
	        foreach ($ratings as $site_id => $rating) {
				$tag = 'div';
				$attribue = '';
			
				if( !empty($rating->review_url) && $badge_type !== 'badge_basic') {
					$tag = 'a';
					$attribue = sprintf('href="%s" target="_blank"', esc_url($rating->review_url));
				}
				
				printf('<%s class="%s %s" %s data-location="%s">', $tag, implode(' ', $badge_class), 'proofratings-widget-' . $site_id, $attribue, $location->id);
					$this->{'proofratings_widgets_' . $badge_type}($rating);
				printf('</%s>', $tag);
	        }

        echo '</div>';
        return ob_get_clean();
	}

	/**
	 * Embed badge sites square
	 */
	public function proofratings_widgets_sites_square($site) {			
    	printf('<div class="review-site-logo" style="-webkit-mask-image:url(%1$s)"><img src="%1$s" alt="%2$s" ></div>', esc_attr($site->logo), esc_attr($site->name));
	
		echo '<div class="proofratings-reviews" itemprop="reviewRating">';
			printf('<span class="proofratings-score">%s</span>', number_format($site->rating, 1));
			printf('<span class="proofratings-stars"><i style="width: %s%%"></i></span>', esc_attr($site->percent));
        echo '</div>';

		printf('<div class="review-count"> %d %s </div>', esc_html($site->count), __('reviews', 'proofratings'));

		echo '<p class="view-reviews">' . __('View Reviews', 'proofratings') . '</p>';
	}

	/**
	 * Embed badge basic
	 */
	public function proofratings_widgets_badge_basic($site) {
    	printf('<div class="review-site-logo" style="-webkit-mask-image:url(%1$s)"><img src="%1$s" alt="%2$s" ></div>', esc_attr($site->logo), esc_attr($site->name));
	
		printf('<span class="proofratings-stars"><i style="width: %s%%"></i></span>', esc_attr($site->percent));

		printf('<div class="review-count"> %d %s </div>', esc_html($site->count), __('user rating', 'proofratings'));

		if ( $review_url = esc_url($site->review_url) ) {
			printf('<a class="view-reviews" href="%s">%s</a>', $review_url, __('View all reviews', 'proofratings'));
		}            
	}

	/**
	 * Embed badge style2
	 */
	public function proofratings_widgets_sites_rectangle($site) {		
    	printf('<div class="review-site-logo">%s</div>', @file_get_contents($site->icon2));

		if ( $site->rating_title ) {
			echo '<h4 class="rating-title">'.$site->rating_title.'</h4>';
		}

	
		echo '<div class="proofratings-reviews" itemprop="reviewRating">';
			printf('<span class="proofratings-score">%s</span>', number_format($site->rating, 1));
			printf('<span class="proofratings-stars"><i style="width: %s%%"></i></span>', esc_attr($site->percent));
        echo '</div>';

		printf('<div class="review-count"> %d %s </div>', esc_html($site->count), __('reviews', 'proofratings'));
	}

	/**
	 * CTA banner 
	 */
	public function overall_ratings_cta_banner($atts, $content = null) {
		$location = get_proofratings()->locations->get($atts['id']);
		if ( !$location || !$location->has_ratings ) {
			return;
		}

		$badge_settings = new Proofratings_Site_Data($location->settings->overall_cta_banner);
		
		$classes = ['proofratings-banner-badge'];
		$classes[] = 'proofratings-banner-badge-'.$location->id;


		if ( $badge_settings->tablet === false) {
			$classes[] = 'badge-hidden-tablet';
		}

		if ( $badge_settings->mobile === false) {
			$classes[] = 'badge-hidden-mobile';
		}

		if ( $badge_settings->shadow !== false ) {
			$classes[] = 'has-shadow';
		}

		$class = implode(' ', $classes);

		$button1 = '';
		if ( isset($badge_settings->button1) ) {
			$button1_settings = new Proofratings_Site_Data($badge_settings->button1);

			$button1_class = 'proofratings-button button1';
			if ( $button1_settings->border == 'yes' ) {
				$button1_class .= ' has-border';
			}

			if ( $button1_settings->rectangle !== true) {
				$button1_class .= ' button-round';
			}

			$target = '';
			if ( $button1_settings->blank == 'yes') {
				$target = 'target="_blank"';
			}

			$button1 .= sprintf('<a href="%s" class="%s" %s>', esc_url( $button1_settings->url), trim($button1_class), $target);
			$button1 .= $button1_settings->text;
			$button1 .= '</a>';			
		}

		$button2 = '';
		if ( isset($badge_settings->button2['show']) && $badge_settings->button2['show'] === true ) {
			$button2_settings = new Proofratings_Site_Data($badge_settings->button2);
			
			$button2_class = 'proofratings-button button2';
			if ( $button2_settings->border ) {
				$button2_class .= ' has-border';
			}
			
			if ( $button2_settings->rectangle === false) {
				$button2_class .= ' button-round';
			}

			$target = '';
			if ( $button2_settings->blank == 'yes') {
				$target = 'target="_blank"';
			}

			$button2 .= sprintf('<a href="%s" class="%s" %s>', esc_url( $button2_settings->url), trim($button2_class), $target);			
			$button2 .= $button2_settings->text;
			$button2 .= '</a>';			
		}

		$close_button = '';
		if ( $badge_settings->close_button !== false ) {
			$close_button = sprintf('<a class="proofratings-banner-close" href="#">%s</a>', __('Close', 'proofratings'));
		}
		
		ob_start(); ?>
		<div class="<?php echo $class; ?>" data-location="<?php echo $location->id ?>" data-type="overall_cta_banner">
			<?php echo $close_button; ?>
			<?php $location->ratings->get_logos(); ?>
			
        	<meta itemprop="worstRating" content = "1">
        	<meta itemprop="ratingValue" content="<?php echo $location->ratings->rating ?>">
        	<meta itemprop="bestRating" content="5">

			<div class="rating-box">
				<span class="proofratings-stars medium"><i style="width: <?php echo $location->ratings->percent ?>%"></i></span> 
				<span class="rating"><?php echo $location->ratings->rating; ?> / 5</span>
			</div>

			<div class="proofratings-review-count"><?php echo $location->ratings->count; ?> customer reviews</div>

			<div class="button-container">
				<?php echo $button1 . $button2; ?>
			</div>
		</div>
		<?php

		return ob_get_clean();
	}
}
