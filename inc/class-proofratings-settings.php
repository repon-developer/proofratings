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
		register_setting( $this->settings_group, 'proofratings_settings' );
		register_setting( $this->settings_group, 'proofratings_floating_badge_settings' );
	}

	public function get_review_sites() {
		return [
			'google' => [
				'title' => __('Google Review Settings', 'proofratings'),
				'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/google.svg'
			],

			'facebook' => [
				'title' => __('Facebook Review Settings', 'proofratings'),
				'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/facebook.svg'
			],

			'energysage' => [
				'title' => __('Energy Sage Review Settings', 'proofratings'),
				'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/energysage.png'
			],

			'solarreviews' => [
				'title' => __('Solar Reviews Settings', 'proofratings'),
				'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/solarreviews.svg'
			],

			'yelp' => [
				'title' => __('Yelp Review Settings', 'proofratings'),
				'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/yelp.svg'
			],

			'bbb' => [
				'title' => __('BBB Review Settings', 'proofratings'),
				'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/bbb.svg'
			],

			'guildquality' => [
				'title' => __('Guild Quality Review Settings', 'proofratings'),
				'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/guildquality.svg'
			],

			'solarquotes' => [
				'title' => __('Solarquotes Review Settings', 'proofratings'),
				'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/solarquotes.png'
			],

			'trustpilot' => [
				'title' => __('Trustpilot Review Settings', 'proofratings'),
				'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/trustpilot.png'
			],

			'wordpress' => [
				'title' => __('Wordpress Review Settings', 'proofratings'),
				'logo' => PROOFRATINGS_PLUGIN_URL . '/assets/images/wordpress.png'
			],
		];
	}

	public function get_floating_badge_settings() {
		return wp_parse_args((array)get_option( 'proofratings_floating_badge_settings'), [
			'position' => '',
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
			<h1 class="wp-heading-inline"><?php _e('Proof Ratings Settings', 'proofratings') ?></h1>
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
				} ?>

				<h2 class="nav-tab-wrapper">
					<a href="#settings-review-sites" class="nav-tab"><?php _e('Review Sites', 'proofratings'); ?></a>
					<a href="#settings-floating-badge" class="nav-tab"><?php _e('Floating Badge', 'proofratings'); ?></a>
				</h2>

				<div id="settings-review-sites" class="settings_panel">
					<div class="shortcode-info">Use shortcode where you want to display review widgets <code>[proofratings_widgets]</code></div>
					<?php
					$proofratings_settings = get_proofratings_settings();					
					echo '<div class="review-sites-checkboxes">';
					foreach ($this->get_review_sites() as $key => $site) {
						printf(
							'<label class="checkbox-review-site" data-site="%1$s"><input type="checkbox" name="proofratings_settings[%1$s][active]" value="yes" %3$s /><img src="%2$s" alt="%1$s" /></label>', 
							$key, esc_attr($site['logo']), checked('yes', $proofratings_settings[$key]['active'], false)
						);
					}
					echo '</div>';

					foreach ($this->get_review_sites() as $key => $site) {
						printf('<fieldset id="review-site-settings-%s" class="fieldset-site-review">', $key);
							if ( $site['title']) {
								echo '<h2 class="title">'. esc_html($site['title']).'</h2>';
							}

							echo '<table class="form-table form-table-review-sites settings">';
								echo '<tr>';
									echo '<th scope="row"><label for="mailserver_url">Theme Color</label></th>';
									printf('<td><input class="proofratings-color-field" name="proofratings_settings[%1$s][theme_color]" type="text" value="%2$s"></td>', $key, esc_attr($proofratings_settings[$key]['theme_color']));
								echo '</tr>';

								echo '<tr>';
									echo '<th scope="row"><label>Text Color</label></th>';
									printf('<td><input class="proofratings-color-field" name="proofratings_settings[%1$s][text_color]" type="text" value="%2$s"></td>', $key, esc_attr($proofratings_settings[$key]['text_color']));
								echo '</tr>';
								
								echo '<tr>';
									echo '<th scope="row"><label for="mailserver_url">Background Color</label></th>';
									printf('<td><input class="proofratings-color-field" name="proofratings_settings[%1$s][background]" type="text" value="%2$s"></td>', $key, esc_attr($proofratings_settings[$key]['background']));
								echo '</tr>';

							echo '</table>';
							echo '<hr>';
						echo '</fieldset>';
					} ?>
				</div>

				<div id="settings-floating-badge" class="settings_panel" style="display:none">
					<?php 
						$badge_settings = $this->get_floating_badge_settings(); 
					?>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Position', 'proofratings') ?></th>
							<td>
								<select name="proofratings_floating_badge_settings[position]">
									<option value="left" <?php selected('left', $badge_settings['position']) ?>><?php _e('Left', 'proofratings') ?></option>
									<option value="right" <?php selected('right', $badge_settings['position']) ?>><?php _e('Right', 'proofratings') ?></option>
								</select>
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Shadow Color', 'proofratings') ?></th>
							<td>
								<input class="proofratings-color-field" type="text" 
									name="proofratings_floating_badge_settings[shadow_color]"
									value="<?php esc_attr_e($badge_settings['shadow_color']) ?>" data-default-color="#f6d300">
							</td>
						</tr>

						<tr>
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
					
				</div>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'proofratings' ); ?>" />
				</p>
			</form>
		</div>
		<?php
	}
}