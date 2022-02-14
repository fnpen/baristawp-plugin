<?php
/**
 * Init search commands.
 *
 * @package barista
 */

	declare(strict_types=1);

namespace Barista\Actions\search;

use Barista\Collection;

add_action( 'barista_init_commands', __NAMESPACE__ . '\\add' );

const COMMAND_NAME = 'search';

/**
 * Adds commands to collection.
 */
function add() {
	Collection::get_instance()->add_command(
		[
			'id'               => COMMAND_NAME,
			'group'            => __( 'Actions', 'barista' ),
			'title'            => __( 'Search...', 'barista' ),
			'icon'             => 'dashicons-search',
			'remoteChildren'   => 'input-enter',
			'inputPlaceholder' => __( 'Search for posts…', 'barista' ),
			'group'            => __( 'Features', 'barista' ),
			'position'         => BARISTA_COMMAND_PRIORITY_FEATURES,
		]
	);
}
