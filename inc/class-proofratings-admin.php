<?php
/**
 * File containing the class WP_ProofRatings_Admin.
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
class ProofRatings_Admin {

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
		include_once dirname( __FILE__ ) . '/class-proofratings-settings.php';
		$this->settings_page = WP_ProofRatings_Settings::instance();
		
		add_action( 'init', [$this, 'register_your_domain']);
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		add_action( "updated_option", [ $this, 'generate_css' ], 10, 3 );
	}

	/**
	 * Register domain for getting review data
	 * @since 1.0.1
	 */
	public function register_your_domain() {
		if ( !isset($_GET['_regsiter_nonce']) ) {
			return;
		}

		if( !wp_verify_nonce( $_GET['_regsiter_nonce'], 'register_proofratings') ) {
			return;
		}

		WP_ProofRatings()->activate();

		exit(wp_safe_redirect(remove_query_arg('_regsiter_nonce')));
	}

	/**
	 * Generate styles 
	 */
	public function generate_css($old_value, $value, $option) {
		if ( !isset($_POST['option_page']) || 'proofratings' != $_POST['option_page'] ) {
			return;
		}

		$postdata = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

		$settings = $postdata['proofratings_settings'];
		$badge_settings = $postdata['proofratings_floating_badge_settings'];
		$proofratings_font = $postdata['proofratings_font'];
		
		ob_start();

		if ( $proofratings_font ) {
			echo ".proofratings-widget, .proofratings-floating-badge {\n";
				printf("\tfont-family: %s!important;\n", $proofratings_font);
			echo "}\n\n";
		}

		foreach ($settings as $key => $site) {
			if ( empty($key)) continue;
			printf(".proofratings-widget.proofratings-widget-%s {\n", $key);

				if ( $site['theme_color'] ) {
					printf("\t--themeColor: %s;\n", $site['theme_color']);
				}

				if ( $site['text_color'] ) {
					printf("\t--textColor: %s;\n", $site['text_color']);
				}

				if ( $site['background'] ) {
					printf("\tbackground-color: %s;\n", $site['background']);
				}
				
			echo "}\n\n";
		}

		echo ".proofratings-floating-badge {\n";			
			if ( $badge_settings['shadow_color'] ) {
				printf("\t--shadowColor: %s66;\n", $badge_settings['shadow_color']);
			}

			if ( $badge_settings['shadow_hover'] ) {
				printf("\t--shadowHover: %s66;\n", $badge_settings['shadow_hover']);
			}

			if ( $badge_settings['background_color'] ) {
				printf("\tbackground-color: %s;\n", $badge_settings['background_color']);
			}
		echo "}\n\n";

		echo ".proofratings-floating-badge .proofratings-stars i {\n";			
			if ( $badge_settings['star_color'] ) {
				printf("\tbackground-color: %s;\n", $badge_settings['star_color']);
			}
		echo "}\n\n";

		echo ".proofratings-floating-badge .proofratings-review-count {\n";
			if ( $badge_settings['review_text_color'] ) {
				printf("\tcolor: %s!important;\n", $badge_settings['review_text_color']);
			}

			if ( $badge_settings['review_background'] ) {
				printf("\tbackground-color: %s!important;\n", $badge_settings['review_background']);
			}
		echo "}";
			
		$styles = ob_get_clean();


		file_put_contents(wp_upload_dir()['basedir'] . '/proofratings-generated.css', $styles);	
	}

	/**
	 * Add menu page
	 */
	public function admin_menu() {
		add_menu_page(__('Proofratings', 'proofratings'), __('Proofratings', 'proofratings'), 'manage_options', 'proofratings', [$this->settings_page, 'output'], 'dashicons-star-filled', 25);
	}

	/**
	 * Enqueues CSS and JS assets.
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();

		if ( in_array( $screen->id, [ 'toplevel_page_proofratings' ] ) ) {
			wp_enqueue_style( 'proofratings', PROOFRATINGS_PLUGIN_URL . '/assets/css/proofratings-admin.css', ['wp-color-picker'], PROOFRATINGS_VERSION);
			wp_enqueue_script( 'proofratings', PROOFRATINGS_PLUGIN_URL . '/assets/js/proofratings-admin.js', ['jquery', 'wp-color-picker'], PROOFRATINGS_VERSION, true);
		}
	}


}
