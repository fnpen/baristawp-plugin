<?php
/**
 * Plugin Name:       Barista
 * Plugin URI:        https://github.com/fnpen/baristawp-plugin
 * Description:
 *
 * Text Domain:       barista
 * Domain Path:       /languages
 *
 * Author:            WP Busters
 * Author URI:        https://wpbusters.com/
 *
 * Version:           0.8.0
 * Requires at least: 5.8
 * Tested up to:      5.9
 * Requires PHP:      7.1
 *
 * @package           barista
 */

declare(strict_types=1);

namespace Barista;

define( 'BARISTA_PATH', \plugin_dir_path( __FILE__ ) );
define( 'BARISTA_URL', \plugins_url( '/', __FILE__ ) );
define( 'BARISTA_PLUGIN_FILE', __FILE__ );
define( 'BARISTA_PLUGIN_DIR', __DIR__ );
define( 'BARISTA_VERSION', '0.8.0' );

// define( 'BARISTA_DEMO', 'animate-up' ); phpcs:ignore Squiz.PHP.CommentedOutCode.Found.

if ( ! defined( 'BARISTA_DEVELOPMENT' ) ) {
	define( 'BARISTA_DEVELOPMENT', false );
}

define( 'BARISTA_COMMAND_PRIORITY_WP_DASHBOARD', 1000 );
define( 'BARISTA_COMMAND_PRIORITY_FEATURES', 2000 );
define( 'BARISTA_COMMAND_PRIORITY_ACTIONS', 3000 );

require __DIR__ . '/inc/bootstrap.php';
