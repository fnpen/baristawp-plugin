<?php
/**
 * Init commands.
 *
 * @package barista
 */

	declare(strict_types=1);

namespace Barista\Commands\plugins;

use Barista\Collection;

add_action( 'barista_init_commands', __NAMESPACE__ . '\\add', 200 );

const COMMAND_NAME = 'plugins';

/**
 * Adds commands to collection.
 */
function add() {
	$collection = [];

	$collection[] = [
		'id'       => COMMAND_NAME,
		'title'    => __( 'Plugins', 'barista' ),
		'icon'     => 'dashicons-admin-plugins',
		'group'    => __( 'Features', 'barista' ),
		'position' => BARISTA_COMMAND_PRIORITY_FEATURES,
	];

	// Check if get_plugins() function exists. This is required on the front end of the
	// site, since it is in a file that is normally only loaded in the admin.
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$all_plugins = get_plugins();

	foreach ( $all_plugins as $plugin_file => $plugin ) {
		$id    = COMMAND_NAME . '-' . $plugin_file;
		$title = implode( ' ', [ $plugin['Title'], '(v.' . $plugin['Version'] . ')', 'by ' . $plugin['Author'] ] );

		$collection[] = [
			'parent'      => COMMAND_NAME,
			'id'          => $id,
			'title'       => $title,
			'titleShort'  => $plugin['Title'],
			'description' => $plugin['Description'],
			'icon'        => 'dashicons-admin-plugins',
			'suffix'      => is_plugin_active( $plugin_file ) ? 'Active' : 'Deactivated',
			'pluginFile'  => $plugin_file,
		];

		$collection[] = [
			'parent'     => $id,
			'id'         => $id . '-activate',
			'title'      => 'Activate',
			'icon'       => 'dashicons-admin-plugins',
			'pluginFile' => $plugin_file,
		];

		$collection[] = [
			'parent'     => $id,
			'id'         => $id . '-Deactivate',
			'title'      => 'Deactivate',
			'icon'       => 'dashicons-admin-plugins',
			'pluginFile' => $plugin_file,
		];

		$collection[] = [
			'parent'     => $id,
			'id'         => $id . '-Update',
			'title'      => 'Update',
			'icon'       => 'dashicons-admin-plugins',
			'pluginFile' => $plugin_file,
		];

		$collection[] = [
			'parent'     => $id,
			'id'         => $id . '-Delete',
			'title'      => 'Delete',
			'icon'       => 'dashicons-admin-plugins',
			'pluginFile' => $plugin_file,
		];
	}

	Collection::get_instance()->add_command( $collection );
}