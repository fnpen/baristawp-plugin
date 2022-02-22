<?php
/**
 * Loads all commands.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

add_action( 'barista_init', __NAMESPACE__ . '\\init_commands', 100 );

/**
 * Loads all commands files.
 *
 * @return void
 */
function init_commands() {
	require BARISTA_PLUGIN_DIR . '/inc/commands/core.php';

	require BARISTA_PLUGIN_DIR . '/inc/commands/home.php';
	require BARISTA_PLUGIN_DIR . '/inc/commands/woocommerce.php';
	require BARISTA_PLUGIN_DIR . '/inc/commands/barista-settings.php';
	require BARISTA_PLUGIN_DIR . '/inc/commands/plugins.php';
	require BARISTA_PLUGIN_DIR . '/inc/commands/admin-menu.php';
	require BARISTA_PLUGIN_DIR . '/inc/commands/flush-rewrite-rules.php';
	require BARISTA_PLUGIN_DIR . '/inc/commands/flush-cache.php';
	require BARISTA_PLUGIN_DIR . '/inc/commands/recently-edited-posts.php';
	require BARISTA_PLUGIN_DIR . '/inc/commands/bookmarks.php';

	if ( 'no' !== Settings::get_instance()->get( 'enableHistory' ) ) {
		require BARISTA_PLUGIN_DIR . '/inc/commands/history.php';
	}

	if ( BARISTA_DEVELOPMENT ) {
		include BARISTA_PLUGIN_DIR . '/inc/commands/test-nested.php';
	}

	require BARISTA_PLUGIN_DIR . '/inc/commands/post.php';
	require BARISTA_PLUGIN_DIR . '/inc/commands/search.php';
}
