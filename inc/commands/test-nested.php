<?php
/**
 * Init test commands.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista\Commands\test_nested;

use Barista\Collection;

add_action( 'barista_init_commands', __NAMESPACE__ . '\\add', 200 );

/**
 * Adds commands to collection.
 */
function add() {
	$collection = [];

	$collection[] = [
		'id'       => 'level1_1',
		'title'    => __( 'Level 1-1', 'barista' ),
		'icon'     => 'dashicons-pets',
		'group'    => __( 'Actions', 'barista' ),
		'position' => BARISTA_COMMAND_PRIORITY_ACTIONS,
	];
	$collection[] = [
		'id'       => 'level2_1',
		'title'    => __( 'Level 2-1', 'barista' ),
		'parent'   => 'level1_1',
		'icon'     => 'dashicons-pets',
		'href'     => 'dd',
		'position' => -100,
	];
	$collection[] = [
		'id'       => 'level2_2',
		'title'    => __( 'Level 2-2 - last', 'barista' ),
		'parent'   => 'level1_1',
		'icon'     => 'dashicons-pets',
		'position' => -100,
	];
	$collection[] = [
		'id'       => 'level3_1',
		'title'    => __( 'Level 3-1 - last', 'barista' ),
		'parent'   => 'level2_1',
		'icon'     => 'dashicons-pets',
		'position' => -100,
	];

	Collection::get_instance()->add_command( $collection );
}
