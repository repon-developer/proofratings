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
		register_setting( $this->settings_group, 'proofratings_settings' );
		register_setting( $this->settings_group, 'proofratings_floating_badge_settings' );
		register_setting( $this->settings_group, 'proofratings_banner_badge' );
	}

	public function get_floating_badge_settings() {
		return wp_parse_args((array)get_option( 'proofratings_floating_badge_settings'), [
			'float' => 'yes',
			'tablet' => 'yes',
			'mobile' => 'yes',
			'on_pages' => [],
			'close_button' => 'yes',
			'position' => '',
			'star_color' => '',
			'shadow' => 'yes',
			'shadow_color' => '',
			'shadow_hover' => '',
			'background_color' => '',
			'review_text_color' => '',
			'review_background' => ''
		]);
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

				$proofratings_settings = get_proofratings_settings(); ?>

				<h2 class="nav-tab-wrapper">
					<a href="#settings-review-sites" class="nav-tab"><?php _e('Review Sites', 'proofratings'); ?></a>
					<a href="#settings-embeddable-badges" class="nav-tab"><?php _e('Embeddable Badges', 'proofratings'); ?></a>
					<a href="#settings-floating-badge" class="nav-tab"><?php _e('Floating Badge', 'proofratings'); ?></a>
					<a href="#settings-banner-badge" class="nav-tab"><?php _e('Banner Badge', 'proofratings'); ?></a>
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

					<?php
					echo '<div class="review-sites-checkboxes">';
					foreach ($proofratings_settings as $key => $site) {
						printf(
							'<label class="checkbox-review-site" data-site="%1$s"><input type="checkbox" name="proofratings_settings[%1$s][active]" value="yes" %3$s /><img src="%2$s" alt="%1$s" /></label>', 
							$key, esc_attr($site->logo), checked('yes', $site->active, false)
						);
					}
					echo '</div>'; ?>
				</div>

				<div id="settings-embeddable-badges" class="settings_panel" style="display:none">
					<table class="form-table">
						<tr>
							<th scope="row" style="vertical-align:middle"><?php _e('Badge Type', 'proofratings') ?></th>
							<td>
								<div class="proofratings-styles">
									<select id="proofratings_widget_style">
										<option value="style1" data-img="<?php echo PROOFRATINGS_PLUGIN_URL; ?>/assets/images/widget-style1.png"><?php _e('Style 1', 'proofratings'); ?></option>
										<option value="style2" data-img="<?php echo PROOFRATINGS_PLUGIN_URL; ?>/assets/images/widget-style2.png"><?php _e('Style 2', 'proofratings'); ?></option>
									</select>

									<img src="<?php echo PROOFRATINGS_PLUGIN_URL; ?>/assets/images/widget-style1.png" alt="Proofratings style">
								</div>
							</td>
						</tr>

						<tr>
							<th scope="row" style="vertical-align:middle">
								<?php _e('Shortcode', 'proofratings') ?>
								<p class="description" style="font-weight: normal">Use shortcode where you want to display review widgets</p>
							</th>
							<td>
								<code class="shortocde-area" id="proofratings-widgets-shortcode">[proofratings_widgets]</code>
							</td>
						</tr>
					</table>

					<?php
					foreach ($proofratings_settings as $key => $site) {
						printf('<fieldset id="review-site-settings-%s" class="fieldset-site-review">', $key);
							if ( $site->title) {
								echo '<h2 class="title">'. esc_html($site->title).'</h2>';
							}

							echo '<table class="form-table form-table-review-sites settings">';
								echo '<tr>';
									echo '<th scope="row"><label for="mailserver_url">Theme Color</label></th>';
									printf('<td><input class="proofratings-color-field" name="proofratings_settings[%1$s][theme_color]" type="text" value="%2$s"></td>', $key, esc_attr($site->theme_color));
								echo '</tr>';

								echo '<tr>';
									echo '<th scope="row"><label>Text Color</label></th>';
									printf('<td><input class="proofratings-color-field" name="proofratings_settings[%1$s][text_color]" type="text" value="%2$s"></td>', $key, esc_attr($site->text_color));
								echo '</tr>';

								echo '<tr>';
									echo '<th scope="row"><label>Review count text color</label></th>';
									printf('<td><input class="proofratings-color-field" name="proofratings_settings[%1$s][review_count_textcolor]" type="text" value="%2$s"></td>', $key, esc_attr($site->review_count_textcolor));
								echo '</tr>';
								
								echo '<tr>';
									echo '<th scope="row"><label for="mailserver_url">Background Color</label></th>';
									printf('<td><input class="proofratings-color-field" name="proofratings_settings[%1$s][background]" type="text" value="%2$s"></td>', $key, esc_attr($site->background));
								echo '</tr>';

							echo '</table>';
							echo '<hr>';
						echo '</fieldset>';
					} ?>
				</div>

				<div id="settings-floating-badge" class="settings_panel" style="display:none">
					<?php $badge_settings = $this->get_floating_badge_settings(); ?>
					<table id="form-table-floating-badge" class="form-table">
						<tr>
							<th scope="row">
								<?php _e('Shortcode', 'proofratings') ?>
								<p class="description" style="font-weight: normal">Embed shortcode</p>
							</th>
							<td><code class="shortocde-area" id="floating-badge-shortcode">[proofratings_floating_badge badge_style="style1"]</code></td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Badge Visibility', 'proofratings') ?></th>
							<td>
								<label>
									<input name="proofratings_floating_badge_settings[float]" value="no" type="hidden">
									<input class="checkbox-switch checkbox-float-embed" name="proofratings_floating_badge_settings[float]" value="yes" type="checkbox" <?php checked( 'yes', $badge_settings['float'] ) ?>>
									<?php _e('Float/Embed only option', 'proofratings'); ?>
								</label>
							</td>
						</tr>

						<tr id="badge-tablet-visibility">
							<th scope="row"><?php _e('Tablet Visibility', 'proofratings') ?></th>
							<td>
								<label>
									<input name="proofratings_floating_badge_settings[tablet]" value="no" type="hidden">
									<input class="checkbox-switch" name="proofratings_floating_badge_settings[tablet]" value="yes" type="checkbox" <?php checked( 'yes', $badge_settings['tablet'] ) ?>>
									<?php _e('Show/Hide on tablet', 'proofratings'); ?>
								</label>
							</td>
						</tr>

						<tr id="badge-mobile-visibility">
							<th scope="row"><?php _e('Mobile Visibility', 'proofratings') ?></th>
							<td>
								<label>
									<input name="proofratings_floating_badge_settings[mobile]" value="no" type="hidden">
									<input class="checkbox-switch" name="proofratings_floating_badge_settings[mobile]" value="yes" type="checkbox" <?php checked( 'yes', $badge_settings['mobile'] ) ?>>
									<?php _e('Show/Hide on mobile', 'proofratings'); ?>
								</label>
							</td>
						</tr>

						<tr id="badge-close-options">
							<th scope="row"><?php _e('Close option', 'proofratings') ?></th>
							<td>
								<label>
									<input name="proofratings_floating_badge_settings[close_button]" value="no" type="hidden">
									<input class="checkbox-switch" name="proofratings_floating_badge_settings[close_button]" value="yes" type="checkbox" <?php checked( 'yes', $badge_settings['close_button'] ) ?>>
								</label>
							</td>
						</tr>

						<tr>
							<th scope="row" style="vertical-align:middle"><?php _e('Badge Type', 'proofratings') ?></th>
							<td>
								<div class="proofratings-styles">
									<select name="proofratings_floating_badge_settings[badge_style]" data-name="badge_style">
										<option value="style1" <?php selected('style1', $badge_settings['badge_style']) ?> data-img="<?php echo PROOFRATINGS_PLUGIN_URL; ?>/assets/images/floating-badge-style1.png"><?php _e('Style 1', 'proofratings'); ?></option>
										<option value="style2" <?php selected('style2', $badge_settings['badge_style']) ?> data-img="<?php echo PROOFRATINGS_PLUGIN_URL; ?>/assets/images/floating-badge-style2.png"><?php _e('Style 2', 'proofratings'); ?></option>
									</select>

									<img src="<?php echo PROOFRATINGS_PLUGIN_URL; ?>/assets/images/floating-badge-style1.png" alt="Proofratings style">
								</div>
							</td>
						</tr>

						<tr id="badge-position">
							<th scope="row"><?php _e('Position', 'proofratings') ?></th>
							<td>
								<select name="proofratings_floating_badge_settings[position]" data-position="<?php echo @$badge_settings['position']; ?>">
									<option value="left" <?php selected('left', $badge_settings['position']) ?>><?php _e('Left', 'proofratings') ?></option>
									<option value="right" <?php selected('right', $badge_settings['position']) ?>><?php _e('Right', 'proofratings') ?></option>
								</select>
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Star Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_floating_badge_settings[star_color]"
									value="<?php esc_attr_e($badge_settings['star_color']) ?>" data-default-color="#212A3D">
							</td>
						</tr>

						<tr id="badge-hide-shadow">
							<th scope="row"><?php _e('Shadow', 'proofratings') ?></th>
							<td>
								<input name="proofratings_floating_badge_settings[shadow]" value="no" type="hidden">
								<input class="checkbox-switch" name="proofratings_floating_badge_settings[shadow]" value="yes" type="checkbox" <?php checked( 'yes', $badge_settings['shadow'] ) ?>>
							</td>
						</tr>

						<tr id="badge-shadow-color">
							<th scope="row"><?php _e('Shadow Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_floating_badge_settings[shadow_color]"
									value="<?php esc_attr_e($badge_settings['shadow_color']) ?>" data-default-color="#f6d300">
							</td>
						</tr>

						<tr id="badge-shadow-hover-color">
							<th scope="row"><?php _e('Shadow Hover Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_floating_badge_settings[shadow_hover]"
									value="<?php esc_attr_e($badge_settings['shadow_hover']) ?>" data-default-color="#377dbc">
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Background Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_floating_badge_settings[background_color]" 
									value="<?php esc_attr_e($badge_settings['background_color']) ?>" data-default-color="#fff">
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Review Text Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_floating_badge_settings[review_text_color]" 
									value="<?php esc_attr_e($badge_settings['review_text_color']) ?>">
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Review Background Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_floating_badge_settings[review_background]" 
									value="<?php esc_attr_e($badge_settings['review_background']) ?>" data-default-color="#212a3d">
							</td>
						</tr>
					</table>

					
					<table id="floating-badge-pages" class="form-table">
						<caption>Page to show on</caption>
						<?php foreach (get_pages() as $page) : ?>
						<tr>
							<th scope="row"><?php echo $page->post_title ?></th>
							<td>	
							<?php
								$checked = !isset($badge_settings['on_pages'][$page->ID]) || $badge_settings['on_pages'][$page->ID] == 'yes'? 'checked' : '';
								printf('<input name="proofratings_floating_badge_settings[on_pages][%s]" value="no" type="hidden">', $page->ID);
								printf(
									'<label><input class="checkbox-switch" name="proofratings_floating_badge_settings[on_pages][%s]" value="yes" %s type="checkbox"></label>',
									$page->ID, $checked
								);
							?>
							</td>
						</tr>
						<?php endforeach; ?>
					</table>
				</div>

				<div id="settings-banner-badge" class="settings_panel" style="display:none">
					<?php 
						$banner_badge = $this->get_banner_badge_settings(); ?>

					<table class="form-table">
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