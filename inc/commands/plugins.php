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
add_filter( 'barista_command_plugins_load_children', __NAMESPACE__ . '\\load_children', 100, 2 );
add_filter( 'barista_command_plugins_refresh', __NAMESPACE__ . '\\load_children', 100, 2 );
add_filter( 'barista_action_activate_plugin', __NAMESPACE__ . '\\activate_plugin', 100, 2 );
add_filter( 'barista_action_deactivate_plugin', __NAMESPACE__ . '\\deactivate_plugin', 100, 2 );

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
		'actions'  => [
			[
				'name'        => 'load_children',
				'loadingLine' => true,
				'setAsParent' => true,
			],
		],
		'position' => BARISTA_COMMAND_PRIORITY_FEATURES,
	];

	Collection::get_instance()->add_command( $collection );
}

/**
 * Returns history items.
 *
 * @param \Barista\Ajax\Action_Response $response Response.
 * @return Action_Response
 */
function load_children( \Barista\Ajax\Action_Response $response ) {
	$collection = new Collection();

	// Check if get_plugins() function exists. This is required on the front end of the
	// site, since it is in a file that is normally only loaded in the admin.
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$all_plugins = apply_filters( 'all_plugins', get_plugins() );

	$items = [];

	foreach ( $all_plugins as $plugin_file => $plugin ) {
		$id    = COMMAND_NAME . '-' . $plugin_file;
		$title = implode( ' ', array_filter( [ $plugin['Title'], $plugin['Version'] ? '(v.' . $plugin['Version'] . ')' : '', 'by ' . $plugin['Author'] ] ) );

		$status = 'Inactive';
		if ( is_plugin_active( $plugin_file ) ) {
			$status = 'Active';
		}

		$items[] = [
			'parent'      => COMMAND_NAME,
			'id'          => $id,
			'title'       => $title,
			'titleShort'  => $plugin['Title'],
			'description' => $plugin['Description'],
			'group'       => $status,
			'icon'        => 'dashicons-admin-plugins',
			'actions'     => [
				[
					'title' => 'Activate',
					'name'  => 'activate_plugin',
				],
				[
					'title' => 'Deactivate',
					'name'  => 'deactivate_plugin',
				],
			],
			'suffix'      => $status,
			'pluginFile'  => $plugin_file,
		];

	}

	$mustuse_plugins = get_mu_plugins();
	// dd($mustuse_plugins);

	foreach ( $mustuse_plugins as $plugin_file => $plugin ) {
		$id    = COMMAND_NAME . '-' . $plugin_file;
		$title = implode( ' ', array_filter( [ $plugin['Title'], $plugin['Version'] ? '(v.' . $plugin['Version'] . ')' : '', 'by ' . $plugin['Author'] ] ) );

		$items[] = [
			'parent'      => COMMAND_NAME,
			'id'          => $id,
			'title'       => $title,
			'titleShort'  => $plugin['Title'],
			'selectable'  => false,
			'description' => $plugin['Description'],
			'group'       => 'Must-Use',
			'icon'        => 'dashicons-admin-plugins',
			'suffix'      => 'Active',
			'pluginFile'  => $plugin_file,
		];

	}

	$collection->add_command(
		$items
	);

	return $response->replace( COMMAND_NAME, false, $collection );
}


/**
 *
 *
 * @param \Barista\Ajax\Action_Response $response Response data.
 * @param \Barista\Ajax\Action_Request  $request Request body data.
 */
function activate_plugin( \Barista\Ajax\Action_Response $response, \Barista\Ajax\Action_Request $request ) {
	if ( ! current_user_can( 'activate_plugin', $request['pluginFile'] ) ) {
		wp_die( __( 'Sorry, you are not allowed to activate plugins for this site.' ) );
	}

	$result = \activate_plugin( $request['pluginFile'], '', false, null );
	if ( is_wp_error( $result ) ) {
		return load_children( $response )->failure( $result->get_error_message() );
	}

	return load_children( $response )->success( __( 'The plugin successfully activated.', 'barista' ) );
}


/**
 *
 *
 * @param \Barista\Ajax\Action_Response $response Response data.
 * @param \Barista\Ajax\Action_Request  $request Request body data.
 */
function deactivate_plugin( \Barista\Ajax\Action_Response $response, \Barista\Ajax\Action_Request $request ) {
	if ( ! current_user_can( 'deactivate_plugin', $request['pluginFile'] ) ) {
		wp_die( __( 'Sorry, you are not allowed to activate plugins for this site.' ) );
	}

	\deactivate_plugins( $request['pluginFile'], '', false, null );

	return load_children( $response )->success( __( 'The plugin successfully deactivated.', 'barista' ) );
}
