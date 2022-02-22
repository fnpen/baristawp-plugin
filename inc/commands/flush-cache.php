<?php
/**
 * Command Flush Cache.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista\Commands\flush_cache;

use Barista\Collection;

const COMMAND_NAME = 'flush_cache';

add_action( 'barista_init_commands', __NAMESPACE__ . '\\add' );
add_filter( 'barista_command_' . COMMAND_NAME . '_' . 'flush', __NAMESPACE__ . '\\run', 200, 2 );

/**
 * Adds commands to collection.
 */
function add() {
	Collection::get_instance()->add_command(
		[
			'id'       => COMMAND_NAME,
			'title'    => __( 'Flush Cache', 'barista' ),
			'icon'     => 'dashicons-controls-play',
			'group'    => __( 'Actions', 'barista' ),
			'actions'  => [
				'name' => 'flush',
			],
			'position' => BARISTA_COMMAND_PRIORITY_ACTIONS,
		]
	);
}

/**
 * Command process method.
 *
 * @param \Barista\Ajax\Action_Response $response Response.
 * @return Action_Response
 */
function run( \Barista\Ajax\Action_Response $response ) {
	if ( false === \wp_cache_flush() ) {
		return $response->failure( __( 'The object cache could not be flushed.', 'barista' ) );
	}

	return $response->success( __( 'All cache items were removed.', 'barista' ) );
}
