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
 * Version:           0.6.38
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
define( 'BARISTA_VERSION', '0.6.38' );

// define( 'BARISTA_DEMO', 'animate-up' ); phpcs:ignore Squiz.PHP.CommentedOutCode.Found.

if ( ! defined( 'BARISTA_DEVELOPMENT' ) ) {
	define( 'BARISTA_DEVELOPMENT', false );
}

define( 'BARISTA_COMMAND_PRIORITY_WP_DASHBOARD', 1000 );
define( 'BARISTA_COMMAND_PRIORITY_FEATURES', 2000 );
define( 'BARISTA_COMMAND_PRIORITY_ACTIONS', 3000 );

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/classes/class-singleton.php';
require __DIR__ . '/classes/class-option.php';
require __DIR__ . '/classes/class-settings.php';
require __DIR__ . '/classes/class-collection.php';
require __DIR__ . '/classes/class-recently-edited-posts.php';
require __DIR__ . '/inc/updating.php';
require __DIR__ . '/inc/admin.php';
require __DIR__ . '/inc/frontend.php';
require __DIR__ . '/inc/assets.php';
require __DIR__ . '/inc/ajax.php';
require __DIR__ . '/inc/init-commands.php';

add_action( 'admin_init', __NAMESPACE__ . '\\admin_init' );

/**
 * Init all hooks.
 */
function admin_init() {
	do_action( 'barista_init' );
	do_action( 'barista_init_commands' );
}
