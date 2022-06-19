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
	 * Hold form data
	 * @since  1.1.7
	 */
	var $form_data;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->error = new WP_Error();
		$this->form_data = new Proofratings_Site_Data();

		add_action( 'init', [$this, 'handle_signup_form'] );
		add_action( 'init', [$this, 'handle_support_form'] );
		add_action( 'init', [$this, 'handle_edit_location'] );

		
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

		$response = wp_remote_get(PROOFRATINGS_API_URL . '/activate_site', get_proofratings_api_args(['license_key' => $license_key]));
		if ( is_wp_error( $response ) ) {
			return $this->error->add('remote_request', $response->get_error_message());
		}

		$result = json_decode(wp_remote_retrieve_body($response));
		if ( !is_object($result)) {
			return $this->error->add('error', 'Unknown error');
		}
		
		if ( !isset($result->success) || $result->success !== true ) {
			return $this->error->add('license_key', $result->message);
		}

		global $wpdb;

		if ( isset($result->data->locations ) && is_object($result->data->locations) ) {
			foreach ($result->data->locations as $location_slug => $location) {
				$location_data = array('location_id' => $location_slug, 'location' => @$location->name);

				$sql = $wpdb->prepare("SELECT * FROM $wpdb->proofratings WHERE location_id = '%s'", $location_slug);
				if ( $get_location = $wpdb->get_row($sql) ) {
					$wpdb->update($wpdb->proofratings, $location_data, ['id' => $get_location->id]);
					continue;
				}

				$wpdb->insert($wpdb->proofratings, $location_data);
			}
		}

		update_proofratings_settings(['status' => $result->data->status]);
	}

	public function handle_support_form() {
		if ( !isset($_POST['_nonce']) || !wp_verify_nonce( $_POST['_nonce'], '_nonce_submit_ticket')) {
			return;
		}

		if ( is_proofratings_demo_mode() ) {
			$this->error->add('error_demo', __('On the demo, you are not able to send a message.'));
		}

		$postdata = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

		if ( empty($postdata['subject']) ) {
			$this->error->add('subject_missing', __('Please fill your subject.'));
		}

		if ( empty($postdata['message']) ) {
			$this->error->add('message_missing', __('Please fill your message.'));
		}

		$this->form_data->subject = sanitize_text_field( $postdata['subject'] );
		$this->form_data->message = sanitize_textarea_field($postdata['message']);

		if ( $this->error->has_errors() ) {
			return;
		}		

		$request = wp_safe_remote_post(PROOFRATINGS_API_URL . '/submit_ticket', get_proofratings_api_args(array(
			'subject' => $this->form_data->subject,
			'message' => $this->form_data->message,
		)));

		if ( is_wp_error( $request ) ) {
			return $this->error = $request;			
		}

		$response = json_decode(wp_remote_retrieve_body( $request ) );
		if ( isset($response->code) ) {
			return $this->error->add($response->code, $response->message);
		}

		$this->form_data = new Proofratings_Site_Data(['success' => 'You have successfully placed your ticket.']);		
	}

	public function get_location_data() {		
		$location_id = false;
		if ( isset($_GET['location']) ) {
			$location_id = $_GET['location'];
		}

		$location = get_proofratings()->query->get($location_id);
		if ( $location === false || $location_id === 0) {
			return new Proofratings_Site_Data(['error' => 'Not a valid location']);			
		}

		$location_data = $location->location;
		$postdata = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
		if ( isset($postdata['_nonce']) && wp_verify_nonce( $postdata['_nonce'], '_nonce_edit_location')) {
			$location_data = $postdata;
		}

		$location_data['location_id'] = $location->location_id;		
		return new Proofratings_Site_Data($location_data);		
	}

	/**
	 * handle edit location form submit
	 * @since 1.0.6
	 */
	public function handle_edit_location() {
		if ( !isset($_POST['_nonce']) || !wp_verify_nonce( $_POST['_nonce'], '_nonce_edit_location')) {
			return;
		}

		$form_data = $this->get_location_data();
		if (!empty($form_data->error) ) {
			wp_die($form_data->error);
		}

		if ( is_proofratings_demo_mode() ) {
			$this->error->add('error_demo', __('On the demo, you are not able to edit the location.'));
		}
		
		if ( empty($form_data->name)) {
			$this->error->add('name', __('Please fill location name field', 'proofratings'));
		}

		if ( empty($form_data->street)) {
			$this->error->add('street', __('Please fill location street field', 'proofratings'));
		}

		if ( empty($form_data->city)) {
			$this->error->add('city', __('Please fill location city field', 'proofratings'));
		}

		if ( empty($form_data->state)) {
			$this->error->add('state', __('Please fill location state/province field', 'proofratings'));
		}

		if ( empty($form_data->zip)) {
			$this->error->add('zip', __('Please fill location zip/postal field', 'proofratings'));
		}

		if ( empty($form_data->country)) {
			$this->error->add('country', __('Please fill location country field', 'proofratings'));
		}

		if ( $this->error->has_errors()) {
			return;
		}

		$location = get_object_vars($form_data);
		foreach (['_nonce', '_wp_http_referer', 'submit'] as $clear_key) {
			unset($location[$clear_key]);
		}

		get_proofratings()->query->update_column($_GET['location'], 'location', $location);

		$response = wp_remote_get(PROOFRATINGS_API_URL . '/update_location', get_proofratings_api_args($location));

		$result = json_decode(wp_remote_retrieve_body($response));
		if ( isset($result->code) && $result->code === 'rest_no_route' ) {
			return $this->error->add('error', "We can't communicate with proofratings website. Please contact with them.");
		}

		if ( isset($result->message) ) {
			return $this->error->add($result->code, $result->message);
		}

		if ( isset($result->success) ) {
			$_POST['success'] = $result->data->message;
		}
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
				<a class="button btn-primary" href="<?php echo home_url('checkout') ?>" target="_blank"><?php _e('SIGN UP FOR TRIAL', 'proofratings') ?></a>

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
					if ( $join_date = wp_date(get_option( 'date_format'), strtotime(get_proofratings_settings('registered')) ) ) {
						printf('<span class="proofratings-join-date">Date Joined: %s</span>', $join_date);
					}
				?>
			</header>

			<div class="proofratings-dashboard-menu">
				<a href="<?php menu_page_url('proofratings-analytics') ?>">
					<i class="menu-icon menu-icon-analytics"></i>
					<span class="menu-label">Analytics</span>
					<p>View your rating widget data from impressions, hover, clicks, to conversions</p>
				</a>

				<a href="<?php menu_page_url('proofratings-rating-badges') ?>">
					<i class="menu-icon menu-icon-rating-badges"></i>
					<span class="menu-label"><?php echo get_proofratings()->query->global ? __('Rating Badges', 'proofratings') :  __('Locations & Rating Badges', 'proofratings'); ?></span>
					<p>Create and view all your rating trust badges</p>
				</a>

				<a href="<?php menu_page_url('proofratings-settings') ?>">
					<i class="menu-icon menu-icon-settings"></i>
					<span class="menu-label">Settings</span>
					<p>Edit review site connections, manage monthly reports and add schema</p>
				</a>

				<a href="<?php menu_page_url('proofratings-support') ?>">
					<i class="menu-icon menu-icon-support"></i>
					<span class="menu-label">Support</span>
					<p>Need help? Submit a ticket</p>
				</a>

				<a href="<?php menu_page_url('proofratings-billing') ?>">
					<i class="menu-icon menu-icon-billing"></i>
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
	public function edit_location() {
		$location = $this->get_location_data();
		if (!empty($location->error) ) {
			wp_die($location->error);
		} ?>
		<div class="wrap proofratings-settings-wrap">
			<header class="proofratins-header header-row">
				<div class="header-left">
					<a class="btn-back-main-menu" href="<?php menu_page_url( 'proofratings' ) ?>"><i class="icon-back fa-solid fa-angle-left"></i> Back to Main Menu</a>
					<h1 class="title"><?php _e('Edit Location', 'proofratings') ?></h1>
				</div>
				
				<div class="header-right">
					<a class="btn-support fa-regular fa-circle-question" href="<?php menu_page_url( 'proofratings-support' ) ?>"></a>
				</div>
			</header>

			<hr class="wp-header-end">

			<?php if ( $this->error->has_errors() ) : ?>
			<div class="notice notice-error is-dismissible">
				<p><?php echo $this->error->get_error_message() ?></p>
			</div>
			<?php endif; ?>

			<?php if ( isset($_POST['success'] ) ): ?>
			<div class="notice notice-success is-dismissible">
				<p><?php echo esc_html($_POST['success']) ?></p>
			</div>
			<?php endif; ?>

			
			<form method="post">
				<?php wp_nonce_field( '_nonce_edit_location', '_nonce' ) ?>
				<table class="form-table">
					<tr>
						<th scope="row"><?php _e('Location Name*', 'proofratings') ?></th>
						<td>
							<input name="name" type="text" value="<?php echo esc_attr($location->name) ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location Street*', 'proofratings') ?></th>
						<td>
							<input name="street" type="text" value="<?php echo esc_attr($location->street) ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location Street 2', 'proofratings') ?></th>
						<td>
							<input name="street2" type="text" value="<?php echo esc_attr($location->street2) ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location City*', 'proofratings') ?></th>
						<td>
							<input name="city" type="text" value="<?php echo esc_attr($location->city) ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location State/Province*', 'proofratings') ?></th>
						<td>
							<input name="state" type="text" value="<?php echo esc_attr($location->state) ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location Zip/Postal*', 'proofratings') ?></th>
						<td>
							<input name="zip" type="text" value="<?php echo esc_attr($location->zip) ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Location Country*', 'proofratings') ?></th>
						<td>
							<input name="country" type="text" value="<?php echo esc_attr($location->country) ?>" />
						</td>
					</tr>
				</table>

				<?php submit_button('Update location'); ?>
			</form>
		</div>
		<?php
	}


	/**
	 * Shows the plugin's settings page.
	 */
	public function settings() { ?>
		<div class="wrap proofratings-settings-wrap">
			<div id="proofratings-settings-root"></div>
		</div>
		<?php
	}

	public function billing() { ?>
		<div class="wrap proofratings-settings-wrap">		
			<header class="proofratins-header header-row">
				<div class="header-left">
					<a class="btn-back-main-menu" href="<?php menu_page_url( 'proofratings' ) ?>"><i class="icon-back fa-solid fa-angle-left"></i> Back to Main Menu</a>
					<h1 class="title"><?php _e('Billing', 'proofratings') ?></h1>
				</div>
				
				<div class="header-right">
					<a class="btn-support fa-regular fa-circle-question" href="<?php menu_page_url( 'proofratings-support' ) ?>"></a>
				</div>
			</header>

			<iframe style="margin: -30px; height: calc(100vh - 116px - 32px); width: calc(100% + 60px)!important" src="https://proofratings.me/customer-panel/"></iframe>
		</div>
		<?php
	}

	public function support() { ?>
		<div class="wrap proofratings-settings-wrap">		
			<header class="proofratins-header header-row">
				<div class="header-left">
					<a class="btn-back-main-menu" href="<?php menu_page_url( 'proofratings' ) ?>"><i class="icon-back fa-solid fa-angle-left"></i> Back to Main Menu</a>
					<h1 class="title"><?php _e('Support', 'proofratings') ?></h1>
				</div>
				
				<div class="header-right">
					<!-- <a class="btn-support fa-regular fa-circle-question" href="<?php menu_page_url( 'proofratings-support' ) ?>"></a> -->
				</div>
			</header>

			<?php if ( $this->error->has_errors() ) : ?>
			<div class="notice notice-error is-dismissible">
				<p><?php echo $this->error->get_error_message() ?></p>
			</div>
			<?php endif;
			
			if ( $this->form_data->success ) : ?>
			<div class="notice notice-success is-dismissible">
				<p><?php echo $this->form_data->success; ?></p>
			</div>
			<?php endif; ?>

			<form class="form-submit-ticket" method="post">
				<hr class="wp-header-end">

				<?php wp_nonce_field( '_nonce_submit_ticket', '_nonce' ) ?>

				<label>Subject</label>
				<input class="input-field" name="subject" type="text" value="<?php echo esc_attr( $this->form_data->subject ) ?>">

				<label>Message</label>
				<textarea class="input-field" name="message"><?php echo esc_textarea($this->form_data->message ) ?></textarea>
				<?php submit_button('SUBMIT'); ?>
			</form>
		</div>
		<?php
	}
}