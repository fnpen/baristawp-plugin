<?php
/**
 * Command Flush Cache.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista\Commands\flush_cache;

use Barista\Collection;

add_action( 'barista_init_commands', __NAMESPACE__ . '\\commands' );

const COMMAND_NAME = 'flush_cache';

/**
 * Init hooks.
 */
function commands() {
	add();
	add_filter( 'barista_command_' . COMMAND_NAME, __NAMESPACE__ . '\\run', 200, 1 );
}

/**
 * Adds commands to collection.
 */
function add() {
	Collection::get_instance()->add_command(
		[
			'id'       => COMMAND_NAME,
			'title'    => __( 'Flush Cache', 'barista' ),
			'icon'     => 'dashicons-controls-play',
			'type'     => 'remote',
			'group'    => __( 'Actions', 'barista' ),
			'position' => BARISTA_COMMAND_PRIORITY_ACTIONS,
		]
	);
}

/**
 * Command process method.
 */
function run() {
	if ( false === \wp_cache_flush() ) {
		wp_send_json_error( __( 'The object cache could not be flushed.', 'barista' ) );
	}

	wp_send_json_success(
		[
			'notification' => [
				'text' => __( 'All cache items were removed.', 'barista' ),
			],
		]
	);
}
