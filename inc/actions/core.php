<?php
/**
 * Init core methods.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista\Actions\core;

add_action( 'barista_init_commands', __NAMESPACE__ . '\\init_actions' );

/**
 * Init core actions.
 */
function init_actions() {
	add_filter( 'barista_commands_collection', __NAMESPACE__ . '\\fill_id_by_title', 20000, 1 );
	add_filter( 'barista_commands_collection', __NAMESPACE__ . '\\add_nonces', 20010, 1 );
}

/**
 * Assigns ids for commands without predefined ids.
 *
 * @param array $collection Commands collection.
 */
function fill_id_by_title( array $collection ): array {
	foreach ( $collection as &$item ) {
		if ( ! isset( $item['id'] ) ) {
			$item['id'] = $item['title'];
		}
	}
	return $collection;
}

/**
 * Adds nonces for all commands.
 *
 * @param array $collection Commands collection.
 */
function add_nonces( array $collection ): array {
	foreach ( $collection as &$item ) {
		$id            = $item['id'] ?? $item['title'];
		$item['nonce'] = wp_create_nonce( 'barista_command_' . $id . '_' . $item['title'] );
	}
	return $collection;
}
