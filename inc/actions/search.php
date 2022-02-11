<?php
/**
 * Init search commands.
 *
 * @package barista
 */

	declare(strict_types=1);

namespace Barista\Actions\search;

add_action( 'barista_init_commands', __NAMESPACE__ . '\\init_actions' );

const COMMAND_NAME = 'search';

/**
 * Init hooks.
 */
function init_actions() {
	add_filter( 'barista_commands_collection', __NAMESPACE__ . '\\add', 200, 1 );
}

/**
 * Adds commands to collection.
 *
 * @param array $collection Commands collection.
 */
function add( array $collection ): array {
	$collection[] = [
		'id'               => COMMAND_NAME,
		'group'            => __( 'Actions', 'barista' ),
		'title'            => __( 'Search...', 'barista' ),
		'icon'             => 'dashicons-search',
		'remoteChildren'   => 'input-enter',
		'inputPlaceholder' => __( 'Search for posts…', 'barista' ),
		'group'            => __( 'Features', 'barista' ),
		'position'         => BARISTA_COMMAND_PRIORITY_FEATURES,
	];

	return $collection;
}
