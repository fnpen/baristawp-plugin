<?php
/**
 * Init core methods.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista\Commands\core;

add_filter( 'barista_add_command', __NAMESPACE__ . '\\add_nonce_title', 100, 1 );

/**
 * Assigns id and nonce for command.
 *
 * @param array $command Command.
 */
function add_nonce_title( array $command ): array {
	if ( ! isset( $command['id'] ) ) {
		$command['id'] = $command['title'] ?? '';

		if ( empty( $command['id'] ) ) {
			$command['id'] = md5( implode( '-', [ ($command['uxType'] ?? ''), ($command['title'] ?? ''), ($command['html'] ?? '') ] ) );
		}
	}

	$command['nonce'] = wp_create_nonce( 'barista_command_' . $command['id'] . '_' . ($command['title'] ?? '') );
	return $command;
}
