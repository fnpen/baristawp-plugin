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
	require __DIR__ . '/commands/core.php';

	require __DIR__ . '/commands/woocommerce.php';
	require __DIR__ . '/commands/barista-settings.php';
	require __DIR__ . '/commands/plugins.php';
	require __DIR__ . '/commands/admin-menu.php';
	require __DIR__ . '/commands/flush-rewrite-rules.php';
	require __DIR__ . '/commands/flush-cache.php';
	require __DIR__ . '/commands/recently-edited-posts.php';

	if ( BARISTA_DEVELOPMENT ) {
		include __DIR__ . '/commands/test-nested.php';
	}

	require __DIR__ . '/commands/post.php';
	require __DIR__ . '/commands/search.php';
}
