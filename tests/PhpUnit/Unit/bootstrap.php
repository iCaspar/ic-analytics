<?php
/**
 * Bootstraps the iC-Analytics Unit Tests
 *
 * @since 1.2.0
 * @author Caspar Green
 * @link https://iCasparWebDevelopment.com
 * @license GNU-2.0+
 */

if ( version_compare( phpversion(), '7.1', '<=' ) ) {
	die( 'iC Analytics tests require at least PHP v.7.1' );
}

define( 'IC_ANALYTICS_TESTS_DIR', __DIR__ );
define( 'IC_ANALYTICS_DIR', dirname( dirname( dirname( __DIR__ ) ) ) . DIRECTORY_SEPARATOR );

$ic_analytics_autoload_path = IC_ANALYTICS_DIR . 'vendor/';

if ( ! file_exists( $ic_analytics_autoload_path . 'autoload.php' ) ) {
	die( 'Composer is not installed in ' . $ic_analytics_autoload_path . 'vendor/. Maybe try "composer install"?' );
}

require_once $ic_analytics_autoload_path . 'autoload.php';

unset ( $ic_analytics_autoload_path );