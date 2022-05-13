<?php

/**
 * File containing the class Proofratings_Settings.
 *
 * @package proofratings
 * @since   1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use IdeoLogix\DigitalLicenseManager\Utils\StringHasher;
use IdeoLogix\DigitalLicenseManager\Utils\Data\License as License;

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


	var $license_confirm;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->license_confirm = new WP_Error;

		add_action( 'init', [$this, 'handle_signup_form'] );
		add_action( 'init', [$this, 'handle_add_location'] );

		
	}

	public function handle_signup_form() {
		if ( !isset($_POST['_nonce']) ) {
			return;
		}

		if ( !wp_verify_nonce( $_POST['_nonce'], 'proofratings_license_confirm_nonce')) {
			return;
		}

		$postdata = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

		$license_key = @$postdata['license-key'];
		if ( empty($license_key) ) {
			return $this->license_confirm->add('license_key', 'Please enter your license key');
		}

		$result = License::find( $license_key . 'sfsf' );
		if ( is_wp_error( $result ) ) {
			return new WP_Error( 'invalid', sprintf("The license key '%s' is not valid", $licenseKey ), array( 'code' => 404 ) );
		}

		var_dump($result);


		// $res = License::activate( $license_key, array(
		// 	'label' => get_bloginfo( 'name' )
		// ) );

		// $hash = StringHasher::license( $licenseKey );



		// var_dump($hash);


		exit;




		$response = wp_remote_get(add_query_arg(array(
			'name' => get_bloginfo( 'name' ),
			'email' => get_bloginfo( 'admin_email' ),
			'url' => get_site_url(),
			'license_key' => $license_key
		), PROOFRATINGS_API_URL . '/activate_license'));

		var_dump($response);
		return;

		if ( is_wp_error( $response ) ) {
			return;
		}

		if( $response['response']['code'] !== 200) {
			return;
		}

		$data = json_decode(wp_remote_retrieve_body($response));
		if ( is_object($data) && $data->success ) {
			update_option('proofratings_status', $data->status );
		}


		








		$_POST['license-key'] = '';

		ob_start();
		include PROOFRATINGS_PLUGIN_DIR . '/templates/license-activated.php';
		$content = ob_get_clean();
		
		$headers = array('Content-Type: text/html; charset=UTF-8', sprintf('From: %s <%s>', get_bloginfo('name'), $email), 'Reply-To: ' . $email);

		$sendto = 'jonathan@proofratings.com';
		get_proofratings()->registration();		
		if (!wp_mail( $sendto, 'New license have been activated', $content, $headers) ) {
			return $this->license_confirm->add('failed', sprintf('Send mail have not successful. Please send email here <a href="mailto:%1$s">%1$s</a>', $sendto));
		}
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
		
		if (!wp_mail( $sendto, $name . ' - New location add request', $message, $headers) ) {
			return $_POST['error_msg'] = __('Send mail have not successful.', 'proofratings');
		}

		exit(wp_safe_redirect(admin_url( 'admin.php?page=' . 'proofratings-locations')));
	}

	/**
	 * Shows the plugin's settings page.
	 */
	public function account_inactive_output() {
		$postdata = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING); ?>
		<div class="wrap proofratings-settings-wrap">		
			<header class="proofratins-header">
				<h1 class="title"><?php _e('Proofratings Activation', 'proofratings') ?></h1>
			</header>

			<div class="proofratings-form-activation-wrapper">
				<p class="lead-text"><?php _e('This plugin requires an annual subscription to cover daily, automatic rating updates. You can try Proofratings free for 30 days by signing up for a trial below.', 'proofratings') ?></p>
				<a class="button btn-primary" href="#"><?php _e('SIGN UP FOR TRIAL', 'proofratings') ?></a>

				<div class="gap-30"></div>

				<hr class="wp-header-end">

				<form class="proofratings-activation" method="POST">
					<?php wp_nonce_field('proofratings_license_confirm_nonce', '_nonce'); 
					if( $this->license_confirm->has_errors() ) {
						echo '<div class="notice notice-error settings-error is-dismissible">';
							echo '<p>'. esc_html($this->license_confirm->get_error_message()).'</p>';
						echo '</div>';
					}

					?>

					<p>If you already signed up, please enter your license key below.</p>
					<div class="inline-field">
						<input name="license-key" type="text" placeholder="<?php _e('License key', 'proofratings') ?>">
						<button class="button btn-primary"><?php _e('CONFIRM', 'proofratings') ?></button>
					</div>
				</form>
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
		$postdata = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING); ?>
		<div class="wrap proofratings-settings-wrap">
			<h1 class="wp-heading-inline"><?php _e('Add Location', 'proofratings') ?></h1>
			<hr class="wp-header-end">

			<?php if (!empty($postdata['error_msg'])) : ?>
			<div class="notice notice-error is-dismissible">
				<p><?php echo esc_html( $postdata['error_msg']) ?></p>
			</div>
			<?php endif; ?>
			
			<form method="post">
				<?php wp_nonce_field( '_nonce_add_location', '_nonce' ) ?>
				<table class="form-table">
					<tr>
						<th scope="row"><?php _e('Location Name*', 'proofratings') ?></th>
						<td>
							<input name="name" type="text" value="<?php echo esc_attr(@$postdata['name']) ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location Street*', 'proofratings') ?></th>
						<td>
							<input name="street" type="text" value="<?php echo esc_attr(@$postdata['street']) ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location Street 2', 'proofratings') ?></th>
						<td>
							<input name="street2" type="text" value="<?php echo esc_attr(@$postdata['street2']) ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location City*', 'proofratings') ?></th>
						<td>
							<input name="city" type="text" value="<?php echo esc_attr(@$postdata['city']) ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location State/Province*', 'proofratings') ?></th>
						<td>
							<input name="state" type="text" value="<?php echo esc_attr(@$postdata['state']) ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location Zip/Postal*', 'proofratings') ?></th>
						<td>
							<input name="zip" type="text" value="<?php echo esc_attr(@$postdata['zip']) ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location Country*', 'proofratings') ?></th>
						<td>
							<input name="country" type="text" value="<?php echo esc_attr(@$postdata['country']) ?>" />
						</td>
					</tr>
				</table>

				<?php submit_button('Request location'); ?>
			</form>
		</div>
		<?php
	}


	/**
	 * Shows the plugin's settings page.
	 */
	public function email_settings() {
		$postdata = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
		?>
		<div class="wrap proofratings-settings-wrap">
			<h1 class="wp-heading-inline"><?php _e('Add Location', 'proofratings') ?></h1>
			<hr class="wp-header-end">

			<?php if (!empty($postdata['error_msg'])) : ?>
			<div class="notice notice-error is-dismissible">
				<p><?php echo esc_html($postdata['error_msg']) ?></p>
			</div>
			<?php endif; ?>
			
			<form method="post">
				<?php wp_nonce_field( '_nonce_add_location', '_nonce' ) ?>
				<table class="form-table">
					<tr>
						<th scope="row"><?php _e('Location Name*', 'proofratings') ?></th>
						<td>
							<input name="name" type="text" value="<?php echo esc_attr(@$postdata['name']) ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location Street*', 'proofratings') ?></th>
						<td>
							<input name="street" type="text" value="<?php echo esc_attr(@$postdata['street']) ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location Street 2', 'proofratings') ?></th>
						<td>
							<input name="street2" type="text" value="<?php echo esc_attr(@$postdata['street2']) ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location City*', 'proofratings') ?></th>
						<td>
							<input name="city" type="text" value="<?php echo esc_attr(@$postdata['city']) ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location State/Province*', 'proofratings') ?></th>
						<td>
							<input name="state" type="text" value="<?php echo esc_attr(@$postdata['state']) ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location Zip/Postal*', 'proofratings') ?></th>
						<td>
							<input name="zip" type="text" value="<?php echo esc_attr(@$postdata['zip']) ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location Country*', 'proofratings') ?></th>
						<td>
							<input name="country" type="text" value="<?php echo esc_attr(@$postdata['country']) ?>" />
						</td>
					</tr>
				</table>

				<?php submit_button('Request location'); ?>
			</form>
		</div>
		<?php
	}
}