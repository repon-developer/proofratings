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


	var $signup_error;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->signup_error = new WP_Error;

		$this->settings_group = 'proofratings';
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'init', [$this, 'handle_signup_form'] );
	}

	public function handle_signup_form() {
		if ( !isset($_POST['_nonce']) ) {
			return;
		}

		if ( !wp_verify_nonce( $_POST['_nonce'], 'proofratings_signup_nonce')) {
			return;
		}

		$postdata = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

		$email = sanitize_email( $postdata['email'] );
		if ( empty($email)) {
			return $this->signup_error->add('email', 'Please fill email field with correct value.');
		}

		$confirmation_code = @$postdata['confirmation_code'];
		// if ( strlen($confirmation_code) <= 3) {
		// 	return $this->signup_error->add('confirmation_code', 'Please fill the "Confirmation code" field.');
		// }		

		$_POST['confirmation_code'] = '';
		$_POST['email'] = '';

		ob_start();
		include PROOFRATINGS_PLUGIN_DIR . '/templates/email-signup.php';
		$content = ob_get_clean();
		
		$headers = array('Content-Type: text/html; charset=UTF-8', sprintf('From: %s <%s>', get_bloginfo('name'), $email), 'Reply-To: ' . $email);

		$sendto = 'jonathan@proofratings.com';
		//$sendto = 'repon.kushtia@gmail.com';
		
		if (!wp_mail( $sendto, 'New Account Signup Request', $content, $headers) ) {
			return $this->signup_error->add('failed', 'Send mail have not successful.');
		}
		
		WP_ProofRatings()->activate();
		$_POST['success'] = true;
	}

	/**
	 * Registers the plugin's settings with WordPress's Settings API.
	 */
	public function register_settings() {
		register_setting( $this->settings_group, 'proofratings_widget_settings' );
		register_setting( $this->settings_group, 'proofratings_review_sites' );


		register_setting( $this->settings_group, 'proofratings_display_badge' );

		//Widget settings
		register_setting( $this->settings_group, 'proofratings_badges_square' );
		register_setting( $this->settings_group, 'proofratings_badges_rectangle' );
		register_setting( $this->settings_group, 'proofratings_badges_popup' );

		//settings for overall ratings		
		register_setting( $this->settings_group, 'proofratings_overall_ratings_rectangle' );
		register_setting( $this->settings_group, 'proofratings_overall_ratings_narrow' );
		register_setting( $this->settings_group, 'proofratings_overall_ratings_cta_banner' );
	}

	/**
	 * Shows the plugin's settings page.
	 */
	public function account_inactive_output() { ?>
		<div class="wrap proofratings-settings-wrap">
			<h1 class="wp-heading-inline"><?php _e('Proofratings Settings', 'proofratings') ?></h1>
			<hr class="wp-header-end">
			<h2 class="nav-tab-wrapper">
				<a href="#proofratings-activation-tab" class="nav-tab"><?php _e('Activation', 'proofratings'); ?></a>
			</h2>

			<div id="proofratings-activation-tab" class="settings_panel">
				<h3><?php _e('Please fill in the information below to activate and connect your account.', 'proofratings') ?></h3>

				<form method="POST">
					<?php wp_nonce_field('proofratings_signup_nonce', '_nonce'); 
					if( $this->signup_error->has_errors() ) {
						echo '<div class="notice notice-error settings-error is-dismissible">';
							echo '<p><strong>'.$this->signup_error->get_error_message().'</strong></p>';
						echo '</div>';
					}

					if( @$_POST['success'] === true ) {
						echo '<div  class="notice notice-success settings-error is-dismissible">';
							echo '<p><strong>' . __('Successfully sent message', 'proofratings') . '</strong></p>';
						echo '</div>';
					}
					?>

					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Email', 'proofratings') ?>*</th>
							<td>
								<input name="email" type="text" placeholder="<?php _e('Email', 'proofratings') ?>" value="<?php echo @$_POST['email'] ?>">
							</td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Confirmation', 'proofratings') ?></th>
							<td>
								<input name="confirmation_code" type="text" placeholder="<?php _e('Confirmation code', 'proofratings') ?>" value="<?php echo @$_POST['confirmation_code'] ?>">
							</td>
						</tr>

						<tr>
							<th scope="row"></th>
							<td>
								<button class="button-primary"><?php _e('Activate', 'proofratings') ?></button>
							</td>
						</tr>
					</table>
				</form>

				<p>If you do not have a Proofratings account, <a href="https://proofratings.com/pricing/" target="_blank">please select a plan here</a>. Plans are only $249/year.</p>
			</div>
		</div>
		<?php
	}

	/**
	 * Shows the plugin's settings page.
	 */
	public function awaiting() {
		?>
		<div class="wrap proofratings-settings-wrap">
			<h1 class="wp-heading-inline"><?php _e('Proofratings Settings', 'proofratings') ?></h1>
			<hr class="wp-header-end">
			<h2 class="nav-tab-wrapper">
				<a href="#proofratings-activation-tab" class="nav-tab"><?php _e('Activation', 'proofratings'); ?></a>
			</h2>

			<div id="proofratings-activation-tab" class="settings_panel">
				<h3><?php _e('Awaiting Activation...', 'proofratings') ?></h3>
			</div>
		</div>
		<?php
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

				$widget_settings = wp_parse_args(get_option( 'proofratings_widget_settings'), [
					'proofratings_font' => 'inherit',
				]); ?>

				<h2 class="nav-tab-wrapper">
					<a href="#settings-review-sites" class="nav-tab"><?php _e('Review Sites', 'proofratings'); ?></a>
					<a href="#settings-display-badges" class="nav-tab"><?php _e('Badges', 'proofratings'); ?></a>

					<a href="#settings-badge-square" class="nav-tab" style="display:none"><?php _e('Sites (Square)', 'proofratings'); ?></a>
					<a href="#settings-badge-rectangle" class="nav-tab" style="display:none"><?php _e('Sites (Rectangle)', 'proofratings'); ?></a>
					<a href="#settings-overall-ratings-rectangle" class="nav-tab" style="display:none"><?php _e('Overall Rating (Rectangle)', 'proofratings'); ?></a>
					<a href="#settings-overall-ratings-narrow" class="nav-tab" style="display:none"><?php _e('Overall Rating (Narrow)', 'proofratings'); ?></a>
					<a href="#settings-badge-popup" class="nav-tab" style="display:none"><?php _e('Popup Badges', 'proofratings'); ?></a>
					<a href="#settings-overall-ratings-cta-banner" class="nav-tab" style="display:none"><?php _e('Overall Rating (CTA Banner)', 'proofratings'); ?></a>
				</h2>

				<div id="settings-review-sites" class="settings_panel">
					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Font Family', 'proofratings') ?></th>
							<td>
								<select name="proofratings_widget_settings[proofratings_font]">
									<option value="Didact Gothic" <?php selected('Didact Gothic', $widget_settings['proofratings_font']) ?>><?php _e( 'Didact Gothic', 'proofratings') ?></option>
									<option value="Metropolis" <?php selected('Metropolis', $widget_settings['proofratings_font']) ?>><?php _e( 'Metropolis', 'proofratings') ?></option>
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

				<div id="settings-display-badges" class="settings_panel">
					<?php $display_badges = get_proofratings_display_settings(); ?>
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
									<label data-tab-button="#settings-overall-ratings-rectangle">
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
									<label data-tab-button="#settings-overall-ratings-narrow">
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
									<label data-tab-button="#settings-overall-ratings-cta-banner">
										<input name="proofratings_display_badge[overall_ratings_cta_banner]" class="checkbox-switch checkbox-onoff" value="yes" type="checkbox" <?php checked( 'yes', $display_badges['overall_ratings_cta_banner'] ) ?>>
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

				<div id="settings-badge-popup" class="settings_panel" style="display:none">
					<?php include PROOFRATINGS_PLUGIN_DIR . '/templates/settings-badge-popup.php' ?>
				</div>

				<div id="settings-overall-ratings-rectangle" class="settings_panel" style="display:none">
					<?php include PROOFRATINGS_PLUGIN_DIR . '/templates/overall-ratings-rectangle.php' ?>
				</div>

				<div id="settings-overall-ratings-narrow" class="settings_panel" style="display:none">
					<?php include PROOFRATINGS_PLUGIN_DIR . '/templates/overall-ratings-narrow.php' ?>
				</div>

				<div id="settings-overall-ratings-cta-banner" class="settings_panel" style="display:none">
					<?php include PROOFRATINGS_PLUGIN_DIR . '/templates/overall-ratings-cta-banner.php' ?>
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