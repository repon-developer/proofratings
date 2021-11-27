<?php
/**
 * Plugin Name: Proofratings
 * Plugin URI: https://proofratings.com
 * Description: Proofratings monitors all your third party sites for reviews by your customers. Sharing review ratings badges on your website increases conversions.
 * Version: 1.0.6
 * Author: Proofratings
 * Requires at least: 5.2
 * Tested up to: 5.5
 * Requires PHP: 7.0
 * Text Domain: proofratings
 * Domain Path: /languages/
 * License: GPL2+
 *
 * @package proofratings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define constants.
define( 'PROOFRATINGS_VERSION', '1.0.6' );
define( 'PROOFRATINGS_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'PROOFRATINGS_PLUGIN_URL', untrailingslashit(plugin_dir_url(__FILE__)));
define( 'PROOFRATINGS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
//define( 'PROOFRATINGS_API_URL', 'https://proofratings.com/wp-json/proofratings/v1');
define( 'PROOFRATINGS_API_URL', 'http://proofratings.me/wp-json/proofratings/v1');


require_once dirname( __FILE__ ) . '/inc/class-proofratings.php';

/**
 * Main instance of Wordpress Proofratings.
 * Returns the main instance of Wordpress_Proofratings to prevent the need to use globals.
 * @since  1.0.1
 * @return Wordpress_Proofratings
 */
function WP_Proofratings() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
	return Wordpress_Proofratings::instance();
}

$GLOBALS['proofratings'] = WP_Proofratings();

register_activation_hook( PROOFRATINGS_PLUGIN_BASENAME, array( WP_Proofratings(), 'activate' ) );