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
		
		$email_reporting_settings = array(
			'automated-report' => isset($postdata['automated-report']),
			'reporting-emails' => $reporting_emails,
			'reporting-agency' => isset($postdata['agency']) ? $postdata['agency'] : []
		);

		update_option( 'proofratings_email_reporting', $email_reporting_settings);

		$email_reporting_settings['domain'] = get_site_url();

		$response = wp_remote_post(add_query_arg($email_reporting_settings, PROOFRATINGS_API_URL . '/save_settings'));
		
		var_dump($email_reporting_settings, $response);
	}

	function get_data() {

		$email_reporting_settings = wp_parse_args(get_option( 'proofratings_email_reporting' ), array(
			'automated-report' => false,
			'reporting-emails' => [],
			'reporting-agency' => []
		)) ;


		$postdata = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

		var_dump($email_reporting_settings);

		return $postdata;

	}


	/**
	 * Shows the plugin's settings page.
	 */
	public function email_settings() {
		$this->get_data(); ?>
		<div class="wrap proofratings-settings-wrap">
			<h1 class="wp-heading-inline"><?php _e('Email Reporting', 'proofratings') ?></h1>
			<hr class="wp-header-end">

			<?php if (!empty($_POST['error_msg'])) : ?>
			<div class="notice notice-error is-dismissible">
				<p><?php echo $_POST['error_msg'] ?></p>
			</div>
			<?php endif; ?>
			
			<form method="post">
				<?php wp_nonce_field( '_nonce_email_reporting', '_nonce' ) ?>

				<table class="form-table">
					<tr>
						<th scope="row"><?php _e('Automated email report', 'proofratings') ?></th>
						<td>
							<input name="automated-email-report" type="checkbox" class="checkbox-switch checkbox-yesno">
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Email addresses', 'proofratings') ?></th>
						<td>
							<input id="email-report-add-email" type="email">
							<p style="font-style:italic;font-size: 13px;margin-top:0"><?php _e('Type email and hit enter', 'proofratings') ?></p>
							<ul id="reporting-email-addresses">
								<li>
									<input type="hidden" name="reporting-emails[]" value="repon.kushtia@gmail.com">
									repon.kushtia@gmail.com
									<span class="remove dashicons dashicons-dismiss"></span>
								</li>
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
						<th scope="row"><?php _e('Sender name*', 'proofratings') ?></th>
						<td>
							<input name="agency[sender-name]" type="text" value="<?php echo @$_POST['name'] ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Sender email', 'proofratings') ?></th>
						<td>
							<input name="agency[sender-email]" type="email" value="<?php echo @$_POST['street'] ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Reply to email', 'proofratings') ?></th>
						<td>
							<input name="agency[reply-to-email]" type="email" value="<?php echo @$_POST['street2'] ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Email logo URL', 'proofratings') ?></th>
						<td>
							<input name="agency[email-logo]" type="url" value="<?php echo @$_POST['city'] ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e('Header Background Color', 'proofratings') ?></th>
						<td>
							<input name="agency[header-background-color]" type="text" value="<?php echo @$_POST['state'] ?>" />
						</td>
					</tr>
				</table>

				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}