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

	public function get_site_settings() {

	}

	/**
	 * Shows the plugin's settings page.
	 */
	public function output() {
		// $xlsx = SimpleXLSX::parse( PROOF_RATINGS_PLUGIN_DIR . '/inc/reviews.xlsx');
		// var_dump($xlsx->rows());
		// exit;


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
				}
				


				
					
				?>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'proof-ratings' ); ?>" />
				</p>
			</form>
		</div>
		<?php
	}
}