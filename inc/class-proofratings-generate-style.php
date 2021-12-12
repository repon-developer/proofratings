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
		if ( $location->settings->font ) {
			printf("#proofratings-widgets-%d .proofratings-widget.proofratings-widget-%s {\n", $location->id, $type);
				printf("\tfont-family: %s!important;\n", $location->settings->font);
			echo "}\n\n";
		}

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
		if ( $location->settings->font ) {
			printf("#proofratings-badge-%d.proofratings-badge.proofratings-badge-rectangle.%s {\n", $location->id, $type_class);
				printf("\tfont-family: %s!important;\n", $location->settings->font);
			echo "}\n\n";
		}

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
		if ( $location->settings->font ) {
			printf("#proofratings-badge-%d.proofratings-badge.proofratings-badge-narrow.%s {\n", $location->id, $type_class);
				printf("\tfont-family: %s!important;\n", $location->settings->font);
			echo "}\n\n";
		}

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
	 * Generate styles overall popup badges
	 */
	public function overall_popup_badges($location) {
		if ( $location->settings->font ) {
			printf(".proofratings-badges-popup.proofratings-badges-popup-%s .proofratings-widget {\n", $location->id);
				printf("\tfont-family: %s!important;\n", $location->settings->font);
			echo "}\n\n";
		}
		
		$badges_popup = new Proofratings_Site_Data($location->settings->overall_popup);
		if ( !$badges_popup->customize) {
			return;
		}

		printf(".proofratings-badges-popup.proofratings-badges-popup-%s .proofratings-widget {\n", $location->id);
			if ( $badges_popup->star_color ) {
				printf("\t--themeColor: %s;\n", $badges_popup->star_color);
			}

			if ( $badges_popup->review_text_color ) {
				printf("\t--reviewCountTextColor: %s;\n", $badges_popup->review_text_color);
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

	/**
	 * Generate styles overall banner badges
	 */
	public function overall_banner_badges($location) {
		$banner_badge = new Proofratings_Site_Data($location->settings->overall_cta_banner);

		if ( $location->settings->font ) {
			printf(".proofratings-banner-badge.proofratings-banner-badge-%s {\n", $location->id);
				printf("\tfont-family: %s!important;\n", $location->settings->font);
			echo "}\n\n";
		}

		if ( $banner_badge->customize) {			
			printf(".proofratings-banner-badge.proofratings-banner-badge-%s {\n", $location->id);

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

		if ( isset($banner_badge->button1) ) {
			$button1 = new Proofratings_Site_Data($banner_badge->button1);

			printf(".proofratings-banner-badge.proofratings-banner-badge-%s .proofratings-button.button1 {\n", $location->id);
				if ( $button1->textcolor ) {
					printf("\t--textColor: %s;\n", $button1->textcolor);
				}

				if ( $button1->hover_textcolor ) {
					printf("\t--textHoverColor: %s;\n", $button1->hover_textcolor);
				}

				if ( $button1->background_color ) {
					printf("\t--backgroundColor: %s;\n", $button1->background_color);
				}

				if ( $button1->hover_background_color ) {
					printf("\t--backgroundHoverColor: %s;\n", $button1->hover_background_color);
				}

				if ( $button1->border ) {
					if ( $button1->border_color ) {
						printf("\t--borderColor: %s;\n", $button1->border_color);
					}
					
					if ( $button1->hover_border_color ) {
						printf("\t--borderHoverColor: %s;\n", $button1->hover_border_color);
					}
				}
			echo "}\n\n";
		}

		if ( isset($banner_badge->button2) ) {
			$button2 = new Proofratings_Site_Data($banner_badge->button2);

			printf(".proofratings-banner-badge.proofratings-banner-badge-%s .proofratings-button.button2 {\n", $location->id);
				if ( $button2->textcolor ) {
					printf("\t--textColor: %s;\n", $button2->textcolor);
				}

				if ( $button2->hover_textcolor ) {
					printf("\t--textHoverColor: %s;\n", $button2->hover_textcolor);
				}

				if ( $button2->background_color ) {
					printf("\t--backgroundColor: %s;\n", $button2->background_color);
				}

				if ( $button2->hover_background_color ) {
					printf("\t--backgroundHoverColor: %s;\n", $button2->hover_background_color);
				}

				if ( $button2->border ) {
					if ( $button2->border_color ) {
						printf("\t--borderColor: %s;\n", $button2->border_color);
					}
					
					if ( $button2->hover_border_color ) {
						printf("\t--borderHoverColor: %s;\n", $button2->hover_border_color);
					}
				}
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

		$this->overall_rectangle($location);
		$this->overall_rectangle($location, 'overall_rectangle_float');

		$this->overall_narrow($location);
		$this->overall_narrow($location, 'overall_narrow_float');
		
		$this->overall_popup_badges($location);
		$this->overall_banner_badges($location);

		$styles = ob_get_clean();
		file_put_contents(wp_upload_dir()['basedir'] . '/proofratings-generated.css', $styles);	
	}
}

return new Proofratings_Generate_Style();