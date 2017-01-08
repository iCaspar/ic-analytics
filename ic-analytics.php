<?php
/**
 * iCaspar Google Analytics for WordPress Plugin.
 *
 * @author: Caspar Green <https://caspar.green/>
 * @package: ICaspar/Analytics
 * @since: 1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:     iCaspar Analytics
 * Plugin URI:      https://caspar.green/
 * Description:     A minimalist plugin to include Google Analytics.
 * Version:         1.0.1
 * Author:          Caspar Green
 * Author URI:      https://caspar.green/
 * Text Domain:     icaspar
 * Requires WP:     4.7
 * Requires PHP:    5.6.3
 */

namespace ICaspar\Analytics;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! defined( 'ICASPAR_ANALYTICS_PLUGIN_DIR' ) ) {
	define( 'CASPAR_EXTENSIONS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'ICASPAR_ANALYTICS_CONFIG_DIR' ) ) {
	define( 'CASPAR_EXTENSIONS_CONFIG_DIR', ICASPAR_ANALYTICS_PLUGIN_DIR . 'config/' );
}

if ( ! defined( 'ICASPAR_ANALYTICS_PLUGIN_URL' ) ) {
	$plugin_url = plugin_dir_url( __FILE__ );
	if ( is_ssl() ) {
		$plugin_url = str_replace( 'http://', 'https://', $plugin_url );
	}
	define( 'ICASPAR_ANALYTICS_PLUGIN_URL', $plugin_url );
}

if ( ! defined( 'ICASPAR_ANALYTICS_ASSETS_URL' ) ) {
	define( 'ICASPAR_ANALYTICS_ASSETS_URL', ICASPAR_ANALYTICS_PLUGIN_URL . 'assets/' );
}

if ( ! defined( 'ICASPAR_ANALYTICS_VERSION' ) ) {
	define( 'ICASPAR_ANALYTICS_VERSION', '1.0.1' );
}

if ( version_compare( $GLOBALS['wp_version'], '4.7', '>=' ) ) {
	add_action( 'plugins_loaded', __NAMESPACE__ . '\launch' );
}

/**
 * Launch the plugin.
 *
 * @since 1.0.0
 *
 * @return void
 */
function launch() {
	require_once( __DIR__ . '/vendor/autoload.php' );
	new IcAnalytics();
}