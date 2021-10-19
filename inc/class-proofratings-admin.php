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

		$widget_settings = wp_parse_args($postdata['proofratings_widget_settings'], [
			'proofratings_font' => 'inherit',
		]);

		$banner_badge = get_option( 'proofratings_banner_badge', [] );
		
		ob_start();

		if ( $widget_settings['proofratings_font'] ) {
			echo ".proofratings-widget, .proofratings-floating-badge {\n";
				printf("\tfont-family: %s!important;\n", $widget_settings['proofratings_font']);
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

			printf(".proofratings-widget.proofratings-widget-style2.proofratings-widget-%s .review-count {\n", $key);
				if ( $site['review_count_textcolor'] ) {
					printf("\t--color: %s!important;\n", $site['review_count_textcolor']);
				}
			echo "}";
		}

		echo "#proofratings-floating-embed .proofrating-close {\n";			
			if ( $badge_settings['star_color'] ) {
				printf("\tcolor: %s;\n", $badge_settings['star_color']);
			}
		echo "}\n\n";

		echo ".proofratings-banner-badge {\n";
			if ( $banner_badge['number_review_text_color'] ) {
				printf("\t--reviewCountTextcolor: %s;\n", $banner_badge['number_review_text_color']);
			}
			
			if ( $banner_badge['background_color'] ) {
				printf("\t--backgroundColor: %s;\n", $banner_badge['background_color']);
			}
		echo "}\n\n";

		echo ".proofratings-banner-badge .rating-box {\n";
			if ( $banner_badge['rating_text_color'] ) {
				printf("\tcolor: %s;\n", $banner_badge['rating_text_color']);
			}

			if ( $banner_badge['review_rating_background_color'] ) {
				printf("\t--backgroundColor: %s;\n", $banner_badge['review_rating_background_color']);
			}
		echo "}\n\n";

		echo ".proofratings-banner-badge .proofratings-stars {\n";
			if ( $banner_badge['star_color'] ) {
				printf("\t--star_color: %s;\n", $banner_badge['star_color']);
			}
		echo "}\n\n";

		echo ".proofratings-banner-badge .proofratings-button.button1 {\n";
			if ( $banner_badge['button1_shape'] == 'round' ) {
				printf("\t--radius: 100px;\n");
			}

			if ( $banner_badge['button1_textcolor'] ) {
				printf("\t--textColor: %s;\n", $banner_badge['button1_textcolor']);
			}

			if ( $banner_badge['button1_hover_textcolor'] ) {
				printf("\t--textHoverColor: %s;\n", $banner_badge['button1_hover_textcolor']);
			}

			if ( $banner_badge['button1_background_color'] ) {
				printf("\t--backgroundColor: %s;\n", $banner_badge['button1_background_color']);
			}

			if ( $banner_badge['button1_hover_background_color'] ) {
				printf("\t--backgroundHoverColor: %s;\n", $banner_badge['button1_hover_background_color']);
			}

			if ( $banner_badge['button1_border'] == 'yes' ) {
				if ( $banner_badge['button1_border_color'] ) {
					printf("\t--borderColor: %s;\n", $banner_badge['button1_border_color']);
				}
				
				if ( $banner_badge['button1_hover_border_color'] ) {
					printf("\t--borderHoverColor: %s;\n", $banner_badge['button1_hover_border_color']);
				}
			}
		echo "}\n\n";
		
		echo ".proofratings-banner-badge .proofratings-button.button2 {\n";
			if ( $banner_badge['button2_shape'] == 'round' ) {
				printf("\t--radius: 100px;\n");
			}

			if ( $banner_badge['button2_textcolor'] ) {
				printf("\t--textColor: %s;\n", $banner_badge['button2_textcolor']);
			}

			if ( $banner_badge['button2_hover_textcolor'] ) {
				printf("\t--textHoverColor: %s;\n", $banner_badge['button2_hover_textcolor']);
			}

			if ( $banner_badge['button2_background_color'] ) {
				printf("\t--backgroundColor: %s;\n", $banner_badge['button2_background_color']);
			}

			if ( $banner_badge['button2_hover_background_color'] ) {
				printf("\t--backgroundHoverColor: %s;\n", $banner_badge['button2_hover_background_color']);
			}

			if ( $banner_badge['button2_border'] == 'yes' ) {
				if ( $banner_badge['button2_border_color'] ) {
					printf("\t--borderColor: %s;\n", $banner_badge['button2_border_color']);
				}
				
				if ( $banner_badge['button2_hover_border_color'] ) {
					printf("\t--borderHoverColor: %s;\n", $banner_badge['button2_hover_border_color']);
				}
			}
		echo "}\n\n";
			
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
			wp_enqueue_style( 'didact-gothic', 'https://fonts.googleapis.com/css2?family=Didact+Gothic&display=swap', [], PROOFRATINGS_VERSION);
			wp_enqueue_style( 'proofratings-frontend', PROOFRATINGS_PLUGIN_URL . '/assets/css/proofratings.css', ['wp-color-picker'], PROOFRATINGS_VERSION);
			
			$upload_dir = wp_upload_dir();
			$generated_css = $upload_dir['basedir'] . '/proofratings-generated.css';
			if ( file_exists($generated_css) ) {
				wp_enqueue_style( 'proofratings-generated', $upload_dir['baseurl'] . '/proofratings-generated.css', [], filemtime($generated_css));	
			}

			wp_enqueue_style( 'proofratings', PROOFRATINGS_PLUGIN_URL . '/assets/css/proofratings-admin.css', ['wp-color-picker'], PROOFRATINGS_VERSION);
			wp_enqueue_script( 'proofratings', PROOFRATINGS_PLUGIN_URL . '/assets/js/proofratings-admin.js', ['jquery', 'wp-color-picker'], PROOFRATINGS_VERSION, true);
		}
	}


}
