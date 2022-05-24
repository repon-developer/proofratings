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

		$location = get_proofratings()->query->get($atts['id']);
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
        printf('<%s %s>', $tag, $attribute_html);
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

	private function overall_ratings_rectangle($location) {		
		echo '<div class="proofratings-inner">';
			
			$location->ratings->get_logos();

			echo '<div class="proofratings-reviews">';
				printf('<span class="proofratings-score">%s</span>', esc_html( $location->ratings->rating));
				printf( '<span class="proofratings-stars"><i style="width: %s%%"></i></span>', esc_html($location->ratings->percent));
			echo '</div>';
		echo '</div>';

		printf('<div class="proofratings-review-count">%d %s</div>', esc_html($location->ratings->count), __('reviews', 'proofratings'));
	}

	private function overall_ratings_narrow($location) {
		$location->ratings->get_logos();

        echo '<div class="proofratings-reviews">';
            printf('<span class="proofratings-score">%s</span>', esc_html($location->ratings->rating));
            printf( '<span class="proofratings-stars"><i style="width: %s%%"></i></span>', esc_html($location->ratings->percent));
        echo '</div>';

    	printf('<div class="proofratings-review-count">%d %s</div>', esc_html($location->ratings->count), __('reviews', 'proofratings'));
	}

	/**
	 * Floating widgets shortcode
	 */
	public function proofratings_badges_popup($atts, $content = null) {
		$atts = shortcode_atts(['id' => 'overall'], $atts);

		$location = get_proofratings()->query->get($atts['id']);
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
				
					echo '<div class="proofratings-reviews">';
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

		$location = get_proofratings()->query->get($atts['id']);
		if ( !$location ) {
			return;
		}

		if ( !$location->has_ratings ) {
			return;
		}

		$badge_style = sanitize_key( $atts['style']);
		$widget_type = sprintf('widget_%s', $badge_style);

		if ( !method_exists($this, $widget_type)) {
			$badge_style = 'square';
			$widget_type = 'widget_square';
		}

		$current_widget = isset($location->settings->$widget_type) ? $location->settings->$widget_type : [];
		$current_widget = new Proofratings_Site_Data($current_widget);

		if ( isset($location->settings->badge_display[$widget_type]) && !$location->settings->badge_display[$widget_type]) {
			return;
		}


		//$connections = wp_list_pluck($this->connections, 'slug');

		$widget_connections = array_map(function($slug){
			
			return get_proofratings()->query->connections[$slug];

		}, $current_widget->widget_connections);


		//var_dump($widget_connections);
		
		
		var_dump($location->reviews);
		exit;
		
		$ratings = $location->reviews;
		
		if ( sizeof($ratings) === 0) {
			return;
		}

		$badge_class = ['proofratings-widget', 'proofratings-widget-' . $location->id, 'proofratings-widget-' . $atts['style']];
		
		if ( $badge_widget->customize ) {
			$badge_class[] = 'proofratings-widget-customized';
		}

		if ( $badge_widget->customize && ($badge_widget->logo_color || $badge_widget->icon_color) ) {
			$badge_class[] = 'proofratings-widget-logo-color';
		}

		if ( $badge_widget->alignment ) {
			$badge_class[] = sprintf('proofratings-widgets-align-%s', esc_attr($badge_widget->alignment) );
		}

		$wrapper_classes[] = 'proofratings-review-widgets-grid';
		$wrapper_classes[] = sprintf('proofratings-widgets-%s', $location->id);
		$wrapper_classes[] = sprintf('proofratings-widgets-grid-%s', $badge_style);

		if ( $badge_widget->position ) {
			$wrapper_classes[] = sprintf('proofratings-widgets-grid-%s', esc_attr($badge_widget->position) );
		}

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
					$this->{$widget_type}($rating);
				printf('</%s>', $tag);
	        }

        echo '</div>';
        return ob_get_clean();
	}

	/**
	 * Embed badge sites square
	 */
	public function widget_square($site) {			
    	printf('<div class="review-site-logo"><img src="%s" alt="%s" ></div>', esc_attr($site->logo), esc_attr($site->name));
	
		echo '<div class="proofratings-reviews"">';
			printf('<span class="proofratings-score">%s</span>', number_format($site->rating, 1));
			printf('<span class="proofratings-stars"><i style="width: %s%%"></i></span>', esc_attr($site->percent));
        echo '</div>';

		printf('<div class="review-count"> %d %s </div>', esc_html($site->count), __('reviews', 'proofratings'));

		echo '<p class="view-reviews">' . __('View Reviews', 'proofratings') . '</p>';
	}

	/**
	 * Embed badge basic
	 */
	public function widgets_badge_basic($site) {
    	printf('<div class="review-site-logo"><img src="%s" alt="%s" ></div>', esc_attr($site->logo), esc_attr($site->name));
	
		printf('<span class="proofratings-stars"><i style="width: %s%%"></i></span>', esc_attr($site->percent));

		printf('<div class="review-count"> %d %s </div>', esc_html($site->count), __('ratings', 'proofratings'));

		if ( $review_url = esc_url($site->review_url) ) {
			printf('<a class="view-reviews" href="%s">%s</a>', esc_attr($review_url), __('View all reviews', 'proofratings'));
		}            
	}

	/**
	 * Embed badge style2
	 */
	public function widgets_sites_rectangle($site) {		
    	printf('<div class="review-site-logo">%s</div>', @file_get_contents($site->logo));
		printf('<span class="proofratings-score">%s</span>', number_format($site->rating, 1));
	
		echo '<div class="proofratings-reviews">';
			printf('<span class="proofratings-stars"><i style="width: %s%%"></i></span>', esc_attr($site->percent));
        echo '</div>';

		printf('<div class="review-count"> %d %s </div>', esc_html($site->count), __('reviews', 'proofratings'));
	}

	/**
	 * Sites icon
	 * @since 1.0.9
	 */
	public function widgets_sites_icon($site) {
    	printf('<div class="review-site-logo"><img src="%s" alt="%s" ></div>', esc_attr($site->icon3), esc_attr($site->name));

		echo '<div class="review-info-container">';	
			printf('<span class="proofratings-stars"><i style="width: %s%%"></i></span>', esc_attr($site->percent));

			echo '<div class="review-info">';
				printf('<span class="proofratings-rating">%s %s</span>', number_format($site->rating, 1), __('Rating', 'proofratings'));
				echo '<span class="separator-circle">●</span>';
				printf('<span class="proofratings-review-number">' . _n( '%s Review', '%s Reviews', $site->count, 'proofratings' ), number_format_i18n( $site->count ) . '</span>');
			echo '</div>';          
		echo '</div>';          
	}

	/**
	 * CTA banner 
	 */
	public function overall_ratings_cta_banner($atts, $content = null) {
		$location = get_proofratings()->query->get($atts['id']);
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
		if ( isset($badge_settings->button1['show']) && $badge_settings->button1['show'] === true ) {
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
		<div class="<?php echo esc_attr($class); ?>" data-location="<?php echo esc_attr($location->id) ?>" data-type="overall_cta_banner">
			<?php echo wp_kses_post($close_button); ?>
			<?php $location->ratings->get_logos(); ?>

			<div class="rating-box">
				<span class="proofratings-stars medium"><i style="width: <?php echo esc_attr( $location->ratings->percent) ?>%"></i></span> 
				<span class="rating"><?php echo esc_html($location->ratings->rating); ?> / 5</span>
			</div>

			<div class="proofratings-review-count"><?php echo esc_html($location->ratings->count); ?> customer reviews</div>

			<div class="button-container">
				<?php echo wp_kses_post($button1 . $button2); ?>
			</div>
		</div>
		<?php

		return ob_get_clean();
	}
}
