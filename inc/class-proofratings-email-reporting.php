<?php
/**
 * File containing the class Proofratings_Email_Reporting.
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
 * @since 1.0.8
 */
class Proofratings_Email_Reporting {
	/**
	 * The single instance of the class.
	 */
	private static $instance = null;

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
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
		$this->signup_error = new WP_Error;

		add_action( 'init', [$this, 'handle_email_reporting_form'] );
	}

	public function handle_email_reporting_form() {
		if ( !isset($_POST['_nonce']) ) {
			return;
		}

		if ( !wp_verify_nonce( $_POST['_nonce'], '_nonce_email_reporting')) {
			return;
		}

		$postdata = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

		$reporting_emails = isset($postdata['reporting-emails']) ? $postdata['reporting-emails'] : [];
		if ( !is_array($reporting_emails)) {
			$reporting_emails = [];
		}

		$settings = get_option('proofratings_settings');
		if ( !is_array($settings) ) {
			$settings = [];
		}

		$settings = wp_parse_args(array(
			'automated-report' => isset($postdata['automated-email-report']),
			'reporting-emails' => $reporting_emails,
			'reporting-agency' => isset($postdata['agency']) ? $postdata['agency'] : []
		), $settings);

		update_option( 'proofratings_settings', $settings);

		if ( sizeof($reporting_emails) == 0) {
			$_POST['proofratings_error'] = __('Please enter aleast one email in the email addresses field.', 'proofratings');
			return;
		}

		$settings['domain'] = get_site_url();
		$response = wp_remote_post(PROOFRATINGS_API_URL . '/save_settings', array('body' => $settings));
	}

	function get_data() {
		return wp_parse_args(get_option( 'proofratings_settings' ), array(
			'automated-report' => false,
			'reporting-emails' => [],
			'reporting-agency' => []
		));
	}


	/**
	 * Shows the plugin's settings page.
	 */
	public function email_settings() {
		$email_report_settings = $this->get_data();
		$reporting_emails = is_array($email_report_settings['reporting-emails']) ? $email_report_settings['reporting-emails'] : []; ?>
		<div class="wrap proofratings-settings-wrap">
			<h1 class="wp-heading-inline"><?php _e('Email Reporting', 'proofratings') ?></h1>
			<hr class="wp-header-end">

			<?php if (!empty($_POST['proofratings_error'])) : ?>
			<div class="notice notice-error is-dismissible">
				<p><?php echo $_POST['proofratings_error'] ?></p>
			</div>
			<?php endif; ?>
			
			<form method="post">
				<?php wp_nonce_field( '_nonce_email_reporting', '_nonce' ) ?>

				<table class="form-table">
					<tr>
						<th scope="row"><?php _e('Automated email report', 'proofratings') ?></th>
						<td>
							<input name="automated-email-report" type="checkbox" class="checkbox-switch checkbox-yesno" <?php checked(true, @$email_report_settings['automated-report']) ?>>
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Email addresses', 'proofratings') ?></th>
						<td>
							<input id="email-report-add-email" type="email">
							<p style="font-style:italic;font-size: 13px;margin-top:0"><?php _e('Type email and hit enter', 'proofratings') ?></p>
							<ul id="reporting-email-addresses">
								<?php
								foreach ($reporting_emails as $email) {
									printf('<li><input type="hidden" name="reporting-emails[]" value="%1$s">%1$s<span class="remove dashicons dashicons-dismiss"></span></li>', $email);
								} ?>
							</ul>

							<script type="text/html" id="tmpl-reporting-email-address">
								<li>
									<input type="hidden" name="reporting-emails[]" value="{{{data.email}}}">
									{{{data.email}}}
									<span class="remove dashicons dashicons-dismiss"></span>
								</li>
							</script>
						</td>
					</tr>
				</table>

				<h2><?php _e('Settings for agency', 'proofratings') ?></h2>
				<table class="form-table">
					<tr>
						<th scope="row"><?php _e('Sender name', 'proofratings') ?></th>
						<td>
							<input name="agency[sender-name]" type="text" value="<?php echo @$email_report_settings['reporting-agency']['sender-name'] ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Sender email', 'proofratings') ?></th>
						<td>
							<input name="agency[sender-email]" type="email" value="<?php echo @$email_report_settings['reporting-agency']['sender-email'] ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Reply to email', 'proofratings') ?></th>
						<td>
							<input name="agency[reply-to-email]" type="email" value="<?php echo @$email_report_settings['reporting-agency']['reply-to-email'] ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Email logo URL', 'proofratings') ?></th>
						<td>
							<input name="agency[email-logo]" type="url" value="<?php echo @$email_report_settings['reporting-agency']['email-logo'] ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Header Background Color', 'proofratings') ?></th>
						<td>
							<input class="proofratings-color-input" name="agency[header-background-color]" type="text" value="<?php echo @$email_report_settings['reporting-agency']['header-background-color'] ?>" />
						</td>
					</tr>
				</table>

				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}