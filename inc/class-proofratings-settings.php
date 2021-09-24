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
	}

	public function get_floating_badge_settings() {
		return wp_parse_args((array)get_option( 'proofratings_floating_badge_settings'), [
			'show' => 'yes',
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
				]); ?>

				<h2 class="nav-tab-wrapper">
					<a href="#settings-review-sites" class="nav-tab"><?php _e('Review Sites', 'proofratings'); ?></a>
					<a href="#settings-floating-badge" class="nav-tab"><?php _e('Floating Badge', 'proofratings'); ?></a>
					<a href="#settings-floating-pages" class="nav-tab"><?php _e('Pages', 'proofratings'); ?></a>
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
					$proofratings_settings = get_proofratings_settings();
					echo '<div class="review-sites-checkboxes">';
					foreach ($proofratings_settings as $key => $site) {
						printf(
							'<label class="checkbox-review-site" data-site="%1$s"><input type="checkbox" name="proofratings_settings[%1$s][active]" value="yes" %3$s /><img src="%2$s" alt="%1$s" /></label>', 
							$key, esc_attr($site->logo), checked('yes', $site->active, false)
						);
					}
					echo '</div>';

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
					<?php 
						$badge_settings = $this->get_floating_badge_settings(); ?>

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
									<input class="checkbox-switch checkbox-float-embed" name="proofratings_floating_badge_settings[float]" value="yes" type="checkbox" <?php checked( 'yes', $badge_settings['show'] ) ?>>
									<?php _e('Float/Embed only option', 'proofratings'); ?>
								</label>
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Tablet Visibility', 'proofratings') ?></th>
							<td>
								<label>
									<input name="proofratings_floating_badge_settings[tablet]" value="no" type="hidden">
									<input class="checkbox-switch" name="proofratings_floating_badge_settings[tablet]" value="yes" type="checkbox" <?php checked( 'yes', $badge_settings['tablet'] ) ?>>
									<?php _e('Show/Hide on tablet', 'proofratings'); ?>
								</label>
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Mobile Visibility', 'proofratings') ?></th>
							<td>
								<label>
									<input name="proofratings_floating_badge_settings[mobile]" value="no" type="hidden">
									<input class="checkbox-switch" name="proofratings_floating_badge_settings[mobile]" value="yes" type="checkbox" <?php checked( 'yes', $badge_settings['mobile'] ) ?>>
									<?php _e('Show/Hide on mobile', 'proofratings'); ?>
								</label>
							</td>
						</tr>

						<tr>
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

						<tr>
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
									value="<?php esc_attr_e($badge_settings['star_color']) ?>" data-default-color="#212A3D" data-name="star_color">
							</td>
						</tr>

						<tr>
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
									value="<?php esc_attr_e($badge_settings['shadow_color']) ?>" data-default-color="#f6d300" data-name="shadow_color">
							</td>
						</tr>

						<tr id="badge-shadow-hover-color">
							<th scope="row"><?php _e('Shadow Hover Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_floating_badge_settings[shadow_hover]"
									value="<?php esc_attr_e($badge_settings['shadow_hover']) ?>" data-default-color="#377dbc" data-name="shadow_hover">
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Background Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_floating_badge_settings[background_color]" 
									value="<?php esc_attr_e($badge_settings['background_color']) ?>" data-default-color="#fff" data-name="background_color">
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Review Text Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_floating_badge_settings[review_text_color]" 
									value="<?php esc_attr_e($badge_settings['review_text_color']) ?>" data-name="review_text_color">
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Review Background Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_floating_badge_settings[review_background]" 
									value="<?php esc_attr_e($badge_settings['review_background']) ?>" data-default-color="#212a3d" data-name="review_background">
							</td>
						</tr>
					</table>
				</div>

				<div id="settings-floating-pages" class="settings_panel" style="display:none">
					<table class="form-table">
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

				<p class="submit">
					<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'proofratings' ); ?>" />
				</p>
			</form>

			<p class="review-us">Enjoying Proofratings? ❤️ Review us <a href="https://wordpress.org/plugins/proofratings/" target="_blank">here</a></p>
		</div>
		<?php
	}
}