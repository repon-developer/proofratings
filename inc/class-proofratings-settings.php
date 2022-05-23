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

	/**
	 * Hold all errors
	 * @var WP_Error
	 * @since  1.0.1
	 */
	var $error;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->error = new WP_Error;

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
			return $this->error->add('license_key', 'Please enter your license key');
		}

		$response = wp_remote_get(add_query_arg(array(
			'name' => get_bloginfo( 'name' ),
			'email' => get_bloginfo( 'admin_email' ),
			'site_url' => get_site_url(),
			'license_key' => $license_key
		), PROOFRATINGS_API_URL . '/register_site'));

		if ( is_wp_error( $response ) ) {
			return $this->error->add('remote_request', $response->get_error_message());
		}

		$result = json_decode(wp_remote_retrieve_body($response));
		
		if ( !isset($result->success) || $result->success !== true ) {
			return $this->error->add('license_key', $result->message);
		}

		update_proofratings_settings(['status' => $result->data->status]);
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
		
		if ( empty($postdata['name'])) {
			$this->error->add('name', __('Please fill location name field', 'proofratings'));
		}

		if ( empty($postdata['street'])) {
			$this->error->add('street', __('Please fill location street field', 'proofratings'));
		}

		if ( empty($postdata['city'])) {
			$this->error->add('city', __('Please fill location city field', 'proofratings'));
		}

		if ( empty($postdata['state'])) {
			$this->error->add('state', __('Please fill location state/province field', 'proofratings'));
		}

		if ( empty($postdata['zip'])) {
			$this->error->add('zip', __('Please fill location zip/postal field', 'proofratings'));
		}

		if ( empty($postdata['country'])) {
			$this->error->add('country', __('Please fill location country field', 'proofratings'));
		}

		if ( $this->error->has_errors()) {
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
	public function license_page() {
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
					if( $this->error->has_errors() ) {
						echo '<div class="notice notice-error settings-error is-dismissible">';
							echo '<p>'. $this->error->get_error_message().'</p>';
						echo '</div>';
					} ?>

					<p>If you already signed up, please enter your license key below.</p>
					<div class="inline-field">
						<input name="license-key" type="text" value="<?php echo esc_attr( @$postdata['license-key'] )  ?>" placeholder="<?php _e('License key', 'proofratings') ?>" style="width: 285px">
						<button class="button btn-primary"><?php _e('CONFIRM', 'proofratings') ?></button>
					</div>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Main menu of proofratings
	 */
	public function main_menu() {?>
		<div class="wrap proofratings-settings-wrap">		
			<header class="proofratins-header header-row">
				<h1 class="title"><?php _e('Proofratings Main Menu', 'proofratings') ?></h1>

				<?php 
					if ( $join_date = wp_date('d-m-Y', strtotime(get_proofratings_settings('registered')) ) ) {
						printf('<span class="proofratings-join-date">Date Joined: %s</span>', $join_date);
					}
				?>
			</header>

			<div class="proofratings-dashboard-menu">
				<a href="<?php menu_page_url('proofratings-analytics') ?>">
					<i class="menu-icon fa-solid fa-chart-line"></i>
					<span class="menu-label">Analytics</span>
					<p>View your rating widget data from impressions, hover, clicks, to conversions</p>
				</a>

				<a href="<?php menu_page_url('proofratings-rating-badges') ?>">
					<i class="menu-icon fa-solid fa-star"></i>
					<span class="menu-label">Ratings Badges</span>
					<p>Create and view all your rating trust badges</p>
				</a>

				<a href="<?php menu_page_url('proofratings-settings') ?>">
					<i class="menu-icon fa-solid fa-screwdriver-wrench"></i>
					<span class="menu-label">Settings</span>
					<p>Edit review site connections, manage monthly reports and add schema</p>
				</a>

				<a href="<?php menu_page_url('proofratings-support') ?>">
					<i class="menu-icon fa-solid fa-circle-question"></i>
					<span class="menu-label">Support</span>
					<p>Need help? Submit a ticket</p>
				</a>

				<a href="<?php menu_page_url('proofratings-billing') ?>">
					<i class="menu-icon fa-solid fa-credit-card"></i>
					<span class="menu-label">Billing</span>
					<p>Manage and update your payment source, subscription and invoices</p>
				</a>
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
			<header class="proofratins-header">
				<h1 class="title"><?php _e('Add a Location', 'proofratings') ?></h1>
			</header>

			<hr class="wp-header-end">

			<?php if ( $this->error->has_errors() ) : ?>
			<div class="notice notice-error is-dismissible">
				<p><?php echo $this->error->get_error_message() ?></p>
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
	public function settings() {?>
		<div class="wrap proofratings-settings-wrap">
			<div id="proofratings-settings-root"></div>
			<p class="review-us">Enjoying Proofratings? <img draggable="false" role="img" class="emoji" alt="❤️" src="https://s.w.org/images/core/emoji/13.1.0/svg/2764.svg"> Review us <a href="https://wordpress.org/plugins/proofratings/" target="_blank">here</a></p>
		</div>
		<?php
	}


	public function asdfasfasfsafasf() {
		$postdata = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);






		return;
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