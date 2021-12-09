<?php
/**
 * @package proofratings
 * @since   1.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles core plugin hooks and action setup.
 *
 * @since 1.0.6
 */
class Proofratings_Locations {
	/**
	 * The single instance of the class.
	 * @var self
	 * @since  1.0.6
	 */
	private static $instance = null;

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 * @since  1.0.6
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
     * @since  1.0.6
	 */
	public function __construct() {	
		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 20, 3 );
		add_action( 'init', [$this, 'process_single_action']);
	}

    /**
	 * set screen option $value.
     * @since  1.0.6
	 */
	public static function set_screen( $status, $option, $value ) {
        return $value;
    }

    /**
	 * add options for screen setting.
     * @since  1.0.6
	 */
	public function screen_option() {
        add_screen_option( 'per_page', [
            'label' => __('Locations Per Page', 'proofratings'),
            'default' => 15,
            'option' => 'locations_per_page'
        ] );
    }

	/**
	 * handle bulk action
     * @since  1.0.1
	 */
	public function process_single_action() {
		if ( !isset($_GET['_nonce'])) {
			return;
		}

		if( !wp_verify_nonce($_GET['_nonce'], 'site_action' ) ) {
			return;
		}

		$site_id = $_GET['id'];

		global $wpdb;

		$site = $wpdb->get_row(sprintf("SELECT * FROM $wpdb->proofratings_manager WHERE id = %d", $_GET['id']));
		if ( !$site ) {
			return;
		}

		$status = $_GET['action'];
		if ( $status == 'approve' ) {
			$status = 'active';
		}

		if ( $site->status == $status) {
			return;
		}
	}

	/**
	 * Render Page
	 * @since  1.0.6
	 */
	public function render() {
		if ( isset($_GET['location']) ) {
			return $this->widgets_settings();
		}

		$this->output();
	}	

	/**
	 * Shows locations
	 * @since  1.0.6
	 */
	public function output() {	
		include_once PROOFRATINGS_PLUGIN_DIR . '/inc/class-proofratings-locations-table.php';
		$location_table = new Proofratings_Locations_Table();
		$location_table->prepare_items(); ?>

		<div class="wrap proofratings-settings-wrap">
			<h1 class="wp-heading-inline"><?php _e('Locations', 'proofratings'); ?> (<?php echo $location_table->total_items ?>)</h1>
			<hr class="wp-header-end">

            <form method="post">
                <?php $location_table->display(); ?>
            </form>
		</div>
		<?php
	}

	/**
	 * Shows locations
	 * @since  1.0.6
	 */
	public function widgets_settings() {?>

		<div class="wrap proofratings-settings-wrap">
			<h1 class="wp-heading-inline"><?php _e('Locations', 'proofratings'); ?></h1>
			<hr class="wp-header-end">

			<h3>We are in widget settings page</h3>
		</div>
		<?php
	}


}