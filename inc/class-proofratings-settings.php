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
class Proofratings_Settings {
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

		add_action( 'init', [$this, 'handle_signup_form'] );
		add_action( 'init', [$this, 'handle_add_location'] );
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
	 * handle add location form submit
	 * @since 1.0.6
	 */
	public function handle_add_location() {
		if ( !isset($_POST['_nonce']) || !wp_verify_nonce( $_POST['_nonce'], '_nonce_add_location')) {
			return;
		}


		$postdata = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

		$validate_data = true;

		if ( empty($postdata['country'])) {
			$validate_data = false;
			$_POST['error_msg'] = __('Please fill location country field', 'proofratings');
		}

		if ( empty($postdata['zip'])) {
			$validate_data = false;
			$_POST['error_msg'] = __('Please fill location zip/postal field', 'proofratings');
		}

		if ( empty($postdata['state'])) {
			$validate_data = false;
			$_POST['error_msg'] = __('Please fill location state/province field', 'proofratings');
		}

		if ( empty($postdata['city'])) {
			$validate_data = false;
			$_POST['error_msg'] = __('Please fill location city field', 'proofratings');
		}

		if ( empty($postdata['street'])) {
			$validate_data = false;
			$_POST['error_msg'] = __('Please fill location street field', 'proofratings');
		}

		if ( empty($postdata['name'])) {
			$validate_data = false;
			$_POST['error_msg'] = __('Please fill location name field', 'proofratings');
		}

		if (!$validate_data ) {
			return;
		}

		
		
		$email = get_option( 'admin_email' );
		$name = get_bloginfo('name');


		ob_start();
		include PROOFRATINGS_PLUGIN_DIR . '/templates/email-add-location.php';
		$message = ob_get_clean();

		$message = preg_replace('/{name}/', $postdata['name'], $message);
		$message = preg_replace('/{street}/', $postdata['street'], $message);
		$message = preg_replace('/{street2}/', $postdata['street2'], $message);
		$message = preg_replace('/{city}/', $postdata['city'], $message);
		$message = preg_replace('/{state}/', $postdata['state'], $message);
		$message = preg_replace('/{zip}/', $postdata['zip'], $message);
		$message = preg_replace('/{country}/', $postdata['country'], $message);
		
		$headers = array('Content-Type: text/html; charset=UTF-8', sprintf('From: %s <%s>', $name, $email), 'Reply-To: ' . $email);

		$sendto = 'jonathan@proofratings.com';
		$sendto = 'repon.kushtia@gmail.com';
		
		if (!wp_mail( $sendto, $name . ' - New location add request', $message, $headers) ) {
			return $_POST['error_msg'] = __('Send mail have not successful.', 'proofratings');
		}

		exit(wp_safe_redirect(admin_url( 'admin.php?page=' . 'proofratings-locations')));
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

				<p>If you do not have a Proofratings account, <a href="https://proofratings.com/#pricing" target="_blank">please select a plan here</a>. Plans are only $275/year.</p>
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
			<h1 class="wp-heading-inline"><?php _e('Proofrating Status', 'proofratings') ?></h1>
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
	public function pause() {
		?>
		<div class="wrap proofratings-settings-wrap">
			<h1 class="wp-heading-inline"><?php _e('Proofrating Status', 'proofratings') ?></h1>
			<hr class="wp-header-end">
			<h2 class="nav-tab-wrapper">
				<a href="#proofratings-pause-tab" class="nav-tab"><?php _e('Pause', 'proofratings'); ?></a>
			</h2>

			<div id="proofratings-pause-tab" class="settings_panel">
				<h3><?php _e('Your account has been paused', 'proofratings') ?></h3>
			</div>
		</div>
		<?php
	}

	/**
	 * Shows the plugin's settings page.
	 */
	public function add_location() {
		?>
		<div class="wrap proofratings-settings-wrap">
			<h1 class="wp-heading-inline"><?php _e('Add Location', 'proofratings') ?></h1>
			<hr class="wp-header-end">

			<?php if (!empty($_POST['error_msg'])) : ?>
			<div class="notice notice-error is-dismissible">
				<p><?php echo $_POST['error_msg'] ?></p>
			</div>
			<?php endif; ?>
			
			<form method="post">
				<?php wp_nonce_field( '_nonce_add_location', '_nonce' ) ?>
				<table class="form-table">
					<tr>
						<th scope="row"><?php _e('Location Name*', 'proofratings') ?></th>
						<td>
							<input name="name" type="text" value="<?php echo @$_POST['name'] ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location Street*', 'proofratings') ?></th>
						<td>
							<input name="street" type="text" value="<?php echo @$_POST['street'] ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location Street 2', 'proofratings') ?></th>
						<td>
							<input name="street2" type="text" value="<?php echo @$_POST['street2'] ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location City*', 'proofratings') ?></th>
						<td>
							<input name="city" type="text" value="<?php echo @$_POST['city'] ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location State/Province*', 'proofratings') ?></th>
						<td>
							<input name="state" type="text" value="<?php echo @$_POST['state'] ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location Zip/Postal*', 'proofratings') ?></th>
						<td>
							<input name="zip" type="text" value="<?php echo @$_POST['zip'] ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location Country*', 'proofratings') ?></th>
						<td>
							<input name="country" type="text" value="<?php echo @$_POST['country'] ?>" />
						</td>
					</tr>
				</table>

				<?php submit_button('Request location'); ?>
			</form>
		</div>
		<?php
	}
}