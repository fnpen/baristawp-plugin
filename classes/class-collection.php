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
class Collection {
	/**
	 * Singleton variable.
	 *
	 * @var Collection
	 */
	private static $instance;

	/**
	 * Method to get the instance.
	 *
	 * @return Collection
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Collection();
		}

		return self::$instance;
	}

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
		if ( ! isset( $command['id'] ) && ! isset( $command['title'] ) ) {
			array_map( [ $this, 'add_command' ], $command );
			return;
		}
		$this->items[] = apply_filters( 'barista_add_command', (array) $command );
	}
}
