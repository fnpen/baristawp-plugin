<?php
/**
 * Actions for menu items.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

/**
 * Uninstall all data.
 */
function uninstall() {
	Bookmarks::get_instance()->remove();
	History::get_instance()->remove();
	Recently_Edited_Posts::get_instance()->remove();
	Settings::get_instance()->remove();
}

register_uninstall_hook( BARISTA_PLUGIN_FILE, __NAMESPACE__ . '\\uninstall' );
