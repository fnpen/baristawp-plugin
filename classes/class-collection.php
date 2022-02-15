<?php
/**
 * Class constrol collection of commands.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

/**
 * Collection class.
 */
class Collection extends Singleton {
	/**
	 * All registered commands.
	 *
	 * @var array
	 */
	private $items = [];

	/**
	 * Returns registered commands.
	 *
	 * @return array
	 */
	public function get_items() {
		return $this->items;
	}

	/**
	 * Adds command to collection.
	 *
	 * @param array $command Command to add.
	 */
	public function add_command( $command ) {
		if ( empty( $command ) ) {
			return;
		}

		if ( $command instanceof Collection ) {
			$command = $command->get_items();
		}

		if ( ! isset( $command['id'] ) && ! isset( $command['title'] ) && ! isset( $command['uxType'] ) ) {
			array_map( [ $this, 'add_command' ], $command );
			return;
		}

		$this->items[] = apply_filters( 'barista_add_command', (array) $command );
	}
}
