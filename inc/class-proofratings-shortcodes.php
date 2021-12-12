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
		$this->reviews = Proofratings_Review::instance();
		
        add_shortcode('proofratings_widgets', [$this, 'proofratings_widgets']);
		add_shortcode('proofratings_badges_popup', [$this, 'proofratings_badges_popup']);

        add_shortcode('proofratings_overall_ratings', [$this, 'proofratings_overall_ratings']);
        add_shortcode('proofratings_overall_ratings_cta_banner', [$this, 'overall_ratings_cta_banner']);
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

		if ( $this->reviews->sites === false) {
			return;
		}

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

		if (!is_array($location->settings->activeSites) || empty($location->settings->activeSites)) {
			return;
		}
		
		$badget_settings = new Proofratings_Site_Data($location->settings->$overall_slug);
				
		$classes = ['proofratings-badge', 'proofratings-badge-'.$type];

		if ( $atts['float'] == 'yes' ) {
			array_push($classes, 'badge-float');

			if ( !empty($badget_settings->position) ) {
				$classes[] = $badget_settings->position;
			}

			if ( $badget_settings->mobile == 'no') {
				$classes[] = 'badge-hidden-mobile';
			}

			if ( $badget_settings->tablet == 'no') {
				$classes[] = 'badge-hidden-tablet';
			}

			$badget_settings->shadow = 'yes';
		}

		$url_attribute = '';
		$tag = 'div';
		if (!empty($atts['url'])) {
			$tag = 'a';
			$url_attribute = sprintf('href="%s"', esc_url($atts['url']));
		}

        ob_start();
        printf('<%s %s class="%s" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">', $tag, $url_attribute, implode(' ', $classes));
			if ( $badget_settings->close_button != 'no' && $atts['float'] == 'yes' ) {
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
		$review_sites = $this->reviews->sites;
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

		$badges_popup = get_proofratings_badges_popup();

		$classes = '';
		if ( $badges_popup->customize == 'yes' ) {
			$classes = 'proofratings-widget-customized';
		}

        ob_start(); 
		
        printf('<div class="proofratings-badges-popup">');
			printf ('<div class="proofratings-popup-widgets-box" data-column="%d">', $column);
	        foreach ($review_sites as $key => $site) {
				$tag = 'div';
				$attribue= '';
				
				if( !empty($site->review_url) ) {
					$tag = 'a';
					$attribue = sprintf('href="%s" target="_blank"', esc_url($site->review_url));
				}
				
				printf('<%s class="proofratings-widget proofratings-widget-%s %s" %s>', $tag, $key, $classes, $attribue);
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
            'id' => 'overall'
        ], $atts);

		$location = get_proofratings()->locations->get($atts['id']);
		if ( !$location ) {
			return;
		}

		if ( !$location->ratings->has_ratings ) {
			return;
		}

		$ratings = $location->reviews;
		
		if ( empty($location->settings->activeSites)) {
			return;
		}

		$badge_styles = array('square' => 'sites_square', 'rectangle' => 'sites_rectangle');

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
		
		$active_sites = $location->settings->activeSites;
		if ( is_array($badge_widget->active_sites) ) {
			$active_sites = array_intersect($active_sites, $badge_widget->active_sites);
		}

		while ($site_id = current($active_sites)) {
			next($active_sites);
			if ( !isset($ratings[$site_id])) {
				$ratings[$site_id] = array('rating' => 0, 'count' => 0, 'percent' => 0);
			}			
		}

		if ( sizeof($ratings) === 0) {
			return;
		}

		$badge_class = ['proofratings-widget', 'proofratings-widget-' . $atts['style']];
		
		if ( $badge_widget->customize ) {
			$badge_class[] = 'proofratings-widget-customized';
		}

		if ( !empty($badge_widget->logo_color) ) {
			$badge_class[] = 'proofratings-widget-logo-color';
		}

        ob_start();		
        printf('<div id="proofratings-widgets-%s" class="proofratings-review-widgets-grid proofratings-widgets-grid-%s">', esc_attr($atts['id']), $badge_style);
	        foreach ($ratings as $site_id => $location) {
				$tag = 'div';
				$attribue = '';
			
				if( !empty($location->review_url) ) {
					$tag = 'a';
					$attribue = sprintf('href="%s" target="_blank"', esc_url($location->review_url));
				}
				
				printf('<%s class="%s %s" %s>', $tag, implode(' ', $badge_class), 'proofratings-widget-' . $site_id, $attribue);
					$this->{'proofratings_widgets_' . $badge_type}($location);
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
	 * Embed badge style2
	 */
	public function proofratings_widgets_sites_rectangle($site) {		
    	//printf('<div class="review-site-logo"><img src="%1$s" alt="%2$s" ></div>', esc_attr($site->icon2), esc_attr($site->rating_title));
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
		if ( $this->reviews->sites === false) {
			return;
		}

		$badge_settings = get_proofratings_overall_ratings_cta_banner();
		$classes = ['proofratings-banner-badge'];
		if ( $badge_settings->tablet == 'no') {
			$classes[] = 'badge-hidden-tablet';
		}

		if ( $badge_settings->mobile == 'no') {
			$classes[] = 'badge-hidden-mobile';
		}

		if ( $badge_settings->shadow != 'no' ) {
			$classes[] = 'has-shadow';
		}

		$class = implode(' ', $classes);


		$button1 = '';
		if ( !empty($badge_settings->button1_text) ) {
			$button1_class = 'proofratings-button button1';
			if ( $badge_settings->button1_border == 'yes' ) {
				$button1_class .= ' has-border';
			}

			$target = '';
			if ( $badge_settings->button1_blank == 'yes') {
				$target = 'target="_blank"';
			}

			$button1 .= sprintf('<a href="%s" class="%s" %s>', esc_url( $badge_settings->button1_url), trim($button1_class), $target);
			$button1 .= $badge_settings->button1_text;
			$button1 .= '</a>';			
		}

		$button2 = '';
		if ( $badge_settings->button2 == 'yes' && !empty($badge_settings->button2_text) ) {
			$button2_class = 'proofratings-button button2';
			if ( $badge_settings->button2_border == 'yes' ) {
				$button2_class .= ' has-border';
			}

			$target = '';
			if ( $badge_settings->button2_blank == 'yes') {
				$target = 'target="_blank"';
			}

			$button2 .= sprintf('<a href="%s" class="%s" %s>', esc_url( $badge_settings->button2_url), trim($button2_class), $target);			
			$button2 .= $badge_settings->button2_text;
			$button2 .= '</a>';			
		}

		$close_button = '';
		if ( $badge_settings->close_button != 'no' ) {
			$close_button = sprintf('<a class="proofratings-banner-close" href="#">%s</a>', __('Close', 'proofratings'));
		}
		
		ob_start(); ?>
		<div class="<?php echo $class; ?>">
			<?php echo $close_button; ?>
			<?php $this->reviews->get_review_logos() ?>

			<div class="rating-box">
				<?php $this->reviews->get_rating_star('medium') ?> <span class="rating"><?php echo $this->reviews->rating; ?> / 5</span>
			</div>

			<div class="proofratings-review-count"><?php echo $this->reviews->count; ?> customer reviews</div>

			<div class="button-container">
				<?php echo $button1 . $button2; ?>
			</div>
		</div>
		<?php		

		return ob_get_clean();
	}
}
