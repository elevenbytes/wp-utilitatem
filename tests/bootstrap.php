<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Gutenberg
 */

// Debug settings for parity with WordPress Core's PHPUnit tests.
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', true );
}
if ( ! defined( 'LOCAL_WP_DEBUG_LOG' ) ) {
	define( 'LOCAL_WP_DEBUG_LOG', true );
}
if ( ! defined( 'LOCAL_WP_DEBUG_DISPLAY' ) ) {
	define( 'LOCAL_WP_DEBUG_DISPLAY', true );
}
if ( ! defined( 'LOCAL_SCRIPT_DEBUG' ) ) {
	define( 'LOCAL_SCRIPT_DEBUG', true );
}
if ( ! defined( 'LOCAL_WP_ENVIRONMENT_TYPE' ) ) {
	define( 'LOCAL_WP_ENVIRONMENT_TYPE', 'local' );
}

define( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH', __DIR__ . '/../vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php' );

define( 'WP_TESTS_CONFIG_FILE_PATH', __DIR__ . '/wp-tests-config.php' );

// Require composer dependencies.
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// If we're running in WP's build directory, ensure that WP knows that, too.
if ( 'build' === getenv( 'LOCAL_DIR' ) ) {
	define( 'WP_RUN_CORE_TESTS', true );
}

$_tests_dir = __DIR__ . '/../vendor/wp-phpunit/wp-phpunit';

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

// Do not try to load JavaScript files from an external URL - this takes a
// while.
define( 'GUTENBERG_LOAD_VENDOR_SCRIPTS', false );

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	require dirname( __DIR__ ) . '/wp-utilitatem.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

/**
 * Adds a wp_die handler for use during tests.
 *
 * If bootstrap.php triggers wp_die, it will not cause the script to fail. This
 * means that tests will look like they passed even though they should have
 * failed. So we throw an exception if WordPress dies during test setup. This
 * way the failure is observable.
 *
 * @param string|WP_Error $message The error message.
 *
 * @throws Exception When a `wp_die()` occurs.
 */
function fail_if_died( $message ) {
	if ( is_wp_error( $message ) ) {
		$message = $message->get_error_message();
	}

	throw new Exception( 'WordPress died: ' . $message );
}
tests_add_filter( 'wp_die_handler', 'fail_if_died' );

$GLOBALS['wp_tests_options'] = array(
	'gutenberg-experiments' => array(
		'gutenberg-widget-experiments' => '1',
		'gutenberg-full-site-editing'  => 1,
	),
);

// Enable the widget block editor.
tests_add_filter( 'gutenberg_use_widgets_block_editor', '__return_true' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';

// Use existing behavior for wp_die during actual test execution.
remove_filter( 'wp_die_handler', 'fail_if_died' );
