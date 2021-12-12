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
	 * Generate styles 
	 */
	public function sites_badge($location, $slug = 'sites_square', $type = 'square') {
		$sites_badge = new Proofratings_Site_Data($location->settings->$slug);
		if ( !$sites_badge->customize) {
			return;
		}

		error_log(print_r($sites_badge, true));

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
	 * Generate styles 
	 */
	public function generate_css() {
		//Get location again - Must use
		get_proofratings()->locations->get_locations();

		$location = get_proofratings()->locations->items[1];


		

		ob_start();		

		$this->sites_badge($location);
		$this->sites_badge($location, 'sites_rectangle', 'rectangle');
		


		$styles = ob_get_clean();
		file_put_contents(wp_upload_dir()['basedir'] . '/proofratings-generated.css', $styles);	

		return;

		if ( $widget_settings['proofratings_font'] ) {
			echo ":root {\n";
				printf("\t--proofratingsFont: %s;\n", $widget_settings['proofratings_font']);
			echo "}\n\n";
		}

		$badges_square = get_proofratings_badges_square();
		if ( $badges_square->customize == 'yes' ) {
			echo ".proofratings-widget.proofratings-widget-square {\n";
				if ( $badges_square->logo_color ) {
					printf("\t--logoColor: %s;\n", $badges_square->logo_color);
				}

				if ( $badges_square->star_color ) {
					printf("\t--themeColor: %s;\n", $badges_square->star_color);
				}

				if ( $badges_square->text_color ) {
					printf("\t--textColor: %s;\n", $badges_square->text_color);
				}

				if ( $badges_square->background ) {
					printf("\tbackground-color: %s;\n", $badges_square->background);
				}

				if ( $badges_square->border != 'no' ) {
					if ( $badges_square->border_color ) {
						printf("\t--borderColor: %s;\n", $badges_square->border_color);
					}

					if ( $badges_square->border_hover_color ) {
						printf("\t--borderHoverColor: %s;\n", $badges_square->border_hover_color);
					}
				}

				if ( $badges_square->border == 'no' ) {
					print("\tborder: none!important;\n");
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

				if ( $badges_rectangle->border != 'no' ) {
					if ( $badges_rectangle->border_color ) {
						printf("\t--borderColor: %s;\n", $badges_rectangle->border_color);
					}

					if ( $badges_rectangle->border_hover_color ) {
						printf("\t--borderHoverColor: %s;\n", $badges_rectangle->border_hover_color);
					}
				}

				if ( $badges_rectangle->border == 'no' ) {
					print("\tborder: none!important;\n");
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
}

return new Proofratings_Generate_Style();