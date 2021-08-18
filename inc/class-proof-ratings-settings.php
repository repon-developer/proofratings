<?php
/**
 * File containing the class WP_Job_Manager.
 *
 * @package wp-job-manager
 * @since   1.33.0
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
				'color' => '#febc00',
				'text_color' => '',
				'title' => __('Google Review Settings', 'proof-ratings'),
				'logo' => PROOF_RATINGS_PLUGIN_URL . '/assets/images/google.svg'
			],

			'facebook' => [
				'color' => '#0f7ff3',
				'text_color' => '',
				'title' => __('Facebook Review Settings', 'proof-ratings'),
				'logo' => PROOF_RATINGS_PLUGIN_URL . '/assets/images/facebook.svg'
			],

			'energysage' => [
				'color' => '#bf793f',
				'text_color' => '',
				'title' => __('Energy Sage Review Settings', 'proof-ratings'),
				'logo' => PROOF_RATINGS_PLUGIN_URL . '/assets/images/energysage.png'
			],

			'solarreviews' => [
				'color' => '#0f92d7',
				'text_color' => '',
				'title' => __('Solar Reviews Review Settings', 'proof-ratings'),
				'logo' => PROOF_RATINGS_PLUGIN_URL . '/assets/images/solarreviews.svg'
			],

			'yelp' => [
				'color' => '#e21c21',
				'text_color' => '',
				'title' => __('Yelp Review Settings', 'proof-ratings'),
				'logo' => PROOF_RATINGS_PLUGIN_URL . '/assets/images/yelp.svg'
			],

			'bbb' => [
				'color' => '#136796',
				'text_color' => '',
				'title' => __('BBB Review Settings', 'proof-ratings'),
				'logo' => PROOF_RATINGS_PLUGIN_URL . '/assets/images/bbb.svg'
			],

			'guildquality' => [
				'color' => '#032e57',
				'text_color' => '',
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
		?>
		<div class="wrap proof-ratings-settings-wrap">
			<form class="proof-ratings-options" method="post" action="options.php">

				<?php settings_fields( $this->settings_group ); ?>

				<?php
				if ( ! empty( $_GET['settings-updated'] ) ) {
					echo '<div class="updated fade"><p>' . esc_html__( 'Settings successfully saved', 'proof-ratings' ) . '</p></div>';
				}

				$proof_ratings_settings = get_option( 'proof_ratings_settings', []);

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
								echo '<th scope="row"><label for="mailserver_url">Color</label></th>';
								printf('<td><input class="proof-ratings-color-field" name="proof_ratings_settings[%1$s][color]" type="text" value="%2$s"></td>', $key, $site['color']);
							echo '</tr>';
							
							echo '<tr>';
								echo '<th scope="row"><label for="mailserver_url">Background Color</label></th>';
								printf('<td><input class="proof-ratings-color-field" name="proof_ratings_settings[%1$s][background]" type="text" value="%2$s"></td>', $key, $site['background']);
							echo '</tr>';

						echo '</table>';
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