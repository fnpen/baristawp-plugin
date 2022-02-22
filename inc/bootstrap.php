<?php
/**
 * Bootstrap plugin
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

add_action( 'init', __NAMESPACE__ . '\\init' );

/**
 * Init all hooks.
 */
function init() {
	if( ! is_user_logged_in() ) {
		return;
	}

	require BARISTA_PLUGIN_DIR . '/vendor/autoload.php';
	require BARISTA_PLUGIN_DIR . '/classes/class-singleton.php';
	require BARISTA_PLUGIN_DIR . '/classes/class-action-request.php';
	require BARISTA_PLUGIN_DIR . '/classes/class-action-response.php';
	require BARISTA_PLUGIN_DIR . '/classes/class-option.php';
	require BARISTA_PLUGIN_DIR . '/classes/class-settings.php';
	require BARISTA_PLUGIN_DIR . '/classes/class-collection.php';
	require BARISTA_PLUGIN_DIR . '/classes/class-recently-edited-posts.php';
	require BARISTA_PLUGIN_DIR . '/classes/class-bookmarks.php';
	require BARISTA_PLUGIN_DIR . '/classes/class-history.php';
	require BARISTA_PLUGIN_DIR . '/inc/utils.php';
	require BARISTA_PLUGIN_DIR . '/inc/updating.php';
	require BARISTA_PLUGIN_DIR . '/inc/admin.php';
	require BARISTA_PLUGIN_DIR . '/inc/frontend.php';
	require BARISTA_PLUGIN_DIR . '/inc/assets.php';
	require BARISTA_PLUGIN_DIR . '/inc/ajax.php';
	require BARISTA_PLUGIN_DIR . '/inc/init-commands.php';

	add_action( 'admin_init', __NAMESPACE__ . '\\admin_init' );

	if ( ! is_admin() ) {
		frontend_init(); }
}

/**
 * Init all hooks.
 */
function admin_init() {
	global $menu, $menu_order, $default_menu_order, $_wp_menu_nopriv, $_wp_submenu_nopriv; // it's important for including.

	if ( is_ajax() && 'barista_run_action' === $_REQUEST['action'] ) {
		if ( ! defined( 'WP_NETWORK_ADMIN' ) ) {
			define( 'WP_NETWORK_ADMIN', false );
		}
		if ( ! defined( 'WP_USER_ADMIN' ) ) {
			define( 'WP_USER_ADMIN', false );
		}

		if ( \WP_NETWORK_ADMIN ) {
			require_once ABSPATH . 'wp-admin/network/menu.php';
		} elseif ( \WP_USER_ADMIN ) {
			require_once ABSPATH . 'wp-admin/user/menu.php';
		} else {
			require_once ABSPATH . 'wp-admin/menu.php';
		}
	}

	do_action( 'barista_init' );
	do_action( 'barista_init_commands' );
}

/**
 * Init all hooks for frontend.
 */
function frontend_init() {
	do_action( 'barista_init' );
	do_action( 'barista_init_commands_frontend' );
}
