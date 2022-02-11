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
 * Version:           0.4.1
 * Requires at least: 5.8
 * Tested up to:      5.9
 * Requires PHP:      7.1
 *
 * @package           barista
 */

declare(strict_types=1);

namespace Barista;

use Puc_v4_Factory;

define( 'BARISTA_PATH', \plugin_dir_path( __FILE__ ) );
define( 'BARISTA_URL', \plugins_url( '/', __FILE__ ) );
define( 'BARISTA_VERSION', '0.4.1' );
define( 'BARISTA_DEVELOPMENT', true );


define( 'BARISTA_COMMAND_PRIORITY_WP_DASHBOARD', 1000 );
define( 'BARISTA_COMMAND_PRIORITY_FEATURES', 2000 );
define( 'BARISTA_COMMAND_PRIORITY_ACTIONS', 3000 );

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/inc/updating.php';
require __DIR__ . '/inc/admin.php';
require __DIR__ . '/inc/frontend.php';
require __DIR__ . '/inc/assets.php';
require __DIR__ . '/inc/ajax.php';
require __DIR__ . '/inc/actions/core.php';
require __DIR__ . '/inc/actions/woocommerce.php';
require __DIR__ . '/inc/actions/barista-settings.php';
require __DIR__ . '/inc/actions/plugins.php';
require __DIR__ . '/inc/actions/admin-menu.php';
require __DIR__ . '/inc/actions/flush-rewrite-rules.php';
require __DIR__ . '/inc/actions/flush-cache.php';
require __DIR__ . '/inc/actions/recently-edited-posts.php';

if ( BARISTA_DEVELOPMENT ) {
	include __DIR__ . '/inc/actions/test-nested.php';
}

require __DIR__ . '/inc/actions/post.php';
require __DIR__ . '/inc/actions/search.php';

add_action( 'admin_init', __NAMESPACE__ . '\\admin_init' );

/**
 * Init all hooks.
 */
function admin_init() {
	do_action( 'barista_init_commands' );
}
