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
		'position' => -100,
	];
	$collection[] = [
		'id'       => 'level2_2',
		'title'    => __( 'Level 2-2 - last - many actions', 'barista' ),
		'parent'   => 'level1_1',
		'actions'  => [
			[
				'name' => 'one',
			],
			[
				'name' => 'two',
			],
		],
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

	$collection[] = [
		'id'       => 'html',
		'title'    => __( 'Html', 'barista' ),
		'parent'   => 'level1_1',
		'position' => -100,
	];

	$collection[] = [
		'uxType' => 'title',
		'parent' => 'html',
		'title'  => 'Hello',
	];

	$collection[] = [
		'uxType' => 'html',
		'parent' => 'html',
		'html'   => 'Hello',
	];

	$collection[] = [
		'uxType' => 'title',
		'parent' => 'html',
		'title'  => 'Hello 2',
	];

	$collection[] = [
		'uxType' => 'html',
		'parent' => 'html',
		'html'   => 'Hello 3',
	];

	$collection[] = [
		'title'         => __( 'Upper', 'barista' ),
		'parent'        => 'html',
		'defaultAction' => 'upper',
		'icon'          => 'dashicons-pets',
	];

	Collection::get_instance()->add_command( $collection );
}
