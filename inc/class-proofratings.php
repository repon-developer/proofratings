<?php
/**
 * File containing the class Wordpress_ProofRatings.
 *
 * @package proofratings
 * @since   1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles core plugin hooks and action setup.
 *
 * @since 1.0.0
 */
class Wordpress_Proofratings {
	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since  1.0.1
	 */
	private static $instance = null;

	/**
	 * Main WP Proofratings Instance.
	 * Ensures only one instance of WP Job Manager is loaded or can be loaded.
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
		include_once PROOFRATINGS_PLUGIN_DIR . '/inc/helpers.php';
		include_once PROOFRATINGS_PLUGIN_DIR . '/inc/class-proofratings-review.php';
		include_once PROOFRATINGS_PLUGIN_DIR . '/inc/class-proofratings-admin.php';
		include_once PROOFRATINGS_PLUGIN_DIR . '/inc/class-proofratings-shortcodes.php';

		$this->reviews = Proofratings_Review::instance();

		$this->admin = ProofRatings_Admin::instance();
		$this->shortcodes = ProofRatings_Shortcodes::instance();


		add_action( 'rest_api_init', [$this, 'register_rest_api']);

		// Actions.
		add_action( 'init', [ $this, 'load_plugin_textdomain' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_footer', [ $this, 'embed_floating_badge' ] );
		add_action( 'wp_footer', [ $this, 'banner_badge' ] );
	}
		
	/**
	 * proofratings rest api for getting data
	 */
	public function register_rest_api() {		
		register_rest_route( 'proofratings/v1', 'set_reviews', array(
			'methods' => 'POST',
			'callback' => [$this, 'set_reviews'],
			'permission_callback' => '__return_true'
		));
	}

	/**
	 * proofratings rest api callback
	 */
	public function set_reviews(WP_REST_Request $request) {
		$reviews_info = wp_parse_args($request->get_params(), ['status' => false, 'message' => '', 'reviews' => array()]);

		$reviews = $reviews_info['reviews'];
		unset($reviews_info['reviews']);

		update_option('proofratings_status', $reviews_info );
		update_option( 'proofratings_reviews', $reviews);
	}

	/**
	 * proof ratings activate
	 */
	public function activate() {
		update_option('proofratings_version', PROOFRATINGS_VERSION );

		$request_url = add_query_arg(array(
			'name' => get_bloginfo( 'name' ),
			'email' => get_bloginfo( 'admin_email' ),
			'url' => get_site_url()
		), PROOFRATINGS_API_URL . '/register');

		$response = wp_remote_get($request_url);
		if ( is_wp_error( $response ) ) {
			return;
		}

		if( $response['response']['code'] !== 200) {
			return;			
		}

		$data = json_decode(wp_remote_retrieve_body($response));
		if ( is_object($data) && $data->success ) {
			update_option('proofratings_status', $data );
		}
	}

	/**
	 * Loads textdomain for plugin.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'proofratings', false, PROOFRATINGS_PLUGIN_DIR . '/languages' );
	}

	/**
	 * frontend CSS and JS assets.
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'didact-gothic', 'https://fonts.googleapis.com/css2?family=Didact+Gothic&display=swap', [], PROOFRATINGS_VERSION);
		wp_enqueue_style( 'proofratings', PROOFRATINGS_PLUGIN_URL . '/assets/css/proofratings.css', [], PROOFRATINGS_VERSION);

		wp_register_script( 'js-cookie', PROOFRATINGS_PLUGIN_URL.  '/assets/js/js.cookie.min.js', [], '3.0.1', true);

		$upload_dir = wp_upload_dir();
		$generated_css = $upload_dir['basedir'] . '/proofratings-generated.css';
		if ( file_exists($generated_css) ) {
			wp_enqueue_style( 'proofratings-generated', $upload_dir['baseurl'] . '/proofratings-generated.css', [], filemtime($generated_css));			
		}

		wp_enqueue_script( 'proofratings', PROOFRATINGS_PLUGIN_URL . '/assets/js/proofratings.js', ['jquery', 'js-cookie'], PROOFRATINGS_VERSION, true);
	}

	/**
	 * Embed floating badge on frontend
	 */
	public function embed_floating_badge() {
		if ( isset($_COOKIE['hide_proofratings_float_badge'])) {
			return;
		}

		$badge_settings = get_option( 'proofratings_floating_badge_settings');
		$on_pages = (array) @$badge_settings['on_pages'];
		$has_page = !isset($badge_settings['on_pages'][get_the_ID()]) || $badge_settings['on_pages'][get_the_ID()] == 'yes'? true : false;

		$show_badge = @$badge_settings['float'];
		if ( !($show_badge != 'yes') && $has_page ) {

			$attributes = [];

			$supported_keys = ['badge_style', 'mobile', 'tablet', 'star_color', 'shadow', 'shadow_color', 'shadow_hover', 'background_color', 'review_text_color', 'review_background'];

			array_walk($supported_keys, function($key) use (&$attributes, $badge_settings) {
				if ( !empty($badge_settings[$key])) {
					$attributes[$key] = $badge_settings[$key];
				}
			});

			$attributes = array_map(function($item, $key){
				return "$key=\"$item\"";
			}, $attributes, array_keys($attributes));

			echo do_shortcode(sprintf('[proofratings_floating_badge float="yes" %s]', implode(' ', $attributes)) );
			echo do_shortcode('[proofratings_floating_widgets]' );
		}
	}

	/**
	 * Banner badge on frontend
	 */
	public function banner_badge() {
		$badge_settings = get_option( 'proofratings_banner_badge' );
		if ( !$badge_settings) {
			return;
		}

		$on_pages = (array) @$badge_settings['on_pages'];
		$is_targeted_page = !isset($badge_settings['on_pages'][get_the_ID()]) || $badge_settings['on_pages'][get_the_ID()] == 'yes'? true : false;

		if ( !$is_targeted_page ) {
			return;
		}

		$classes = ['proofratings-banner-badge'];
		if ( $badge_settings['tablet'] == 'no') {
			$classes[] = 'badge-hidden-tablet';
		}

		if ( $badge_settings['mobile'] == 'no') {
			$classes[] = 'badge-hidden-mobile';
		}

		$class = implode(' ', $classes);


		$button1 = '';
		if ( !empty($badge_settings['button1_text']) ) {
			$button1_class = 'proofratings-button button1';
			if ( $badge_settings['button1_border'] == 'yes' ) {
				$button1_class .= ' has-border';
			}

			$target = '';
			if ( @$badge_settings['button1_blank'] == 'yes') {
				$target = 'target="_blank"';
			}

			$button1 .= sprintf('<a href="%s" class="%s" %s>', esc_url( $badge_settings['button1_url']), trim($button1_class), $target);
			$button1 .= $badge_settings['button1_text'];			
			$button1 .= '</a>';			
		}

		$button2 = '';
		if ( @$badge_settings['button2'] == 'yes' && !empty($badge_settings['button2_text']) ) {
			$button2_class = 'proofratings-button button2';
			if ( $badge_settings['button2_border'] == 'yes' ) {
				$button2_class .= ' has-border';
			}

			$target = '';
			if ( @$badge_settings['button2_blank'] == 'yes') {
				$target = 'target="_blank"';
			}

			$button2 .= sprintf('<a href="%s" class="%s" %s>', esc_url( $badge_settings['button2_url']), trim($button2_class), $target);			
			$button2 .= $badge_settings['button2_text'];			
			$button2 .= '</a>';			
		}

		
		?>

		<div class="<?php echo $class; ?>">
			<a class="proofratings-banner-close" href="#"><?php _e('Close', 'proofratings') ?></a>

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

	}
}
