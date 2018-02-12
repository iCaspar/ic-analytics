<?php
/**
 * Bootstraps the iC-Analytics Integration Tests
 *
 * @since 1.2.0
 * @author Caspar Green
 * @link https://iCasparWebDevelopment.com
 * @license GNU-2.0+
 */


if ( ! file_exists( '../../../wp-content' ) ) {
	trigger_error( 'Cannot find the wp-content directory.', E_USER_ERROR );
}

define( 'WP_CONTENT_DIR', dirname( dirname(  getcwd() ) ) );

if ( ! defined( 'WP_PLUGIN_DIR' ) ) {
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins/' );
}

define( 'IC_ANALYTICS_DIR', WP_PLUGIN_DIR . 'ic-analytics/' );
define( 'IC_ANALYTICS_TESTS_DIR', __DIR__ );

$WPTestsDir = getTestsDir();
require $WPTestsDir . '/includes/functions.php';
require $WPTestsDir . '/includes/bootstrap.php';

tests_add_filter( 'muplugins_loaded', function() {
	require IC_ANALYTICS_DIR . 'ic-analytics.php';
});

function getTestsDir(): string {
	$testsDir = getenv( 'WP_TESTS_DIR' );

	if ( empty( $testsDir ) ) {
		$testsDir = '/tmp/wordpress-tests';
	}

	if ( ! file_exists( $testsDir . '/includes/' ) ) {
		$testsDir = '../../../../tests/phpunit';
	}

	if ( ! file_exists( $testsDir . '/includes/' ) ) {
		trigger_error( 'Unable to find WP test suite.', E_USER_ERROR );
	}

	return rtrim( $testsDir, DIRECTORY_SEPARATOR );
}