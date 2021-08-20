<?php
/**
 * Plugin Name: Proof Ratings
 * Plugin URI: https://proofratings.com
 * Description: Proofratings monitors all your third party sites for reviews by your customers. Sharing review ratings badges on your website increases conversions.
 * Version: 1.0.1
 * Author: Proof Ratings
 * Author URI: https://proofratings.com
 * Requires at least: 5.2
 * Tested up to: 5.5
 * Requires PHP: 7.0
 * Text Domain: proof-ratings
 * Domain Path: /languages/
 * License: GPL2+
 *
 * @package proof-ratings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define constants.
define( 'PROOF_RATINGS_VERSION', '1.0.1' );
define( 'PROOF_RATINGS_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'PROOF_RATINGS_PLUGIN_URL', untrailingslashit(plugin_dir_url(__FILE__)));
define( 'PROOF_RATINGS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'PROOF_RATINGS_API_URL', 'http://wooshop.me/wp-json/proof-ratings/v1');


require_once dirname( __FILE__ ) . '/inc/class-proof-ratings.php';

/**
 * Main instance of Wordpress Proof Ratings.
 * Returns the main instance of Wordpress_Proof_Ratings to prevent the need to use globals.
 * @since  1.0.1
 * @return Wordpress_Proof_Ratings
 */
function WP_Proof_Ratings() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
	return Wordpress_Proof_Ratings::instance();
}

$GLOBALS['proof_ratings'] = WP_Proof_Ratings();

register_activation_hook( PROOF_RATINGS_PLUGIN_BASENAME, array( WP_Proof_Ratings(), 'activate' ) );