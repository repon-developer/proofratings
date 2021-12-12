<?php
/**
 * File containing the class Proofratings_Generate_Style.
 *
 * @package proofratings
 * @since   1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generate style
 * @since 1.0.5
 */
class Proofratings_Generate_Style {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( "proofrating_location_save_settings", [ $this, 'generate_css' ]);
	}

	/**
	 * Generate styles for sites badge
	 */
	public function sites_badge($location, $slug = 'sites_square', $type = 'square') {
		$sites_badge = new Proofratings_Site_Data($location->settings->$slug);
		if ( !$sites_badge->customize) {
			return;
		}

		printf("#proofratings-widgets-%d .proofratings-widget.proofratings-widget-%s {\n", $location->id, $type);
			if ( $sites_badge->star_color ) {
				printf("\t--themeColor: %s;\n", $sites_badge->star_color);
			}

			if ( $sites_badge->icon_color ) {
				printf("\t--iconColor: %s;\n", $sites_badge->icon_color);
			}

			if ( $sites_badge->textcolor ) {
				printf("\t--textColor: %s;\n", $sites_badge->textcolor);
			}

			if ( $sites_badge->review_color_textcolor ) {
				printf("\t--reviewCountTextColor: %s;\n", $sites_badge->review_color_textcolor);
			}

			if ( $sites_badge->background_color ) {
				printf("\tbackground-color: %s;\n", $sites_badge->background_color);
			}

			if ( isset($sites_badge->border['show']) && $sites_badge->border['show']) {
				if ( $sites_badge->border['color'] ) {
					printf("\t--borderColor: %s;\n", $sites_badge->border['color']);
				}

				if ( $sites_badge->border['hover'] ) {
					printf("\t--borderHoverColor: %s;\n", $sites_badge->border['hover']);
				}
			}

			if ( isset($sites_badge->border['show']) && !$sites_badge->border['show'] ) {
				print("\tborder: none!important;\n");
			}

			if ( isset($sites_badge->shadow['shadow']) && $sites_badge->shadow['shadow'] ) {
				if ( $sites_badge->shadow['color'] ) {
					printf("\t--shadowColor: %s;\n", $sites_badge->shadow['color']);
				}
			}
		echo "}\n\n";

		if ( isset($sites_badge->shadow['shadow']) && $sites_badge->shadow['shadow'] ) {
			printf("#proofratings-widgets-%d .proofratings-widget.proofratings-widget-%s:hover {\n", $location->id, $type);
				if ( $sites_badge->shadow['hover'] ) {
					printf("\t--shadowColor: %s;\n", $sites_badge->shadow['hover']);
				}
			echo "}\n\n";
		}

		if ( isset($sites_badge->shadow['shadow']) && !$sites_badge->shadow['shadow'] ) {
			printf("#proofratings-widgets-%1\$d .proofratings-widget.proofratings-widget-%2\$s, #proofratings-widgets-%1\$d .proofratings-widget.proofratings-widget-%2\$s:hover {\n", $location->id, $type);
				echo "\t--shadowColor: transparent;\n";
			echo "}\n\n";
		}
	}

	/**
	 * Generate styles overall ratings
	 */
	public function overall_rectangle($location, $type = 'overall_rectangle_embed') {		
		$overall_badge = new Proofratings_Site_Data($location->settings->$type);
		if ( !$overall_badge->customize) {
			return;
		}

		$type_class = 'badge-embed';
		if ( $type === 'overall_rectangle_float') {
			$type_class = 'badge-float';
		}

		printf("#proofratings-badge-%d.proofratings-badge.proofratings-badge-rectangle.%s {\n", $location->id, $type_class);
			if ( $overall_badge->star_color ) {
				printf("\t--star_color: %s;\n", $overall_badge->star_color);
			}
			
			if ( $overall_badge->rating_color ) {
				printf("\t--rating_color: %s;\n", $overall_badge->rating_color);
			}

			if ( $overall_badge->review_text_color ) {
				printf("\t--review_text_color: %s;\n", $overall_badge->review_text_color);
			}

			if ( $overall_badge->review_background ) {
				printf("\t--review_background: %s;\n", $overall_badge->review_background);
			}

			if ( $overall_badge->background_color ) {
				printf("\t--background_color: %s;\n", $overall_badge->background_color);
			}

			if ( !isset($overall_badge->shadow) || !$overall_badge->shadow['shadow']) {
				printf("\t--shadow_color: transparent!important;\n");
				printf("\t--shadow_hover: transparent!important;\n");
			}

			if ( isset($overall_badge->shadow['shadow']) && $overall_badge->shadow['shadow'] ) {		
				if ( $overall_badge->shadow['color'] ) {
					printf("\t--shadow_color: %s;\n", $overall_badge->shadow['color']);
				}
				
				if ( $overall_badge->shadow['hover'] ) {
					printf("\t--shadow_hover: %s;\n", $overall_badge->shadow['hover']);
				}
			}
		echo "}\n\n";
	}

	/**
	 * Generate styles overall ratings narrow
	 */
	public function overall_narrow($location, $type = 'overall_narrow_embed') {		
		$overall_badge = new Proofratings_Site_Data($location->settings->$type);
		if ( !$overall_badge->customize) {
			return;
		}

		$type_class = 'badge-embed';
		if ( $type === 'overall_narrow_float') {
			$type_class = 'badge-float';
		}

		printf("#proofratings-badge-%d.proofratings-badge.proofratings-badge-narrow.%s {\n", $location->id, $type_class);
			if ( $overall_badge->star_color ) {
				printf("\t--star_color: %s;\n", $overall_badge->star_color);
			}
			
			if ( $overall_badge->rating_color ) {
				printf("\t--rating_color: %s;\n", $overall_badge->rating_color);
			}

			if ( $overall_badge->review_text_color ) {
				printf("\t--review_text_color: %s;\n", $overall_badge->review_text_color);
			}

			if ( $overall_badge->review_background ) {
				printf("\t--review_background: %s;\n", $overall_badge->review_background);
			}

			if ( $overall_badge->background_color ) {
				printf("\t--background_color: %s;\n", $overall_badge->background_color);
			}

			if ( !isset($overall_badge->shadow) || !$overall_badge->shadow['shadow']) {
				printf("\t--shadow_color: transparent!important;\n");
				printf("\t--shadow_hover: transparent!important;\n");
			}

			if ( isset($overall_badge->shadow['shadow']) && $overall_badge->shadow['shadow'] ) {		
				if ( $overall_badge->shadow['color'] ) {
					printf("\t--shadow_color: %s;\n", $overall_badge->shadow['color']);
				}
				
				if ( $overall_badge->shadow['hover'] ) {
					printf("\t--shadow_hover: %s;\n", $overall_badge->shadow['hover']);
				}
			}
		echo "}\n\n";
	}

	/**
	 * Generate styles 
	 */
	public function generate_css() {
		//Get location again - Must use
		get_proofratings()->locations->get_locations();

		$location = get_proofratings()->locations->items[1];		

		ob_start();
		$this->sites_badge($location);
		$this->sites_badge($location, 'sites_rectangle', 'rectangle');

		$this->overall_rectangle($location);
		$this->overall_rectangle($location, 'overall_rectangle_float');

		$this->overall_narrow($location);
		$this->overall_narrow($location, 'overall_narrow_float');

		
		


		$styles = ob_get_clean();
		file_put_contents(wp_upload_dir()['basedir'] . '/proofratings-generated.css', $styles);	

		return;

		if ( $widget_settings['proofratings_font'] ) {
			echo ":root {\n";
				printf("\t--proofratingsFont: %s;\n", $widget_settings['proofratings_font']);
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
}

return new Proofratings_Generate_Style();