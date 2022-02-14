<?php
/**
 * Init core methods.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista\Actions\core;

add_action( 'barista_init', __NAMESPACE__ . '\\init' );

/**
 * Init core processors
 */
function init() {
	add_filter( 'barista_add_command', __NAMESPACE__ . '\\fill_id_by_title', 100, 1 );
	add_filter( 'barista_add_command', __NAMESPACE__ . '\\add_nonces', 101, 1 );
}

/**
 * Assigns id for command without predefined id.
 *
 * @param array $command Command.
 */
function fill_id_by_title( array $command ): array {
	if ( ! isset( $command['id'] ) ) {
		$command['id'] = $command['title'];
	}
	return $command;
}

/**
 * Adds nonce for command.
 *
 * @param array $command Command.
 */
function add_nonces( array $command ): array {
	$id               = $command['id'] ?? $command['title'];
	$command['nonce'] = wp_create_nonce( 'barista_command_' . $id . '_' . $command['title'] );
	return $command;
}
