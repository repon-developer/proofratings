<?php
/**
 * File containing the class WP_Proof_Ratings_Settings.
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
class WP_Proof_Ratings_Settings {
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
		$this->settings_group = 'proof_ratings';
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	/**
	 * Registers the plugin's settings with WordPress's Settings API.
	 */
	public function register_settings() {
		register_setting( $this->settings_group, 'proof_ratings_settings' );
		register_setting( $this->settings_group, 'proof_ratings_floating_badge_settings' );
	}

	public function get_review_sites() {
		return [
			'google' => [
				'title' => __('Google Review Settings', 'proof-ratings'),
				'logo' => PROOF_RATINGS_PLUGIN_URL . '/assets/images/google.svg'
			],

			'facebook' => [
				'title' => __('Facebook Review Settings', 'proof-ratings'),
				'logo' => PROOF_RATINGS_PLUGIN_URL . '/assets/images/facebook.svg'
			],

			'energysage' => [
				'title' => __('Energy Sage Review Settings', 'proof-ratings'),
				'logo' => PROOF_RATINGS_PLUGIN_URL . '/assets/images/energysage.png'
			],

			'solarreviews' => [
				'title' => __('Solar Reviews Settings', 'proof-ratings'),
				'logo' => PROOF_RATINGS_PLUGIN_URL . '/assets/images/solarreviews.svg'
			],

			'yelp' => [
				'title' => __('Yelp Review Settings', 'proof-ratings'),
				'logo' => PROOF_RATINGS_PLUGIN_URL . '/assets/images/yelp.svg'
			],

			'bbb' => [
				'title' => __('BBB Review Settings', 'proof-ratings'),
				'logo' => PROOF_RATINGS_PLUGIN_URL . '/assets/images/bbb.svg'
			],

			'guildquality' => [
				'title' => __('Guild Quality Review Settings', 'proof-ratings'),
				'logo' => PROOF_RATINGS_PLUGIN_URL . '/assets/images/guildquality.svg'
			],
		];
	}

	public function get_floating_badge_settings() {
		return wp_parse_args((array)get_option( 'proof_ratings_floating_badge_settings'), [
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
		<div class="wrap proof-ratings-settings-wrap">
			<h1 class="wp-heading-inline"><?php _e('Proof Ratings Settings', 'proof-ratings') ?></h1>
			<hr class="wp-header-end">

			<form class="proof-ratings-options" method="post" action="options.php">

				<?php settings_fields( $this->settings_group ); ?>

				<?php
				if ( ! empty( $_GET['settings-updated'] ) ) {
					echo '<div class="updated fade"><p>' . esc_html__( 'Settings successfully saved', 'proof-ratings' ) . '</p></div>';
				}

				$proof_ratings_status = get_proof_ratings_current_status();
				if ( !$proof_ratings_status ) {
					echo '<div class="proof-ratings-status">';
					echo sprintf('<p>You have not registered your site. <a href="%s">Register now</a></p>', add_query_arg(['_regsiter_nonce' => wp_create_nonce( 'register_proof_ratings' )], menu_page_url('proof-ratings', false)) );
					echo '</div>';
				}

				if ( in_array($proof_ratings_status->status, ['pending', 'pause', 'suspend', 'no_sheet_id']) ) {
					echo '<div class="proof-ratings-status">';
						if ($proof_ratings_status->status == 'suspend') {
							printf('<p>'. __('Your application has been suspended.', 'proof-ratings') .'</p>');
						} else {							
							printf('<p>%s</p>', $proof_ratings_status->message);
						}
					echo '</div>';
				} ?>

				<h2 class="nav-tab-wrapper">
					<a href="#settings-review-sites" class="nav-tab"><?php _e('Review Sites', 'proof-ratings'); ?></a>
					<a href="#settings-floating-badge" class="nav-tab"><?php _e('Floating Badge', 'proof-ratings'); ?></a>
				</h2>

				<div id="settings-review-sites" class="settings_panel">
					<?php
					$proof_ratings_settings = get_proof_ratings_settings();
					
					echo '<div class="review-sites-checkboxes">';
					foreach ($this->get_review_sites() as $key => $site) {
						printf(
							'<label class="checkbox-review-site" data-site="%1$s"><input type="checkbox" name="proof_ratings_settings[%1$s][active]" value="yes" %3$s /><img src="%2$s" alt="%1$s" /></label>', 
							$key, $site['logo'], checked('yes', $proof_ratings_settings[$key]['active'], false)
						);
					}
					echo '</div>';

					foreach ($this->get_review_sites() as $key => $site) {
						printf('<fieldset id="review-site-settings-%s" class="fieldset-site-review">', $key);
							if ( $site['title']) {
								echo '<h2 class="title">'.$site['title'].'</h2>';
							}

							echo '<table class="form-table form-table-review-sites settings">';
								echo '<tr>';
									echo '<th scope="row"><label for="mailserver_url">Theme Color</label></th>';
									printf('<td><input class="proof-ratings-color-field" name="proof_ratings_settings[%1$s][theme_color]" type="text" value="%2$s"></td>', $key, $proof_ratings_settings[$key]['theme_color']);
								echo '</tr>';

								echo '<tr>';
									echo '<th scope="row"><label>Text Color</label></th>';
									printf('<td><input class="proof-ratings-color-field" name="proof_ratings_settings[%1$s][text_color]" type="text" value="%2$s"></td>', $key, $proof_ratings_settings[$key]['text_color']);
								echo '</tr>';
								
								echo '<tr>';
									echo '<th scope="row"><label for="mailserver_url">Background Color</label></th>';
									printf('<td><input class="proof-ratings-color-field" name="proof_ratings_settings[%1$s][background]" type="text" value="%2$s"></td>', $key, $proof_ratings_settings[$key]['background']);
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
							<th scope="row"><?php _e('Position', 'proof-ratings') ?></th>
							<td>
								<select name="proof_ratings_floating_badge_settings[position]">
									<option value="left" <?php selected('left', $badge_settings['position']) ?>><?php _e('Left', 'proof-ratings') ?></option>
									<option value="right" <?php selected('right', $badge_settings['position']) ?>><?php _e('Right', 'proof-ratings') ?></option>
								</select>
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Shadow Color', 'proof-ratings') ?></th>
							<td>
								<input class="proof-ratings-color-field" type="text" 
									name="proof_ratings_floating_badge_settings[shadow_color]"
									value="<?php echo $badge_settings['shadow_color'] ?>" data-default-color="#f6d300">
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Shadow Hover Color', 'proof-ratings') ?></th>
							<td>
								<input class="proof-ratings-color-field" type="text" 
									name="proof_ratings_floating_badge_settings[shadow_hover]"
									value="<?php echo $badge_settings['shadow_hover'] ?>" data-default-color="#377dbc">
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Background Color', 'proof-ratings') ?></th>
							<td>
								<input class="proof-ratings-color-field" type="text" 
									name="proof_ratings_floating_badge_settings[background_color]" 
									value="<?php echo $badge_settings['background_color'] ?>" data-default-color="#fff">
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Review Text Color', 'proof-ratings') ?></th>
							<td>
								<input class="proof-ratings-color-field" type="text" 
									name="proof_ratings_floating_badge_settings[review_text_color]" 
									value="<?php echo $badge_settings['review_text_color'] ?>">
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Review Background Color', 'proof-ratings') ?></th>
							<td>
								<input class="proof-ratings-color-field" type="text" 
									name="proof_ratings_floating_badge_settings[review_background]" 
									value="<?php echo $badge_settings['review_background'] ?>" data-default-color="#212a3d">
							</td>
						</tr>
					</table>
					
				</div>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'proof-ratings' ); ?>" />
				</p>
			</form>
		</div>
		<?php
	}
}