<?php
/**
 * Init home.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista\Commands\home;

use Barista\Collection;

add_action( 'barista_init_commands_frontend', __NAMESPACE__ . '\\commands_frontend' );
add_action( 'barista_init_commands', __NAMESPACE__ . '\\commands' );
add_filter( 'barista_command_home_load_children', __NAMESPACE__ . '\\load_children', 100, 2 );

/**
 * Adds home command.
 */
function commands_frontend() {
	Collection::get_instance()->add_command(
		[
			'id'      => 'home',
			'parent'  => 'root',
			'title'   => 'Barista',
			'actions' => [
				[
					'name'        => 'load_children',
					'loadingLine' => true,
				],
			],
		]
	);
}
/**
 * Adds home command.
 */
function commands() {
	Collection::get_instance()->add_command(
		[
			'id'     => 'home',
			'parent' => 'root',
			'title'  => 'Barista',
		]
	);
}

/**
 * Returns all items.
 *
 * @param \Barista\Ajax\Action_Response $response Response.
 * @return Action_Response
 */
function load_children( \Barista\Ajax\Action_Response $response ) {
	// do_action( 'barista_init_commands' );

	Collection::get_instance()->add_command(
		[
			'id'     => 'home',
			'parent' => 'root',
			'title'  => 'Barista',
		]
	);

	return $response->commands( Collection::get_instance() );
}
