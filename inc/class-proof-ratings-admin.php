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
 * Handles core plugin hooks and action setup.
 *
 * @since 1.0.0
 */
class Proof_Ratings_Admin {

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
		include_once dirname( __FILE__ ) . '/class-proof-ratings-settings.php';
		$this->settings_page = WP_Proof_Ratings_Settings::instance();

		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		add_action( "updated_option", [ $this, 'generate_css' ], 10, 3 );
	}

	/**
	 * Generate styles 
	 */
	public function generate_css($old_value, $value, $option) {
		if ( !isset($_POST['option_page']) || 'proof_ratings' != $_POST['option_page'] ) {
			return;
		}

		$settings = $_POST['proof_ratings_settings'];
		$badge_settings = $_POST['proof_ratings_floating_badge_settings'];

		ob_start();
		foreach ($settings as $key => $site) {
			if ( empty($key)) continue;
			printf(".proof-ratings-widget.proof-ratings-widget-%s {\n", $key);

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

		echo ".proof-ratings-floating-badge {\n";			
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

		echo ".proof-ratings-floating-badge .proof-ratings-review-count {\n";
			if ( $badge_settings['review_text_color'] ) {
				printf("\tcolor: %s!important;\n", $badge_settings['review_text_color']);
			}

			if ( $badge_settings['review_background'] ) {
				printf("\tbackground-color: %s!important;\n", $badge_settings['review_background']);
			}
		echo "}";
			
		$styles = ob_get_clean();
		file_put_contents(PROOF_RATINGS_PLUGIN_DIR . '/assets/css/proof-ratings-generated.css', $styles);	
	}

	/**
	 * Add menu page
	 */
	public function admin_menu() {
		add_menu_page(__('Proof Ratings', 'proof-ratings'), __('Proof Ratings', 'proof-ratings'), 'manage_options', 'proof-ratings', [$this->settings_page, 'output'], 'dashicons-star-filled', 25);
	}

	/**
	 * Enqueues CSS and JS assets.
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();

		if ( in_array( $screen->id, [ 'toplevel_page_proof-ratings' ] ) ) {
			wp_enqueue_style( 'proof-ratings', PROOF_RATINGS_PLUGIN_URL . '/assets/css/proof-ratings-admin.css', ['wp-color-picker'], PROOF_RATINGS_VERSION);
			wp_enqueue_script( 'proof-ratings', PROOF_RATINGS_PLUGIN_URL . '/assets/js/proof-ratings-admin.js', ['jquery', 'wp-color-picker'], PROOF_RATINGS_VERSION, true);
		}
	}


}
