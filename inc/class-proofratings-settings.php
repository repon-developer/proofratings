<?php
/**
 * File containing the class WP_ProofRatings_Settings.
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
class WP_ProofRatings_Settings {
	/**
	 * The single instance of the class.
	 * @var self
	 * @since  1.0.1
	 */
	private static $instance = null;

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
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
		$this->settings_group = 'proofratings';
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	/**
	 * Registers the plugin's settings with WordPress's Settings API.
	 */
	public function register_settings() {
		register_setting( $this->settings_group, 'proofratings_widget_settings' );
		register_setting( $this->settings_group, 'proofratings_display_badge' );
		register_setting( $this->settings_group, 'proofratings_badges_square' );
		register_setting( $this->settings_group, 'proofratings_badges_rectangle' );

		//settings for overall ratings		
		register_setting( $this->settings_group, 'proofratings_overall_rectangle' );

		register_setting( $this->settings_group, 'proofratings_settings' );
		register_setting( $this->settings_group, 'proofratings_floating_badge_settings' );
		register_setting( $this->settings_group, 'proofratings_banner_badge' );
	}

	public function get_banner_badge_settings() {
		return wp_parse_args((array)get_option( 'proofratings_banner_badge'), [
			'show' => 'yes',
			'tablet' => 'yes',
			'close_button' => 'yes',
			'mobile' => 'yes',
			'star_color' => '',
			'top_shadow' => 'yes',
			'background_color' => '',
			'rating_text_color' => '',
			'review_rating_background_color' => '',
			'number_review_text_color' => '',

			'button1_text' => '',
			'button1_url' => '',
			'button1_blank' => 'no',
			'button1_textcolor' => '',
			'button1_hover_textcolor' => '',
			'button1_shape' => 'rectangle',
			'button1_background_color' => '',
			'button1_hover_background_color' => '',
			'button1_border' => 'yes',
			'button1_border_color' => '',
			'button1_hover_border_color' => '',
			
			'button2' => 'no',
			'button2_text' => '',
			'button2_url' => '',
			'button2_blank' => 'no',
			'button2_textcolor' => '',
			'button2_shape' => '',
			'button2_background_color' => '',
			'button2_border' => 'yes',
			'button2_border_color' => '',
			'button2_hover_textcolor' => '',
			'button2_hover_background_color' => '',
			'button2_hover_border_color' => '',
		]);
	}

	/**
	 * Shows the plugin's settings page.
	 */
	public function output() {
		?>
		<div class="wrap proofratings-settings-wrap">
			<h1 class="wp-heading-inline"><?php _e('Proofratings Settings', 'proofratings') ?></h1>
			<hr class="wp-header-end">

			<form class="proofratings-options" method="post" action="options.php">

				<?php settings_fields( $this->settings_group ); ?>

				<?php
				if ( ! empty( $_GET['settings-updated'] ) ) {
					echo '<div class="updated fade"><p>' . esc_html__( 'Settings successfully saved', 'proofratings' ) . '</p></div>';
				}

				$proofratings_status = get_proofratings_current_status();
				if ( !$proofratings_status || 'not_registered' == $proofratings_status->status ) {
					echo '<div class="proofratings-status">';
					printf('<p>You have not registered your site. For register, we will collect your website name, admin email, and domain. <a href="%s">Register now</a></p>', add_query_arg(['_regsiter_nonce' => wp_create_nonce( 'register_proofratings' )], menu_page_url('proofratings', false)) );
					echo '</div>';

				} else if ( in_array($proofratings_status->status, ['pending', 'pause', 'suspend', 'no_sheetid']) ) {
					echo '<div class="proofratings-status">';
						if ($proofratings_status->status == 'suspend') {
							printf('<p>'. __('Your application has been suspended.', 'proofratings') .'</p>');
						} else {							
							printf('<p>%s</p>', esc_html__($proofratings_status->message));
						}
					echo '</div>';
				}

				$widget_settings = wp_parse_args(get_option( 'proofratings_widget_settings'), [
					'proofratings_font' => 'inherit',
					'badge_style' => 'style1'
				]);


				$display_badges = get_proofratings_display_settings();

				$proofratings_settings = get_proofratings_settings(); ?>

				<h2 class="nav-tab-wrapper">
					<a href="#settings-review-sites" class="nav-tab"><?php _e('Review Sites', 'proofratings'); ?></a>
					<a href="#settings-badges" class="nav-tab"><?php _e('Badges', 'proofratings'); ?></a>

					<a href="#settings-badge-square" class="nav-tab" style="display:none"><?php _e('Sites (Square)', 'proofratings'); ?></a>
					<a href="#settings-badge-rectangle" class="nav-tab" style="display:none"><?php _e('Sites (Rectangle)', 'proofratings'); ?></a>
					<a href="#settings-overall-rating-rectangle" class="nav-tab" style="display:none"><?php _e('Overall Rating (Rectangle)', 'proofratings'); ?></a>

					<!-- <a href="#settings-banner-badge" class="nav-tab"><?php _e('Banner Badge', 'proofratings'); ?></a> -->
				</h2>

				<div id="settings-review-sites" class="settings_panel">
					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Font Family', 'proofratings') ?></th>
							<td>
								<select name="proofratings_widget_settings[proofratings_font]">
									<option value="Didact Gothic" <?php selected('Didact Gothic', $widget_settings['proofratings_font']) ?>><?php _e( 'Didact Gothic', 'proofratings') ?></option>
								</select>
							</td>
						</tr>
					</table>

					<h2><?php _e('General Review Sites', 'proofratings') ?></h2>
					<?php get_proofratings_review_sites('general'); ?>

					<h2><?php _e('Home Services Review Sites', 'proofratings') ?></h2>
					<?php get_proofratings_review_sites('home-service'); ?>

					<h2><?php _e('Solar Review Sites', 'proofratings') ?></h2>
					<?php get_proofratings_review_sites('solar'); ?>

					<h2><?php _e('SaaS/Software Review Sites', 'proofratings') ?></h2>
					<?php get_proofratings_review_sites('software'); ?>
				</div>

				<div id="settings-badges" class="settings_panel">
					<table class="form-table">
						<tr>
							<th scope="row" style="vertical-align:middle"><?php _e('Sites (Square)', 'proofratings') ?></th>
							<td>
								<div class="proofratings-image-option">
									<img src="<?php echo PROOFRATINGS_PLUGIN_URL; ?>/assets/images/widget-style1.png" alt="Proofratings style">
									<label data-tab-button="#settings-badge-square">
										<input name="proofratings_display_badge[square]" class="checkbox-switch checkbox-onoff" value="yes" type="checkbox" <?php checked( 'yes', $display_badges['square'] ) ?>>
										<?php _e('Embed only', 'proofratings') ?>
									</label>
								</div>
							</td>
						</tr>

						<tr>
							<th scope="row" style="vertical-align:middle"><?php _e('Sites (Rectangle)', 'proofratings') ?></th>
							<td>
								<div class="proofratings-image-option">
									<img src="<?php echo PROOFRATINGS_PLUGIN_URL; ?>/assets/images/widget-style2.png" alt="Proofratings style">
									<label data-tab-button="#settings-badge-rectangle">
										<input name="proofratings_display_badge[rectangle]" class="checkbox-switch checkbox-onoff" value="yes" type="checkbox" <?php checked( 'yes', $display_badges['rectangle'] ) ?>>
										<?php _e('Embed only', 'proofratings') ?>
									</label>
								</div>
							</td>
						</tr>

						<tr>
							<th scope="row" style="vertical-align:middle"><?php _e('Overall Rating (Rectangle)', 'proofratings') ?></th>
							<td>
								<div class="proofratings-image-option">
									<img src="<?php echo PROOFRATINGS_PLUGIN_URL; ?>/assets/images/floating-badge-style1.png" alt="Proofratings style">
									<label data-tab-button="#settings-overall-rating-rectangle">
										<input name="proofratings_display_badge[overall_ratings_rectangle]" class="checkbox-switch checkbox-onoff" value="yes" type="checkbox" <?php checked( 'yes', $display_badges['overall_ratings_rectangle'] ) ?>>
										<?php _e('Embed and/or float', 'proofratings') ?>
									</label>
								</div>
							</td>
						</tr>

						<tr>
							<th scope="row" style="vertical-align:middle"><?php _e('Overall Rating (Narrow)', 'proofratings') ?></th>
							<td>
								<div class="proofratings-image-option">
									<img src="<?php echo PROOFRATINGS_PLUGIN_URL; ?>/assets/images/floating-badge-style2.png" alt="Proofratings style">
									<label>
										<input name="proofratings_display_badge[overall_ratings_narrow]" class="checkbox-switch checkbox-onoff" value="yes" type="checkbox" <?php checked( 'yes', $display_badges['overall_ratings_narrow'] ) ?>>
										<?php _e('Embed and/or float', 'proofratings') ?>
									</label>
								</div>
							</td>
						</tr>

						<tr>
							<th scope="row" style="vertical-align:middle"><?php _e('Overall Rating CTA Banner', 'proofratings') ?></th>
							<td>
								<div class="proofratings-image-option">
									<img src="<?php echo PROOFRATINGS_PLUGIN_URL; ?>/assets/images/cta-badge.png" alt="Proofratings style">
									<label>
										<input name="proofratings_display_badge[overall_ratings_cta]" class="checkbox-switch checkbox-onoff" value="yes" type="checkbox" <?php checked( 'yes', $display_badges['overall_ratings_cta'] ) ?>>
										<?php _e('Float only', 'proofratings') ?>
									</label>
								</div>
							</td>
						</tr>
					</table>
				</div>

				<div id="settings-badge-square" class="settings_panel" style="display:none">
					<?php include PROOFRATINGS_PLUGIN_DIR . '/templates/settings-badge-square.php' ?>
				</div>

				<div id="settings-badge-rectangle" class="settings_panel" style="display:none">
					<?php include PROOFRATINGS_PLUGIN_DIR . '/templates/settings-badge-rectangle.php' ?>
				</div>

				<div id="settings-overall-rating-rectangle" class="settings_panel" style="display:none">
					<?php include PROOFRATINGS_PLUGIN_DIR . '/templates/overall-rating-rectangle.php' ?>
				</div>


				<div id="settings-banner-badge" class="settings_panel" style="display:none">
					<?php 
						$banner_badge = $this->get_banner_badge_settings(); ?>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Banner Visibility', 'proofratings') ?></th>
							<td>
								<label>
									<input name="proofratings_banner_badge[show]" value="no" type="hidden">
									<input class="checkbox-switch" name="proofratings_banner_badge[show]" value="yes" type="checkbox" <?php checked( 'yes', $banner_badge['show'] ) ?>>
									<?php _e('Show/Hide on desktop', 'proofratings'); ?>
								</label>
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Tablet Visibility', 'proofratings') ?></th>
							<td>
								<label>
									<input name="proofratings_banner_badge[tablet]" value="no" type="hidden">
									<input class="checkbox-switch" name="proofratings_banner_badge[tablet]" value="yes" type="checkbox" <?php checked( 'yes', $banner_badge['tablet'] ) ?>>
									<?php _e('Show/Hide on tablet', 'proofratings'); ?>
								</label>
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Mobile Visibility', 'proofratings') ?></th>
							<td>
								<label>
									<input name="proofratings_banner_badge[mobile]" value="no" type="hidden">
									<input class="checkbox-switch" name="proofratings_banner_badge[mobile]" value="yes" type="checkbox" <?php checked( 'yes', $banner_badge['mobile'] ) ?>>
									<?php _e('Show/Hide on mobile', 'proofratings'); ?>
								</label>
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Close option', 'proofratings') ?></th>
							<td>
								<label>
									<input name="proofratings_banner_badge[close_button]" value="no" type="hidden">
									<input class="checkbox-switch" name="proofratings_banner_badge[close_button]" value="yes" type="checkbox" <?php checked( 'yes', $banner_badge['close_button'] ) ?>>
								</label>
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Star Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_banner_badge[star_color]"
									value="<?php esc_attr_e($banner_badge['star_color']) ?>" data-default-color="#212A3D">
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Top Shadow', 'proofratings') ?></th>
							<td>
								<input name="proofratings_banner_badge[top_shadow]" value="no" type="hidden">
								<input class="checkbox-switch" name="proofratings_banner_badge[top_shadow]" value="yes" type="checkbox" <?php checked( 'yes', $banner_badge['top_shadow'] ) ?>>
							</td>
						</tr>

						<tr id="banner-badge-shadow-color">
							<th scope="row"><?php _e('Background Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_banner_badge[background_color]"
									value="<?php esc_attr_e($banner_badge['background_color']) ?>" data-default-color="#f6d300">
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Rating Text Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_banner_badge[rating_text_color]"
									value="<?php esc_attr_e($banner_badge['rating_text_color']) ?>" data-default-color="#377dbc">
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Review Rating Background Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_banner_badge[review_rating_background_color]" 
									value="<?php esc_attr_e($banner_badge['review_rating_background_color']) ?>" data-default-color="#fff">
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Number of Review Text Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_banner_badge[number_review_text_color]" 
									value="<?php esc_attr_e($banner_badge['number_review_text_color']) ?>">
							</td>
						</tr>
					</table>

					<h2><?php _e('Call-to-action Button', 'proofratings') ?></h2>
					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Button 1 Text', 'proofratings') ?></th>
							<td>
								<input type="text" name="proofratings_banner_badge[button1_text]" value="<?php esc_attr_e($banner_badge['button1_text']) ?>">
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Button1 URL', 'proofratings') ?></th>
							<td>
								<input type="text" name="proofratings_banner_badge[button1_url]" value="<?php esc_attr_e($banner_badge['button1_url']) ?>">
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Open in new tab', 'proofratings') ?></th>
							<td>
								<label>
									<input name="proofratings_banner_badge[button1_blank]" value="no" type="hidden">
									<input class="checkbox-switch checkbox-onoff" name="proofratings_banner_badge[button1_blank]" value="yes" type="checkbox" <?php checked( 'yes', $banner_badge['button1_blank'] ) ?>>
								</label>
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Button1 Text Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_banner_badge[button1_textcolor]"
									value="<?php esc_attr_e($banner_badge['button1_textcolor']) ?>">
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Button1 Background Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_banner_badge[button1_background_color]"
									value="<?php esc_attr_e($banner_badge['button1_background_color']) ?>">
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Button1 Shape', 'proofratings') ?></th>
							<td>
								<label>
									<input name="proofratings_banner_badge[button1_shape]" value="round" type="hidden">
									<input class="checkbox-switch checkbox-shape" name="proofratings_banner_badge[button1_shape]" value="rectangle" type="checkbox" <?php checked( 'rectangle', $banner_badge['button1_shape'] ) ?>>
								</label>
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Button1 Border', 'proofratings') ?></th>
							<td>
								<input  name="proofratings_banner_badge[button1_border]" value="no" type="hidden">
								<label>
									<input class="checkbox-switch" name="proofratings_banner_badge[button1_border]" value="yes" type="checkbox" <?php checked( 'yes', $banner_badge['button1_border'] ) ?>>
								</label>
							</td>
						</tr>
						
						<tr id="button1-border-color">
							<th scope="row"><?php _e('Button1 Border Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text"  name="proofratings_banner_badge[button1_border_color]" value="<?php esc_attr_e($banner_badge['button1_border_color']) ?>">
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Button1 Hover Text Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_banner_badge[button1_hover_textcolor]"
									value="<?php esc_attr_e($banner_badge['button1_hover_textcolor']) ?>">
							</td>
						</tr>
						
						<tr>
							<th scope="row"><?php _e('Button1 Hover Background Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_banner_badge[button1_hover_background_color]"
									value="<?php esc_attr_e($banner_badge['button1_hover_background_color']) ?>">
							</td>
						</tr>

						<tr id="button1-border-hover-color">
							<th scope="row"><?php _e('Button1 Hover Border Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_banner_badge[button1_hover_border_color]"
									value="<?php esc_attr_e($banner_badge['button1_hover_border_color']) ?>">
							</td>
						</tr>


						<!-- Button 2 settings -->
						<tr>
							<th scope="row"><?php _e('Button2', 'proofratings') ?></th>
							<td>
								<label>
									<input class="checkbox-switch" name="proofratings_banner_badge[button2]" value="yes" type="checkbox" <?php checked( 'yes', $banner_badge['button2'] ) ?>>
								</label>
							</td>
						</tr>

						<tbody id="cta-button2-options">
							<tr>
								<th scope="row"><?php _e('Button2 Text', 'proofratings') ?></th>
								<td>
									<input type="text" name="proofratings_banner_badge[button2_text]" value="<?php esc_attr_e($banner_badge['button2_text']) ?>">
								</td>
							</tr>

							<tr>
								<th scope="row"><?php _e('Button2 URL', 'proofratings') ?></th>
								<td>
									<input type="text" name="proofratings_banner_badge[button2_url]" value="<?php esc_attr_e($banner_badge['button2_url']) ?>">
								</td>
							</tr>

							<tr>
							<th scope="row"><?php _e('Open in new tab', 'proofratings') ?></th>
							<td>
								<label>
									<input name="proofratings_banner_badge[button2_blank]" value="no" type="hidden">
									<input class="checkbox-switch checkbox-onoff" name="proofratings_banner_badge[button2_blank]" value="yes" type="checkbox" <?php checked( 'yes', $banner_badge['button2_blank'] ) ?>>
								</label>
							</td>
						</tr>

							<tr>
								<th scope="row"><?php _e('Button2 Text Color', 'proofratings') ?></th>
								<td>
									<input class="proofratings-color-field" type="text" 
										name="proofratings_banner_badge[button2_textcolor]"
										value="<?php esc_attr_e($banner_badge['button2_textcolor']) ?>">
								</td>
							</tr>

							<tr>
								<th scope="row"><?php _e('Button2 Background Color', 'proofratings') ?></th>
								<td>
									<input class="proofratings-color-field" type="text" 
										name="proofratings_banner_badge[button2_background_color]"
										value="<?php esc_attr_e($banner_badge['button2_background_color']) ?>">
								</td>
							</tr>

							<tr>
								<th scope="row"><?php _e('Button2 Shape', 'proofratings') ?></th>
								<td>
									<label>
										<input name="proofratings_banner_badge[button2_shape]" value="round" type="hidden">
										<input class="checkbox-switch checkbox-shape" name="proofratings_banner_badge[button2_shape]" value="rectangle" type="checkbox" <?php checked( 'rectangle', $banner_badge['button2_shape'] ) ?>>
									</label>
								</td>
							</tr>

							<tr>
								<th scope="row"><?php _e('Button2 Border', 'proofratings') ?></th>
								<td>
									<label>
										<input name="proofratings_banner_badge[button2_border]" value="no" type="hidden">
										<input class="checkbox-switch" name="proofratings_banner_badge[button2_border]" value="yes" type="checkbox" <?php checked( 'yes', $banner_badge['button2_border'] ) ?>>
									</label>
								</td>
							</tr>
							
							<tr id="button2-border-color">
								<th scope="row"><?php _e('Button2 Border Color', 'proofratings') ?></th>
								<td>
									<input class="proofratings-color-field" type="text"  name="proofratings_banner_badge[button2_border_color]" value="<?php esc_attr_e($banner_badge['button2_border_color']) ?>">
								</td>
							</tr>

							<tr>
								<th scope="row"><?php _e('Button2 Hover Text Color', 'proofratings') ?></th>
								<td>
									<input class="proofratings-color-field" type="text" 
										name="proofratings_banner_badge[button2_hover_textcolor]"
										value="<?php esc_attr_e($banner_badge['button2_hover_textcolor']) ?>">
								</td>
							</tr>
							
							<tr>
								<th scope="row"><?php _e('Button2 Hover Background Color', 'proofratings') ?></th>
								<td>
									<input class="proofratings-color-field" type="text" 
										name="proofratings_banner_badge[button2_hover_background_color]"
										value="<?php esc_attr_e($banner_badge['button2_hover_background_color']) ?>">
								</td>
							</tr>

							<tr id="button2-border-hover-color">
								<th scope="row"><?php _e('Button2 Hover Border Color', 'proofratings') ?></th>
								<td>
									<input class="proofratings-color-field" type="text" 
										name="proofratings_banner_badge[button2_hover_border_color]"
										value="<?php esc_attr_e($banner_badge['button2_hover_border_color']) ?>">
								</td>
							</tr>
						</tbody>
					</table>

					<table class="form-table">
						<?php foreach (get_pages() as $page) : ?>
						<tr>
							<th scope="row"><?php echo $page->post_title ?></th>
							<td>	
							<?php
								$checked = !isset($banner_badge['on_pages'][$page->ID]) || $banner_badge['on_pages'][$page->ID] == 'yes'? 'checked' : '';
								printf('<input name="proofratings_banner_badge[on_pages][%s]" value="no" type="hidden">', $page->ID);
								printf(
									'<label><input class="checkbox-switch" name="proofratings_banner_badge[on_pages][%s]" value="yes" %s type="checkbox"></label>',
									$page->ID, $checked
								);
							?>
							</td>
						</tr>
						<?php endforeach; ?>
					</table>					
				</div>

				<p class="submit">
					<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'proofratings' ); ?>" />
				</p>
			</form>

			<p class="review-us">Enjoying Proofratings? ❤️ Review us <a href="https://wordpress.org/plugins/proofratings/" target="_blank">here</a></p>
		</div>
		<?php
	}
}