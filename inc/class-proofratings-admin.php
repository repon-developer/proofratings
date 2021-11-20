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

		$this->analytics = include_once dirname( __FILE__ ) . '/class-proofratings-analytics.php';
		
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
		
		$widget_settings = wp_parse_args($postdata['proofratings_widget_settings'], [
			'proofratings_font' => 'inherit',
		]);
		
		ob_start();

		if ( $widget_settings['proofratings_font'] ) {
			echo ":root {\n";
				printf("\t--proofratingsFont: %s;\n", $widget_settings['proofratings_font']);
			echo "}\n\n";
		}

		$badges_square = get_proofratings_badges_square();
		if ( $badges_square->customize == 'yes' ) {
			echo ".proofratings-widget.proofratings-widget-square {\n";
				if ( $badges_square->star_color ) {
					printf("\t--themeColor: %s;\n", $badges_square->star_color);
				}

				if ( $badges_square->text_color ) {
					printf("\t--textColor: %s;\n", $badges_square->text_color);
				}

				if ( $badges_square->background ) {
					printf("\tbackground-color: %s;\n", $badges_square->background);
				}

				if ( $badges_square->shadow == 'yes' ) {					
					if ( $badges_square->shadow_color ) {
						printf("\t--shadowColor: %s;\n", $badges_square->shadow_color);
					}
				}

			echo "}\n\n";

			if ( $badges_square->shadow == 'yes' ) {
				echo ".proofratings-widget.proofratings-widget-square:hover {\n";
					if ( $badges_square->star_color ) {
						printf("\t--borderColor: %s;\n", $badges_square->star_color);
					}

					if ( $badges_square->shadow_hover_color ) {
						printf("\t--shadowColor: %s;\n", $badges_square->shadow_hover_color);
					}
				echo "}\n\n";
			}

			if ( $badges_square->shadow == 'no' ) {
				echo ".proofratings-widget.proofratings-widget-square, .proofratings-widget.proofratings-widget-square:hover {\n";
					echo "\t--borderColor: transparent;\n";
					echo "\t--shadowColor: transparent;\n";
				echo "}\n\n";
			}
		}

		$badges_rectangle = get_proofratings_badges_rectangle();
		if ( $badges_rectangle->customize == 'yes' ) {
			echo ".proofratings-widget.proofratings-widget-rectangle {\n";
				if ( $badges_rectangle->star_color ) {
					printf("\t--themeColor: %s;\n", $badges_rectangle->star_color);
				}

				if ( $badges_rectangle->icon_color ) {
					printf("\t--iconColor: %s;\n", $badges_rectangle->icon_color);
				}

				if ( $badges_rectangle->text_color ) {
					printf("\t--textColor: %s;\n", $badges_rectangle->text_color);
				}

				if ( $badges_rectangle->review_count_textcolor ) {
					printf("\t--reviewCountTextColor: %s;\n", $badges_rectangle->review_count_textcolor);
				}

				if ( $badges_rectangle->background ) {
					printf("\tbackground-color: %s;\n", $badges_rectangle->background);
				}

				if ( $badges_rectangle->shadow == 'yes' ) {					
					if ( $badges_rectangle->shadow_color ) {
						printf("\t--shadowColor: %s;\n", $badges_rectangle->shadow_color);
					}
				}

			echo "}\n\n";

			if ( $badges_rectangle->shadow == 'yes' ) {
				echo ".proofratings-widget.proofratings-widget-rectangle:hover {\n";
					if ( $badges_rectangle->star_color ) {
						printf("\t--borderColor: %s;\n", $badges_rectangle->star_color);
					}

					if ( $badges_rectangle->shadow_hover_color ) {
						printf("\t--shadowColor: %s;\n", $badges_rectangle->shadow_hover_color);
					}
				echo "}\n\n";
			}

			if ( $badges_rectangle->shadow == 'no' ) {
				echo ".proofratings-widget.proofratings-widget-rectangle, .proofratings-widget.proofratings-widget-rectangle:hover {\n";
					echo "\t--borderColor: transparent;\n";
					echo "\t--shadowColor: transparent;\n";
				echo "}\n\n";
			}
		}

		$overall_rectangle = get_proofratings_overall_ratings_rectangle();
		if ( $overall_rectangle->customize == 'yes' ) {
			echo ".proofratings-badge.proofratings-badge-rectangle {\n";
				if ( $overall_rectangle->star_color ) {
					printf("\t--star_color: %s;\n", $overall_rectangle->star_color);
				}
				
				if ( $overall_rectangle->rating_color ) {
					printf("\t--rating_color: %s;\n", $overall_rectangle->rating_color);
				}

				if ( $overall_rectangle->review_text_color ) {
					printf("\t--review_text_color: %s;\n", $overall_rectangle->review_text_color);
				}

				if ( $overall_rectangle->review_background ) {
					printf("\t--review_background: %s;\n", $overall_rectangle->review_background);
				}

				if ( $overall_rectangle->background_color ) {
					printf("\t--background_color: %s;\n", $overall_rectangle->background_color);
				}

				if ( $overall_rectangle->shadow == 'no') {
					printf("\t--shadow_color: transparent!important;\n");
					printf("\t--shadow_hover: transparent!important;\n");
				} else {
					if ( $overall_rectangle->shadow_color ) {
						printf("\t--shadow_color: %s;\n", $overall_rectangle->shadow_color);
					}
					
					if ( $overall_rectangle->shadow_hover ) {
						printf("\t--shadow_hover: %s;\n", $overall_rectangle->shadow_hover);
					}
				}
			echo "}\n\n";

			echo ".proofratings-badge.proofratings-badge-rectangle.badge-float {\n";
				if ( $overall_rectangle->shadow_color ) {
					printf("\t--shadow_color: %s;\n", $overall_rectangle->shadow_color);
				}
				
				if ( $overall_rectangle->shadow_hover ) {
					printf("\t--shadow_hover: %s;\n", $overall_rectangle->shadow_hover);
				}
			echo "}\n\n";
		}

		$overall_narrow = get_proofratings_overall_ratings_narrow();
		if ( $overall_narrow->customize == 'yes' ) {
			echo ".proofratings-badge.proofratings-badge-narrow {\n";
				if ( $overall_narrow->star_color ) {
					printf("\t--star_color: %s;\n", $overall_narrow->star_color);
				}
				
				if ( $overall_narrow->rating_color ) {
					printf("\t--rating_color: %s;\n", $overall_narrow->rating_color);
				}

				if ( $overall_narrow->review_text_color ) {
					printf("\t--review_text_color: %s;\n", $overall_narrow->review_text_color);
				}

				if ( $overall_narrow->review_background ) {
					printf("\t--review_background: %s;\n", $overall_narrow->review_background);
				}

				if ( $overall_narrow->background_color ) {
					printf("\t--background_color: %s;\n", $overall_narrow->background_color);
				}

				if ( $overall_narrow->shadow == 'no') {
					printf("\t--shadow_color: transparent!important;\n");
					printf("\t--shadow_hover: transparent!important;\n");
				} else {
					if ( $overall_narrow->shadow_color ) {
						printf("\t--shadow_color: %s;\n", $overall_narrow->shadow_color);
					}
	
					if ( $overall_narrow->shadow_hover ) {
						printf("\t--shadow_hover: %s;\n", $overall_narrow->shadow_hover);
					}
				}

			echo "}\n\n";

			echo ".proofratings-badge.proofratings-badge-narrow.badge-float {\n";
				if ( $overall_narrow->shadow_color ) {
					printf("\t--shadow_color: %s;\n", $overall_narrow->shadow_color);
				}

				if ( $overall_narrow->shadow_hover ) {
					printf("\t--shadow_hover: %s;\n", $overall_narrow->shadow_hover);
				}
			echo "}\n\n";
		}
		

		$badges_popup = get_proofratings_badges_popup();
		if ( $badges_popup->customize == 'yes' ) {
			echo ".proofratings-badges-popup .proofratings-widget {\n";
				if ( $badges_popup->star_color ) {
					printf("\t--themeColor: %s;\n", $badges_popup->star_color);
				}

				if ( $badges_popup->text_color ) {
					printf("\t--reviewCountTextColor: %s;\n", $badges_popup->reviewCountTextColor);
				}

				if ( $badges_popup->review_text_background ) {
					printf("\t--review_text_background: %s;\n", $badges_popup->review_text_background);
				}

				if ( $badges_popup->view_review_color ) {
					printf("\t--view_review_color: %s;\n", $badges_popup->view_review_color);
				}

				if ( $badges_popup->rating_color ) {
					printf("\t--rating_color: %s;\n", $badges_popup->rating_color);
				}
			echo "}\n\n";
		}

		$banner_badge = get_proofratings_overall_ratings_cta_banner();

		if ($banner_badge->customize == 'yes' ) {
			echo ".proofratings-banner-badge {\n";
				if ( $banner_badge->star_color ) {
					printf("\t--star_color: %s;\n", $banner_badge->star_color);
				}

				if ( $banner_badge->number_review_text_color ) {
					printf("\t--reviewCountTextcolor: %s;\n", $banner_badge->number_review_text_color);
				}
				
				if ( $banner_badge->background_color ) {
					printf("\t--backgroundColor: %s;\n", $banner_badge->background_color);
				}

				if ( $banner_badge->rating_text_color ) {
					printf("\t--rating_text_color: %s;\n", $banner_badge->rating_text_color);
				}

				if ( $banner_badge->review_rating_background_color ) {
					printf("\t--review_rating_background_color: %s;\n", $banner_badge->review_rating_background_color);
				}
			echo "}\n\n";
		}

		echo ".proofratings-banner-badge .proofratings-button.button1 {\n";
			if ( $banner_badge->button1_shape == 'round' ) {
				printf("\t--radius: 100px;\n");
			}

			if ( $banner_badge->button1_textcolor ) {
				printf("\t--textColor: %s;\n", $banner_badge->button1_textcolor);
			}

			if ( $banner_badge->button1_hover_textcolor ) {
				printf("\t--textHoverColor: %s;\n", $banner_badge->button1_hover_textcolor);
			}

			if ( $banner_badge->button1_background_color ) {
				printf("\t--backgroundColor: %s;\n", $banner_badge->button1_background_color);
			}

			if ( $banner_badge->button1_hover_background_color ) {
				printf("\t--backgroundHoverColor: %s;\n", $banner_badge->button1_hover_background_color);
			}

			if ( $banner_badge->button1_border == 'yes' ) {
				if ( $banner_badge->button1_border_color ) {
					printf("\t--borderColor: %s;\n", $banner_badge->button1_border_color);
				}
				
				if ( $banner_badge->button1_hover_border_color ) {
					printf("\t--borderHoverColor: %s;\n", $banner_badge->button1_hover_border_color);
				}
			}
		echo "}\n\n";
		
		echo ".proofratings-banner-badge .proofratings-button.button2 {\n";
			if ( $banner_badge->button2_shape == 'round' ) {
				printf("\t--radius: 100px;\n");
			}

			if ( $banner_badge->button2_textcolor ) {
				printf("\t--textColor: %s;\n", $banner_badge->button2_textcolor);
			}

			if ( $banner_badge->button2_hover_textcolor ) {
				printf("\t--textHoverColor: %s;\n", $banner_badge->button2_hover_textcolor);
			}

			if ( $banner_badge->button2_background_color ) {
				printf("\t--backgroundColor: %s;\n", $banner_badge->button2_background_color);
			}

			if ( $banner_badge->button2_hover_background_color ) {
				printf("\t--backgroundHoverColor: %s;\n", $banner_badge->button2_hover_background_color);
			}

			if ( $banner_badge->button2_border == 'yes' ) {
				if ( $banner_badge->button2_border_color ) {
					printf("\t--borderColor: %s;\n", $banner_badge->button2_border_color);
				}
				
				if ( $banner_badge->button2_hover_border_color ) {
					printf("\t--borderHoverColor: %s;\n", $banner_badge->button2_hover_border_color);
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
		
		$setting_output = 'awaiting';

		$proofratings_status = get_proofratings_current_status();
		if ( !$proofratings_status || 'not_registered' == $proofratings_status->status) {
			$setting_output = 'account_inactive_output';
		}
		
		if (isset($proofratings_status->status) && $proofratings_status->status == 'active' ) {
			$setting_output = 'output';
		}

		add_menu_page(__('Proofratings', 'proofratings'), __('Proofratings', 'proofratings'), 'manage_options', 'proofratings', [$this->settings_page, $setting_output], 'dashicons-star-filled', 25);
		add_submenu_page('proofratings', __('Proofratings Analytics', 'proofratings'), __('Analytics', 'proofratings'), 'manage_options', 'proofratings-analytics', [$this->analytics, 'output']);
	}

	/**
	 * Enqueues CSS and JS assets.
	 */
	public function admin_enqueue_scripts() {	
		$screen = get_current_screen();
		if ( in_array( $screen->id, [ 'toplevel_page_proofratings', 'proofratings_page_proofratings-analytics' ] ) ) {
			wp_enqueue_style( 'didact-gothic', 'https://fonts.googleapis.com/css2?family=Didact+Gothic&display=swap', [], PROOFRATINGS_VERSION);
			wp_enqueue_style( 'proofratings-frontend', PROOFRATINGS_PLUGIN_URL . '/assets/css/proofratings.css', ['wp-color-picker'], PROOFRATINGS_VERSION);
			wp_enqueue_style( 'proofratings', PROOFRATINGS_PLUGIN_URL . '/assets/css/proofratings-admin.css', ['wp-color-picker'], PROOFRATINGS_VERSION);
			wp_enqueue_script( 'proofratings', PROOFRATINGS_PLUGIN_URL . '/assets/js/proofratings-admin.js', ['jquery', 'wp-color-picker'], PROOFRATINGS_VERSION, true);
		}
	}
}
