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
