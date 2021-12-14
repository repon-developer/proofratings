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
class Proofratings_Locations_Admin {
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
		add_action( 'init', [$this, 'handle_delete_action']);
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
     * @since  1.0.6
	 */
	public function handle_delete_action() {
		if ( !isset($_GET['_nonce']) || empty($_GET['id'])) {
			return;
		}

		if( !wp_verify_nonce($_GET['_nonce'], 'delete-location' ) ) {
			return;
		}

		global $wpdb;
		$wpdb->update($wpdb->proofratings, ['status' => 'deleted'], ['id' => $_GET['id']]);

		exit(wp_safe_redirect(admin_url( 'admin.php?page=' . 'proofratings-locations')));
	}

	/**
	 * Render Page
	 * @since  1.0.6
	 */
	public function render() {
		$location_id = isset($_GET['location']) ? $_GET['location'] : false;
		if ( get_proofratings()->locations->global ) {
			$location_id = get_proofratings()->locations->get_global_id();
		}

		$this->widgets_settings($location_id);
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
	public function widgets_settings($location_id) {
		global $wpdb;

		$location = get_proofratings()->locations->get($location_id);

		var_dump($location_id, $location);


		if ( !$location ) {
			return $this->output();
		}

		$location_name = 'Overall';
		if ( $location ) {
			$location_name = $location->location;
		} ?>

		<div class="wrap proofratings-settings-wrap">
			<h1 class="wp-heading-inline"><?php _e('Proofratings Settings', 'proofratings'); ?> (<?php echo $location_name ?>)</h1>
			<hr class="wp-header-end">
			<div id="proofratings-widgets-root" data-location=<?php echo $location_id ?>></div>
			<p class="review-us">Enjoying Proofratings? <img draggable="false" role="img" class="emoji" alt="❤️" src="https://s.w.org/images/core/emoji/13.1.0/svg/2764.svg"> Review us <a href="https://wordpress.org/plugins/proofratings/" target="_blank">here</a></p>
		</div>
		<?php
	}
}